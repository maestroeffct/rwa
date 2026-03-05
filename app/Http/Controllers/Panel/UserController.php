<?php

namespace App\Http\Controllers\Panel;

use App\Bitwise\UserLevelOfTraining;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Web\traits\UserFormFieldsTrait;
use App\Mixins\Geo\Geo;
use App\Mixins\RegistrationPackage\UserPackage;
use App\Models\Category;
use App\Models\DeleteAccountRequest;
use App\Models\Newsletter;
use App\Models\Region;
use App\Models\ReserveMeeting;
use App\Models\Reward;
use App\Models\RewardAccounting;
use App\Models\Role;
use App\Models\UserBank;
use App\Models\UserLoginHistory;
use App\Models\UserMeta;
use App\Models\UserOccupation;
use App\Models\UserSelectedBank;
use App\Models\UserSelectedBankSpecification;
use App\Models\UserZoomApi;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    use UserFormFieldsTrait;

    public function setting(Request $request, $step = "basic_information")
    {
        $this->authorize("panel_others_profile_setting");

        $user = auth()->user();

        $data = [
            'pageTitle' => trans('panel.settings'),
            'user' => $user,
        ];
        $data = array_merge($data, $this->getUserEditPageData($request, $user, $step));

        return view('design_1.panel.settings.index', $data);
    }

    public function getUserEditPageData(Request $request, $user, $step): array
    {
        $categories = Category::getCategories();

        $userMetas = $user->userMetas;

        if (!empty($userMetas)) {
            foreach ($userMetas as $meta) {
                $user->{$meta->name} = $meta->value;
            }
        }

        $occupations = $user->occupations->pluck('category_id')->toArray();


        $userLanguages = getGeneralSettings('user_languages');
        if (!empty($userLanguages) and is_array($userLanguages)) {
            $userLanguages = getLanguages($userLanguages);
        } else {
            $userLanguages = [];
        }

        $countries = null;
        $provinces = null;
        $cities = null;
        $districts = null;
        $attachments = null;
        $userLoginHistories = null;
        $formFieldsHtml = null;

        if ($step == "extra_information") {
            $countries = Region::select(DB::raw('*, ST_AsText(geo_center) as geo_center'))
                ->where('type', Region::$country)
                ->get();

            $userType = "organization";
            if ($user->isTeacher()) {
                $userType = "teacher";
            } elseif ($user->isUser()) {
                $userType = "user";
            }

            $formFieldsHtml = $this->getFormFieldsByUserType($request, $userType, true, $user);

        } elseif ($step == "about") {
            $attachments = $user->profileAttachments;
        } elseif ($step == "login_history") {
            $userLoginHistories = UserLoginHistory::query()->where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->get();
        }

        $userBanks = UserBank::query()
            ->with([
                'specifications'
            ])
            ->orderBy('created_at', 'desc')
            ->get();

        return [
            'categories' => $categories,
            'educations' => $userMetas->where('name', 'education'),
            'experiences' => $userMetas->where('name', 'experience'),
            'occupations' => $occupations,
            'userLanguages' => $userLanguages,
            'currentStep' => $step,
            'countries' => $countries,
            'provinces' => $provinces,
            'cities' => $cities,
            'districts' => $districts,
            'userBanks' => $userBanks,
            'formFieldsHtml' => $formFieldsHtml,
            'attachments' => $attachments,
            'userLoginHistories' => $userLoginHistories,
        ];
    }

    public function update(Request $request)
    {
        $data = $request->all();

        $organization = null;
        if (!empty($data['organization_id']) and !empty($data['user_id'])) {
            $organization = auth()->user();
            $user = User::where('id', $data['user_id'])
                ->where('organ_id', $organization->id)
                ->first();
        } else {
            $user = auth()->user();
        }

        $step = $data['step'] ?? "basic_information";

        $rules = [];

        if ($step == "basic_information") {
            $registerMethod = getGeneralSettings('register_method') ?? 'mobile';

            $rules = [
                'full_name' => 'required|string',
                'email' => (($registerMethod == 'email') ? 'required' : 'nullable') . '|email|max:255|unique:users,email,' . $user->id,
                'mobile' => (($registerMethod == 'mobile') ? 'required' : 'nullable') . '|numeric|unique:users,mobile,' . $user->id,
            ];
        }

        $this->validate($request, $rules);

        if (!empty($user)) {

            if (!empty($data['password'])) {
                $this->validate($request, [
                    'password' => 'required|confirmed|min:6',
                ]);

                $user->update([
                    'password' => User::generatePassword($data['password'])
                ]);
            }

            $updateData = [];
            $updateUserMeta = [];

            if ($step == "basic_information") {
                $joinNewsletter = (!empty($data['join_newsletter']) and $data['join_newsletter'] == 'on');

                $updateData = [
                    'full_name' => $data['full_name'],
                    'email' => $data['email'],
                    'mobile' => $data['mobile'],
                    'language' => $data['language'] ?? null,
                    'timezone' => $data['timezone'] ?? null,
                    'currency' => $data['currency'] ?? null,
                    'offline' => (!empty($data['offline']) and $data['offline'] == "on"),
                    'offline_message' => (!empty($data['offline_message'])) ? $data['offline_message'] : null,
                    'newsletter' => $joinNewsletter,
                    'public_message' => (!empty($data['public_message']) and $data['public_message'] == 'on'),
                    'enable_profile_statistics' => (!empty($data['enable_profile_statistics']) and $data['enable_profile_statistics'] == 'on'),
                    'auto_renew_subscription' => (!empty($data['auto_renew_subscription']) and $data['auto_renew_subscription'] == 'on'),
                ];

                $this->handleNewsletter($data['email'], $user->id, $joinNewsletter);
            } elseif ($step == "extra_information") {
                $updateData = [
                    "meeting_type" => $data['meeting_type'] ?? null,
                    "level_of_training" => !empty($data['level_of_training']) ? (new UserLevelOfTraining())->getValue($data['level_of_training']) : null,
                    "country_id" => $data['country_id'] ?? null,
                    "province_id" => $data['province_id'] ?? null,
                    "city_id" => $data['city_id'] ?? null,
                    "district_id" => $data['district_id'] ?? null,
                    "location" => (!empty($data['latitude']) and !empty($data['longitude'])) ? DB::raw("POINT(" . $data['latitude'] . "," . $data['longitude'] . ")") : null,
                    "address" => $data['address'] ?? null,
                ];

                $updateUserMeta = [
                    "birthday" => !empty($data['birthday']) ? convertTimeToUTCzone($data['birthday'])->getTimestamp() : null,
                    "gender" => $data['gender'] ?? null,
                ];

                // Handle User Socials
                $updateUserMeta['socials'] = (!empty($data['socials']) and is_array($data['socials'])) ? json_encode($data['socials']) : null;

                // Store Additional Forms
                $this->handleUserExtraForm($request, $user);

            } elseif ($step == "financial") {

                // Update User Bank Account
                if (!empty($data['bank_id'])) {
                    $this->handleUserBankAccount($user, $data);
                }

                $updateData = [
                    'identity_scan' => $this->handleUploadImagesAndFiles($request, $user, "identity_scan"),
                    'certificate' => $this->handleUploadImagesAndFiles($request, $user, "certificate"),
                ];

            } elseif ($step == "images") {

                $updateData = [
                    'avatar' => $this->handleUploadImagesAndFiles($request, $user, "avatar"),
                    'profile_video' => $this->handleUploadImagesAndFiles($request, $user, "profile_video"),
                    'cover_img' => $this->handleUploadImagesAndFiles($request, $user, "cover_img"),
                    'profile_secondary_image' => $this->handleUploadImagesAndFiles($request, $user, "profile_secondary_image"),
                ];


                if (!empty($request->file("signature_img"))) {
                    $signatureImgPath = $this->handleUploadImagesAndFiles($request, $user, "signature_img");

                    $updateUserMeta = [
                        'signature' => $signatureImgPath
                    ];
                }

            } elseif ($step == "about") {
                $updateData = [
                    'about' => $data['about'] ?? null,
                    'bio' => $data['bio'] ?? null,
                ];

                if (!$user->isUser()) {
                    UserOccupation::where('user_id', $user->id)->delete();

                    if (!empty($data['occupations'])) {
                        foreach ($data['occupations'] as $category_id) {
                            UserOccupation::create([
                                'user_id' => $user->id,
                                'category_id' => $category_id
                            ]);
                        }
                    }
                }

            } elseif ($step == "zoom") {

                if (!empty($data['zoom_api_key']) and !empty($data['zoom_api_secret'])) {
                    UserZoomApi::updateOrCreate(
                        [
                            'user_id' => $user->id,
                        ],
                        [
                            'api_key' => $data['zoom_api_key'] ?? null,
                            'api_secret' => $data['zoom_api_secret'] ?? null,
                            'account_id' => $data['zoom_account_id'] ?? null,
                            'created_at' => time()
                        ]
                    );
                } else {
                    UserZoomApi::where('user_id', $user->id)->delete();
                }
            }

            if (!empty($updateData)) {
                $user->update($updateData);
            }

            if (!empty($updateUserMeta)) {
                foreach ($updateUserMeta as $metaName => $metaValue) {
                    UserMeta::query()->where('user_id', $user->id)->where('name', $metaName)->delete();

                    if (!empty($metaValue)) {
                        UserMeta::query()->create([
                            'user_id' => $user->id,
                            'name' => $metaName,
                            'value' => $metaValue
                        ]);
                    }
                }
            }

            $url = "/panel/setting/step/{$step}";
            if (!empty($organization)) {
                $userType = $user->isTeacher() ? 'instructors' : 'students';
                $url = "/panel/manage/{$userType}/{$user->id}/edit";
            }

            $toastData = [
                'title' => trans('public.request_success'),
                'msg' => trans('panel.user_setting_success'),
                'status' => 'success'
            ];
            return redirect($url)->with(['toast' => $toastData]);
        }
        abort(404);
    }

    private function handleUserBankAccount($user, $data)
    {
        UserSelectedBank::query()->where('user_id', $user->id)->delete();

        $userSelectedBank = UserSelectedBank::query()->create([
            'user_id' => $user->id,
            'user_bank_id' => $data['bank_id']
        ]);

        if (!empty($data['bank_specifications'])) {
            $specificationInsert = [];

            foreach ($data['bank_specifications'] as $specificationId => $specificationValue) {
                if (!empty($specificationValue)) {
                    $specificationInsert[] = [
                        'user_selected_bank_id' => $userSelectedBank->id,
                        'user_bank_specification_id' => $specificationId,
                        'value' => $specificationValue
                    ];
                }
            }

            UserSelectedBankSpecification::query()->insert($specificationInsert);
        }
    }

    private function handleUploadImagesAndFiles(Request $request, $user, $name)
    {
        $path = $user->{$name};

        if (!empty($request->file($name))) {
            if (!empty($path)) {
                $this->removeFile($path);
            }

            $path = $this->uploadFile($request->file($name), "setting", $name, $user->id);
        }

        return $path;
    }

    private function handleUserExtraForm(Request $request, $user)
    {
        $userType = "organization";
        if ($user->isTeacher()) {
            $userType = "teacher";
        } elseif ($user->isUser()) {
            $userType = "user";
        }

        $form = $this->getFormFieldsByType($userType);

        if (!empty($form)) {
            $errors = $this->checkFormRequiredFields($request, $form);

            if (count($errors)) {
                return redirect()->back()->withErrors($errors);
            }

            $this->storeFormFields($request->all(), $user);
        }

        return "ok";
    }

    private function handleNewsletter($email, $user_id, $joinNewsletter)
    {
        $check = Newsletter::where('email', $email)->first();

        if ($joinNewsletter) {
            if (empty($check)) {
                Newsletter::create([
                    'user_id' => $user_id,
                    'email' => $email,
                    'created_at' => time()
                ]);
            } else {
                $check->update([
                    'user_id' => $user_id,
                ]);
            }

            $newsletterReward = RewardAccounting::calculateScore(Reward::NEWSLETTERS);
            RewardAccounting::makeRewardAccounting($user_id, $newsletterReward, Reward::NEWSLETTERS, $user_id, true);
        } elseif (!empty($check)) {
            $reward = RewardAccounting::where('user_id', $user_id)
                ->where('item_id', $user_id)
                ->where('type', Reward::NEWSLETTERS)
                ->where('status', RewardAccounting::ADDICTION)
                ->first();

            if (!empty($reward)) {
                $reward->delete();
            }

            $check->delete();
        }
    }

    public function storeMetas(Request $request)
    {
        $data = $request->all();

        if (!empty($data['name']) and !empty($data['value'])) {

            if (!empty($data['user_id'])) {
                $organization = auth()->user();
                $user = User::where('id', $data['user_id'])
                    ->where('organ_id', $organization->id)
                    ->first();
            } else {
                $user = auth()->user();
            }

            UserMeta::create([
                'user_id' => $user->id,
                'name' => $data['name'],
                'value' => $data['value'],
            ]);

            return response()->json([
                'code' => 200
            ], 200);
        }

        return response()->json([], 422);
    }

    public function updateMeta(Request $request, $meta_id)
    {
        $data = $request->all();
        $user = auth()->user();

        if (!empty($data['user_id'])) {
            $checkUser = User::find($data['user_id']);

            if ((!empty($checkUser) and ($data['user_id'] == $user->id) or $checkUser->organ_id == $user->id)) {
                $meta = UserMeta::where('id', $meta_id)
                    ->where('user_id', $data['user_id'])
                    ->where('name', $data['name'])
                    ->first();

                if (!empty($meta)) {
                    $meta->update([
                        'value' => $data['value']
                    ]);

                    return response()->json([
                        'code' => 200
                    ], 200);
                }

                return response()->json([
                    'code' => 403
                ], 200);
            }
        }

        return response()->json([], 422);
    }

    public function deleteMeta(Request $request, $meta_id)
    {
        $data = $request->all();
        $user = auth()->user();

        if (!empty($data['user_id'])) {
            $checkUser = User::find($data['user_id']);

            if (!empty($checkUser) and ($data['user_id'] == $user->id or $checkUser->organ_id == $user->id)) {
                $meta = UserMeta::where('id', $meta_id)
                    ->where('user_id', $data['user_id'])
                    ->first();

                $meta->delete();

                return response()->json([
                    'code' => 200
                ], 200);
            }
        }

        return response()->json([], 422);
    }

    public function offlineToggle(Request $request)
    {
        $user = auth()->user();

        $message = $request->get('message');
        $toggle = $request->get('toggle');
        $toggle = (!empty($toggle) and $toggle == 'true');

        $user->offline = $toggle;
        $user->offline_message = $message;

        $user->save();

        return response()->json([
            'code' => 200
        ], 200);
    }

    public function deleteAccount(Request $request)
    {
        $user = auth()->user();

        if (!empty($user)) {
            DeleteAccountRequest::updateOrCreate([
                'user_id' => $user->id,
            ], [
                'created_at' => time()
            ]);

            return response()->json([
                'code' => 200,
                'title' => trans('public.request_success'),
                'text' => trans('update.delete_account_request_stored_msg'),
                'dont_reload' => true
            ]);
        }

        abort(403);
    }

    public function getUserInfo($id)
    {
        $user = User::query()->select('id', 'username', 'full_name', 'role_id', 'role_name', 'avatar', 'avatar_settings')
            ->where('id', $id)
            ->first();

        if (!empty($user)) {
            $user->avatar = $user->getAvatar(40);
            $user->profile_url = $user->getProfileUrl();

            return response()->json([
                'user' => $user
            ]);
        }

        return response()->json([], 422);
    }

    public function deleteUserMedia($type)
    {
        $user = auth()->user();
        $items = ['avatar', 'cover_img', 'profile_secondary_image', 'profile_video', 'signature_img'];

        if (in_array($type, $items)) {
            if ($type == 'signature_img') {
                $user->userMetas()->where('name', 'signature')->delete();
            } else {
                $user->update([
                    "{$type}" => null,
                ]);
            }

            return response()->json([
                'code' => 200,
                'title' => trans('public.request_success'),
                'msg' => trans("update.delete_account_{$type}_msg"),
            ]);
        }

        return response()->json([], 422);
    }

}
