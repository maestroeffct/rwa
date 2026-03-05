<?php

namespace App\Http\Controllers\Web;

use App\Enums\MorphTypesEnum;
use App\Http\Controllers\Controller;
use App\Mixins\Logs\VisitLogMixin;
use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\BlogFeaturedCategory;
use App\User;
use Illuminate\Http\Request;

class BlogController extends Controller
{

    public function index(Request $request, $categorySlug = null)
    {
        $selectedCategory = null;
        $selectedAuthor = null;

        if ($categorySlug) {
            $selectedCategory = BlogCategory::query()->where('slug', $categorySlug)
                ->withCount([
                    'blog' => function ($query) {
                        $query->where('status', 'publish');
                    }
                ])
                ->first();
        }

        if ($request->get('author')) {
            $selectedAuthor = User::query()->where('username', $request->get('author'))
                ->withCount([
                    'blog' => function ($query) {
                        $query->where('status', 'publish');
                    }
                ])
                ->first();
        }

        $query = Blog::query()->where('status', 'publish');

        $filterMaxStudyTime = deepClone($query)->max('study_time');

        $query = $this->handleFilters($request, $query, $selectedCategory, $selectedAuthor);

        $getListData = $this->getListData($request, $query);

        if ($request->ajax()) {
            return $getListData;
        }

        $blogCategories = BlogCategory::query()
            ->withCount([
                'blog' => function ($query) {
                    $query->where('status', 'publish');
                }
            ])
            ->get();

        $seoSettings = getSeoMetas('blog');
        $pageTitle = !empty($seoSettings['title']) ? $seoSettings['title'] : trans('home.blog');
        $pageDescription = !empty($seoSettings['description']) ? $seoSettings['description'] : trans('home.blog');
        $pageRobot = getPageRobot('blog');

        $data = [
            'pageTitle' => $pageTitle,
            'pageDescription' => $pageDescription,
            'pageRobot' => $pageRobot,
            'blogCategories' => $blogCategories,
            'selectedCategory' => $selectedCategory,
            'selectedAuthor' => $selectedAuthor,
            'filterMaxStudyTime' => $filterMaxStudyTime,
            'popularPosts' => $this->getPopularPosts(),
            'recentPosts' => $this->getRecentPosts(),
        ];

        $data = array_merge($data, $getListData);

        if (empty($selectedCategory)) {
            $data = array_merge($data, $this->getBlogFeaturedContents());
        }

        return view('design_1.web.blog.lists.index', $data);
    }

    private function handleFilters(Request $request, $query, $selectedCategory = null, $selectedAuthor = null)
    {
        $min_study_time = $request->get('min_study_time');
        $max_study_time = $request->get('max_study_time');
        $authorId = $request->get('author_id');
        $sort = $request->get('sort');

        if (!empty($selectedAuthor)) {
            $query->where('author_id', $selectedAuthor->id);
        }

        if (!empty($selectedCategory)) {
            $query->where('category_id', $selectedCategory->id);
        }

        if (!empty($min_study_time) and !empty($max_study_time)) {
            $query->whereBetween('study_time', [$min_study_time, $max_study_time]);
        }

        if (!empty($authorId)) {
            $query->where('author_id', $authorId);
        }

        if (!empty($sort)) {
            switch ($sort) {
                case 'newest':
                    $query->orderBy('created_at', 'desc');
                    break;
                case 'oldest':
                    $query->orderBy('created_at', 'asc');
                    break;
                case 'most_views':
                    $query->orderBy('visit_count', 'desc');
                    break;
            }
        } else {
            $query->orderBy('created_at', 'desc');
        }

        return $query;
    }

    private function getListData(Request $request, $query)
    {
        $page = $request->get('page') ?? 1;
        $count = 9;

        $total = $query->count();

        $query->limit($count);
        $query->offset(($page - 1) * $count);

        $posts = $query->with([
            'category',
            'author' => function ($query) {
                $query->select('id', 'username', 'full_name', 'bio', 'avatar', 'avatar_settings', 'role_id', 'role_name');
            }
        ])
            ->withCount([
                'comments' => function ($query) {
                    $query->where('status', 'active');
                }
            ])
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
        $html = (string)view()->make('design_1.web.blog.components.cards.grids.index', [
            'posts' => $posts,
            'gridCardClassName' => "col-12 col-md-6 col-lg-4 mt-36",
            'withoutStyles' => true
        ]);

        return response()->json([
            'data' => $html,
            'pagination' => $this->makePagination($request, $posts, $total, $count, true)
        ]);
    }

    private function getBlogFeaturedContents(): array
    {
        $data = [];
        $settings = getBlogFeaturedContentsSettings();

        if (!empty($settings) and !empty($settings['featured_posts'])) {
            $data['featuredPosts'] = Blog::query()->whereIn('id', $settings['featured_posts'])
                ->with([
                    'author' => function ($query) {
                        $query->select('id', 'username', 'full_name', 'bio', 'avatar', 'avatar_settings', 'role_id', 'role_name');
                    }
                ])
                ->withCount([
                    'comments' => function ($query) {
                        $query->where('status', 'active');
                    }
                ])
                ->get();
        }

        if (!empty($settings) and !empty($settings['featured_authors'])) {
            $data['featuredAuthors'] = User::query()->select('id', 'username', 'full_name', 'role_id', 'role_name', 'avatar', 'avatar_settings')
                ->whereIn('id', $settings['featured_authors'])
                ->withCount([
                    'blog' => function ($query) {
                        $query->where('status', 'publish');
                    }
                ])
                ->get();
        }

        $data['featuredCategories'] = BlogFeaturedCategory::query()
            ->with([
                'category' => function ($query) {
                    $query->withCount([
                        'blog' => function ($query) {
                            $query->where('status', 'publish');
                        }
                    ]);
                }
            ])
            ->get();

        return $data;
    }

    public function show(Request $request, $slug)
    {
        $post = Blog::where('slug', $slug)
            ->where('status', 'publish')
            ->with([
                'category',
                'author' => function ($query) {
                    $query->select('id', 'username', 'full_name', 'role_id', 'avatar', 'role_name', 'bio', 'about');
                    $query->with('role');
                    $query->withCount([
                        'blog' => function ($query) {
                            $query->where('status', 'publish');
                        }
                    ]);
                },
                'relatedPosts' => function ($query) {
                    $query->with([
                        'post'
                    ]);
                }
            ])
            ->first();

        if (!empty($post)) {
            $post->update(['visit_count' => $post->visit_count + 1]);

            $post->author->someRandomPosts = Blog::query()->where('author_id', $post->author->id)
                ->where('id', '!=', $post->id)
                ->where('status', 'publish')
                ->inRandomOrder()
                ->limit(3)
                ->get();

            $blogCategories = BlogCategory::all();
            $popularPosts = $this->getPopularPosts();

            $commentController = new CommentController();
            $postComments = $commentController->getComments($request, 'blog', $post->id);

            // Visit Logs
            $visitLogMixin = new VisitLogMixin();
            $visitLogMixin->storeVisit($request, $post->author_id, $post->id, MorphTypesEnum::BLOG);

            $pageRobot = getPageRobot('blog');

            $data = [
                'pageTitle' => $post->title,
                'pageDescription' => $post->meta_description,
                'pageMetaImage' => $post->image,
                'blogCategories' => $blogCategories,
                'popularPosts' => $popularPosts,
                'pageRobot' => $pageRobot,
                'post' => $post,
                'postComments' => $postComments,
            ];

            return view('design_1.web.blog.show.index', $data);
        }

        abort(404);
    }

    private function getPopularPosts()
    {
        return Blog::where('status', 'publish')
            ->orderBy('visit_count', 'desc')
            ->limit(3)
            ->get();
    }

    private function getRecentPosts()
    {
        return Blog::where('status', 'publish')
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get();
    }

    public function getShareModal($slug)
    {
        $post = Blog::where('slug', $slug)
            ->where('status', 'publish')
            ->first();

        if (!empty($post)) {
            $data = [
                'post' => $post,
            ];

            $html = (string)view()->make('design_1.web.blog.components.modals.share_modal', $data);

            return response()->json([
                'code' => 200,
                'html' => $html,
            ]);
        }

        abort(404);
    }
}
