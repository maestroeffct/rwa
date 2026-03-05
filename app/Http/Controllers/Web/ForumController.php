<?php

namespace App\Http\Controllers\Web;

use App\Enums\MorphTypesEnum;
use App\Http\Controllers\Controller;
use App\Mixins\Logs\VisitLogMixin;
use App\Models\Forum;
use App\Models\ForumFeaturedTopic;
use App\Models\ForumRecommendedTopic;
use App\Models\ForumTopic;
use App\Models\ForumTopicAttachment;
use App\Models\ForumTopicBookmark;
use App\Models\ForumTopicLike;
use App\Models\ForumTopicPost;
use App\Models\ForumTopicReport;
use App\Models\Reward;
use App\Models\RewardAccounting;
use App\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ForumController extends Controller
{
    public function __construct()
    {
        $forumsStatus = getForumsGeneralSettings('forums_status');

        if (empty($forumsStatus) or $forumsStatus == '0') {
            abort(403);
        }
    }

    public function index()
    {
        $user = auth()->user();
        $userGroup = !empty($user) ? $user->getUserGroup() : null;

        $forums = Forum::query()->whereNull('parent_id')
            ->where('status', 'active')
            ->where(function (Builder $query) use ($userGroup) {
                $query->whereNull('group_id');

                if (!empty($userGroup)) {
                    $query->orWhere('group_id', $userGroup->id);
                }
            })
            ->where(function (Builder $query) use ($user) {
                $query->whereNull('role_id');

                if (!empty($user)) {
                    $query->orWhere('role_id', $user->role_id);
                }
            })
            ->orderBy('order', 'asc')
            ->with([
                'subForums' => function ($query) {
                    $query->where('status', 'active');
                    $query->withCount([
                        'topics',
                    ]);
                },
            ])
            ->withCount([
                'topics',
            ])
            ->get();

        foreach ($forums as $forum) {
            if (!empty($forum->subForums) and count($forum->subForums)) {
                foreach ($forum->subForums as $item) {
                    $item = $this->handleForumExtraData($item);
                }
            } else {
                $forum = $this->handleForumExtraData($forum);
            }
        }


        $seoSettings = getSeoMetas('forum');
        $pageTitle = $seoSettings['title'] ?? '';
        $pageDescription = $seoSettings['description'] ?? '';
        $pageRobot = getPageRobot('forum');

        $forumsCount = Forum::where('status', 'active')
            ->whereDoesntHave('subForums')
            ->count();

        $activeUsersIds = ForumTopicPost::query()->groupBy('user_id')->inRandomOrder()->limit(9)->pluck('user_id')->toArray();
        $randomlyActiveUsers = User::query()->select('id', 'full_name', 'username', 'avatar', 'avatar_settings')->whereIn('id', $activeUsersIds)->get();

        $data = [
            'pageTitle' => $pageTitle,
            'pageDescription' => $pageDescription,
            'pageRobot' => $pageRobot,
            'forums' => $forums,
            'forumsCount' => $forumsCount,
            'randomlyActiveUsers' => $randomlyActiveUsers,
            'featuredTopics' => $this->getFeaturedTopics(),
            'recommendedTopics' => $this->getRecommendedTopics(),
            'heroSettings' => getForumsHomepageSettings(),
            'forumImagesSettings' => getForumsImagesSettings(),
        ];
        $data = array_merge($data, $this->getHeroStats());

        return view('design_1.web.forums.homepage.index', $data);
    }

    private function getFeaturedTopics()
    {
        $featuredTopics = ForumFeaturedTopic::orderBy('created_at', 'desc')
            ->with([
                'topic' => function ($query) {
                    $query->with([
                        'creator' => function ($query) {
                            $query->select('id', 'full_name', 'username', 'avatar', 'avatar_settings');
                        },
                        'posts'
                    ]);
                    $query->withCount([
                        'posts'
                    ]);
                }
            ])->get();

        foreach ($featuredTopics as $featuredTopic) {
            $usersAvatars = [];

            if ($featuredTopic->topic->posts_count > 0) {
                foreach ($featuredTopic->topic->posts as $post) {
                    if (!empty($post->user) and count($usersAvatars) < 2 and empty($usersAvatars[$post->user->id])) {
                        $usersAvatars[$post->user->id] = $post->user;
                    }
                }
            }

            $featuredTopic->usersAvatars = $usersAvatars;
        }

        return $featuredTopics;
    }

    private function getRecommendedTopics()
    {
        return ForumRecommendedTopic::orderBy('created_at', 'desc')
            ->with([
                'topics'
            ])
            ->get();
    }

    private function handleForumExtraData(&$forum)
    {
        $topicsIds = ForumTopic::where('forum_id', $forum->id)->pluck('id')->toArray();

        $forum->posts_count = ForumTopicPost::whereIn('topic_id', $topicsIds)->count();

        $forum->lastTopic = ForumTopic::where('forum_id', $forum->id)->orderBy('created_at', 'desc')->first();

        return $forum;
    }

    public function search(Request $request)
    {
        $search = $request->get('search');

        $query = ForumTopic::query();

        $topicsData = $this->handleTopics($request, $query);

        if ($request->ajax()) {
            return $topicsData;
        }

        $pageRobot = getPageRobot('forum_search_topics');

        $data = [
            'pageTitle' => trans('update.search_results_for', ['temp' => $search]),
            'pageDescription' => "",
            'pageRobot' => $pageRobot,
            'topUsers' => $this->getTopUsers(),
            'popularTopics' => $this->getPopularTopics(),
        ];

        // topics Data
        $data = array_merge($data, $topicsData);
        // Hero Stats
        $data = array_merge($data, $this->getHeroStats());

        return view('design_1.web.forums.search.index', $data);
    }

    public function topics(Request $request, $slug)
    {
        $forum = Forum::where('slug', $slug)
            ->where('status', 'active')
            ->first();

        if (!empty($forum)) {
            $query = ForumTopic::where('forum_topics.forum_id', $forum->id);

            $topicsData = $this->handleTopics($request, $query);

            if ($request->ajax()) {
                return $topicsData;
            }


            // Visit Logs
            $visitLogMixin = new VisitLogMixin();
            $visitLogMixin->storeVisit($request, null, $forum->id, MorphTypesEnum::FORUM);

            $pageRobot = getPageRobot('forum_topics');

            $data = [
                'pageTitle' => $forum->title,
                'pageDescription' => !empty($forum->description) ? truncate(strip_tags($forum->description), 160) : '',
                'pageRobot' => $pageRobot,
                'forum' => $forum,
                'topUsers' => $this->getTopUsers(),
                'popularTopics' => $this->getPopularTopics(),
            ];

            // topics Data
            $data = array_merge($data, $topicsData);
            // Hero Stats
            $data = array_merge($data, $this->getHeroStats($forum));

            return view('design_1.web.forums.topics.lists.index', $data);
        }

        abort(404);
    }

    private function handleTopics(Request $request, $query)
    {
        $search = $request->get('search');
        $sort = $request->get('sort');

        if (!empty($search)) {
            $topicsIds = ForumTopicPost::where('description', 'like', "%$search%")
                ->pluck('topic_id')
                ->toArray();

            $query->where(function ($query) use ($topicsIds, $search) {
                $query->whereIn('forum_topics.id', $topicsIds)
                    ->orWhere('forum_topics.title', 'like', "%$search%")
                    ->orWhere('forum_topics.description', 'like', "%$search%");
            });
        }

        $query->orderBy('forum_topics.pin', 'desc');

        if (!empty($sort) and $sort != 'newest') {
            if ($sort == 'popular_topics') {
                $query->join('forum_topic_posts', 'forum_topic_posts.topic_id', 'forum_topics.id')
                    ->select('forum_topics.*', DB::raw("count(forum_topic_posts.topic_id) as topic_posts_count"))
                    ->orderBy('topic_posts_count', 'desc');
            } elseif ($sort == 'not_answered') {
                $query->whereDoesntHave('posts');
                $query->orderBy('forum_topics.created_at', 'desc');
            }
        } else {
            $query->orderBy('forum_topics.created_at', 'desc');
        }

        $count = 6;
        $page = $request->get('page') ?? 1;
        $total = $query->count();

        $query->limit($count);
        $query->offset(($page - 1) * $count);

        $forumTopics = $query
            ->with([
                'creator' => function ($qu) {
                    $qu->select('id', 'full_name', 'username', 'bio', 'avatar', 'avatar_settings');
                },
                'forum'
            ])
            ->withCount([
                'posts',
                'likes',
                'visits',
            ])
            ->get();

        foreach ($forumTopics as $topic) {
            $topic->lastActivity = $topic->posts()->orderBy('created_at', 'desc')->first();
        }

        if ($request->ajax()) {
            $html = (string)view()->make('design_1.web.forums.components.cards.topic.index', ['forumTopics' => $forumTopics, 'cardClassName' => "mt-24", 'withoutStyles' => true]);

            return response()->json([
                'data' => $html,
                'has_more_item' => (($page * $count) < $total),
            ]);
        }

        return [
            'forumTopics' => $forumTopics,
            'hasMoreForumTopics' => (($page * $count) < $total),
            'totalTopicsCount' => $total,
        ];
    }

    private function getTopUsers()
    {
        return User::leftJoin('forum_topics', 'forum_topics.creator_id', 'users.id')
            ->leftJoin('forum_topic_posts', 'forum_topic_posts.user_id', 'users.id')
            ->select('users.id', 'users.full_name', 'users.avatar', DB::raw("count(forum_topics.creator_id) as topics, count(forum_topic_posts.user_id) as posts"), DB::raw("(count(forum_topics.creator_id) + count(forum_topic_posts.user_id)) as all_posts"))
            ->whereHas('forumTopics')
            ->groupBy('forum_topics.creator_id')
            ->groupBy('forum_topic_posts.user_id')
            ->orderBy('all_posts', 'desc')
            ->limit(4)
            ->get();
    }

    private function getPopularTopics()
    {
        return ForumTopic::query()
            ->join('forum_topic_posts', 'forum_topic_posts.topic_id', 'forum_topics.id')
            ->select('forum_topics.*', DB::raw("count(forum_topic_posts.topic_id) as posts_count"))
            ->whereHas('creator')
            ->with([
                'creator' => function ($query) {
                    $query->select('id', 'full_name', 'username', 'avatar', 'avatar_settings');
                }
            ])
            ->orderBy('posts_count', 'desc')
            ->groupBy('forum_topics.id')
            ->limit(4)
            ->get();
    }

    private function getHeroStats($forum = null)
    {
        $topicsCountQuery = ForumTopic::query();
        $postsCountQuery = ForumTopicPost::query();
        $membersCountQuery = ForumTopicPost::query()->select(DB::raw('count(distinct user_id) as count'));

        if (!empty($forum)) {
            $topicsCountQuery->where('forum_id', $forum->id);

            $postsCountQuery->whereHas('topic', function ($query) use ($forum) {
                $query->where('forum_id', $forum->id);
            });

            $membersCountQuery->whereHas('topic', function ($query) use ($forum) {
                $query->where('forum_id', $forum->id);
            });
        }

        $topicsCount = $topicsCountQuery->count();
        $postsCount = $postsCountQuery->count();

        $membersCount = $membersCountQuery->first()->count;

        return [
            'topicsCount' => $topicsCount,
            'postsCount' => $postsCount,
            'membersCount' => $membersCount,
        ];
    }

    public function createTopic(Request $request)
    {
        $user = auth()->user();

        if (empty($user)) {
            return redirect('/login');
        }

        $forums = Forum::orderBy('order', 'asc')
            ->whereNull('parent_id')
            ->where('status', 'active')
            ->with([
                'subForums' => function ($query) {
                    $query->where('status', 'active');
                }
            ])->get();

        $data = [
            'pageTitle' => trans('update.create_new_topic'),
            'pageDescription' => '',
            'pageRobot' => '',
            'forums' => $forums,
        ];
        $data = array_merge($data, $this->getHeroStats());

        return view('design_1.web.forums.topics.create.index', $data);
    }

    public function storeTopic(Request $request)
    {
        $user = auth()->user();

        if (empty($user)) {
            abort(403);
        }

        $this->validate($request, [
            'title' => 'required|max:255',
            'forum_id' => 'required|exists:forums,id',
            'description' => 'required',
        ]);

        $data = $request->all();

        $forum = Forum::where('id', $data['forum_id'])
            ->where('status', 'active')
            ->where('close', false)
            ->first();


        if (!empty($forum) and $forum->checkUserCanCreateTopic($user)) {

            $topic = ForumTopic::create([
                'slug' => ForumTopic::makeSlug($data['title']),
                'creator_id' => $user->id,
                'forum_id' => $data['forum_id'],
                'title' => $data['title'],
                'description' => $data['description'],
                'close' => false,
                'created_at' => time(),
            ]);

            $this->handleTopicCoverAndAttachments($request, $topic);

            $buyStoreReward = RewardAccounting::calculateScore(Reward::MAKE_TOPIC);
            RewardAccounting::makeRewardAccounting($topic->creator_id, $buyStoreReward, Reward::MAKE_TOPIC, $topic->id);


            $notifyOptions = [
                '[u.name]' => $user->full_name,
                '[topic_title]' => $topic->title,
                '[forum_title]' => $forum->title,
            ];
            sendNotification("new_forum_topic", $notifyOptions, 1);

            $toastData = [
                'title' => trans('public.request_success'),
                'msg' => trans('update.new_topic_successfully_created'),
                'status' => 'success'
            ];

            $url = "/forums/{$topic->forum->slug}/topics/{$topic->slug}/edit";
            return redirect($url)->with(['toast' => $toastData]);

        }

        abort(403);
    }

    private function handleTopicCoverAndAttachments(Request $request, $topic)
    {
        $user = auth()->user();
        $destination = "forums/topics/{$topic->id}";

        $coverPath = $topic->cover ?? null;
        $coverFile = $request->file('cover');

        if (!empty($coverFile)) {
            $coverPath = $this->uploadFile($coverFile, $destination, 'cover', $user->id);
        }

        $topic->update([
            'cover' => $coverPath,
        ]);

        $attachmentsFiles = $request->file('attachments');

        if (!empty($attachmentsFiles) and count($attachmentsFiles)) {
            $destination .= "/attachments";
            $attachmentsInserts = [];

            foreach ($attachmentsFiles as $attachKey => $attachmentFile) {
                $checkAttachment = ForumTopicAttachment::query()->where('topic_id', $topic->id)
                    ->where('id', $attachKey)
                    ->first();

                $path = $this->uploadFile($attachmentFile, $destination, null, $user->id);

                if (!empty($checkAttachment)) {
                    $checkAttachment->update([
                        'path' => $path,
                    ]);
                } else {
                    $attachmentsInserts[] = [
                        'creator_id' => $user->id,
                        'topic_id' => $topic->id,
                        'path' => $path,
                    ];
                }
            }

            if (count($attachmentsInserts)) {
                ForumTopicAttachment::query()->insert($attachmentsInserts);
            }
        }

    }

    public function topicLikeToggle($forumSlug, $topicSlug)
    {
        $user = auth()->user();

        $forum = Forum::where('slug', $forumSlug)
            ->where('status', 'active')
            ->first();

        if (!empty($user) and !empty($forum) and $forum->checkUserCanCreateTopic($user)) {
            $topic = ForumTopic::where('slug', $topicSlug)
                ->where('forum_id', $forum->id)
                ->first();

            if (!empty($topic)) {
                $like = ForumTopicLike::where('user_id', $user->id)
                    ->where('topic_id', $topic->id)
                    ->first();

                $likeStatus = true;
                if (!empty($like)) {
                    $like->delete();
                    $likeStatus = false;
                } else {
                    ForumTopicLike::create([
                        'user_id' => $user->id,
                        'topic_id' => $topic->id,
                    ]);
                }

                return response()->json([
                    'code' => 200,
                    'likes' => $topic->likes->count(),
                    'status' => $likeStatus
                ]);
            }
        }

        abort(403);
    }

    public function topicBookmarkToggle($forumSlug, $topicSlug)
    {
        $user = auth()->user();

        $forum = Forum::where('slug', $forumSlug)
            ->where('status', 'active')
            ->first();

        if (!empty($user) and !empty($forum) and $forum->checkUserCanCreateTopic($user)) {
            $topic = ForumTopic::where('slug', $topicSlug)
                ->where('forum_id', $forum->id)
                ->first();

            if (!empty($topic)) {
                $add = true;
                $bookmark = ForumTopicBookmark::where('user_id', $user->id)
                    ->where('topic_id', $topic->id)
                    ->first();

                if (!empty($bookmark)) {
                    $add = false;

                    $bookmark->delete();
                } else {
                    ForumTopicBookmark::create([
                        'user_id' => $user->id,
                        'topic_id' => $topic->id,
                        'created_at' => time(),
                    ]);
                }

                return response()->json([
                    'code' => 200,
                    'add' => $add,
                    'title' => trans('public.request_success'),
                    'msg' => $add ? trans('update.topic_bookmarked_successfully') : trans('update.topic_un_bookmarked_successfully'),
                ]);
            }
        }

        abort(403);
    }

    public function topicEdit($forumSlug, $topicSlug)
    {
        $user = auth()->user();

        $forum = Forum::where('slug', $forumSlug)
            ->where('status', 'active')
            ->first();

        if (!empty($user) and !empty($forum) and $forum->checkUserCanCreateTopic($user)) {
            $topic = ForumTopic::where('slug', $topicSlug)
                ->where('forum_id', $forum->id)
                ->where('creator_id', $user->id)
                ->first();

            if (!empty($topic)) {
                $forums = Forum::orderBy('order', 'asc')
                    ->whereNull('parent_id')
                    ->where('status', 'active')
                    ->with([
                        'subForums' => function ($query) {
                            $query->where('status', 'active');
                        }
                    ])->get();

                $data = [
                    'pageTitle' => trans('update.edit_topic'),
                    'pageDescription' => '',
                    'pageRobot' => '',
                    'forums' => $forums,
                    'topic' => $topic,
                ];
                $data = array_merge($data, $this->getHeroStats());

                return view('design_1.web.forums.topics.create.index', $data);
            }
        }

        abort(403);
    }

    public function topicUpdate(Request $request, $forumSlug, $topicSlug)
    {
        $user = auth()->user();

        $forum = Forum::where('slug', $forumSlug)
            ->where('status', 'active')
            ->first();

        if (!empty($user) and !empty($forum) and $forum->checkUserCanCreateTopic($user)) {
            $topic = ForumTopic::where('slug', $topicSlug)
                ->where('forum_id', $forum->id)
                ->where('creator_id', $user->id)
                ->first();

            if (!empty($topic)) {
                $this->validate($request, [
                    'title' => 'required|max:255',
                    'forum_id' => 'required|exists:forums,id',
                    'description' => 'required',
                ]);

                $data = $request->all();

                $topic->update([
                    'forum_id' => $data['forum_id'],
                    'title' => $data['title'],
                    'description' => $data['description'],
                    'close' => false,
                ]);

                $this->handleTopicCoverAndAttachments($request, $topic);

                $toastData = [
                    'title' => trans('public.request_success'),
                    'msg' => trans('update.new_topic_successfully_created'),
                    'status' => 'success'
                ];

                $url = '/forums/' . $topic->forum->slug . '/topics';
                return redirect($url)->with(['toast' => $toastData]);
            }
        }

        abort(403);
    }

    public function topicDownloadAttachment($forumSlug, $topicSlug, $attachmentId)
    {
        $forum = Forum::where('slug', $forumSlug)
            ->where('status', 'active')
            ->first();

        if (!empty($forum)) {
            $topic = ForumTopic::where('slug', $topicSlug)
                ->where('forum_id', $forum->id)
                ->first();

            if (!empty($topic)) {
                $attachment = ForumTopicAttachment::where('id', $attachmentId)
                    ->where('topic_id', $topic->id)
                    ->first();

                if (!empty($attachment)) {
                    $filePath = public_path($attachment->path);

                    if (file_exists($filePath)) {
                        $fileInfo = pathinfo($filePath);
                        $type = (!empty($fileInfo) and !empty($fileInfo['extension'])) ? $fileInfo['extension'] : '';

                        $fileName = str_replace(' ', '-', "attachment-{$attachment->id}");
                        $fileName = str_replace('.', '-', $fileName);
                        $fileName .= '.' . $type;

                        $headers = array(
                            'Content-Type: application/' . $type,
                        );

                        return response()->download($filePath, $fileName, $headers);
                    }
                }
            }
        }

        abort(403);
    }

    public function deleteTopicAttachment($attachmentId)
    {
        $user = auth()->user();
        $attachment = ForumTopicAttachment::where('id', $attachmentId)
            ->where('creator_id', $user->id)
            ->first();

        if (!empty($attachment)) {
            $path = $attachment->path;
            $attachment->delete();
            $this->removeFile($path);

            return response()->json([
                'code' => 200,
                'title' => trans('public.request_success'),
                'msg' => trans('update.items_deleted_successful'),
            ]);
        }

        return response()->json([], 422);
    }

    public function posts(Request $request, $forumSlug, $topicSlug)
    {
        $forum = Forum::where('slug', $forumSlug)
            ->where('status', 'active')
            ->first();

        if (!empty($forum) and $forum->checkUserCanCreateTopic()) {

            $topic = ForumTopic::where('slug', $topicSlug)
                ->where('forum_id', $forum->id)
                ->with([
                    'forum',
                    'attachments',
                    'posts' => function ($query) {
                        $query->orderBy('pin', 'desc');
                        $query->orderBy('created_at', 'asc');
                        $query->with([
                            'parent'
                        ]);
                    },
                    'creator' => function ($query) {
                        $query->select('id', 'full_name', 'username', 'role_id', 'avatar', 'avatar_settings', 'created_at');
                    },
                ])
                ->first();

            if (!empty($topic)) {
                $user = auth()->user();

                $likedPostsIds = [];
                if (!empty($user)) {
                    $likedPostsIds = ForumTopicLike::where('user_id', $user->id)->pluck('topic_post_id')->toArray();

                    $topicLiked = ForumTopicLike::where('user_id', $user->id)
                        ->where('topic_id', $topic->id)
                        ->first();

                    $bookmarked = ForumTopicBookmark::where('user_id', $user->id)
                        ->where('topic_id', $topic->id)
                        ->first();

                    $topic->liked = !empty($topicLiked);
                    $topic->bookmarked = !empty($bookmarked);
                }

                $topic->members_count = ForumTopicPost::query()->select(DB::raw('count(distinct user_id) as count'))
                    ->where('topic_id', $topic->id)
                    ->first()->count;


                // Visit Logs
                $visitLogMixin = new VisitLogMixin();
                $visitLogMixin->storeVisit($request, $topic->creator_id, $topic->id, MorphTypesEnum::FORUM_TOPIC);

                $data = [
                    'pageTitle' => $topic->title,
                    'pageDescription' => !empty($topic->description) ? truncate(strip_tags($topic->description), 160) : '',
                    'pageRobot' => '',
                    'forum' => $forum,
                    'topic' => $topic,
                    'likedPostsIds' => $likedPostsIds,
                ];

                return view('design_1.web.forums.posts.index', $data);
            }
        }

        abort(404);
    }

}
