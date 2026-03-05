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
use App\Models\Role;
use App\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ForumTopicPostsController extends Controller
{
    public function __construct()
    {
        $forumsStatus = getForumsGeneralSettings('forums_status');

        if (empty($forumsStatus) or $forumsStatus == '0') {
            abort(403);
        }
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
                    'creator' => function ($query) {
                        $query->select('id', 'full_name', 'username', 'role_id', 'avatar', 'avatar_settings', 'created_at');
                    },
                ])
                ->first();

            if (!empty($topic)) {
                $query = ForumTopicPost::query()->where('topic_id', $topic->id);
                $query->orderBy('pin', 'desc');
                $query->orderBy('created_at', 'asc');
                $query->with([
                    'parent'
                ]);

                $getListData = $this->getListData($request, $query, $topic, $forum);

                if ($request->ajax()) {
                    return $getListData;
                }

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
                $data = array_merge($data, $getListData);

                return view('design_1.web.forums.posts.index', $data);
            }
        }

        abort(404);
    }

    private function getListData(Request $request, Builder $query, $topic, $forum)
    {
        $count = $this->perPage;
        $page = $request->get('page') ?? 1;
        $total = $query->count();

        $query->limit($count);
        $query->offset(($page - 1) * $count);

        $posts = $query->get();

        if ($request->ajax()) {
            return $this->getAjaxResponse($request, $posts, $total, $count, $topic, $forum);
        }

        return [
            'posts' => $posts,
            'pagination' => $this->makePagination($request, $posts, $total, $count, true),
        ];
    }

    private function getAjaxResponse(Request $request, $posts, $total, $count, $topic, $forum)
    {
        $user = auth()->user();

        $likedPostsIds = [];
        if (!empty($user)) {
            $likedPostsIds = ForumTopicLike::where('user_id', $user->id)->pluck('topic_post_id')->toArray();
        }


        $html = "";
        foreach ($posts as $post) {
            $html .= (string)view()->make('design_1.web.forums.posts.includes.post_card', [
                'post' => $post,
                'topic' => $topic,
                'forum' => $forum,
                'likedPostsIds' => $likedPostsIds,
            ]);
        }

        return response()->json([
            'data' => $html,
            'pagination' => $this->makePagination($request, $posts, $total, $count, true)
        ]);
    }


    public function storePost(Request $request, $forumSlug, $topicSlug)
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
                $forum = $topic->forum;

                if (!$topic->close and !$forum->isClosed()) {
                    $data = $request->all();

                    $validator = Validator::make($data, [
                        'description' => 'required|string|min:3'
                    ]);

                    if ($validator->fails()) {
                        return response([
                            'code' => 422,
                            'errors' => $validator->errors(),
                        ], 422);
                    }

                    $replyPostId = (!empty($data['reply_post_id']) and $data['reply_post_id'] != '') ? $data['reply_post_id'] : null;

                    $post = ForumTopicPost::create([
                        'user_id' => $user->id,
                        'topic_id' => $topic->id,
                        'parent_id' => $replyPostId,
                        'description' => $data['description'],
                        'attach' => null,
                        'created_at' => time(),
                    ]);

                    // Handle Attachment
                    $this->handlePostAttachment($request, $user, $topic, $post);

                    $buyStoreReward = RewardAccounting::calculateScore(Reward::SEND_TOPIC_POST);
                    RewardAccounting::makeRewardAccounting($post->user_id, $buyStoreReward, Reward::SEND_TOPIC_POST, $post->id);

                    $notifyOptions = [
                        '[topic_title]' => $topic->title,
                        '[u.name]' => $user->full_name
                    ];
                    sendNotification('send_post_in_topic', $notifyOptions, $topic->creator_id);

                    return response()->json([
                        'code' => 200
                    ]);
                }
            }
        }

        abort(403);
    }

    private function handlePostAttachment(Request $request, $user, $topic, $post)
    {
        $attachPath = $post->attach;
        $attachFile = $request->file('attachment');

        $destination = "forums/topics/{$topic->id}/posts/{$post->id}";

        if (!empty($attachFile)) {
            $attachPath = $this->uploadFile($attachFile, $destination, null, $user->id);
        }

        $post->update([
            'attach' => $attachPath,
        ]);
    }

    public function getReportModal(Request $request, $forumSlug, $topicSlug)
    {
        $topic = ForumTopic::where('slug', $topicSlug)->first();

        if (!empty($topic)) {
            $data = [
                'topic' => $topic,
                'type' => $request->get('type'),
                'item' => $request->get('item'),
            ];

            $html = (string)view()->make('design_1.web.forums.posts.includes.modals.report', $data);

            return response()->json([
                'code' => 200,
                'html' => $html,
            ]);
        }

        return response()->json([], 422);
    }

    public function storeTopicReport(Request $request, $forumSlug, $topicSlug)
    {
        $user = auth()->user();


        if (!empty($user)) {
            $data = $request->all();

            $validator = Validator::make($data, [
                'item_id' => 'required',
                'item_type' => 'required',
                'message' => 'required|min:3',
            ]);

            if ($validator->fails()) {
                return response([
                    'code' => 422,
                    'errors' => $validator->errors(),
                ], 422);
            }

            ForumTopicReport::create([
                'user_id' => $user->id,
                'topic_id' => ($data['item_type'] == 'topic') ? $data['item_id'] : null,
                'topic_post_id' => ($data['item_type'] == 'topic_post') ? $data['item_id'] : null,
                'message' => $data['message'],
                'created_at' => time(),
            ]);

            $notifyOptions = [
                '[u.name]' => $user->full_name,
                '[content_type]' => trans('public.' . $data['item_type'])
            ];
            sendNotification("new_report_item_for_admin", $notifyOptions, 1);


            return response()->json([
                'code' => 200
            ]);
        }

        abort(403);
    }

    public function postLikeToggle($forumSlug, $topicSlug, $postId)
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
                $post = ForumTopicPost::where('id', $postId)
                    ->where('topic_id', $topic->id)
                    ->first();

                if (!empty($post)) {
                    $like = ForumTopicLike::where('user_id', $user->id)
                        ->where('topic_post_id', $postId)
                        ->first();

                    $likeStatus = true;
                    if (!empty($like)) {
                        $like->delete();
                        $likeStatus = false;
                    } else {
                        ForumTopicLike::create([
                            'user_id' => $user->id,
                            'topic_post_id' => $postId,
                        ]);
                    }

                    return response()->json([
                        'code' => 200,
                        'likes' => $post->likes->count(),
                        'status' => $likeStatus
                    ]);
                }
            }
        }


        abort(403);
    }

    public function postPinToggle($forumSlug, $topicSlug, $postId)
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
                $post = ForumTopicPost::where('id', $postId)
                    ->where('topic_id', $topic->id)
                    ->first();

                if (!empty($post)) {
                    $pin = !$post->pin;

                    $post->update([
                        'pin' => $pin
                    ]);

                    return response()->json([
                        'code' => 200,
                        'title' => trans('public.request_success'),
                        'msg' => $pin ? trans('update.post_pined_successfully') : trans('update.post_unpined_successfully')
                    ]);
                }
            }
        }

        abort(403);
    }

    public function postEdit($forumSlug, $topicSlug, $postId)
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
                $post = ForumTopicPost::where('id', $postId)
                    ->where('user_id', $user->id)
                    ->where('topic_id', $topic->id)
                    ->first();

                if (!empty($post)) {

                    $data = [
                        'forum' => $forum,
                        'topic' => $topic,
                        'post' => $post,
                    ];

                    $html = (string)view()->make('design_1.web.forums.posts.includes.modals.edit_post', $data);

                    return response()->json([
                        'code' => 200,
                        'html' => $html,
                    ]);
                }
            }
        }

        abort(403);
    }

    public function postUpdate(Request $request, $forumSlug, $topicSlug, $postId)
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

                $post = ForumTopicPost::where('id', $postId)
                    ->where('user_id', $user->id)
                    ->where('topic_id', $topic->id)
                    ->first();

                if (!empty($post)) {
                    $data = $request->all();

                    $validator = Validator::make($data, [
                        'description' => 'required|string|min:3'
                    ]);

                    if ($validator->fails()) {
                        return response([
                            'code' => 422,
                            'errors' => $validator->errors(),
                        ], 422);
                    }

                    $post->update([
                        'description' => $data['description'],
                    ]);

                    // Handle Attachment
                    $this->handlePostAttachment($request, $user, $topic, $post);

                    return response()->json([
                        'code' => 200
                    ]);
                }
            }
        }

        abort(403);
    }

    public function postDownloadAttachment($forumSlug, $topicSlug, $postId)
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
                $post = ForumTopicPost::where('id', $postId)
                    ->where('topic_id', $topic->id)
                    ->first();

                if (!empty($post)) {
                    $filePath = public_path($post->attach);

                    if (file_exists($filePath)) {
                        $fileInfo = pathinfo($filePath);
                        $type = (!empty($fileInfo) and !empty($fileInfo['extension'])) ? $fileInfo['extension'] : '';

                        $fileName = str_replace(' ', '-', "attachment-{$post->id}");
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

}
