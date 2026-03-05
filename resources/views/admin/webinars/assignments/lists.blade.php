@extends('admin.layouts.app')

@push('styles_top')

@endpush

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{ $pageTitle }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ getAdminPanelUrl() }}">{{trans('admin/main.dashboard')}}</a>
                </div>
                <div class="breadcrumb-item">{{ $pageTitle }}</div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card-statistic">
                    <div class="card-statistic__mask"></div>
                    <div class="card-statistic__wrap">
                        <div class="d-flex align-items-start justify-content-between">
                            <span class="text-gray-500 mt-8">{{trans('update.course_assignments')}}</span>
                            <div class="d-flex-center size-48 bg-primary-30 rounded-12">
                                <x-iconsax-bul-edit class="icons text-primary" width="24px" height="24px"/>
                            </div>
                        </div>
                        <h5 class="font-24 mt-12 line-height-1 text-black">{{ $courseAssignmentsCount }}</h5>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card-statistic">
                    <div class="card-statistic__mask"></div>
                    <div class="card-statistic__wrap">
                        <div class="d-flex align-items-start justify-content-between">
                            <span class="text-gray-500 mt-8">{{trans('update.pending_review')}}</span>
                            <div class="d-flex-center size-48 bg-warning-30 rounded-12">
                                <x-iconsax-bul-timer class="icons text-warning" width="24px" height="24px"/>
                            </div>
                        </div>
                        <h5 class="font-24 mt-12 line-height-1 text-black">{{ $pendingReviewCount }}</h5>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card-statistic">
                    <div class="card-statistic__mask"></div>
                    <div class="card-statistic__wrap">
                        <div class="d-flex align-items-start justify-content-between">
                            <span class="text-gray-500 mt-8">{{trans('quiz.passed')}}</span>
                            <div class="d-flex-center size-48 bg-success-30 rounded-12">
                                <x-iconsax-bul-user-tick class="icons text-success" width="24px" height="24px"/>
                            </div>
                        </div>
                        <h5 class="font-24 mt-12 line-height-1 text-black">{{ $passedCount }}</h5>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card-statistic">
                    <div class="card-statistic__mask"></div>
                    <div class="card-statistic__wrap">
                        <div class="d-flex align-items-start justify-content-between">
                            <span class="text-gray-500 mt-8">{{trans('quiz.failed')}}</span>
                            <div class="d-flex-center size-48 bg-danger-30 rounded-12">
                                <x-iconsax-bul-user-remove class="icons text-danger" width="24px" height="24px"/>
                            </div>
                        </div>
                        <h5 class="font-24 mt-12 line-height-1 text-black">{{ $failedCount }}</h5>
                    </div>
                </div>
            </div>

        </div>

        <div class="section-body">
            <section class="card mt-32">
                <div class="card-body pb-4">
                    <form method="get" class="mb-0">
                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="input-label">{{trans('admin/main.start_date')}}</label>
                                    <div class="input-group">
                                        <input type="date" id="fsdate" class="text-center form-control" name="from" value="{{ request()->get('from') }}" placeholder="Start Date">
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="input-label">{{trans('admin/main.end_date')}}</label>
                                    <div class="input-group">
                                        <input type="date" id="lsdate" class="text-center form-control" name="to" value="{{ request()->get('to') }}" placeholder="End Date">
                                    </div>
                                </div>
                            </div> 

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="input-label">{{trans('admin/main.class')}}</label>
                                    <select name="webinar_ids[]" multiple="multiple" class="form-control search-webinar-select2"
                                            data-placeholder="Search classes">

                                        @if(!empty($webinars) and $webinars->count() > 0)
                                            @foreach($webinars as $webinar)
                                                <option value="{{ $webinar->id }}" selected>{{ $webinar->title }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="input-label">{{trans('admin/main.status')}}</label>
                                    <select name="status" class="form-control populate">
                                        <option value="">{{ trans('public.all') }}</option>
                                        <option value="active" {{ (request()->get('status') == 'active') ? 'selected' : '' }}>{{ trans('admin/main.active') }}</option>
                                        <option value="inactive" {{ (request()->get('status') == 'inactive') ? 'selected' : '' }}>{{ trans('admin/main.inactive') }}</option>
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

            <section class="card">

            <div class="card-header justify-content-between">
                            
                            <div>
                               <h5 class="font-14 mb-0">{{ $pageTitle }}</h5>
                               <p class="font-12 mt-4 mb-0 text-gray-500">{{ trans('update.manage_all_assignment_in_a_single_place') }}</p>
                           </div>                           
                           
                       </div>

                <div class="card-body">
                    <table class="table custom-table font-14" id="datatable-details">

                        <tr>
                            <th>{{ trans('update.title_and_course') }}</th>
                            <th class="text-center">{{ trans('public.students') }}</th>
                            <th class="text-center">{{ trans('quiz.grade') }}</th>
                            <th class="text-center">{{ trans('update.pass_grade') }}</th>
                            <th class="text-center">{{ trans('public.status') }}</th>
                            <th class="text-right">{{ trans('admin/main.action') }}</th>
                        </tr>

                        @foreach($assignments as $assignment)
                            <tr>
                                <td class="text-left">
                                    <span class="d-block text-dark">{{ $assignment->title }}</span>
                                    <span class="d-block font-12 text-gray-500">{{ $assignment->webinar->title }}</span>
                                </td>

                                <td class="align-middle">
                                    <span>{{ count($assignment->instructorAssignmentHistories) }}</span>
                                </td>

                                <td class="align-middle">
                                    <span>{{ $assignment->grade }}</span>
                                </td>

                                <td class="align-middle">
                                    <span>{{ $assignment->pass_grade }}</span>
                                </td>

                                <td class="align-middle">
                                    <span class="badge-status {{ ($assignment->status == 'active') ? 'text-success bg-success-30' : 'text-warning bg-warning-30' }}">{{ trans('admin/main.'.$assignment->status) }}</span>
                                </td>

                                <td class="align-middle text-right">
                                <div class="btn-group dropdown table-actions position-relative">
                                        <button type="button" class="btn-transparent dropdown-toggle" data-toggle="dropdown">
                                            <x-iconsax-lin-more class="icons text-gray-500" width="20px" height="20px"/>
                                        </button>

                                        <div class="dropdown-menu dropdown-menu-right">
                                        @can('admin_reviews_status_toggle')
                                                <a href="{{ getAdminPanelUrl() }}/assignments/{{ $assignment->id }}/students" class="dropdown-item d-flex align-items-center mb-3 py-3 px-0 gap-4">
                                                <x-iconsax-lin-teacher class="icons text-gray-500 mr-2" width="18px" height="18px"/>
                                                    <span class="text-gray-500 font-14">{{ trans('public.students') }}</span>
                                                </a>
                                            @endcan

                                                <a href="{{ getAdminPanelUrl() }}/webinars/{{ $assignment->webinar_id }}/edit" class="dropdown-item d-flex align-items-center mb-3 py-3 px-0 gap-4">
                                                <x-iconsax-lin-video-play class="icons text-gray-500 mr-2" width="18px" height="18px"/>
                                                    <span class="text-gray-500 font-14">{{ trans('admin/main.course') }}</span>
                                                </a>

                                                @can('admin_webinars_edit')

                                                @include('admin.includes.delete_button',[
                                                           'url' => getAdminPanelUrl().'/assignments/'.$assignment->id.'/delete',
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
            </section>
        </div>
    </section>

@endsection

@push('scripts_bottom')

@endpush
