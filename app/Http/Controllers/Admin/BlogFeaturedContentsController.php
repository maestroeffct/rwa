<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Setting;
use App\User;
use Illuminate\Http\Request;

class BlogFeaturedContentsController extends Controller
{
    public function index()
    {
        $this->authorize('admin_blog_featured_contents');
        removeContentLocale();

        $posts = Blog::query()->get();
        $authors = User::query()->select('id', 'username', 'full_name', 'role_id', 'role_name', 'avatar', 'avatar_settings')
            ->whereHas('blog')
            ->get();

        $setting = Setting::where('page', 'general')
            ->where('name', Setting::$blogFeaturedContentsSettingsName)
            ->first();

        $settingValues = !empty($setting) ? $setting->value : null;

        if (!empty($settingValues)) {
            $settingValues = json_decode($settingValues, true);
        }


        $data = [
            'pageTitle' => trans('update.featured_categories'),
            'posts' => $posts,
            'authors' => $authors,
            'settingValues' => $settingValues,
        ];

        return view('admin.blog.featured_contents', $data);
    }

}
