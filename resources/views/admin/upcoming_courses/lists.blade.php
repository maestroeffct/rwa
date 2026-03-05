@extends('admin.layouts.app')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{ $pageTitle }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ getAdminPanelUrl() }}">{{trans('admin/main.dashboard')}}</a>
                </div>
                <div class="breadcrumb-item">{{ trans('update.upcoming_courses') }}</div>
            </div>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card-statistic">
                        <div class="card-statistic__mask"></div>
                        <div class="card-statistic__wrap">
                            <div class="d-flex align-items-start justify-content-between">
                                <span class="text-gray-500 mt-8">{{ trans('update.total_courses') }}</span>
                                <div class="d-flex-center size-48 bg-primary-30 rounded-12">
                                    <x-iconsax-bul-video-time class="icons text-primary" width="24px" height="24px"/>
                                </div>
                            </div>
                            <h5 class="font-24 mt-12 line-height-1 text-black">{{ $totalCourses }}</h5>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card-statistic">
                        <div class="card-statistic__mask"></div>
                        <div class="card-statistic__wrap">
                            <div class="d-flex align-items-start justify-content-between">
                                <span class="text-gray-500 mt-8">{{ trans('update.released_courses') }}</span>
                                <div class="d-flex-center size-48 bg-success-30 rounded-12">
                                    <x-iconsax-bul-video-tick class="icons text-success" width="24px" height="24px"/>
                                </div>
                            </div>
                            <h5 class="font-24 mt-12 line-height-1 text-black">{{ $releasedCourses }}</h5>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card-statistic">
                        <div class="card-statistic__mask"></div>
                        <div class="card-statistic__wrap">
                            <div class="d-flex align-items-start justify-content-between">
                                <span class="text-gray-500 mt-8">{{ trans('update.not_released') }}</span>
                                <div class="d-flex-center size-48 bg-danger-30 rounded-12">
                                    <x-iconsax-bul-video-remove class="icons text-danger" width="24px" height="24px"/>
                                </div>
                            </div>
                            <h5 class="font-24 mt-12 line-height-1 text-black">{{ $notReleased }}</h5>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card-statistic">
                        <div class="card-statistic__mask"></div>
                        <div class="card-statistic__wrap">
                            <div class="d-flex align-items-start justify-content-between">
                                <span class="text-gray-500 mt-8">{{ trans('update.followers') }}</span>
                                <div class="d-flex-center size-48 bg-secondary-30 rounded-12">
                                    <x-iconsax-bul-profile-2user class="icons text-secondary" width="24px" height="24px"/>
                                </div>
                            </div>
                            <h5 class="font-24 mt-12 line-height-1 text-black">{{ $followers }}</h5>
                        </div>
                    </div>
                </div>
            </div>

            <section class="card mt-32">
                <div class="card-body pb-4">
                    <form method="get" class="mb-0">

                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="input-label">{{trans('admin/main.search')}}</label>
                                    <input name="title" type="text" class="form-control" value="{{ request()->get('title') }}">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="input-label">{{trans('update.release_date')}}</label>
                                    <div class="input-group">
                                        <input type="date" id="from" class="text-center form-control" name="from" value="{{ request()->get('from') }}" placeholder="Start Date">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="input-label">{{trans('admin/main.end_date')}}</label>
                                    <div class="input-group">
                                        <input type="date" id="to" class="text-center form-control" name="to" value="{{ request()->get('to') }}" placeholder="End Date">
                                    </div>
                                </div>
                            </div>


                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="input-label">{{trans('admin/main.filters')}}</label>
                                    <select name="sort" data-plugin-selectTwo class="form-control populate">
                                        <option value="">{{ trans('public.all') }}</option>
                                        <option value="newest" @if(request()->get('sort', null) == 'newest') selected="selected" @endif>{{ trans('public.newest') }}</option>
                                        <option value="earliest_publish_date" @if(request()->get('sort', null) == 'earliest_publish_date') selected="selected" @endif>{{ trans('update.earliest_publish_date') }}</option>
                                        <option value="farthest_publish_date" @if(request()->get('sort', null) == 'farthest_publish_date') selected="selected" @endif>{{ trans('update.farthest_publish_date') }}</option>
                                        <option value="highest_price" @if(request()->get('sort', null) == 'highest_price') selected="selected" @endif>{{ trans('update.highest_price') }}</option>
                                        <option value="lowest_price" @if(request()->get('sort', null) == 'lowest_price') selected="selected" @endif>{{ trans('update.lowest_price') }}</option>
                                        <option value="only_not_released_courses" @if(request()->get('sort', null) == 'only_not_released_courses') selected="selected" @endif>{{ trans('update.only_not_released_courses') }}</option>
                                    </select>
                                </div>
                            </div>


                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="input-label">{{trans('admin/main.instructor')}}</label>
                                    <select name="teacher_ids[]" multiple="multiple" data-search-option="just_teacher_role" class="form-control search-user-select2"
                                            data-placeholder="Search teachers">

                                        @if(!empty($teachers) and $teachers->count() > 0)
                                            @foreach($teachers as $teacher)
                                                <option value="{{ $teacher->id }}" selected>{{ $teacher->full_name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>


                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="input-label">{{trans('admin/main.category')}}</label>
                                    <select name="category_id" data-plugin-selectTwo class="form-control populate">
                                        <option value="">{{trans('admin/main.all_categories')}}</option>

                                        @foreach($categories as $category)
                                            @if(!empty($category->subCategories) and count($category->subCategories))
                                                <optgroup label="{{ $category->title }}">
                                                    @foreach($category->subCategories as $subCategory)
                                                        <option value="{{ $subCategory->id }}" @if(request()->get('category_id') == $subCategory->id) selected="selected" @endif>{{ $subCategory->title }}</option>
                                                    @endforeach
                                                </optgroup>
                                            @else
                                                <option value="{{ $category->id }}" @if(request()->get('category_id') == $category->id) selected="selected" @endif>{{ $category->title }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>


                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="input-label">{{trans('admin/main.status')}}</label>
                                    <select name="status" data-plugin-selectTwo class="form-control populate">
                                        <option value="">{{trans('admin/main.all_status')}}</option>
                                        <option value="pending" @if(request()->get('status') == 'pending') selected @endif>{{trans('admin/main.pending_review')}}</option>
                                        <option value="active" @if(request()->get('status') == 'active') selected @endif>{{trans('admin/main.published')}}</option>
                                        <option value="inactive" @if(request()->get('status') == 'inactive') selected @endif>{{trans('admin/main.rejected')}}</option>
                                        <option value="is_draft" @if(request()->get('status') == 'is_draft') selected @endif>{{trans('admin/main.draft')}}</option>
                                    </select>
                                </div>
                            </div>


                            <div class="col-md-3 d-flex align-items-center ">
                                <button type="submit" class="btn btn-primary btn-block btn-lg">{{trans('admin/main.show_results')}}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </section>

            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="card">

                        <div class="card-header justify-content-between">

                            <div>
                                <h5 class="font-14 mb-0">{{ $pageTitle }}</h5>
                                <p class="font-12 mt-4 mb-0 text-gray-500">{{ trans('update.manage_all_upcoming_courses_in_a_single_place') }}</p>
                            </div>

                            <div class="d-flex align-items-center gap-12">

                                @can('admin_webinars_export_excel')
                                    <a href="{{ getAdminPanelUrl('/upcoming_courses/excel?'. http_build_query(request()->all())) }}" class="btn bg-white bg-hover-gray-100 border-gray-400 text-gray-500">
                                        <x-iconsax-lin-import-2 class="icons text-gray-500" width="18px" height="18px"/>
                                        <span class="ml-4 font-12">{{ trans('admin/main.export_xls') }}</span>
                                    </a>
                                @endcan

                                @can('admin_upcoming_courses_create')
                                    <a href="{{ getAdminPanelUrl('/upcoming_courses/new') }}" target="_blank" class="btn btn-primary">
                                        <x-iconsax-lin-add class="icons text-white" width="18px" height="18px"/>
                                        <span class="ml-4 font-12">{{ trans('admin/main.new') }} {{ trans('update.upcoming_course') }}</span>
                                    </a>
                                @endcan

                            </div>

                        </div>

                        <div class="card-body">
                            <div>
                                <table class="table custom-table font-14 ">
                                    <tr>
                                        <th>{{trans('admin/main.id')}}</th>
                                        <th class="text-left">{{trans('admin/main.title')}}</th>
                                        <th class="text-left">{{trans('admin/main.instructor')}}</th>
                                        <th>{{trans('admin/main.type')}}</th>
                                        <th>{{trans('admin/main.price')}}</th>
                                        <th>{{trans('update.followers')}}</th>
                                        <th>{{trans('admin/main.start_date')}}</th>
                                        <th>{{trans('admin/main.created_at')}}</th>
                                        <th>{{trans('admin/main.status')}}</th>
                                        <th width="120">{{trans('admin/main.actions')}}</th>
                                    </tr>

                                    @foreach($upcomingCourses as $upcomingCourse)
                                        <tr class="text-center">
                                            <td>{{ $upcomingCourse->id }}</td>

                                            <td width="18%" class="text-left">
                                                <a class="text-dark mt-0 mb-1" href="{{ $upcomingCourse->getUrl() }}">{{ $upcomingCourse->title }}</a>
                                                @if(!empty($upcomingCourse->category->title))
                                                    <div class="text-small text-gray-500">{{ $upcomingCourse->category->title }}</div>
                                                @else
                                                    <div class="text-small text-warning">{{trans('admin/main.no_category')}}</div>
                                                @endif
                                            </td>

                                            <td class="text-left">{{ $upcomingCourse->teacher->full_name }}</td>

                                            <td class="">{{ trans('admin/main.'.$upcomingCourse->type) }}</td>

                                            <td>
                                                @if(!empty($upcomingCourse->price) and $upcomingCourse->price > 0)
                                                    <span class="mt-0 mb-1">
                                                        {{ handlePrice($upcomingCourse->price, true, true) }}
                                                    </span>
                                                @else
                                                    {{ trans('public.free') }}
                                                @endif
                                            </td>

                                            <td class="font-12">
                                                <a href="{{ getAdminPanelUrl('/upcoming_courses/'. $upcomingCourse->id .'/followers') }}" target="_blank" class="text-dark">{{ $upcomingCourse->followers_count }}</a>
                                            </td>

                                            <td>{{ dateTimeFormat($upcomingCourse->publish_date, 'Y M j | H:i') }}</td>

                                            <td>{{ dateTimeFormat($upcomingCourse->created_at, 'Y M j | H:i') }}</td>

                                            <td>
                                                @if(!empty($upcomingCourse->webinar_id))
                                                    <span class="badge-status text-success bg-success-30">{{ trans('update.released') }}</span>
                                                @else
                                                    @switch($upcomingCourse->status)
                                                        @case(\App\Models\Webinar::$active)
                                                            <span class="badge-status text-primary bg-primary-30">{{ trans('admin/main.published') }}</span>
                                                            @break
                                                        @case(\App\Models\Webinar::$isDraft)
                                                            <span class="badge-status text-dark bg-dark-30">{{ trans('admin/main.is_draft') }}</span>
                                                            @break
                                                        @case(\App\Models\Webinar::$pending)
                                                            <span class="badge-status text-warning bg-warning-30">{{ trans('admin/main.waiting') }}</span>
                                                            @break
                                                        @case(\App\Models\Webinar::$inactive)
                                                            <span class="badge-status text-danger bg-danger-30">{{ trans('public.rejected') }}</span>
                                                            @break
                                                    @endswitch
                                                @endif
                                            </td>


                                            <td>
                                                <div class="btn-group dropdown table-actions position-relative">
                                                    <button type="button" class="btn-transparent dropdown-toggle" data-toggle="dropdown">
                                                        <x-iconsax-lin-more class="icons text-gray-500" width="20px" height="20px"/>
                                                    </button>

                                                    <div class="dropdown-menu dropdown-menu-right">

                                                        @can('admin_upcoming_courses_edit')
                                                            @if($upcomingCourse->status != \App\Models\Webinar::$active)

                                                                @include('admin.includes.delete_button',[
                                                                'url' => getAdminPanelUrl('/upcoming_courses/'.$upcomingCourse->id.'/approve'),
                                                               'btnClass' => 'dropdown-item text-success mb-3 py-3 px-0 font-14',
                                                               'btnText' => trans("admin/main.approve"),
                                                               'btnIcon' => 'tick-square',
                                                               'iconType' => 'lin',
                                                               'iconClass' => 'text-success mr-2',
                                                            ])
                                                            @endif
                                                            @if($upcomingCourse->status == \App\Models\Webinar::$pending)
                                                                @include('admin.includes.delete_button',[
                                                                   'url' => getAdminPanelUrl('/upcoming_courses/'.$upcomingCourse->id.'/reject'),
                                                                   'btnClass' => 'dropdown-item  text-danger mb-3 py-3 px-0 font-14',
                                                                   'btnText' => trans("admin/main.reject"),
                                                                   'btnIcon' => 'close-square',
                                                                   'iconType' => 'lin',
                                                                   'iconClass' => 'text-danger mr-2',
                                                                ])
                                                            @endif
                                                            @if($upcomingCourse->status == \App\Models\Webinar::$active)
                                                                @include('admin.includes.delete_button',[
                                                                'url' => getAdminPanelUrl('/upcoming_courses/'.$upcomingCourse->id.'/unpublish'),
                                                               'btnClass' => 'dropdown-item text-danger mb-3 py-3 px-0 font-14',
                                                               'btnText' => trans("admin/main.unpublish"),
                                                               'btnIcon' => 'gallery-slash',
                                                               'iconType' => 'lin',
                                                               'iconClass' => 'text-danger mr-2',
                                                            ])

                                                            @endif
                                                        @endcan

                                                        @can('admin_upcoming_courses_followers')
                                                            <a href="{{ getAdminPanelUrl() }}/upcoming_courses/{{ $upcomingCourse->id }}/followers" class="dropdown-item d-flex align-items-center mb-3 py-3 px-0 gap-4">
                                                                <x-iconsax-lin-people class="icons text-gray-500 mr-2" width="18px" height="18px"/>
                                                                <span class="text-gray-500 font-14">{{ trans('update.followers') }}</span>
                                                            </a>
                                                        @endcan

                                                        @can('admin_upcoming_courses_edit')
                                                            <a href="{{ getAdminPanelUrl('/upcoming_courses/'. $upcomingCourse->id .'/edit') }}" class="dropdown-item d-flex align-items-center mb-3 py-3 px-0 gap-4">
                                                                <x-iconsax-lin-edit-2 class="icons text-gray-500 mr-2" width="18px" height="18px"/>
                                                                <span class="text-gray-500 font-14">{{ trans('admin/main.edit') }}</span>
                                                            </a>
                                                        @endcan

                                                        @can('admin_webinars_delete')

                                                            @include('admin.includes.delete_button',[
                                                             'url' => getAdminPanelUrl('/upcoming_courses/'.$upcomingCourse->id.'/delete'),
                                                            'btnClass' => 'dropdown-item text-danger mb-0 py-3 px-0 font-14',
                                                            'btnText' => trans("admin/main.delete"),
                                                            'btnIcon' => 'trash',
                                                            'iconType' => 'lin',
                                                            'iconClass' => 'text-danger mr-2',
                                                         ])
                                                        @endcan
                                                    </div>
                                                </div>
                                            </td>


                                        </tr>
                                    @endforeach
                                </table>
                            </div>
                        </div>

                        <div class="card-footer text-center">
                            {{ $upcomingCourses->appends(request()->input())->links() }}
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </section>
@endsection
