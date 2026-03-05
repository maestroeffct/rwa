<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Form;
use App\Models\FormSubmission;
use App\Models\FormSubmissionItem;
use Illuminate\Http\Request;

class FormsController extends Controller
{

    public function index(Request $request, $url)
    {
        $form = Form::query()->where('url', $url)
            ->where('enable', true)
            ->with([
                'fields' => function ($query) {
                    $query->orderBy('order', 'asc');
                    $query->with([
                        'options' => function ($query) {
                            $query->orderBy('order', 'asc');
                        }
                    ]);
                }
            ])
            ->first();

        if (!empty($form)) {
            $user = auth()->user();

            $data = [
                'pageTitle' => $form->title,
                'form' => $form
            ];

            if (!empty($form->start_date) and $form->start_date > time()) {
                return view('design_1.web.forms.pages.not_start', $data);
            }

            if (!empty($form->end_date) and $form->end_date < time()) {
                return view('design_1.web.forms.pages.expired', $data);
            }

            if ($form->enable_login and empty($user)) { // if enable login and user not login
                return view('design_1.web.forms.pages.please_login', $data);
            }


            $checkAccess = $this->checkAccess($form, $user);
            if (!$checkAccess) {
                return view('design_1.web.forms.pages.access_denied', $data);
            }

            $showWelcome = false;
            $showTanks = false;
            $hasSubmission = false;

            if (!$form->enable_resubmission and !empty($user)) {
                $submission = FormSubmission::query()->where('form_id', $form->id)
                    ->where('user_id', $user->id)
                    ->first();

                $hasSubmission = !empty($submission);
            }

            if (!$hasSubmission and $form->enable_welcome_message and empty($request->get('fields')) and empty($request->get('tanks'))) {
                $showWelcome = true;
            }

            if ($form->enable_tank_you_message and !empty($request->get('tanks'))) {
                $showTanks = true;
            }

            if ($showWelcome) {
                return view('design_1.web.forms.pages.welcome', $data);
            }

            if ($showTanks) {
                return view('design_1.web.forms.pages.tanks', $data);
            }

            if ($hasSubmission) {
                return view('design_1.web.forms.pages.already_submitted', $data);
            }

            return view('design_1.web.forms.pages.fields', $data);
        }

        abort(404);
    }


    public function store(Request $request, $url)
    {
        $form = Form::query()->where('url', $url)
            ->where('enable', true)
            ->with([
                'fields' => function ($query) {
                    $query->orderBy('order', 'asc');
                    $query->with([
                        'options' => function ($query) {
                            $query->orderBy('order', 'asc');
                        }
                    ]);
                }
            ])
            ->first();

        if (!empty($form)) {
            $user = auth()->user();


            $checkAccess = $this->checkAccess($form, $user);

            if ($checkAccess) {
                $errors = $this->checkRequiredFields($request, $form);

                if (!empty($errors) and count($errors)) {
                    return back()->withErrors($errors)->withInput($request->all());
                }

                $submission = FormSubmission::query()->create([
                    "user_id" => !empty($user) ? $user->id : null,
                    "form_id" => $form->id,
                    "created_at" => time(),
                ]);

                $fieldsData = $request->get('fields');
                $fieldsData = $this->handleUploadData($request, $form, $submission, $fieldsData);

                foreach ($fieldsData as $fieldId => $value) {
                    FormSubmissionItem::query()->create([
                        "submission_id" => $submission->id,
                        "form_field_id" => $fieldId,
                        "value" => (is_array($value)) ? json_encode($value) : $value,
                    ]);
                }

                $notifyOptions = [
                    '[u.name]' => !empty($user) ? $user->full_name : trans('update.guest_(not_login)'),
                    '[form_title]' => $form->title,
                    '[time.date]' => dateTimeFormat($submission->created_at, 'j M Y H:i')
                ];

                sendNotification('submit_form_by_users', $notifyOptions, 1);

                $redirectUrl = "/";
                if ($form->enable_tank_you_message) {
                    $redirectUrl = "/forms/{$form->url}?tanks=1";
                }

                $toastData = [
                    'title' => trans('public.request_success'),
                    'msg' => trans('update.the_form_information_has_been_saved_successfully'),
                    'status' => 'success'
                ];

                return redirect($redirectUrl)->with(['toast' => $toastData]);
            }
        }

        abort(404);
    }

    private function handleUploadData(Request $request, $form, $submission, $fieldsData)
    {

        foreach ($form->fields as $field) {
            if (in_array($field->type, ['upload'])) {
                $files = $request->file("fields.{$field->id}");

                if (!empty($files)) {
                    $path = "/forms/{$form->id}/submission/{$submission->id}";

                    if (is_array($files)) {
                        foreach ($files as $file) {
                            $fieldsData[$field->id][] = $this->uploadFile($file, $path);
                        }
                    } else {
                        $fieldsData[$field->id] = $this->uploadFile($files, $path);
                    }
                } else {
                    $submissionItem = $submission->items->where('form_field_id', $field->id)->first();

                    if (!empty($submissionItem)) {
                        $fieldsData[$field->id] = $submissionItem->value;
                    }
                }
            }
        }

        return $fieldsData;
    }

    private function checkAccess($form, $user)
    {
        $access = true;

        if ($form->enable_login and !empty($user)) {
            // check user and role and group
            $userGroupsIds = $form->userGroups->pluck('id')->toArray();
            $usersIds = $form->users->pluck('id')->toArray();
            $rolesIds = $form->roles->pluck('id')->toArray();

            if (!empty($userGroupsIds) and count($userGroupsIds)) {
                $currentUserGroup = $user->getUserGroup();

                if (empty($currentUserGroup) or !in_array($currentUserGroup->id, $userGroupsIds)) {
                    $access = false;
                }
            }

            if (!empty($usersIds) and count($usersIds)) {
                if (!in_array($user->id, $usersIds)) {
                    $access = false;
                }
            }

            if (!empty($rolesIds) and count($rolesIds)) {
                if (!in_array($user->role_id, $rolesIds)) {
                    $access = false;
                }
            }

        }

        return $access;
    }

    private function checkRequiredFields(Request $request, $form)
    {
        $errors = [];
        $fieldsData = $request->get('fields');


        foreach ($form->fields as $field) {
            if ($field->required and empty($fieldsData[$field->id])) {
                $errors[$field->id] = trans('validation.required', ['attribute' => $field->title]);
            }
        }

        return $errors;
    }
}
