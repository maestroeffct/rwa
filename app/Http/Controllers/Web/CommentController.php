<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\CommentReport;
use App\Models\Reward;
use App\Models\RewardAccounting;
use App\Models\Webinar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
{

    public function getComments(Request $request, $itemType, $itemId)
    {
        $itemName = "{$itemType}_id";

        $page = $request->get('page', 1);
        $count = 10;

        $query = Comment::query()->where($itemName, $itemId);
        $query->where('status', 'active');
        $query->whereNull('reply_id');
        $query->whereNull('review_id');

        $query->with([
            'user' => function ($query) {
                $query->select('id', 'full_name', 'role_name', 'role_id', 'username', 'avatar', 'avatar_settings');
            },
            'replies' => function ($query) {
                $query->where('status', 'active');
                $query->with([
                    'user' => function ($query) {
                        $query->select('id', 'full_name', 'role_name', 'role_id', 'username', 'avatar', 'avatar_settings');
                    }
                ]);
            }
        ]);
        $query->orderBy('created_at', 'desc');

        $total = $query->count();

        $query->limit($count);
        $query->offset(($page - 1) * $count);

        $comments = $query->get();
        $hasMore = $total > ($page * $count);

        if ($request->ajax()) {
            $html = (string)view()->make('design_1.web.components.comments.all_cards', [
                'comments' => $comments,
                'commentForItemId' => $itemId,
                'commentForItemName' => $itemName,
            ]);

            return response()->json([
                'code' => 200,
                'html' => $html,
                'has_more' => $hasMore,
            ]);
        }

        return [
            'comments' => $comments,
            'comments_count' => $total,
            'has_more' => $hasMore,
        ];
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $user = auth()->user();

        $validator = Validator::make($data, [
            'item_id' => 'required',
            'comment' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'code' => 422,
                'errors' => $validator->errors(),
            ], 422);
        }

        $item_name = $data['item_name'] ?? 'webinar_id';
        $item_id = $data['item_id'];

        $comment = Comment::create([
            $item_name => $item_id,
            'user_id' => $user->id,
            'comment' => $data['comment'],
            'reply_id' => $data['reply_id'] ?? null,
            'status' => $this->getStatus(),
            'created_at' => time()
        ]);

        if ($item_name == 'webinar_id') {
            $webinar = Webinar::FindOrFail($item_id);
            $notifyOptions = [
                '[c.title]' => $webinar->title,
                '[u.name]' => $user->full_name
            ];
            sendNotification('new_comment', $notifyOptions, 1);
        } elseif ($item_name == 'product_id') {
            $product = $comment->product;

            $notifyOptions = [
                '[p.title]' => $product->title,
                '[u.name]' => $user->full_name
            ];
            sendNotification('product_new_comment', $notifyOptions, 1);
        } elseif ($item_name == 'blog_id') {
            $blog = $comment->blog;

            if (!empty($blog) and !$blog->author->isAdmin()) {
                $notifyOptions = [
                    '[blog_title]' => $blog->title,
                    '[u.name]' => $user->full_name
                ];
                sendNotification('new_comment_for_instructor_blog_post', $notifyOptions, $blog->author->id);

                $buyStoreReward = RewardAccounting::calculateScore(Reward::COMMENT_FOR_INSTRUCTOR_BLOG);
                RewardAccounting::makeRewardAccounting($comment->user_id, $buyStoreReward, Reward::COMMENT_FOR_INSTRUCTOR_BLOG, $comment->id);
            }
        }

        return response()->json([
            'code' => 200,
            'title' => trans('product.comment_success_store'),
            'msg' => trans('product.comment_success_store_msg'),
        ]);
    }

    public function storeReply(Request $request, $commentId)
    {
        $this->validate($request, [
            'item_id' => 'required',
            'reply' => 'required|string',
        ]);

        $item_name = $request->get('item_name');
        $item_id = $request->get('item_id');

        Comment::create([
            $item_name => $item_id,
            'user_id' => auth()->user()->id,
            'comment' => $request->input('reply'),
            'reply_id' => $commentId,
            'status' => $this->getStatus(),
            'created_at' => time()
        ]);

        return response()->json([
            'code' => 200,
            'title' => trans('product.comment_success_store'),
            'msg' => trans('product.comment_success_store_msg'),
        ]);
    }

    public function update(Request $request, $id)
    {
        $user = auth()->user();

        $this->validate($request, [
            'webinar_id' => 'required',
            'comment' => 'nullable',
        ]);

        $comment = Comment::where('id', $id)
            ->where('user_id', $user->id)
            ->first();
        if (!empty($comment)) {
            $comment->update([
                'webinar_id' => $request->input('webinar_id'),
                'user_id' => $user->id,
                'comment' => $request->input('comment'),
                'reply_id' => $request->input('reply_id'),
                'status' => $this->getStatus(),
                'created_at' => time()
            ]);

            return redirect()->back();
        }

        abort(404);
    }

    public function getStatus()
    {
        $status = Comment::$pending;
        if (!empty(getGeneralOptionsSettings('direct_publication_of_comments'))) {
            $status = Comment::$active;
        }

        return $status;
    }

    public function destroy(Request $request, $id)
    {
        $user = auth()->user();
        $comment = Comment::where('id', $id)
            ->where('user_id', $user->id)
            ->first();

        if (!empty($comment)) {
            $comment->delete();
        }

        if ($request->ajax()) {
            return response()->json([
                'code' => 200,
            ]);
        }

        return redirect()->back();
    }

    public function getReportModal(Request $request)
    {
        if ($request->ajax()) {
            $commentId = $request->get('comment');
            $itemId = $request->get('item');
            $itemType = $request->get('type');

            $comment = Comment::query()->where('id', $commentId)
                ->where($itemType, $itemId)
                ->first();

            if (!empty($comment)) {
                $data = [
                    'comment' => $comment,
                    'itemId' => $itemId,
                    'itemType' => $itemType,
                ];

                $html = (string)view()->make("design_1.web.components.comments.report_modal", $data);

                return response()->json([
                    'code' => 200,
                    'html' => $html,
                ]);
            }
        }

        abort(403);
    }

    public function report(Request $request, $id)
    {
        $data = $request->all();

        $validator = Validator::make($data, [
            'item_id' => 'required',
            'item_name' => 'required',
            'message' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'code' => 422,
                'errors' => $validator->errors(),
            ], 422);
        }

        $itemName = $data['item_name'];
        $itemId = $data['item_id'];

        $user = auth()->user();

        if (!empty($user)) {
            $comment = Comment::query()->where('id', $id)
                ->where($itemName, $itemId)
                ->first();

            if (!empty($comment)) {
                CommentReport::create([
                    $itemName => $itemId,
                    'user_id' => $user->id,
                    'comment_id' => $comment->id,
                    'message' => $data['message'],
                    'created_at' => time()
                ]);

                $notifyOptions = [
                    '[u.name]' => $user->full_name,
                    '[content_type]' => trans('admin/main.comment')
                ];
                sendNotification("new_report_item_for_admin", $notifyOptions, 1);

            } else {
                return response()->json([
                    'code' => 422,
                    'errors' => [
                        'message' => [trans('update.the_comment_was_not_found')]
                    ],
                ], 422);
            }
        }

        return response()->json([
            'code' => 200,
            'title' => trans('public.request_success'),
            'msg' => trans('panel.report_success'),
        ], 200);
    }
}
