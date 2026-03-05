<div class="d-flex align-items-center bg-primary-20 p-8 rounded-12">
    <div class="d-flex-center size-48 rounded-12 bg-primary">
        <x-iconsax-bul-category class="icons text-white" width="24px" height="24px"/>
    </div>
    <div class="ml-8">
        <h4 class="font-14 m-0 text-primary">{{ trans('update.category_import_instructions') }}</h4>
        <div class="font-12 text-primary opacity-70">{{ trans('update.follow_these_steps_to_prepare_and_import_your_category_data_correctly') }}</div>
    </div>
</div>


<!-- Sample Table -->
<div class="table-responsive mt-20">
    <table class="table custom-table font-14">

        <tr class="text-uppercase">
            <th>Title</th>
            <th>Subtitle</th>
            <th>SEO Title</th>
            <th>SEO Description</th>
            <th>Parent ID</th>
        </tr>

        <tr>
            <td>Laravel Development</td>
            <td>This category helps you create...</td>
            <td>This category helps you create additiona...</td>
            <td>This category helps you core of the system goes here to be...</td>
            <td>16</td>
        </tr>

    </table>
</div>


<!-- Field Requirements -->
<div class="row mt-5">
    <div class="col-md-4">
        <h5 class="fs-14 mb-3">{{ trans('update.required_fields') }}:</h5>
        <ul class="list-style-disc text-gray-500 mb-0" id="requiredFields">

            <li class="py-1">Title</li>

        </ul>
    </div>

    <div class="col-md-4">
        <h5 class="fs-14 mb-3">{{ trans('update.optional_fields') }}:</h5>

        <ul class="list-style-disc text-gray-500 mb-0" id="optionalFields">
            <li class="py-1"> Parent ID</li>
            <li class="py-1"> Subtitle</li>
            <li class="py-1">SEO Title</li>
            <li class="py-1"> SEO Description</li>
        </ul>
    </div>

    <div class="col-md-4">
        <h5 class="fs-14 mb-3">{{ trans('update.csv_file_requirements') }}:</h5>
        <ul class="list-style-disc text-gray-500 mb-0">
            <li class="py-1"> {{ trans('update.file_must_be_in_CSV_format') }}</li>
            <li class="py-1"> {{ trans('update.the_first_row_must_include_column_headers_exactly_as_displayed_above') }}</li>
            <li class="py-1"> {{ trans('update.all_required_fields_must_be_completed') }}</li>
            <li class="py-1"> {{ trans('update.import_from_csv_category_parent_id_hint') }}</li>
        </ul>
    </div>

</div>

<div class="col-lg-12 mt-16">
    <div class="d-flex align-items-center justify-content-end">
        <a href="{{ getAdminPanelUrl("/imports/download-sample?type=categories") }}" class="btn btn-primary btn-lg gap-8">
            <x-iconsax-bul-import class="icons text-white" width="24px" height="24px"/> {{ trans('update.download_sample_file') }}
        </a>
    </div>
</div>
