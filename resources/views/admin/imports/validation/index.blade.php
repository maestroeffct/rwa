@extends('admin.layouts.app')

@section('content')
    <section class="section mb-48">
        <div class="section-header">
            <h1>{{ trans('update.bulk_imports') }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ getAdminPanelUrl() }}">{{ trans('admin/main.dashboard') }}</a></div>
                <div class="breadcrumb-item">{{ trans('update.bulk_imports') }}</div>
            </div>
        </div>

        <div class="bg-white py-16 rounded-16 mt-24">
            <div class="d-flex align-items-center justify-content-between px-16">
                <div class="">
                    <h3 class="font-14 text-dark">{{ trans('update.data_validation') }}</h3>
                    <p class="mb-0 mt-4 font-12 text-gray-500">{{ trans('update.bulk_import_data_validation_page_msg') }}</p>
                </div>

                <a href="{{ getAdminPanelUrl("/imports") }}" class="btn btn-outline-light">{{ trans('update.back_to_import') }}</a>
            </div>

            <div class="px-16 mt-20 pt-20 border-top-gray-200">
                <ul class="nav nav-pills" id="myTab3" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="validRecords-tab" data-toggle="tab" href="#validRecords" role="tab" aria-controls="validRecords" aria-selected="true">
                            {{ trans('update.valid_records') }} ({{ count($validatedItems) }})
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" id="invalidRecords-tab" data-toggle="tab" href="#invalidRecords" role="tab" aria-controls="invalidRecords" aria-selected="true">
                            {{ trans('update.invalid_records') }} ({{ count($errorsItems) }})
                        </a>
                    </li>
                </ul>

                <div class="tab-content" id="myTabContent2">
                    <div class="tab-pane mt-3 fade show active" id="validRecords" role="tabpanel" aria-labelledby="validRecords-tab">
                        @include('admin.imports.validation.valid_tab')
                    </div>

                    <div class="tab-pane mt-3 fade" id="invalidRecords" role="tabpanel" aria-labelledby="invalidRecords-tab">
                        @include('admin.imports.validation.invalid_tab')
                    </div>
                </div>
            </div>


            @if(count($validatedItems) > 0)
                <form action="{{ getAdminPanelUrl("/imports/store") }}" class="mt-3" method="post" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <input type="hidden" name="type" value="{{ $type }}">
                    <input type="hidden" name="locale" value="{{ $locale ?? '' }}">
                    <input type="hidden" name="currency" value="{{ $currency ?? '' }}">

                    <div class="d-flex align-items-center justify-content-end">
                        <button type="submit" class="btn btn-primary btn-lg gap-8 mr-16">
                            <x-iconsax-bul-document-upload class="icons text-white" width="24px" height="24px"/> {{ trans('update.import_valid_items') }}
                        </button>
                    </div>
                </form>
            @endif

        </div>


    </section>
@endsection
