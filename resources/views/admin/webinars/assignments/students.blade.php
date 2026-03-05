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
            <div class="col-lg-4 col-md-6 col-sm-6 col-12">
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

            <div class="col-lg-4 col-md-6 col-sm-6 col-12">
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

            <div class="col-lg-4 col-md-6 col-sm-6 col-12">
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

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="input-label">{{trans('admin/main.student')}}</label>
                                    <select name="student_ids[]" multiple="multiple" class="form-control search-user-select2"
                                            data-placeholder="Search students">

                                        @if(!empty($students) and $students->count() > 0)
                                            @foreach($students as $student)
                                                <option value="{{ $student->id }}" selected>{{ $student->full_name }}</option>
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
                                        @foreach(\App\Models\WebinarAssignmentHistory::$assignmentHistoryStatus as $status)
                                            <option value="{{ $status }}" {{ (request()->get('status') == $status) ? 'selected' : '' }}>{{ trans('update.assignment_history_status_'.$status) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-2 d-flex align-items-center ">
                                <button type="submit" class="btn btn-primary btn-block btn-lg">{{trans('admin/main.show_results')}}</button>
                            </div>

                        </div>
                    </form>
                </div>
            </section>

            <section class="card">
                <div class="card-body">
                    <table class="table custom-table font-14" id="datatable-details">

                        <tr>
                            <th>{{ trans('quiz.student') }}</th>
                            <th class="text-center">{{ trans('panel.purchase_date') }}</th>
                            <th class="text-center">{{ trans('update.first_submission') }}</th>
                            <th class="text-center">{{ trans('update.last_submission') }}</th>
                            <th class="text-center">{{ trans('update.attempts') }}</th>
                            <th class="text-center">{{ trans('quiz.grade') }}</th>
                            <th class="text-center">{{ trans('public.status') }}</th>
                            <th class="text-right">{{ trans('admin/main.action') }}</th>
                        </tr>

                        @foreach($histories as $history)
                            <tr>
                                <td class="text-left">
                                    <div class="user-inline-avatar d-flex align-items-center">
                                        <div class="avatar bg-gray200">
                                            <img src="{{ $history->student->getAvatar() }}" class="img-cover" alt="">
                                        </div>
                                        <div class="ml-1">
                                            <span class="d-block font-weight-500">{{ $history->student->full_name }}</span>
                                            <span class="mt-1 font-12 text-gray-500 d-block">{{ $history->student->email }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="align-middle">
                                    <span class="font-weight-500">{{ !empty($history->purchase_date) ? dateTimeFormat($history->purchase_date, 'j M Y') : '-' }}</span>
                                </td>

                                <td class="align-middle">
                                    <span class="font-weight-500">{{ !empty($history->first_submission) ? dateTimeFormat($history->first_submission, 'j M Y | H:i') : '-' }}</span>
                                </td>

                                <td class="align-middle">
                                    <span class="font-weight-500">{{ !empty($history->last_submission) ? dateTimeFormat($history->last_submission, 'j M Y | H:i') : '-' }}</span>
                                </td>

                                <td class="align-middle">
                                    <span class="font-weight-500">{{ !empty($assignment->attempts) ? "{$history->usedAttemptsCount}/{$assignment->attempts}" : '-' }}</span>
                                </td>

                                <td class="align-middle">
                                    <span>{{ (!empty($history->grade)) ? $history->grade : '-' }}</span>
                                </td>

                                <td class="align-middle">
                                    @if(empty($history) or ($history->status == \App\Models\WebinarAssignmentHistory::$notSubmitted))
                                        <span class="text-danger font-weight-500">{{ trans('update.assignment_history_status_not_submitted') }}</span>
                                    @else
                                        @switch($history->status)
                                            @case(\App\Models\WebinarAssignmentHistory::$passed)
                                            <span class="badge-status text-success bg-success-30">{{ trans('quiz.passed') }}</span>
                                            @break
                                            @case(\App\Models\WebinarAssignmentHistory::$pending)
                                            <span class="badge-status text-warning bg-warning-30">{{ trans('public.pending') }}</span>
                                            @break
                                            @case(\App\Models\WebinarAssignmentHistory::$notPassed)
                                            <span class="badge-status text-danger bg-danger-30">{{ trans('quiz.failed') }}</span>
                                            @break
                                        @endswitch
                                    @endif
                                </td>

                                <td class="align-middle text-right">
    <div class="btn-group dropdown table-actions position-relative">
        <button type="button" class="btn-transparent dropdown-toggle" data-toggle="dropdown">
            <x-iconsax-lin-more class="icons text-gray-500" width="20px" height="20px"/>
        </button>

        <div class="dropdown-menu dropdown-menu-right">
            @can('admin_webinar_assignments_conversations')
                <a href="{{ getAdminPanelUrl() }}/assignments/{{ $assignment->id }}/history/{{ $history->id }}/conversations" class="dropdown-item d-flex align-items-center mb-0 py-3 px-0 gap-4">
                    <x-iconsax-lin-eye class="icons text-gray-500 mr-2" width="18px" height="18px"/>
                    <span class="text-gray-500 font-14">{{ trans('admin/main.conversations') }}</span>
                </a>
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
