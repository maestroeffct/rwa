<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Web\traits\InstallmentsTrait;
use App\Http\Controllers\Web\traits\UserFormFieldsTrait;
use App\Mixins\Installment\InstallmentPlans;
use App\Mixins\RegistrationPackage\UserPackage;
use App\Models\BecomeInstructor;
use App\Models\Category;
use App\Models\RegistrationPackage;
use App\Models\Role;
use App\Models\UserBank;
use App\Models\UserOccupation;
use App\Models\UserSelectedBank;
use App\Models\UserSelectedBankSpecification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class BecomeInstructorController extends Controller
{
    use InstallmentsTrait;
    use UserFormFieldsTrait;

    public function index(Request $request)
    {
        $user = auth()->user();

        if ($user->isUser()) {
            $categories = Category::getCategories();

            $occupations = $user->occupations->pluck('category_id')->toArray();

            $lastRequest = BecomeInstructor::where('user_id', $user->id)
                ->whereIn('status', ['pending', 'pending_pay_package'])
                ->first();

            $isOrganizationRole = (!empty($lastRequest) and $lastRequest->role == Role::$organization);
            $isInstructorRole = (empty($lastRequest) or $lastRequest->role == Role::$teacher);

            $userBanks = UserBank::query()
                ->with([
                    'specifications'
                ])
                ->orderBy('created_at', 'desc')
                ->get();

            $formFields = $this->getFormFieldsByUserType($request, 'become_instructor', true, null, $lastRequest);


            $data = [
                'pageTitle' => trans('site.become_instructor'),
                'user' => $user,
                'lastRequest' => $lastRequest,
                'categories' => $categories,
                'occupations' => $occupations,
                'isOrganizationRole' => $isOrganizationRole,
                'isInstructorRole' => $isInstructorRole,
                'userBanks' => $userBanks,
                'formFields' => $formFields,
                'becomeInstructorSettings' => getBecomeInstructorSettings(),
            ];

            return view('design_1.web.become_instructor.wizard.index', $data);
        }

        abort(404);
    }

    public function store(Request $request)
    {
        $user = auth()->user();

        if ($user->isUser()) {
            $hasLastRequest = BecomeInstructor::where('user_id', $user->id)
                ->whereIn('status', ['pending', 'accept', 'pending_pay_package'])
                ->first();

            $data = $request->all();

            $rules = [
                'role' => 'required',
                'occupations' => 'required',
                'certificate' => 'nullable',
                'bank_id' => 'required',
                'identity_scan' => 'required',
                'description' => 'nullable|string',
            ];

            $validate = Validator::make($data, $rules);

            if ($validate->fails()) {
                $errors = $validate->errors();

                $type = ($data['role'] == "teacher") ? "become_instructor" : "become_organization";
                $form = $this->getFormFieldsByType($type);

                if (!empty($form)) {
                    $fieldErrors = $this->checkFormRequiredFields($request, $form);

                    if (!empty($fieldErrors) and count($fieldErrors)) {
                        foreach ($fieldErrors as $id => $error) {
                            $errors->add($id, $error);
                        }
                    }
                }

                throw new ValidationException($validate);
            } else {
                $type = ($data['role'] == "teacher") ? "become_instructor" : "become_organization";
                $form = $this->getFormFieldsByType($type);
                $errors = [];

                if (!empty($form)) {
                    $fieldErrors = $this->checkFormRequiredFields($request, $form);

                    if (!empty($fieldErrors) and count($fieldErrors)) {
                        foreach ($fieldErrors as $id => $error) {
                            $errors[$id] = $error;
                        }
                    }
                }

                if (count($errors)) {
                    return back()->withErrors($errors)->withInput($request->all());
                }
            }

            $certificatePath = $user->certificate;
            $identityScanPath = $user->identity_scan;

            if (!empty($request->file('certificate'))) {
                $certificatePath = $this->uploadFile($request->file('certificate'), 'setting', 'certificate', $user->id);
            }

            if (!empty($request->file('identity_scan'))) {
                $identityScanPath = $this->uploadFile($request->file('identity_scan'), 'setting', 'identity_scan', $user->id);
            }


            $forceBuyPackages = (!empty(getRegistrationPackagesGeneralSettings('show_packages_during_registration')) and !empty(getRegistrationPackagesGeneralSettings('force_user_to_select_a_package')));


            $lastRequest = BecomeInstructor::query()->updateOrCreate([
                'user_id' => $user->id,
            ], [
                'role' => $data['role'],
                'certificate' => $certificatePath,
                'description' => $data['description'],
                'status' => $forceBuyPackages ? 'pending_pay_package' : 'pending',
                'created_at' => time(),
            ]);

            $user->update([
                'identity_scan' => $identityScanPath,
                'certificate' => $certificatePath,
            ]);

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

            if (!empty($data['occupations'])) {
                UserOccupation::where('user_id', $user->id)->delete();

                foreach ($data['occupations'] as $category_id) {
                    UserOccupation::create([
                        'user_id' => $user->id,
                        'category_id' => $category_id
                    ]);
                }
            }


            if (empty($hasLastRequest)) {
                $notifyOptions = [
                    '[u.name]' => $user->full_name,
                    '[time.date]' => dateTimeFormat(time(), 'j M Y H:i'),
                ];
                sendNotification("new_become_instructor_request", $notifyOptions, 1);

            }

            // Extra Form
            $this->storeBecomeInstructorFormFields($data, $lastRequest, $user);


            if (!empty(getRegistrationPackagesGeneralSettings('show_packages_during_registration')) and getRegistrationPackagesGeneralSettings('show_packages_during_registration')) {
                return redirect(route('becomeInstructorPackages'));
            }

            $toastData = [
                'title' => trans('public.request_success'),
                'msg' => trans('site.become_instructor_success_request'),
                'status' => 'success'
            ];
            return back()->with(['toast' => $toastData]);
        }

        abort(404);
    }

    public function packages()
    {
        $user = auth()->user();

        $role = 'instructors';

        if (!empty($user) and $user->isUser()) {
            $becomeInstructor = BecomeInstructor::where('user_id', $user->id)->first();

            if (!empty($becomeInstructor) and $becomeInstructor->role == Role::$organization) {
                $role = 'organizations';
            }

            $packages = RegistrationPackage::where('role', $role)
                ->where('status', 'active')
                ->get();

            $userPackage = new UserPackage();
            $defaultPackage = $userPackage->getDefaultPackage($role);

            $data = [
                'pageTitle' => trans('update.registration_packages'),
                'packages' => $packages,
                'defaultPackage' => $defaultPackage,
                'becomeInstructor' => $becomeInstructor ?? null,
                'selectedRole' => $role
            ];

            return view('design_1.web.become_instructor.packages.index', $data);
        }

        abort(404);
    }

    public function checkPackageHasInstallment($id)
    {
        $user = auth()->user();

        if (!empty($user) and $user->isUser()) {
            $package = RegistrationPackage::where('id', $id)
                ->where('status', 'active')
                ->first();

            if (!empty($package) and $package->price > 0 and getInstallmentsSettings('status') and (empty($user) or $user->enable_installments)) {
                $installmentPlans = new InstallmentPlans($user);
                $installments = $installmentPlans->getPlans('registration_packages', $package->id);

                return response()->json([
                    'has_installment' => (!empty($installments) and count($installments))
                ]);
            }
        }

        return response()->json([
            'has_installment' => false
        ]);
    }
}
