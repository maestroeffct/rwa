@extends('admin.layouts.app')

@push('styles_top')

    <link rel="stylesheet" href="/assets/default/vendors/daterangepicker/daterangepicker.min.css">
    <link rel="stylesheet" href="/assets/default/vendors/bootstrap-timepicker/bootstrap-timepicker.min.css">

    <link rel="stylesheet" href="/assets/default/vendors/bootstrap-tagsinput/bootstrap-tagsinput.min.css">
    <link rel="stylesheet" href="/assets/vendors/summernote/summernote-bs4.min.css">
    <style>
        .bootstrap-timepicker-widget table td input {
            width: 35px !important;
        }

        .select2-container {
            z-index: 1212 !important;
        }
    </style>
@endpush

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{!empty($bundle) ?trans('/admin/main.edit'): trans('admin/main.new') }} {{ trans('update.bundle') }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ getAdminPanelUrl() }}">{{ trans('admin/main.dashboard') }}</a>
                </div>
                <div class="breadcrumb-item active">
                    <a href="{{ getAdminPanelUrl() }}/bundles">{{ trans('update.bundles') }}</a>
                </div>
                <div class="breadcrumb-item">{{!empty($bundle) ?trans('/admin/main.edit'): trans('admin/main.new') }}</div>
            </div>
        </div>

        <div class="section-body">

            <div class="row">
                <div class="col-12 ">
                    <div class="card">
                        <div class="card-body">

                            <form method="post" action="{{ getAdminPanelUrl() }}/bundles/{{ !empty($bundle) ? $bundle->id.'/update' : 'store' }}" id="webinarForm" class="webinar-form" enctype="multipart/form-data">
                                {{ csrf_field() }}
                                <section>
                                    <h2 class="section-title after-line">{{ trans('public.basic_information') }}</h2>

                                    <div class="row">
                                        <div class="col-12 col-md-5">

                                            @if(!empty(getGeneralSettings('content_translate')))
                                                <div class="form-group">
                                                    <label class="input-label">{{ trans('auth.language') }}</label>
                                                    <select name="locale" class="form-control {{ !empty($bundle) ? 'js-edit-content-locale' : '' }}">
                                                        @foreach($userLanguages as $lang => $language)
                                                            <option value="{{ $lang }}" @if(mb_strtolower(request()->get('locale', app()->getLocale())) == mb_strtolower($lang)) selected @endif>{{ $language }}</option>
                                                        @endforeach
                                                    </select>
                                                    @error('locale')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                    @enderror
                                                </div>
                                            @else
                                                <input type="hidden" name="locale" value="{{ getDefaultLocale() }}">
                                            @endif


                                            <div class="form-group mt-15">
                                                <label class="input-label">{{ trans('public.title') }}</label>
                                                <input type="text" name="title" value="{{ !empty($bundle) ? $bundle->title : old('title') }}" class="form-control @error('title')  is-invalid @enderror" placeholder=""/>
                                                @error('title')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                                @enderror
                                            </div>

                                            <div class="form-group mt-15">
                                                <label class="input-label">{{ trans('update.required_points') }}</label>
                                                <input type="text" name="points" value="{{ !empty($bundle) ? $bundle->points : old('points') }}" class="form-control @error('points')  is-invalid @enderror" placeholder="Empty means inactive this mode"/>
                                                <div class="text-gray-500 text-small mt-1">{{ trans('update.product_points_hint') }}</div>
                                                @error('points')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                                @enderror
                                            </div>

                                            <div class="form-group mt-15">
                                                <label class="input-label">{{ trans('update.bundle_url') }}</label>
                                                <input type="text" name="slug" value="{{ !empty($bundle) ? $bundle->slug : old('slug') }}" class="form-control @error('slug')  is-invalid @enderror" placeholder=""/>
                                                <div class="text-gray-500 text-small mt-1">{{ trans('update.bundle_url_hint') }}</div>
                                                @error('slug')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                                @enderror
                                            </div>

                                            @if(!empty($bundle) and $bundle->creator->isOrganization())
                                                <div class="form-group mt-15 ">
                                                    <label class="input-label d-block">{{ trans('admin/main.organization') }}</label>

                                                    <select class="form-control" disabled readonly data-placeholder="{{ trans('public.search_instructor') }}">
                                                        <option selected>{{ $bundle->creator->full_name }}</option>
                                                    </select>
                                                </div>
                                            @endif


                                            <div class="form-group mt-15 ">
                                                <label class="input-label d-block">{{ trans('admin/main.select_a_instructor') }}</label>


                                                <select name="teacher_id" data-search-option="except_user" class="form-control search-user-select2"
                                                        data-placeholder="{{ trans('public.select_a_teacher') }}"
                                                >
                                                    @if(!empty($bundle))
                                                        <option value="{{ $bundle->teacher->id }}" selected>{{ $bundle->teacher->full_name }}</option>
                                                    @else
                                                        <option selected disabled>{{ trans('public.select_a_teacher') }}</option>
                                                    @endif
                                                </select>

                                                @error('teacher_id')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                                @enderror
                                            </div>


                                            <div class="form-group mt-15">
                                                <label class="input-label">{{ trans('public.seo_description') }}</label>
                                                <input type="text" name="seo_description" value="{{ !empty($bundle) ? $bundle->seo_description : old('seo_description') }}" class="form-control @error('seo_description')  is-invalid @enderror"/>
                                                <div class="text-gray-500 text-small mt-1">{{ trans('admin/main.seo_description_hint') }}</div>
                                                @error('seo_description')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                                @enderror
                                            </div>

                                            <div class="form-group mt-15">
                                                <label class="input-label">{{ trans('public.thumbnail_image') }}</label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <button type="button" class="input-group-text admin-file-manager" data-input="thumbnail" data-preview="holder">
                                                            <i class="fa fa-upload"></i>
                                                        </button>
                                                    </div>
                                                    <input type="text" name="thumbnail" id="thumbnail" value="{{ !empty($bundle) ? $bundle->thumbnail : old('thumbnail') }}" class="form-control @error('thumbnail')  is-invalid @enderror"/>
                                                    <div class="input-group-append">
                                                        <button type="button" class="input-group-text admin-file-view" data-input="thumbnail">
                                                            <i class="fa fa-eye"></i>
                                                        </button>
                                                    </div>
                                                    @error('thumbnail')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                    @enderror
                                                </div>
                                            </div>


                                            <div class="form-group mt-15">
                                                <label class="input-label">{{ trans('public.cover_image') }}</label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <button type="button" class="input-group-text admin-file-manager" data-input="cover_image" data-preview="holder">
                                                            <i class="fa fa-upload"></i>
                                                        </button>
                                                    </div>
                                                    <input type="text" name="image_cover" id="cover_image" value="{{ !empty($bundle) ? $bundle->image_cover : old('image_cover') }}" class="form-control @error('image_cover')  is-invalid @enderror"/>
                                                    <div class="input-group-append">
                                                        <button type="button" class="input-group-text admin-file-view" data-input="cover_image">
                                                            <i class="fa fa-eye"></i>
                                                        </button>
                                                    </div>
                                                    @error('image_cover')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="form-group mt-25">
                                                <label class="input-label">{{ trans('public.demo_video') }} ({{ trans('public.optional') }})</label>


                                                <div class="">
                                                    <label class="input-label font-12">{{ trans('public.source') }}</label>
                                                    <select name="video_demo_source"
                                                            class="js-video-demo-source form-control"
                                                    >
                                                        @foreach(getAvailableUploadFileSources() as $source)
                                                            <option value="{{ $source }}" @if(!empty($bundle) and $bundle->video_demo_source == $source) selected @endif>{{ trans('update.file_source_'.$source) }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="js-video-demo-other-inputs form-group mt-0 {{ (empty($bundle) or !in_array($bundle->video_demo_source, ['secure_host', 's3'])) ? '' : 'd-none' }}">
                                                <label class="input-label font-12">{{ trans('update.path') }}</label>
                                                <div class="input-group js-video-demo-path-input">
                                                    <div class="input-group-prepend">
                                                        <button type="button" class="js-video-demo-path-upload input-group-text admin-file-manager {{ (empty($bundle) or empty($bundle->video_demo_source) or $bundle->video_demo_source == 'upload') ? '' : 'd-none' }}" data-input="demo_video" data-preview="holder">
                                                            <i class="fa fa-upload"></i>
                                                        </button>

                                                        <button type="button" class="js-video-demo-path-links rounded-left input-group-text input-group-text-rounded-left  {{ (empty($bundle) or empty($bundle->video_demo_source) or $bundle->video_demo_source == 'upload') ? 'd-none' : '' }}">
                                                            <i class="fa fa-link"></i>
                                                        </button>
                                                    </div>
                                                    <input type="text" name="video_demo" id="demo_video" value="{{ !empty($bundle) ? $bundle->video_demo : old('video_demo') }}" class="form-control @error('video_demo')  is-invalid @enderror"/>
                                                    @error('video_demo')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="form-group js-video-demo-file-input {{ (!empty($bundle) and in_array($bundle->video_demo_source, ['secure_host', 's3'])) ? '' : 'd-none' }}">
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <button type="button" class="input-group-text">
                                                            <i class="fa fa-upload"></i>
                                                        </button>
                                                    </div>
                                                    <div class="custom-file js-ajax-s3_file">
                                                        <input type="file" name="video_demo_file" class="custom-file-input cursor-pointer" id="video_demo_file" accept="video/*">
                                                        <label class="custom-file-label cursor-pointer" for="video_demo_file">{{ trans('update.choose_file') }}</label>
                                                    </div>

                                                    <div class="invalid-feedback"></div>
                                                </div>
                                            </div>


                                            <div class="form-group mt-15">
                                                <label class="input-label">{{ trans('public.summary') }}</label>
                                                <textarea name="summary" rows="5" class="form-control @error('summary')  is-invalid @enderror" placeholder="{{ trans('forms.webinar_summary_placeholder') }}">{!! !empty($bundle) ? $bundle->summary : old('summary')  !!}</textarea>
                                                @error('summary')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                                @enderror
                                            </div>

                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-12">
                                            <div class="form-group mt-15">
                                                <label class="input-label">{{ trans('public.description') }}</label>
                                                <textarea id="summernote" name="description" class="form-control @error('description')  is-invalid @enderror" placeholder="{{ trans('forms.webinar_description_placeholder') }}">{!! !empty($bundle) ? $bundle->description : old('description')  !!}</textarea>
                                                @error('description')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </section>

                                <section class="mt-3">
                                    <h2 class="section-title after-line">{{ trans('public.additional_information') }}</h2>

                                    <div class="row">
                                        <div class="col-12 col-md-6">

                                            <div class="form-group mt-3">
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <label class="" for="includeCertificateSwitch">{{ trans('update.bundle_completion_certificate') }}</label>
                                                    <div class="custom-control custom-switch">
                                                        <input type="checkbox" name="certificate" class="custom-control-input" id="includeCertificateSwitch" {{ !empty($bundle) && $bundle->certificate ? 'checked' : '' }}>
                                                        <label class="custom-control-label" for="includeCertificateSwitch"></label>
                                                    </div>
                                                </div>

                                                <p class="mt-10 font-12 font-12 text-gray-500">- {{ trans('update.bundle_completion_certificate_hint') }}</p>
                                            </div>

                                            <div class="form-group mt-3">
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <label class="cursor-pointer" for="privateSwitch">{{ trans('webinars.private') }}</label>
                                                    <div class="custom-control custom-switch">
                                                        <input type="checkbox" name="private" class="custom-control-input" id="privateSwitch" {{ (!empty($bundle) && $bundle->private) ? 'checked' : ''  }}>
                                                        <label class="custom-control-label" for="privateSwitch"></label>
                                                    </div>
                                                </div>

                                                <p class="text-gray-500 font-12 mt-4">{{ trans('webinars.create_private_course_hint') }}</p>
                                            </div>

                                            <div class="form-group mt-30 d-flex align-items-center justify-content-between">
                                                <label class="cursor-pointer" for="availableOnlyForStudentsSwitch">{{ trans('update.available_only_for_students') }}</label>
                                                <div class="custom-control custom-switch">
                                                    <input type="checkbox" name="only_for_students" class="custom-control-input" id="availableOnlyForStudentsSwitch" {{ (!empty($bundle) and $bundle->only_for_students) ? 'checked' : ''  }}>
                                                    <label class="custom-control-label" for="availableOnlyForStudentsSwitch"></label>
                                                </div>
                                            </div>


                                            <div class="form-group mt-3 d-flex align-items-center justify-content-between">
                                                <label class="cursor-pointer" for="subscribeSwitch">{{ trans('public.subscribe') }}</label>
                                                <div class="custom-control custom-switch">
                                                    <input type="checkbox" name="subscribe" class="custom-control-input" id="subscribeSwitch" {{ (!empty($bundle) && $bundle->subscribe) ? 'checked' : ''  }}>
                                                    <label class="custom-control-label" for="subscribeSwitch"></label>
                                                </div>
                                            </div>

                                            <div class="form-group mt-15">
                                                <label class="input-label">{{ trans('update.access_days') }}</label>
                                                <input type="text" name="access_days" value="{{ !empty($bundle) ? $bundle->access_days : old('access_days') }}" class="form-control @error('access_days')  is-invalid @enderror"/>
                                                @error('access_days')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                                @enderror
                                                <p class="mt-1">- {{ trans('update.access_days_input_hint') }}</p>
                                            </div>

                                            <div class="form-group mt-15">
                                                <label class="input-label">{{ trans('public.price') }} ({{ $currency }})</label>
                                                <input type="text" name="price" value="{{ !empty($bundle) ? $bundle->price : old('price') }}" class="form-control @error('price')  is-invalid @enderror" placeholder="{{ trans('public.0_for_free') }}"/>
                                                @error('price')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                                @enderror
                                            </div>

                                            {{-- Product Badges --}}
                                            @if(!empty($bundle))
                                                @include('admin.product_badges.content_include', ['itemTarget' => $bundle])
                                            @endif


                                            <div class="form-group mt-15">
                                                <label class="input-label d-block">{{ trans('public.tags') }}</label>
                                                <input type="text" name="tags" data-max-tag="5" value="{{ !empty($bundle) ? implode(',',$bundleTags) : '' }}" class="form-control inputtags" placeholder="{{ trans('public.type_tag_name_and_press_enter') }} ({{ trans('admin/main.max') }} : 5)"/>
                                            </div>


                                            <div class="form-group mt-15">
                                                <label class="input-label">{{ trans('public.category') }}</label>

                                                <select id="categories" class="custom-select @error('category_id')  is-invalid @enderror" name="category_id" required>
                                                    <option {{ !empty($bundle) ? '' : 'selected' }} disabled>{{ trans('public.choose_category') }}</option>
                                                    @foreach($categories as $category)
                                                        @if(!empty($category->subCategories) and count($category->subCategories))
                                                            <optgroup label="{{  $category->title }}">
                                                                @foreach($category->subCategories as $subCategory)
                                                                    <option value="{{ $subCategory->id }}" {{ (!empty($bundle) and $bundle->category_id == $subCategory->id) ? 'selected' : '' }}>{{ $subCategory->title }}</option>
                                                                @endforeach
                                                            </optgroup>
                                                        @else
                                                            <option value="{{ $category->id }}" {{ (!empty($bundle) and $bundle->category_id == $category->id) ? 'selected' : '' }}>{{ $category->title }}</option>
                                                        @endif
                                                    @endforeach
                                                </select>

                                                @error('category_id')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                                @enderror
                                            </div>

                                        </div>
                                    </div>

                                    <div class="form-group mt-15 {{ (!empty($bundleCategoryFilters) and count($bundleCategoryFilters)) ? '' : 'd-none' }}" id="categoriesFiltersContainer">
                                        <span class="input-label d-block">{{ trans('public.category_filters') }}</span>
                                        <div id="categoriesFiltersCard" class="row mt-3">

                                            @if(!empty($bundleCategoryFilters) and count($bundleCategoryFilters))
                                                @foreach($bundleCategoryFilters as $filter)
                                                    <div class="col-12 col-md-3">
                                                        <div class="webinar-category-filters">
                                                            <strong class="category-filter-title d-block">{{ $filter->title }}</strong>
                                                            <div class="py-10"></div>

                                                            @foreach($filter->options as $option)
                                                                <div class="form-group mt-3 d-flex align-items-center justify-content-between">
                                                                    <label class="font-12 text-gray-500 font-14" for="filterOptions{{ $option->id }}">{{ $option->title }}</label>
                                                                    <div class="custom-control custom-checkbox">
                                                                        <input type="checkbox" name="filters[]" value="{{ $option->id }}" {{ ((!empty($bundleFilterOptions) && in_array($option->id,$bundleFilterOptions)) ? 'checked' : '') }} class="custom-control-input" id="filterOptions{{ $option->id }}">
                                                                        <label class="custom-control-label" for="filterOptions{{ $option->id }}"></label>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @endif

                                        </div>
                                    </div>
                                </section>

                                @if(!empty($bundle))
                                    <section class="mt-30">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <h2 class="section-title after-line">{{ trans('admin/main.price_plans') }}</h2>
                                            <button id="webinarAddTicket" type="button" class="btn btn-primary btn-sm mt-3">{{ trans('admin/main.add_price_plan') }}</button>
                                        </div>

                                        <div class="row mt-10">
                                            <div class="col-12">

                                                @if(!empty($tickets) and !$tickets->isEmpty())
                                                    <div class="table-responsive">
                                                        <table class="table custom-table text-center font-14">

                                                            <tr>
                                                                <th>{{ trans('public.title') }}</th>
                                                                <th>{{ trans('public.discount') }}</th>
                                                                <th>{{ trans('public.capacity') }}</th>
                                                                <th>{{ trans('public.date') }}</th>
                                                                <th width="80px">{{ trans('admin/main.action') }}</th>
                                                            </tr>

                                                            @foreach($tickets as $ticket)
                                                                <tr>
                                                                    <th scope="row">{{ $ticket->title }}</th>
                                                                    <td>{{ $ticket->discount }}%</td>
                                                                    <td>{{ $ticket->capacity }}</td>
                                                                    <td>{{ dateTimeFormat($ticket->start_date,'j F Y') }} - {{ (new DateTime())->setTimestamp($ticket->end_date)->format('j F Y') }}</td>
                                                                    <td>
                                                                        <div class="btn-group dropdown table-actions position-relative">
                                                                            <button type="button" class="btn-transparent dropdown-toggle" data-toggle="dropdown">
                                                                                <x-iconsax-lin-more class="icons text-gray-500" width="20px" height="20px"/>
                                                                            </button>

                                                                            <div class="dropdown-menu dropdown-menu-right">
                                                                                <button type="button"
                                                                                        data-ticket-id="{{ $ticket->id }}"
                                                                                        data-webinar-id="{{ !empty($bundle) ? $bundle->id : '' }}"
                                                                                        class="dropdown-item d-flex align-items-center mb-3 py-3 px-0 gap-4 edit-ticket">
                                                                                    <x-iconsax-lin-edit-2 class="icons text-gray-500 mr-2" width="18px" height="18px"/>
                                                                                    <span class="text-gray-500 font-14">{{ trans('admin/main.edit') }}</span>
                                                                                </button>

                                                                                @include('admin.includes.delete_button',[
                                                                                    'url' => getAdminPanelUrl().'/tickets/'.$ticket->id.'/delete',
                                                                                    'btnClass' => 'dropdown-item text-danger mb-0 py-3 px-0 font-14',
                                                                                    'btnText' => trans('admin/main.delete'),
                                                                                    'btnIcon' => 'trash',
                                                                                    'iconType' => 'lin',
                                                                                    'iconClass' => 'text-danger mr-2'
                                                                                ])
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            @endforeach

                                                        </table>
                                                    </div>
                                                @else
                                                    <div class="d-flex-center flex-column px-32 py-120 text-center">
                                                        <div class="d-flex-center size-64 rounded-12 bg-primary-30">
                                                            <x-iconsax-bul-receipt-2 class="icons text-primary" width="32px" height="32px"/>
                                                        </div>
                                                        <h3 class="font-16 font-weight-bold mt-12">{{ trans('public.ticket_no_result') }}</h3>
                                                        <p class="mt-4 font-12 text-gray-500">{!! trans('public.ticket_no_result_hint') !!}</p>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </section>


                                    <section class="mt-30">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <h2 class="section-title after-line">{{ trans('product.courses') }}</h2>
                                            <button id="bundleAddNewCourses" type="button" class="btn btn-primary btn-sm mt-3">{{ trans('update.add_new_course') }}</button>
                                        </div>

                                        <div class="row mt-10">
                                            <div class="col-12">
                                                @if(!empty($bundleWebinars) and !$bundleWebinars->isEmpty())
                                                    <div class="table-responsive">
                                                        <table class="table custom-table text-center font-14">

                                                            <tr>
                                                                <th>{{ trans('public.title') }}</th>
                                                                <th class="text-left">{{ trans('public.instructor') }}</th>
                                                                <th>{{ trans('public.price') }}</th>
                                                                <th>{{ trans('public.publish_date') }}</th>
                                                                <th width="80px">{{ trans('admin/main.action') }}</th>
                                                            </tr>

                                                            @foreach($bundleWebinars as $bundleWebinar)
                                                                @if(!empty($bundleWebinar->webinar->title))
                                                                    <tr>
                                                                        <th>{{ $bundleWebinar->webinar->title }}</th>
                                                                        <td class="text-left">{{ $bundleWebinar->webinar->teacher->full_name }}</td>
                                                                        <td>{{  !empty($bundleWebinar->webinar->price) ? handlePrice($bundleWebinar->webinar->price) : trans("public.free") }}</td>
                                                                        <td>{{ dateTimeFormat($bundleWebinar->webinar->created_at,'j F Y | H:i') }}</td>

                                                                        <td>
                                                                            <div class="btn-group dropdown table-actions position-relative">
                                                                                <button type="button" class="btn-transparent dropdown-toggle" data-toggle="dropdown">
                                                                                    <x-iconsax-lin-more class="icons text-gray-500" width="20px" height="20px"/>
                                                                                </button>

                                                                                <div class="dropdown-menu dropdown-menu-right">
                                                                                    <button type="button"
                                                                                            data-item-id="{{ $bundleWebinar->id }}"
                                                                                            data-bundle-id="{{ !empty($bundle) ? $bundle->id : '' }}"
                                                                                            class="dropdown-item d-flex align-items-center mb-3 py-3 px-0 gap-4 edit-bundle-webinar">
                                                                                        <x-iconsax-lin-edit-2 class="icons text-gray-500 mr-2" width="18px" height="18px"/>
                                                                                        <span class="text-gray-500 font-14">{{ trans('admin/main.edit') }}</span>
                                                                                    </button>

                                                                                    @include('admin.includes.delete_button',[
                                                                                        'url' => getAdminPanelUrl().'/bundle-webinars/'.$bundleWebinar->id.'/delete',
                                                                                        'btnClass' => 'dropdown-item text-danger mb-0 py-3 px-0 font-14',
                                                                                        'btnText' => trans('admin/main.delete'),
                                                                                        'btnIcon' => 'trash',
                                                                                        'iconType' => 'lin',
                                                                                        'iconClass' => 'text-danger mr-2'
                                                                                    ])
                                                                                </div>
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                @endif
                                                            @endforeach

                                                        </table>
                                                    </div>
                                                @else
                                                    <div class="d-flex-center flex-column px-32 py-120 text-center">
                                                        <div class="d-flex-center size-64 rounded-12 bg-primary-30">
                                                            <x-iconsax-bul-video-play class="icons text-primary" width="32px" height="32px"/>
                                                        </div>
                                                        <h3 class="font-16 font-weight-bold mt-12">{{ trans('update.bundle_webinar_no_result') }}</h3>
                                                        <p class="mt-4 font-12 text-gray-500">{!! trans('update.bundle_webinar_no_result_hint') !!}</p>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </section>

                                    {{-- Related Course --}}
                                    @include('admin.webinars.relatedCourse.add_related_course', [
                                            'relatedCourseItemId' => $bundle->id,
                                             'relatedCourseItemType' => 'bundle',
                                             'relatedCourses' => $bundle->relatedCourses
                                        ])

                                    <section class="mt-30">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <h2 class="section-title after-line">{{ trans('public.faq') }}</h2>
                                            <button id="webinarAddFAQ" type="button" class="btn btn-primary btn-sm mt-3">{{ trans('public.add_faq') }}</button>
                                        </div>

                                        <div class="row mt-10">
                                            <div class="col-12">
                                                @if(!empty($faqs) and !$faqs->isEmpty())
                                                    <div class="table-responsive">
                                                        <table class="table custom-table text-center font-14">

                                                            <tr>
                                                                <th>{{ trans('public.title') }}</th>
                                                                <th>{{ trans('public.answer') }}</th>
                                                                <th width="80px">{{ trans('admin/main.action') }}</th>
                                                            </tr>

                                                            @foreach($faqs as $faq)
                                                                <tr>
                                                                    <th>{{ $faq->title }}</th>
                                                                    <td>
                                                                        <button type="button" class="js-get-faq-description btn btn-sm btn-gray200">{{ trans('public.view') }}</button>
                                                                        <input type="hidden" value="{{ $faq->answer }}"/>
                                                                    </td>

                                                                    <td>
                                                                        <div class="btn-group dropdown table-actions position-relative">
                                                                            <button type="button" class="btn-transparent dropdown-toggle" data-toggle="dropdown">
                                                                                <x-iconsax-lin-more class="icons text-gray-500" width="20px" height="20px"/>
                                                                            </button>

                                                                            <div class="dropdown-menu dropdown-menu-right">
                                                                                <button type="button"
                                                                                        data-faq-id="{{ $faq->id }}"
                                                                                        data-bundle-id="{{ !empty($bundle) ? $bundle->id : '' }}"
                                                                                        class="dropdown-item d-flex align-items-center mb-3 py-3 px-0 gap-4 edit-faq">
                                                                                    <x-iconsax-lin-edit-2 class="icons text-gray-500 mr-2" width="18px" height="18px"/>
                                                                                    <span class="text-gray-500 font-14">{{ trans('admin/main.edit') }}</span>
                                                                                </button>

                                                                                @include('admin.includes.delete_button',[
                                                                                    'url' => getAdminPanelUrl().'/faqs/'.$faq->id.'/delete',
                                                                                    'btnClass' => 'dropdown-item text-danger mb-0 py-3 px-0 font-14',
                                                                                    'btnText' => trans('admin/main.delete'),
                                                                                    'btnIcon' => 'trash',
                                                                                    'iconType' => 'lin',
                                                                                    'iconClass' => 'text-danger mr-2'
                                                                                ])
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            @endforeach

                                                        </table>
                                                    </div>
                                                @else
                                                    <div class="d-flex-center flex-column px-32 py-120 text-center">
                                                        <div class="d-flex-center size-64 rounded-12 bg-primary-30">
                                                            <x-iconsax-bul-message-question class="icons text-primary" width="32px" height="32px"/>
                                                        </div>
                                                        <h3 class="font-16 font-weight-bold mt-12">{{ trans('public.faq_no_result') }}</h3>
                                                        <p class="mt-4 font-12 text-gray-500">{!! trans('public.faq_no_result_hint') !!}</p>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </section>
                                @endif

                                <section class="mt-3">
                                    <h2 class="section-title after-line">{{ trans('public.message_to_reviewer') }}</h2>
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="form-group mt-15">
                                                <textarea name="message_for_reviewer" rows="10" class="form-control">{{ (!empty($bundle) and $bundle->message_for_reviewer) ? $bundle->message_for_reviewer : old('message_for_reviewer') }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </section>

                                <input type="hidden" name="draft" value="no" id="forDraft"/>

                                <div class="row">
                                    <div class="col-12">
                                        <button type="button" id="saveAndPublish" class="btn btn-success">{{ !empty($bundle) ? trans('admin/main.save_and_publish') : trans('admin/main.save_and_continue') }}</button>

                                        @if(!empty($bundle))
                                            <button type="button" id="saveReject" class="btn btn-warning">{{ trans('public.reject') }}</button>

                                            @include('admin.includes.delete_button',[
                                                    'url' => getAdminPanelUrl().'/bundles/'. $bundle->id .'/delete',
                                                    'btnText' => trans('public.delete'),
                                                    'hideDefaultClass' => true,
                                                    'btnClass' => 'btn btn-danger'
                                                    ])
                                        @endif
                                    </div>
                                </div>
                            </form>


                            @include('admin.bundles.modals.bundle-webinar')
                            @include('admin.bundles.modals.ticket')
                            @include('admin.bundles.modals.faq')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts_bottom')
    <script>
        var saveSuccessLang = '{{ trans('webinars.success_store') }}';
        var titleLang = '{{ trans('admin/main.title') }}';
    </script>


    <script src="/assets/default/vendors/feather-icons/dist/feather.min.js"></script>

    <script src="/assets/default/vendors/moment.min.js"></script>
    <script src="/assets/default/vendors/daterangepicker/daterangepicker.min.js"></script>
    <script src="/assets/default/vendors/bootstrap-timepicker/bootstrap-timepicker.min.js"></script>
    <script src="/assets/default/vendors/bootstrap-tagsinput/bootstrap-tagsinput.min.js"></script>
    <script src="/assets/vendors/summernote/summernote-bs4.min.js"></script>
    <script src="/assets/admin/js/parts/webinar.min.js"></script>
@endpush
