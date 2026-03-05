@extends('admin.layouts.app')

@push('libraries_top')

@endpush

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{ $pageTitle  }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ getAdminPanelUrl() }}">{{trans('admin/main.dashboard')}}</a>
                </div>
                <div class="breadcrumb-item">{{trans('admin/main.classes')}}</div>

                <div class="breadcrumb-item">{{ $pageTitle }}</div>
            </div>
        </div>

        <div class="section-body">

            <div class="row">

            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card-statistic">
                        <div class="card-statistic__mask"></div>
                        <div class="card-statistic__wrap">
                            <div class="d-flex align-items-start justify-content-between">
                                <span class="text-gray-500 mt-8"><h4>{{trans('update.total_bundles')}}</h4></span>
                                <div class="d-flex-center size-48 bg-primary-30 rounded-12">
                                <x-iconsax-bul-box class="icons text-primary" width="24px" height="24px"/>
                                </div>
                            </div>

                            <h5 class="font-24 mt-12 line-height-1 text-black">{{ $totalBundles }}</h5>
                        </div>
                    </div>
                </div>

                 <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card-statistic">
                        <div class="card-statistic__mask"></div>
                        <div class="card-statistic__wrap">
                            <div class="d-flex align-items-start justify-content-between">
                                <span class="text-gray-500 mt-8"><h4>{{trans('admin/main.pending_review')}}</h4></span>
                                <div class="d-flex-center size-48 bg-warning-30 rounded-12">
                                    <x-iconsax-bul-video-time class="icons text-warning" width="24px" height="24px"/>
                                </div>
                            </div>

                            <h5 class="font-24 mt-12 line-height-1 text-black">{{ $totalPendingBundles }}</h5>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card-statistic">
                        <div class="card-statistic__mask"></div>
                        <div class="card-statistic__wrap">
                            <div class="d-flex align-items-start justify-content-between">
                                <span class="text-gray-500 mt-8"> <h4>{{trans('admin/main.total_sales')}}</h4></span>
                                <div class="d-flex-center size-48 bg-success-30 rounded-12">
                                    <x-iconsax-bul-bag class="icons text-success" width="24px" height="24px"/>
                                </div>
                            </div>

                            <h5 class="font-24 mt-12 line-height-1 text-black">{{ !empty($totalSales) ? $totalSales->sales_count : 0 }}</h5>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card-statistic">
                        <div class="card-statistic__mask"></div>
                        <div class="card-statistic__wrap">
                            <div class="d-flex align-items-start justify-content-between">
                                <span class="text-gray-500 mt-8"> <h4>{{trans('admin/main.total_sales')}}</h4></span>
                                <div class="d-flex-center size-48 bg-secondary-30 rounded-12">
                                    <x-iconsax-bul-dollar-square class="icons text-secondary" width="24px" height="24px"/>
                                </div>
                            </div>

                            <h5 class="font-24 mt-12 line-height-1 text-black">{{ (!empty($totalSales) and !empty($totalSales->total_amount)) ? handlePrice($totalSales->total_amount) : 0 }}</h5>
                        </div>
                    </div>
                </div>

        
            </div>

            <section class="card mt-32">
                <div class="card-body">
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
                                    </select>
                                </div>
                            </div>


                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="input-label">{{trans('admin/main.instructor')}}</label>
                                    <select name="teacher_ids[]" multiple="multiple" data-search-option="just_teacher_role" class="form-control search-user-select2"
                                            data-placeholder="{{trans('public.search_instructors')}}">

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
                                <p class="font-12 mt-4 mb-0 text-gray-500">{{ trans('update.manage_all_bundles_in_a_single_place') }}</p>
                            </div>
                            
                             <div class="d-flex align-items-center gap-12">

                             @can('admin_webinars_export_excel')
                                    <a href="{{ getAdminPanelUrl() }}/bundles/excel?{{ http_build_query(request()->all()) }}" class="btn bg-white bg-hover-gray-100 border-gray-400 text-gray-500">
                                        <x-iconsax-lin-import-2 class="icons text-gray-500" width="18px" height="18px"/>
                                        <span class="ml-4 font-12">{{ trans('admin/main.export_xls') }}</span>
                                    </a>
                                @endcan

                                @can('admin_bundles_create')
                                    <a href="{{ getAdminPanelUrl("/bundles/create") }}" target="_blank" class="btn btn-primary">
                                        <x-iconsax-lin-add class="icons text-white" width="18px" height="18px"/>
                                        <span class="ml-4 font-12">{{ trans('admin/main.new') }} {{ trans('update.bundle') }}</span>
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
                                        <th>{{trans('admin/main.course_count')}}</th>
                                        <th>{{trans('admin/main.created_at')}}</th>
                                        <th>{{trans('admin/main.updated_at')}}</th>
                                        <th>{{trans('admin/main.status')}}</th>
                                        <th width="80">{{trans('admin/main.actions')}}</th>
                                    </tr>

                                    @foreach($bundles as $bundle)
                                        <tr class="text-center">

                                            <td>{{ $bundle->id }}</td>

                                            <td width="12%" class="text-left">
                                                <a class="text-dark mt-0 mb-1" href="{{ $bundle->getUrl() }}">{{ $bundle->title }}</a>
                                                @if(!empty($bundle->category->title))
                                                    <div class="text-small font-12 text-gray-500">{{ $bundle->category->title }}</div>
                                                @else
                                                    <div class="text-small font-12 text-warning">{{trans('admin/main.no_category')}}</div>
                                                @endif
                                            </td>


                                            <td class="text-left">{{ $bundle->teacher->full_name }}</td>

                                            <td>
                                                @if(!empty($bundle->price) and $bundle->price > 0)
                                                    <span class="mt-0 mb-1">
                                                        {{ handlePrice($bundle->price, true, true) }}
                                                    </span>

                                                    @if($bundle->getDiscountPercent() > 0)
                                                        <div class="text-danger text-small">{{ $bundle->getDiscountPercent() }}% {{trans('admin/main.off')}}</div>
                                                    @endif
                                                @else
                                                    {{ trans('public.free') }}
                                                @endif
                                            </td>
                                            <td>
                                                <span class="text-dark mt-0 mb-1">
                                                    {{ $bundle->sales->count() }}
                                                </span>
                                            </td>

                                            <td>{{ handlePrice($bundle->sales->sum('total_amount')) }}</td>

                                            <td class="font-12">
                                                {{ $bundle->bundle_webinars_count }}
                                            </td>

                                            <td>{{ dateTimeFormat($bundle->created_at, 'Y M j | H:i') }}</td>

                                            <td>{{ dateTimeFormat($bundle->updated_at, 'Y M j | H:i') }}</td>

                                            <td>
                                                @switch($bundle->status)
                                                    @case(\App\Models\Webinar::$active)
                                                    <span class="badge-status text-success bg-success-30">{{ trans('admin/main.published') }}</span>
                                                        @break
                                                    @case(\App\Models\Bundle::$isDraft)
                                                    <span class="badge-status text-dark bg-dark-30">{{ trans('admin/main.is_draft') }}</span>
                                                        @break
                                                    @case(\App\Models\Bundle::$pending)
                                                    <span class="badge-status text-warning bg-warning-30">{{ trans('admin/main.waiting') }}</span>
                                                        @break
                                                    @case(\App\Models\Bundle::$inactive)
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
                                                         @if(in_array($bundle->status, [\App\Models\Bundle::$pending, \App\Models\Bundle::$inactive]))
                                                       
                                                            @include('admin.includes.delete_button',[
                                                           'url' => getAdminPanelUrl("/bundles/{$bundle->id}/approve"),
                                                           'btnClass' => 'dropdown-item text-success mb-3 py-3 px-0 font-14',
                                                           'btnText' => trans("admin/main.approve"),
                                                           'btnIcon' => 'tick-square',
                                                           'iconType' => 'lin',
                                                           'iconClass' => 'text-success mr-2',
                                                        ])
                                                        @endif
                                                        @if($bundle->status == \App\Models\Bundle::$pending)
                                                        @include('admin.includes.delete_button',[
                                                           'url' => getAdminPanelUrl().'/bundles/'.$bundle->id.'/reject',
                                                           'btnClass' => 'dropdown-item text-danger mb-3 py-3 px-0 font-14',
                                                           'btnText' => trans("admin/main.reject"),
                                                           'btnIcon' => 'close-square',
                                                           'iconType' => 'lin',
                                                           'iconClass' => 'text-danger mr-2',
                                                        ])
                                                        @endif
                                                        @if($bundle->status == \App\Models\Bundle::$active)
                                                            @include('admin.includes.delete_button',[
                                                           'url' => getAdminPanelUrl().'/bundles/'.$bundle->id.'/unpublish',
                                                           'btnClass' => 'dropdown-item text-danger mb-3 py-3 px-0 font-14',
                                                           'btnText' => trans("admin/main.unpublish"),
                                                           'btnIcon' => 'gallery-slash',
                                                           'iconType' => 'lin',
                                                           'iconClass' => 'text-danger mr-2',
                                                        ])

                                                        @endif
                                                        @endcan

                                                        @can('admin_webinar_notification_to_students')
                                                            <a href="{{ getAdminPanelUrl() }}/bundles/{{ $bundle->id }}/sendNotification" class="dropdown-item d-flex align-items-center mb-3 py-3 px-0 gap-4">
                                                            <x-iconsax-lin-notification-bing class="icons text-gray-500 mr-2" width="18px" height="18px"/>
                                                                <span class="text-gray-500 font-14">{{ trans('notification.send_notification') }}</span>
                                                            </a>
                                                        @endcan

                                                        @can('admin_webinar_students_lists')
                                                            <a href="{{ getAdminPanelUrl() }}/bundles/{{ $bundle->id }}/students" class="dropdown-item d-flex btn-transparent align-items-center mb-3 py-3 px-0 gap-4">
                                                            <x-iconsax-lin-teacher class="icons text-gray-500 mr-2" width="18px" height="18px"/>
                                                                <span class="text-gray-500 font-14">{{ trans('admin/main.students') }}</span>
                                                            </a>
                                                        @endcan

                                                        @can('admin_support_send')
                                                            <a href="{{ getAdminPanelUrl() }}/supports/create?user_id={{ $bundle->teacher->id }}" class="dropdown-item d-flex align-items-center mb-3 py-3 px-0 gap-4">
                                                            <x-iconsax-lin-sms-tracking class="icons text-gray-500 mr-2" width="18px" height="18px"/>
                                                                <span class="text-gray-500 font-14">{{ trans('site.send_message') }}</span>
                                                            </a>
                                                        @endcan

                                                        @can('admin_webinars_edit')
                                                            <a href="{{ getAdminPanelUrl() }}/bundles/{{ $bundle->id }}/edit" class="dropdown-item d-flex align-items-center mb-3 py-3 px-0 gap-4">
                                                            <x-iconsax-lin-edit-2 class="icons text-gray-500 mr-2" width="18px" height="18px"/>
                                                                <span class="text-gray-500 font-14">{{ trans('admin/main.edit') }}</span>
                                                            </a>
                                                        @endcan

                                                        @can('admin_webinars_delete')

                                                            @include('admin.includes.delete_button',[
                                                           'url' => getAdminPanelUrl().'/bundles/'.$bundle->id.'/delete',
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
                            {{ $bundles->appends(request()->input())->links() }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts_bottom')

@endpush
