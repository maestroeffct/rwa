<div class="d-flex align-items-center bg-primary-20 p-8 rounded-12">
    <div class="d-flex-center size-48 rounded-12 bg-primary">
        <x-iconsax-bul-profile-2user class="icons text-white" width="24px" height="24px"/>
    </div>
    <div class="ml-8">
        <h4 class="font-14 m-0 text-primary">{{ trans('update.users_import_instructions') }}</h4>
        <div class="font-12 text-primary opacity-70">{{ trans('update.follow_these_steps_to_prepare_and_import_your_users_data_correctly') }}</div>
    </div>
</div>


<!-- Sample Table -->
<div class="table-responsive mt-20">
    <table class="table custom-table font-14">

        <tr class="text-uppercase">
            <th>Full Name</th>
            <th>Email</th>
            <th>Mobile</th>
            <th>Role ID</th>
            <th>Organization ID</th>
            <th>Password</th>
        </tr>

        <tr>
            <td>John Doe</td>
            <td>John@gmail.com</td>
            <td>123456789</td>
            <td>4</td>
            <td>12</td>
            <td>25121321</td>
        </tr>

    </table>
</div>


<!-- Field Requirements -->
<div class="row mt-5">
    <div class="col-md-4">
        <h5 class="fs-14 mb-3">{{ trans('update.required_fields') }}:</h5>
        <ul class="list-style-disc text-gray-500 mb-0" id="requiredFields">

            <li class="py-1">Full Name</li>
            <li class="py-1">Email</li>
            <li class="py-1"> Role ID</li>
        </ul>
    </div>

    <div class="col-md-4">
        <h5 class="fs-14 mb-3">{{ trans('update.optional_fields') }}:</h5>

        <ul class="list-style-disc text-gray-500 mb-0" id="optionalFields">
            <li class="py-1"> Mobile</li>
            <li class="py-1"> Organization ID</li>
            <li class="py-1">Password</li>
        </ul>
    </div>

    <div class="col-md-4">
        <h5 class="fs-14 mb-3">{{ trans('update.csv_file_requirements') }}:</h5>
        <ul class="list-style-disc text-gray-500 mb-0">
            <li class="py-1"> {{ trans('update.file_must_be_in_CSV_format') }}</li>
            <li class="py-1"> {{ trans('update.the_first_row_must_include_column_headers_exactly_as_displayed_above') }}</li>
            <li class="py-1"> {{ trans('update.all_required_fields_must_be_completed') }}</li>
            <li class="py-1"> {{ trans('update.import_from_csv_users_role_id_hint') }}</li>
            <li class="py-1"> {{ trans('update.import_from_csv_users_password_hint') }}</li>
            <li class="py-1"> {{ trans('update.import_from_csv_users_mobile_hint') }}</li>
        </ul>
    </div>

</div>

<div class="col-lg-12 mt-16">
    <div class="d-flex align-items-center justify-content-end">
        <a href="{{ getAdminPanelUrl("/imports/download-sample?type=users") }}" class="btn btn-primary btn-lg gap-8">
            <x-iconsax-bul-import class="icons text-white" width="24px" height="24px"/> {{ trans('update.download_sample_file') }}
        </a>
    </div>
</div>
