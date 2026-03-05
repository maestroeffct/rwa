@extends('admin.layouts.app')

@push('libraries_top')

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
                            <span class="text-gray-500 mt-8">{{trans('admin/main.total_conversations')}}</span>
                            <div class="d-flex-center size-48 bg-primary-30 rounded-12">
                                <x-iconsax-bul-messages-3 class="icons text-primary" width="24px" height="24px"/>
                            </div>
                        </div>
                        <h5 class="font-24 mt-12 line-height-1 text-black">{{ $totalConversations }}</h5>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card-statistic">
                    <div class="card-statistic__mask"></div>
                    <div class="card-statistic__wrap">
                        <div class="d-flex align-items-start justify-content-between">
                            <span class="text-gray-500 mt-8">{{trans('admin/main.open_conversations')}}</span>
                            <div class="d-flex-center size-48 bg-success-30 rounded-12">
                                <x-iconsax-bul-messages-3 class="icons text-success" width="24px" height="24px"/>
                            </div>
                        </div>
                        <h5 class="font-24 mt-12 line-height-1 text-black">{{ $openConversationsCount }}</h5>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card-statistic">
                    <div class="card-statistic__mask"></div>
                    <div class="card-statistic__wrap">
                        <div class="d-flex align-items-start justify-content-between">
                            <span class="text-gray-500 mt-8">{{trans('admin/main.closed_conversations')}}</span>
                            <div class="d-flex-center size-48 bg-danger-30 rounded-12">
                                <x-iconsax-bul-messages-3 class="icons text-danger" width="24px" height="24px"/>
                            </div>
                        </div>
                        <h5 class="font-24 mt-12 line-height-1 text-black">{{ $closeConversationsCount }}</h5>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card-statistic">
                    <div class="card-statistic__mask"></div>
                    <div class="card-statistic__wrap">
                        <div class="d-flex align-items-start justify-content-between">
                            <span class="text-gray-500 mt-8">{{trans('admin/main.classes_with_support')}}</span>
                            <div class="d-flex-center size-48 bg-secondary-30 rounded-12">
                                <x-iconsax-bul-video-play class="icons text-secondary" width="24px" height="24px"/>
                            </div>
                        </div>
                        <h5 class="font-24 mt-12 line-height-1 text-black">{{ $classesWithSupport }}</h5>
                    </div>
                </div>
            </div>
        </div>

        <div class="section-body">

            <section class="card mt-32">
                <div class="card-body pb-4">
                    <form class="mb-0">
                        <input type="hidden" name="type" value="course_conversations">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="input-label">{{trans('admin/main.search')}}</label>
                                    <input type="text" name="webinar_title" value="{{ request()->get('webinar_title') }}" class="form-control text-center">
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
                                    <label class="input-label">{{trans('admin/main.status')}}</label>
                                    <select name="status" data-plugin-selectTwo class="form-control populate">
                                        <option value="">{{trans('admin/main.all_status')}}</option>
                                        <option value="open" @if(request()->get('status') == 'open') selected @endif>Open</option>
                                        <option value="close" @if(request()->get('status') == 'close') selected @endif>Closed</option>
                                    </select>
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
                                    <label class="input-label">{{trans('admin/main.student')}}</label>
                                    <select name="student_ids[]" multiple="multiple" data-search-option="just_student_role" class="form-control search-user-select2"
                                            data-placeholder="Search students">

                                        @if(!empty($students) and $students->count() > 0)
                                            @foreach($students as $student)
                                                <option value="{{ $student->id }}" selected>{{ $student->full_name }}</option>
                                            @endforeach
                                        @endif
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
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table custom-table font-14">
                                    <tr>
                                        <th>{{trans('admin/main.subject')}}</th>
                                        <th class="text-left">{{trans('admin/main.class')}}</th>
                                        <th class="text-left">{{trans('admin/main.instructor')}}</th>
                                        <th class="text-left">{{trans('admin/main.student')}}</th>
                                        <th class="text-center">{{trans('admin/main.created_date')}}</th>
                                        <th class="text-center">{{trans('admin/main.last_update')}}</th>
                                        <th class="text-center">{{trans('admin/main.status')}}</th>
                                        <th class="text-center">{{trans('admin/main.actions')}}</th>
                                    </tr>
                                    @foreach($supports as $support)
                                        <tr>
                                            <td>{{ $support->title }}</td>

                                            <td class="text-left">
                                                <a  class="text-dark" href="{{ $support->webinar->getUrl() }}" target="_blank">{{ $support->webinar->title }}</a>
                                            </td>

                                            <td class="text-left">
                                                <a class="text-dark" href="{{ $support->webinar->teacher->getProfileUrl() }}" target="_blank">{{ $support->webinar->teacher->full_name }}</a>
                                            </td>

                                            <td class="text-left">
                                                <a class="text-dark" href="{{ $support->user->getProfileUrl() }}" target="_blank">{{ $support->user->full_name }}</a>
                                            </td>

                                            <td>{{ dateTimeFormat($support->created_at,'j M Y | H:i') }}</td>

                                            <td class="font-12">{{ (!empty($support->updated_at)) ? dateTimeFormat($support->updated_at,'j M Y | H:i') : '-' }}</td>

                                            <td class="text-center">
                                                @if($support->status == 'close')
                                                    <span class="badge-status text-danger bg-danger-30">{{ trans('admin/main.close') }}</span>
                                                @else
                                                    <span class="badge-status text-success bg-success-30">{{ trans('admin/main.open') }}</span>
                                                @endif
                                            </td>

                                            <td class="text-center" width="50">
    <div class="btn-group dropdown table-actions position-relative">
        <button type="button" class="btn-transparent dropdown-toggle" data-toggle="dropdown">
            <x-iconsax-lin-more class="icons text-gray-500" width="20px" height="20px"/>
        </button>

        <div class="dropdown-menu dropdown-menu-right">
            @can('admin_supports_reply')
                <a href="{{ getAdminPanelUrl() }}/supports/{{ $support->id }}/conversation"
                   class="dropdown-item d-flex align-items-center mb-3 py-3 px-0 gap-4">
                    <x-iconsax-lin-messages-2 class="icons text-gray-500 mr-2" width="18px" height="18px"/>
                    <span class="text-gray-500 font-14">{{ trans('admin/main.conversation') }}</span>
                </a>
            @endcan

            @can('admin_supports_delete')
                @include('admin.includes.delete_button',[
                    'url' => getAdminPanelUrl().'/supports/'.$support->id.'/delete',
                    'btnClass' => 'dropdown-item text-danger mb-0 py-3 px-0 font-14',
                    'btnText' => trans('admin/main.delete'),
                    'btnIcon' => 'trash',
                    'iconType' => 'lin',
                    'iconClass' => 'text-danger mr-2'
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
                            {{ $supports->appends(request()->input())->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts_bottom')

@endpush
