<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\RelatedPost;
use Illuminate\Http\Request;
use Validator;

class BlogRelatedPostsController extends Controller
{
    public function store(Request $request, $postId)
    {
        $user = auth()->user();
        $post = Blog::query()->where('id', $postId)
            ->where('author_id', $user->id)
            ->first();

        if (empty($post)) {
            abort(404);
        }

        $data = $request->get('ajax')['new'];

        $validator = Validator::make($data, [
            'post_id' => 'required|exists:blog,id',
        ]);

        if ($validator->fails()) {
            return response([
                'code' => 422,
                'errors' => $validator->errors(),
            ], 422);
        }

        RelatedPost::query()->updateOrCreate([
            'targetable_id' => $post->id,
            'targetable_type' => "App\Models\Blog",
            'post_id' => $data['post_id']
        ], [
            'order' => null,
        ]);

        return response()->json([
            'code' => 200,
            'title' => trans('public.request_success'),
            'msg' => trans('update.related_post_assigned_successfully'),
        ], 200);
    }

    public function update(Request $request, $postId, $id)
    {
        $user = auth()->user();
        $post = Blog::query()->where('id', $postId)
            ->where('author_id', $user->id)
            ->first();

        if (empty($post)) {
            abort(404);
        }

        $data = $request->get('ajax')[$id];

        $validator = Validator::make($data, [
            'post_id' => 'required|exists:blog,id',
        ]);

        if ($validator->fails()) {
            return response([
                'code' => 422,
                'errors' => $validator->errors(),
            ], 422);
        }

        $item = RelatedPost::query()
            ->where('targetable_id', $post->id)
            ->where('targetable_type', "App\Models\Blog")
            ->where('id', $id)
            ->first();

        if (!empty($item)) {
            $item->update([
                'post_id' => $data['post_id']
            ]);
        }

        return response()->json([
            'code' => 200,
            'title' => trans('public.request_success'),
            'msg' => trans('update.related_post_assigned_successfully'),
        ], 200);
    }

    public function destroy(Request $request, $postId, $id)
    {
        $user = auth()->user();
        $post = Blog::query()->where('id', $postId)
            ->where('author_id', $user->id)
            ->first();

        if (empty($post)) {
            abort(404);
        }

        $item = RelatedPost::query()
            ->where('targetable_id', $post->id)
            ->where('targetable_type', "App\Models\Blog")
            ->where('id', $id)
            ->first();

        if (!empty($item)) {
            $item->delete();
        }

        return response()->json([
            'code' => 200,
            'title' => trans('public.request_success'),
            'msg' => trans('update.related_post_deleted_successfully'),
        ], 200);
    }


}
