<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Accounting;
use App\Models\BecomeInstructor;
use App\Models\Order;
use App\Models\RegistrationPackage;
use App\Models\Role;
use App\Models\Sale;
use App\Models\Setting;
use Illuminate\Http\Request;

class BecomeInstructorController extends Controller
{
    public function index($page)
    {
        $this->authorize('admin_become_instructors_list');

        if ($page == 'organizations') {
            $role = Role::$organization;
        } else {
            $role = Role::$teacher;
        }

        $query = BecomeInstructor::where('role', $role);

        if (getRegistrationPackagesGeneralSettings('force_user_to_select_a_package')) {
            $query->whereNotNull('package_id');
        }

        $becomeInstructors = $query->with(['user', 'registrationPackage'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $data = [
            'pageTitle' => trans('admin/main.become_instructors_list'),
            'becomeInstructors' => $becomeInstructors
        ];

        return view('admin.users.become_instructors.lists', $data);
    }

    public function reject($id)
    {
        $this->authorize('admin_become_instructors_reject');

        $becomeInstructors = BecomeInstructor::findOrFail($id);

        $this->handleRefundPackage($becomeInstructors);

        $becomeInstructors->update([
            'status' => 'reject'
        ]);

        // Send Notification
        $becomeInstructors->sendNotificationToUser('reject');

        return redirect()->back();
    }

    public function delete($id)
    {
        $this->authorize('admin_become_instructors_delete');

        $becomeInstructors = BecomeInstructor::findOrFail($id);

        $this->handleRefundPackage($becomeInstructors);

        $becomeInstructors->delete();

        return redirect()->back();
    }

    private function handleRefundPackage($becomeInstructors)
    {
        if (!empty($becomeInstructors->package_id)) {
            $sale = Sale::where('buyer_id', $becomeInstructors->user_id)
                ->where('type', Sale::$registrationPackage)
                ->where('registration_package_id', $becomeInstructors->package_id)
                ->whereNull('refund_at')
                ->first();

            if (!empty($sale)) {
                if (!empty($sale->total_amount)) {

                    Accounting::refundAccounting($sale);
                }

                $sale->update(['refund_at' => time()]);
            }
        }
    }

    public function settings(Request $request)
    {
        $this->authorize('admin_instructor_finder_settings');

        removeContentLocale();

        $setting = Setting::where('page', 'general')
            ->where('name', Setting::$becomeInstructorSettingsName)
            ->first();

        $data = [
            'pageTitle' => trans('update.settings'),
            'setting' => $setting,
            'selectedLocale' => mb_strtolower($request->get('locale', Setting::$defaultSettingsLocale)),
        ];

        return view('admin.users.become_instructors.settings.index', $data);
    }

}
