@extends('admin.layouts.app')

@push('libraries_top')

@endpush

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{ trans('admin/main.type_'.$classesType.'s') }} {{trans('admin/main.list')}}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ getAdminPanelUrl() }}">{{trans('admin/main.dashboard')}}</a>
                </div>
                <div class="breadcrumb-item">{{trans('admin/main.classes')}}</div>

                <div class="breadcrumb-item">{{ trans('admin/main.type_'.$classesType.'s') }}</div>
            </div>
        </div>

        <div class="section-body">

            <div class="row">
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card-statistic">
                        <div class="card-statistic__mask"></div>
                        <div class="card-statistic__wrap">
                            <div class="d-flex align-items-start justify-content-between">
                                <span class="text-gray-500 mt-8">{{trans('admin/main.total')}} {{ trans('admin/main.type_'.$classesType.'s') }}</span>
                                <div class="d-flex-center size-48 bg-primary-30 rounded-12">
                                    <x-iconsax-bul-video-play class="icons text-primary" width="24px" height="24px"/>
                                </div>
                            </div>

                            <h5 class="font-24 mt-12 line-height-1 text-black">{{ $totalWebinars }}</h5>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card-statistic">
                        <div class="card-statistic__mask"></div>
                        <div class="card-statistic__wrap">
                            <div class="d-flex align-items-start justify-content-between">
                                <span class="text-gray-500 mt-8">{{trans('admin/main.pending_review')}} {{ trans('admin/main.type_'.$classesType.'s') }}</span>
                                <div class="d-flex-center size-48 bg-warning-30 rounded-12">
                                    <x-iconsax-bul-video-time class="icons text-warning" width="24px" height="24px"/>
                                </div>
                            </div>

                            <h5 class="font-24 mt-12 line-height-1 text-black">{{ $totalPendingWebinars }}</h5>
                        </div>
                    </div>
                </div>

                @if($classesType == 'webinar')
                    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                        <div class="card-statistic">
                            <div class="card-statistic__mask"></div>
                            <div class="card-statistic__wrap">
                                <div class="d-flex align-items-start justify-content-between">
                                    <span class="text-gray-500 mt-8">{{trans('admin/main.inprogress_live_classes')}}</span>
                                    <div class="d-flex-center size-48 bg-accent-30 rounded-12">
                                        <x-iconsax-bul-timer class="icons text-accent" width="24px" height="24px"/>
                                    </div>
                                </div>

                                <h5 class="font-24 mt-12 line-height-1 text-black">{{ $inProgressWebinars }}</h5>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                        <div class="card-statistic">
                            <div class="card-statistic__mask"></div>
                            <div class="card-statistic__wrap">
                                <div class="d-flex align-items-start justify-content-between">
                                    <span class="text-gray-500 mt-8">{{trans('admin/main.total_durations')}}</span>
                                    <div class="d-flex-center size-48 bg-accent-30 rounded-12">
                                        <x-iconsax-bul-clock class="icons text-accent" width="24px" height="24px"/>
                                    </div>
                                </div>

                                <h5 class="font-24 mt-12 line-height-1 text-black">{{ convertMinutesToHourAndMinute($totalDurations) }} {{ trans('home.hours') }}</h5>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card-statistic">
                        <div class="card-statistic__mask"></div>
                        <div class="card-statistic__wrap">
                            <div class="d-flex align-items-start justify-content-between">
                                <span class="text-gray-500 mt-8">{{trans('admin/main.total_sales')}}</span>
                                <div class="d-flex-center size-48 bg-success-30 rounded-12">
                                    <x-iconsax-bul-bag class="icons text-success" width="24px" height="24px"/>
                                </div>
                            </div>

                            <h5 class="font-24 mt-12 line-height-1 text-black">{{ $totalSales }}</h5>
                        </div>
                    </div>
                </div>
            </div>

            <section class="card mt-32">
                <div class="card-body pb-4">
                    <form method="get" class="mb-0">
                        <input type="hidden" name="type" value="{{ request()->get('type') }}">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="input-label">{{trans('admin/main.search')}}</label>
                                    <input name="title" type="text" class="form-control" value="{{ request()->get('title') }}">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="input-label">{{trans('admin/main.start_date')}}</label>
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
                                        <option value="">{{trans('admin/main.filter_type')}}</option>
                                        <option value="has_discount" @if(request()->get('sort') == 'has_discount') selected @endif>{{trans('admin/main.discounted_classes')}}</option>
                                        <option value="sales_asc" @if(request()->get('sort') == 'sales_asc') selected @endif>{{trans('admin/main.sales_ascending')}}</option>
                                        <option value="sales_desc" @if(request()->get('sort') == 'sales_desc') selected @endif>{{trans('admin/main.sales_descending')}}</option>
                                        <option value="price_asc" @if(request()->get('sort') == 'price_asc') selected @endif>{{trans('admin/main.Price_ascending')}}</option>
                                        <option value="price_desc" @if(request()->get('sort') == 'price_desc') selected @endif>{{trans('admin/main.Price_descending')}}</option>
                                        <option value="income_asc" @if(request()->get('sort') == 'income_asc') selected @endif>{{trans('admin/main.Income_ascending')}}</option>
                                        <option value="income_desc" @if(request()->get('sort') == 'income_desc') selected @endif>{{trans('admin/main.Income_descending')}}</option>
                                        <option value="created_at_asc" @if(request()->get('sort') == 'created_at_asc') selected @endif>{{trans('admin/main.create_date_ascending')}}</option>
                                        <option value="created_at_desc" @if(request()->get('sort') == 'created_at_desc') selected @endif>{{trans('admin/main.create_date_descending')}}</option>
                                        <option value="updated_at_asc" @if(request()->get('sort') == 'updated_at_asc') selected @endif>{{trans('admin/main.update_date_ascending')}}</option>
                                        <option value="updated_at_desc" @if(request()->get('sort') == 'updated_at_desc') selected @endif>{{trans('admin/main.update_date_descending')}}</option>
                                        <option value="public_courses" @if(request()->get('sort') == 'public_courses') selected @endif>{{trans('update.public_courses')}}</option>
                                        <option value="courses_private" @if(request()->get('sort') == 'courses_private') selected @endif>{{trans('update.courses_private')}}</option>
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
                                                <optgroup label="{{  $category->title }}">
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
                                        @if($classesType == 'webinar')
                                            <option value="active_not_conducted" @if(request()->get('status') == 'active_not_conducted') selected @endif>{{trans('admin/main.publish_not_conducted')}}</option>
                                            <option value="active_in_progress" @if(request()->get('status') == 'active_in_progress') selected @endif>{{trans('admin/main.publish_inprogress')}}</option>
                                            <option value="active_finished" @if(request()->get('status') == 'active_finished') selected @endif>{{trans('admin/main.publish_finished')}}</option>
                                        @else
                                            <option value="active" @if(request()->get('status') == 'active') selected @endif>{{trans('admin/main.published')}}</option>
                                        @endif
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
                                <h5 class="font-14 mb-0">{{ trans('admin/main.type_'.$classesType.'s') }}</h5>
                                <p class="font-12 mt-4 mb-0 text-gray-500">{{ trans('update.manage_all_courses_in_a_single_place') }}</p>
                            </div>

                            <div class="d-flex align-items-center gap-12">
                                @can('admin_webinars_export_excel')
                                    <a href="{{ getAdminPanelUrl() }}/webinars/excel?{{ http_build_query(request()->all()) }}" class="btn bg-white bg-hover-gray-100 border-gray-400 text-gray-500">
                                        <x-iconsax-lin-import-2 class="icons text-gray-500" width="18px" height="18px"/>
                                        <span class="ml-4 font-12">{{ trans('admin/main.export_xls') }}</span>
                                    </a>
                                @endcan

                                @can('admin_webinars_create')
                                    <a href="{{ getAdminPanelUrl("/webinars/create") }}" target="_blank" class="btn btn-primary">
                                        <x-iconsax-lin-add class="icons text-white" width="18px" height="18px"/>
                                        <span class="ml-4 font-12">{{ trans('admin/main.webinar_new_page_title') }}</span>
                                    </a>
                                @endcan

                            </div>
                        </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table custom-table font-14 ">
                                    <tr>
                                        <th>{{trans('admin/main.id')}}</th>
                                        <th class="text-left">{{trans('admin/main.title')}}</th>
                                        <th class="text-left">{{trans('admin/main.instructor')}}</th>
                                        <th>{{trans('admin/main.price')}}</th>
                                        <th>{{trans('admin/main.sales')}}</th>
                                        <th>{{trans('admin/main.income')}}</th>
                                        <th>{{trans('admin/main.students_count')}}</th>
                                        <th>{{trans('admin/main.created_at')}}</th>
                                        @if($classesType == 'webinar')
                                            <th>{{trans('admin/main.start_date')}}</th>
                                            <th>{{trans('admin/main.live_status')}}</th>
                                        @else
                                            <th>{{trans('admin/main.updated_at')}}</th>
                                        @endif
                                        <th>{{trans('admin/main.status')}}</th>
                                        <th width="120">{{trans('admin/main.actions')}}</th>
                                    </tr>

                                    @foreach($webinars as $webinar)
                                        <tr class="text-center">
                                            <td>{{ $webinar->id }}</td>
                                            <td width="18%" class="text-left">
                                                <a class="text-dark mt-0 mb-1 " href="{{ $webinar->getUrl() }}">{{ $webinar->title }}</a>
                                                @if(!empty($webinar->category->title))
                                                    <div class="text-small text-gray-500">{{ $webinar->category->title }}</div>
                                                @else
                                                    <div class="text-small text-warning">{{trans('admin/main.no_category')}}</div>
                                                @endif
                                            </td>

                                            <td class="text-left">{{ $webinar->teacher->full_name }}</td>

                                            <td>
                                                @if(!empty($webinar->price) and $webinar->price > 0)
                                                    <span class="mt-0 mb-1">
                                                        {{ handlePrice($webinar->price, true, true) }}
                                                    </span>

                                                    @if($webinar->getDiscountPercent() > 0)
                                                        <div class="badge-status text-danger bg-danger-30">{{ $webinar->getDiscountPercent() }}% {{trans('admin/main.off')}}</div>
                                                    @endif
                                                @else
                                                    {{ trans('public.free') }}
                                                @endif
                                            </td>
                                            <td>
                                                <span class="text-dark mt-0 mb-1 ">
                                                    {{ $webinar->sales->count() }}
                                                </span>

                                                @if(!empty($webinar->capacity))
                                                    <div class="font-12 text-gray-500">{{trans('admin/main.capacity')}} : {{ $webinar->getWebinarCapacity() }}</div>
                                                @endif
                                            </td>

                                            <td>{{ handlePrice($webinar->sales->sum('total_amount')) }}</td>

                                            <td class="font-12">
                                                <a href="{{ getAdminPanelUrl() }}/webinars/{{ $webinar->id }}/students" target="_blank" class="">{{ $webinar->sales->count() }}</a>
                                            </td>

                                            <td>{{ dateTimeFormat($webinar->created_at, 'Y M j | H:i') }}</td>

                                            @if($classesType == 'webinar')
                                                <td>{{ dateTimeFormat($webinar->start_date, 'Y M j | H:i') }}</td>
                                                @if($webinar->isWebinar())
                                                <td>
                                                @switch($webinar->status)
                                                    @case(\App\Models\Webinar::$active)
                                                        @if($webinar->isWebinar())
                                                            @if($webinar->start_date > time())
                                                                <div class="font-14 badge-status text-danger">{{  trans('admin/main.not_conducted') }}</div>
                                                            @elseif($webinar->isProgressing())
                                                                <div class="font-14 badge-status text-warning">{{  trans('webinars.in_progress') }}</div>
                                                            @else
                                                                <div class=" font-14 badge-status text-success">{{ trans('public.finished') }}</div>
                                                            @endif
                                                        @endif
                                                        @break
                                                         @case(\App\Models\Webinar::$isDraft)
                                                        <span class="font-14 badge-status">-</span>
                                                        @break
                                                    @case(\App\Models\Webinar::$pending)
                                                        <span class="font-14 badge-status">-</span>
                                                        @break
                                                    @case(\App\Models\Webinar::$inactive)
                                                        <span class="font-14 badge-status">-</span>
                                                        @break

                                                @endswitch
                                               </td>
                                                @endif
                                            @else
                                                <td>{{ dateTimeFormat($webinar->updated_at, 'Y M j | H:i') }}</td>
                                            @endif

                                            <td>
                                                @switch($webinar->status)
                                                    @case(\App\Models\Webinar::$active)
                                                        <div class="badge-status text-success bg-success-30">{{ trans('admin/main.published') }}</div>
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
                                            </td>


                                            <td>
                                                <div class="btn-group dropdown table-actions position-relative">
                                                    <button type="button" class="btn-transparent dropdown-toggle" data-toggle="dropdown">
                                                        <x-iconsax-lin-more class="icons text-gray-500" width="20px" height="20px"/>
                                                    </button>

                                                    <div class="dropdown-menu dropdown-menu-right">

                                                        @can('admin_webinars_edit')
                                                            @if(in_array($webinar->status, [\App\Models\Webinar::$pending, \App\Models\Webinar::$inactive]))

                                                                @include('admin.includes.delete_button',[
                                                                   'url' => getAdminPanelUrl().'/webinars/'.$webinar->id.'/approve',
                                                                   'btnClass' => 'dropdown-item text-success mb-3 py-3 px-0 font-14',
                                                                   'btnText' => trans("admin/main.approve"),
                                                                   'btnIcon' => 'tick-square',
                                                                   'iconType' => 'lin',
                                                                   'iconClass' => 'text-success mr-2',
                                                                ])
                                                            @endif
                                                            @if($webinar->status == \App\Models\Webinar::$pending)
                                                                @include('admin.includes.delete_button',[
                                                                   'url' => getAdminPanelUrl().'/webinars/'.$webinar->id.'/reject',
                                                                   'btnClass' => 'dropdown-item  text-danger mb-3 py-3 px-0 font-14',
                                                                   'btnText' => trans("admin/main.reject"),
                                                                   'btnIcon' => 'close-square',
                                                                   'iconType' => 'lin',
                                                                   'iconClass' => 'text-danger mr-2',
                                                                ])
                                                            @endif
                                                            @if($webinar->status == \App\Models\Webinar::$active)

                                                                @include('admin.includes.delete_button',[
                                                               'url' => getAdminPanelUrl().'/webinars/'.$webinar->id.'/unpublish',
                                                               'btnClass' => 'dropdown-item text-danger mb-3 py-3 px-0 font-14',
                                                               'btnText' => trans("admin/main.unpublish"),
                                                               'btnIcon' => 'gallery-slash',
                                                               'iconType' => 'lin',
                                                               'iconClass' => 'text-danger mr-2',
                                                            ])

                                                            @endif
                                                        @endcan

                                                        @can('admin_webinar_notification_to_students')
                                                            <a href="{{ getAdminPanelUrl() }}/webinars/{{ $webinar->id }}/sendNotification" class="dropdown-item d-flex align-items-center mb-3 py-3 px-0 gap-4">
                                                                <x-iconsax-lin-notification-bing class="icons text-gray-500 mr-2" width="18px" height="18px"/>
                                                                <span class="text-gray-500 font-14">{{ trans('notification.send_notification') }}</span>
                                                            </a>
                                                        @endcan

                                                        @can('admin_webinar_students_lists')
                                                            <a href="{{ getAdminPanelUrl() }}/webinars/{{ $webinar->id }}/students" class="dropdown-item d-flex btn-transparent align-items-center mb-3 py-3 px-0 gap-4">
                                                                <x-iconsax-lin-teacher class="icons text-gray-500 mr-2" width="18px" height="18px"/>
                                                                <span class="text-gray-500 font-14">{{ trans('admin/main.students') }}</span>
                                                            </a>
                                                        @endcan

                                                        @can('admin_webinar_statistics')
                                                            <a href="{{ getAdminPanelUrl() }}/webinars/{{ $webinar->id }}/statistics" class="dropdown-item btn-transparent d-flex align-items-center mb-3 py-3 px-0 gap-4">
                                                                <x-iconsax-lin-graph class="icons text-gray-500 mr-2" width="18px" height="18px"/>
                                                                <span class="text-gray-500 font-14">{{ trans('update.statistics') }}</span>
                                                            </a>
                                                        @endcan

                                                        @can('admin_support_send')
                                                            <a href="{{ getAdminPanelUrl() }}/supports/create?user_id={{ $webinar->teacher->id }}" class="dropdown-item d-flex align-items-center mb-3 py-3 px-0 gap-4">
                                                                <x-iconsax-lin-sms-tracking class="icons text-gray-500 mr-2" width="18px" height="18px"/>
                                                                <span class="text-gray-500 font-14">{{ trans('site.send_message') }}</span>
                                                            </a>
                                                        @endcan

                                                        @can('admin_webinars_edit')
                                                            <a href="{{ getAdminPanelUrl() }}/webinars/{{ $webinar->id }}/edit" class="dropdown-item d-flex align-items-center mb-3 py-3 px-0 gap-4">
                                                                <x-iconsax-lin-edit-2 class="icons text-gray-500 mr-2" width="18px" height="18px"/>
                                                                <span class="text-gray-500 font-14">{{ trans('admin/main.edit') }}</span>
                                                            </a>
                                                        @endcan

                                                        @can('admin_webinars_delete')

                                                            @include('admin.includes.delete_button',[
                                                           'url' => getAdminPanelUrl().'/webinars/'.$webinar->id.'/delete',
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
                            {{ $webinars->appends(request()->input())->links() }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts_bottom')

@endpush
