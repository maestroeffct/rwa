<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Newsletter;
use App\Models\Reward;
use App\Models\RewardAccounting;
use App\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function makeNewsletter(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'newsletter_email' => 'required|email|max:255|unique:newsletters,email'
        ], [
            'newsletter_email.required' => [trans('update.entering_the_email_for_the_newsletter_is_required')],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'code' => 422,
                'errors' => $validator->errors(),
            ], 422);
        }


        $user_id = null;
        $email = $data['newsletter_email'];

        if (auth()->check()) {
            $user = auth()->user();

            if (empty($user->email)) {
                $user->update([
                    'email' => $email,
                    'newsletter' => true,
                ]);
            } else if ($user->email == $email) {
                $user_id = $user->id;

                $user->update([
                    'newsletter' => true,
                ]);
            }
        }

        $check = Newsletter::where('email', $data['newsletter_email'])->first();

        if (!empty($check)) {
            if (!empty($check->user_id) and !empty($user_id) and $check->user_id != $user_id) {
                return response()->json([
                    'toast_alert' => [
                        'title' => trans('public.request_failed'),
                        'msg' => trans('update.this_email_used_by_another_user'),
                    ]
                ], 422);
            } elseif (empty($check->user_id) and !empty($user_id)) {
                $check->update([
                    'user_id' => $user_id
                ]);
            }
        } else {
            Newsletter::create([
                'user_id' => $user_id,
                'email' => $data['newsletter_email'],
                'created_at' => time()
            ]);
        }

        if (!empty($user_id)) {
            $newsletterReward = RewardAccounting::calculateScore(Reward::NEWSLETTERS);
            RewardAccounting::makeRewardAccounting($user_id, $newsletterReward, Reward::NEWSLETTERS, $user_id, true);
        }

        return response()->json([
            'code' => 200,
            'title' => trans('public.request_success'),
            'msg' => trans('site.create_newsletter_success'),
        ]);
    }

    public function search(Request $request)
    {
        $term = $request->get('term');
        $option = $request->get('option', null);
        $user = auth()->user();

        if (!empty($term)) {
            $query = User::select('id', 'full_name')
                ->where(function ($query) use ($term) {
                    $query->where('full_name', 'like', '%' . $term . '%');
                    $query->orWhere('email', 'like', '%' . $term . '%');
                    $query->orWhere('mobile', 'like', '%' . $term . '%');
                });

            /*->where('id', '<>', $user->id)
                ->whereNotIn('role_name', ['admin']);*/

            if (!empty($option) and $option == 'just_teachers') {
                $query->where('role_name', 'teacher');
            }

            if ($option == "just_student_role") {
                $query->where('role_name', Role::$user);
            }

            if ($option == "just_post_authors") {
                $query->whereHas('blog');
            }

            if ($option === "except_user") {
                $query->where('role_name', '!=', Role::$user);
            }

            $users = $query->get();

            return response()->json($users, 200);
        }

        return response('', 422);
    }

}
