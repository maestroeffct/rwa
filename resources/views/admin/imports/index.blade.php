@extends('admin.layouts.app')

@php
    $selectedType = old('type', "courses");
@endphp

@section('content')
    <section class="section mb-48">
        <div class="section-header">
            <h1>{{ trans('update.bulk_imports') }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ getAdminPanelUrl() }}">{{ trans('admin/main.dashboard') }}</a></div>
                <div class="breadcrumb-item">{{ trans('update.bulk_imports') }}</div>
            </div>
        </div>

        {{-- Form Data Type --}}
        <form action="{{ getAdminPanelUrl("/imports/validation") }}" method="post" enctype="multipart/form-data">
            {{ csrf_field() }}

            <div class="bg-white py-16 rounded-16 mt-24">
                <div class="px-16">
                    <h3 class="font-14 text-dark">{{ trans('update.import_data') }}</h3>
                    <p class="mb-0 mt-4 font-12 text-gray-500">{{ trans('update.easily_import_large_volumes_of_data_in_one_step') }}</p>
                </div>

                {{-- Select CSV --}}
                <div class="px-16 mt-20 pt-20 border-top-gray-200">
                    <div class="row">
                        {{-- Data Type --}}
                        <div class="col-12 col-md-6 col-lg-3">
                            <div class="form-group mb-0">
                                <label class="input-label">{{ trans('update.data_type') }}</label>
                                <select name="type" class="form-control">
                                    <option value="">{{ trans('update.select_a_data_type') }}</option>

                                    @can('admin_imports_from_csv_courses')
                                        <option value="courses" {{ ($selectedType == "courses") ? 'selected' : '' }}>{{ trans('update.courses') }}</option>
                                    @endcan

                                    @can('admin_imports_from_csv_categories')
                                        <option value="categories" {{ ($selectedType == "categories") ? 'selected' : '' }}>{{ trans('update.categories') }}</option>
                                    @endcan

                                    @can('admin_imports_from_csv_users')
                                        <option value="users" {{ ($selectedType == "users") ? 'selected' : '' }}>{{ trans('admin/main.users') }}</option>
                                    @endcan

                                    @can('admin_imports_from_csv_products')
                                        <option value="products" {{ ($selectedType == "products") ? 'selected' : '' }}>{{ trans('update.products') }}</option>
                                    @endcan
                                </select>

                                @error('type')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- Content Language --}}
                        @if(!empty(getGeneralSettings("content_translate")))
                            <div class="col-12 col-md-6 col-lg-3 js-courses-instructions js-products-instructions js-categories-instructions {{ !in_array($selectedType, ['courses', 'products', 'categories']) ? 'd-none' : '' }}">
                                <div class="form-group mb-0">
                                    <label class="input-label">{{ trans('update.content_language') }}</label>
                                    <select name="locale" class="form-control">
                                        @foreach(getUserLanguagesLists() as $languageLocal => $language)
                                            <option value="{{ $languageLocal }}">{{ $language }}</option>
                                        @endforeach
                                    </select>

                                    @error('locale')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        @else
                            <input type="hidden" name="locale" value="{{ getDefaultLocale() }}">
                        @endif

                        {{-- Price Currency --}}
                        @if(!empty($currencies) and count($currencies))
                            <div class="col-12 col-md-6 col-lg-3 js-courses-instructions js-products-instructions {{ !in_array($selectedType, ['courses', 'products']) ? 'd-none' : '' }}">
                                <div class="form-group mb-0">
                                    <label class="input-label">{{ trans('update.currency_prices') }}</label>
                                    <select name="currency" class="form-control">
                                        @foreach($currencies as $currencyItem)
                                            <option value="{{ $currencyItem->currency }}">{{ currenciesLists($currencyItem->currency) }} ({{ currencySign($currencyItem->currency) }})</option>
                                        @endforeach
                                    </select>

                                    @error('currency')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        @else
                            <input type="hidden" name="currency" value="{{ getDefaultCurrency() }}">
                        @endif

                        <div class="col-12 col-md-6 col-lg-3">
                            <div class="form-group mb-0">
                                <label class="">{{ trans('update.csv_file') }}</label>

                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <button type="button" class="input-group-text">
                                            <i class="fa fa-upload"></i>
                                        </button>
                                    </div>
                                    <div class="custom-file">
                                        <input type="file" name="csv_file" class="custom-file-input cursor-pointer" id="csvFileInput" accept=".csv">
                                        <label class="custom-file-label cursor-pointer" for="csvFileInput">{{ trans('update.choose_file') }}</label>
                                        <div class="invalid-feedback custom-inv-fck"></div>
                                    </div>
                                </div>

                                @error('csv_file')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                    </div> {{-- End Row --}}

                    <div class="d-flex align-items-center justify-content-end mt-20">
                        <button type="submit" class="btn btn-primary btn-lg gap-8 text-white" id="proceedImport">
                            <x-iconsax-bul-document-upload class="icons text-white" width="24px" height="24px"/> {{ trans('update.upload_and_validate') }}
                        </button>
                    </div>

                </div>
            </div>

        </form>
        {{-- End Form  --}}


        <div class="bg-white py-16 rounded-16 mt-24">
            <div class="px-16">
                <h3 class="font-14 text-dark">{{ trans('update.sample_data_and_instructions') }}</h3>
                <p class="mb-0 mt-4 font-12 text-gray-500">{{ trans('update.use_sample_data_and_instructions_to_ensure_correct_formatting') }}</p>
            </div>

            {{-- Select CSV --}}
            <div class="px-16 mt-20 pt-20 border-top-gray-200">

                {{-- courses --}}
                <div class="js-courses-instructions {{ ($selectedType == "courses") ? '' : 'd-none' }}">
                    @include('admin.imports.sample_data.courses')
                </div>

                {{-- categories --}}
                <div class="js-categories-instructions {{ ($selectedType == "categories") ? '' : 'd-none' }}">
                    @include('admin.imports.sample_data.categories')
                </div>

                {{-- users --}}
                <div class="js-users-instructions {{ ($selectedType == "users") ? '' : 'd-none' }}">
                    @include('admin.imports.sample_data.users')
                </div>

                {{-- products --}}
                <div class="js-products-instructions {{ ($selectedType == "products") ? '' : 'd-none' }}">
                    @include('admin.imports.sample_data.products')
                </div>

            </div>
        </div>

    </section>
@endsection

@push('scripts_bottom')

    <script src="/assets/admin/js/parts/imports_from_csv.min.js"></script>
@endpush
