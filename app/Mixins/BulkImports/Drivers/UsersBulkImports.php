<?php

namespace App\Mixins\BulkImports\Drivers;


use App\Mixins\BulkImports\IImportChannel;
use App\Models\Role;
use App\User;
use Illuminate\Support\Facades\Hash;


class UsersBulkImports implements IImportChannel
{
    protected $usersAffiliateStatus;
    protected $timezone;
    protected $accessContent;

    public function import(array $items, $locale = null, $currency = null)
    {
        $referralSettings = getReferralSettings();
        $this->usersAffiliateStatus = (!empty($referralSettings) and !empty($referralSettings['users_affiliate_status']));

        $this->timezone = getGeneralSettings('default_time_zone') ?? null;

        $disableViewContentAfterUserRegister = getFeaturesSettings('disable_view_content_after_user_register');
        $this->accessContent = !((!empty($disableViewContentAfterUserRegister) and $disableViewContentAfterUserRegister));


        $chunks = array_chunk($items, 200);

        foreach ($chunks as $chunkItems) {
            foreach ($chunkItems as $item) {
                $newUser = $this->makeNewUser($item);

            }
        }
    }

    private function makeNewUser($data)
    {
        $role = Role::query()->find($data['role_id']);
        $mobile = !empty($data['mobile']) ? ltrim($data['mobile'], '+') : null;

        // username generated auto in Model

        return User::query()->create([
            'full_name' => $data['full_name'],
            'role_id' => $role->id,
            'role_name' => $role->name,
            'email' => $data['email'],
            'organ_id' => !empty($data['organization_id']) ? $data['organization_id'] : null,
            'mobile' => $mobile,
            'password' => !empty($data['password']) ? Hash::make($data['password']) : null,
            'status' => User::$active,
            'access_content' => $this->accessContent,
            'affiliate' => $this->usersAffiliateStatus,
            'timezone' => $this->timezone,
            'created_at' => time(),
        ]);
    }

    public function getValidatorRule(): array
    {
        return [
            'full_name' => 'required|min:3|max:255',
            'email' => 'required|email|unique:users,email',
            'role_id' => 'required|exists:roles,id',
            'mobile' => 'nullable|numeric|unique:users,mobile',
            'organization_id' => 'nullable|exists:users,id',
            'password' => 'nullable|string',
        ];
    }

    public function checkDuplicateRows(&$duplicateRowsLogs, $row, $rowKey)
    {
        if (empty($duplicateRowsLogs['seenEmails'])) {
            $duplicateRowsLogs['seenEmails'] = [];
        }

        if (empty($duplicateRowsLogs['seenMobiles'])) {
            $duplicateRowsLogs['seenMobiles'] = [];
        }

        if (!empty($row['email'])) {
            if (in_array($row['email'], $duplicateRowsLogs['seenEmails'])) {
                $duplicateRowsLogs['errors'][$rowKey]['email'] = [trans("update.this_attribute_is_duplicated_in_the_CSV_file", ['attribute' => trans('auth.email')])];
            } else {
                $duplicateRowsLogs['seenEmails'][$rowKey] = $row['email'];
            }
        }


        if (!empty($row['mobile'])) {
            if (in_array($row['mobile'], $duplicateRowsLogs['seenMobiles'])) {
                $duplicateRowsLogs['errors'][$rowKey]['mobile'] = [trans("update.this_attribute_is_duplicated_in_the_CSV_file", ['attribute' => trans('auth.mobile')])];
            } else {
                $duplicateRowsLogs['seenMobiles'][$rowKey] = $row['mobile'];
            }
        }
    }
}
