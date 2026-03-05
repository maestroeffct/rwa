<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\Comment;
use App\Models\Reward;
use App\Models\RewardAccounting;
use App\Models\Translation\BlogTranslation;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BlogPostsController extends Controller
{
    private function handleAuthorize($user)
    {
        if (!$user->isTeacher()) {
            abort(403);
        }
    }

    public function index(Request $request)
    {
        $this->authorize("panel_blog_my_articles");

        $user = auth()->user();

        $this->handleAuthorize($user);

        $query = Blog::query()->where('author_id', $user->id);

        $copyQuery = deepClone($query);
        $getListData = $this->getListsData($request, $query);

        if ($request->ajax()) {
            return $getListData;
        }

        $blogIds = deepClone($copyQuery)->pluck('id')->toArray();

        $postsCount = count($blogIds);
        $commentsCount = Comment::whereIn('blog_id', $blogIds)->count();
        $pendingPublishCount = deepClone($copyQuery)->where('status', 'pending')->count();


        $data = [
            'pageTitle' => trans('site.posts'),
            'postsCount' => $postsCount,
            'commentsCount' => $commentsCount,
            'pendingPublishCount' => $pendingPublishCount,
        ];
        $data = array_merge($data, $getListData);

        return view('design_1.panel.blog.posts.lists.index', $data);
    }

    private function getListsData(Request $request, Builder $query)
    {
        $page = $request->get('page') ?? 1;
        $count = $this->perPage;

        $total = $query->count();

        $query->limit($count);
        $query->offset(($page - 1) * $count);

        $posts = $query
            ->withCount([
                'comments'
            ])
            ->orderBy('created_at', 'desc')
            ->get();

        if ($request->ajax()) {
            return $this->getAjaxResponse($request, $posts, $total, $count);
        }

        return [
            'posts' => $posts,
            'pagination' => $this->makePagination($request, $posts, $total, $count, true),
        ];
    }

    private function getAjaxResponse(Request $request, $posts, $total, $count)
    {
        $html = "";

        foreach ($posts as $postRow) {
            $html .= (string)view()->make('design_1.panel.blog.posts.lists.table_items', ['post' => $postRow]);
        }

        return response()->json([
            'data' => $html,
            'pagination' => $this->makePagination($request, $posts, $total, $count, true)
        ]);
    }

    public function create()
    {
        $this->authorize("panel_blog_new_article");

        $user = auth()->user();

        $this->handleAuthorize($user);

        $blogCategories = BlogCategory::all();

        $data = [
            'pageTitle' => trans('update.create_a_post'),
            'blogCategories' => $blogCategories
        ];

        return view('design_1.panel.blog.posts.create.index', $data);
    }

    public function store(Request $request)
    {
        $this->authorize("panel_blog_new_article");

        $user = auth()->user();

        $this->handleAuthorize($user);

        $this->validate($request, [
            'locale' => 'required',
            'title' => 'required|string|max:255',
            'subtitle' => 'required|string',
            'category_id' => 'required|numeric',
            'image' => 'required|file',
            'description' => 'required|string',
            'content' => 'required|string',
            'study_time' => 'nullable|numeric',
        ]);

        $storeData = $this->makeStoreData($request, $user);
        $blog = Blog::create($storeData);

        $this->handleStoreExtraData($request, $user, $blog);

        $directPublicationOfBlog = !empty(getGeneralOptionsSettings('direct_publication_of_blog'));
        if ($directPublicationOfBlog) {
            $createPostReward = RewardAccounting::calculateScore(Reward::CREATE_BLOG_BY_INSTRUCTOR);
            RewardAccounting::makeRewardAccounting($user->id, $createPostReward, Reward::CREATE_BLOG_BY_INSTRUCTOR, $blog->id, true);
        }

        $notifyOptions = [
            '[u.name]' => $user->full_name,
            '[blog_title]' => $blog->title,
        ];
        sendNotification("new_user_blog_post", $notifyOptions, 1);

        $toastData = [
            'title' => trans('public.request_success'),
            'msg' => trans('update.blog_created_success'),
            'status' => 'success'
        ];
        return redirect("/panel/blog/{$blog->id}/edit")->with(['toast' => $toastData]);
    }

    public function edit(Request $request, $post_id)
    {
        $this->authorize("panel_blog_new_article");

        $user = auth()->user();

        $this->handleAuthorize($user);

        $post = Blog::where('id', $post_id)
            ->where('author_id', $user->id)
            ->first();

        if (!empty($post)) {
            $locale = $request->get('locale', app()->getLocale());

            $blogCategories = BlogCategory::all();
            $otherPosts = Blog::query()->where('id', '!=', $post->id)
                ->with([
                    'author'
                ])->get();

            $data = [
                'pageTitle' => trans('public.edit') . ' | ' . $post->title,
                'blogCategories' => $blogCategories,
                'locale' => mb_strtolower($locale),
                'post' => $post,
                'otherPosts' => $otherPosts,
            ];

            return view('design_1.panel.blog.posts.create.index', $data);
        }

        abort(404);
    }

    public function update(Request $request, $post_id)
    {
        $this->authorize("panel_blog_new_article");

        $user = auth()->user();

        $this->handleAuthorize($user);

        $this->validate($request, [
            'title' => 'required|string|max:255',
            'subtitle' => 'required|string',
            'category_id' => 'required|numeric',
            'image' => 'nullable|file',
            'description' => 'required|string',
            'content' => 'required|string',
            'study_time' => 'nullable|numeric',
        ]);

        $blog = Blog::query()->where('id', $post_id)
            ->where('author_id', $user->id)
            ->first();

        if (!empty($blog)) {
            $storeData = $this->makeStoreData($request, $user);
            $blog->update($storeData);

            $this->handleStoreExtraData($request, $user, $blog);

            $toastData = [
                'title' => trans('public.request_success'),
                'msg' => trans('update.blog_updated_success'),
                'status' => 'success'
            ];
            return redirect("/panel/blog/{$blog->id}/edit")->with(['toast' => $toastData]);
        }

        abort(404);
    }

    public function delete(Request $request, $post_id)
    {
        $this->authorize("panel_blog_delete_article");

        if (!canDeleteContentDirectly()) {
            if ($request->ajax()) {
                return response()->json([], 422);
            } else {
                $toastData = [
                    'title' => trans('public.request_failed'),
                    'msg' => trans('update.it_is_not_possible_to_delete_the_content_directly'),
                    'status' => 'error'
                ];
                return redirect()->back()->with(['toast' => $toastData]);
            }
        }

        $user = auth()->user();

        $this->handleAuthorize($user);

        $post = Blog::where('id', $post_id)
            ->where('author_id', $user->id)
            ->first();

        if (!empty($post)) {
            $post->delete();
        }

        return response()->json([
            'code' => 200,
        ]);
    }

    private function makeStoreData(Request $request, $user, $blog = null)
    {
        $data = $request->all();
        $directPublicationOfBlog = !empty(getGeneralOptionsSettings('direct_publication_of_blog'));

        return [
            'slug' => !empty($blog) ? $blog->slug : Blog::makeSlug($data['title']),
            'category_id' => $data['category_id'],
            'author_id' => $user->id,
            'enable_comment' => true,
            'study_time' => $data['study_time'] ?? null,
            'status' => $directPublicationOfBlog ? 'publish' : 'pending',
            'created_at' => time(),
            'updated_at' => time(),
        ];
    }

    private function handleStoreExtraData(Request $request, $user, $blog)
    {
        $data = $request->all();

        BlogTranslation::updateOrCreate([
            'blog_id' => $blog->id,
            'locale' => mb_strtolower($data['locale']),
        ], [
            'title' => $data['title'],
            'subtitle' => $data['subtitle'] ?? null,
            'description' => $data['description'],
            'meta_description' => strip_tags($data['description']),
            'content' => $data['content'],
        ]);

        $imagePath = $blog->image ?? null;

        if (!empty($request->file('image'))) {
            $imagePath = $this->uploadFile($request->file('image'), "blog/{$blog->id}", 'image', $user->id);
        }

        $blog->update([
            'image' => $imagePath
        ]);
    }
}
