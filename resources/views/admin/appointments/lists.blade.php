@extends('admin.layouts.app')

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
                            <span class="text-gray-500 mt-8">{{trans('admin/main.total_appointments')}}</span>
                            <div class="d-flex-center size-48 bg-primary-30 rounded-12">
                                <x-iconsax-bul-calendar class="icons text-primary" width="24px" height="24px"/>
                            </div>
                        </div>
                        <h5 class="font-24 mt-12 line-height-1 text-black">{{ $totalAppointments }}</h5>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card-statistic">
                    <div class="card-statistic__mask"></div>
                    <div class="card-statistic__wrap">
                        <div class="d-flex align-items-start justify-content-between">
                            <span class="text-gray-500 mt-8">{{trans('admin/main.open_appointments')}}</span>
                            <div class="d-flex-center size-48 bg-secondary-30 rounded-12">
                                <x-iconsax-bul-calendar-2 class="icons text-secondary" width="24px" height="24px"/>
                            </div>
                        </div>
                        <h5 class="font-24 mt-12 line-height-1 text-black">{{ $openAppointments }}</h5>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card-statistic">
                    <div class="card-statistic__mask"></div>
                    <div class="card-statistic__wrap">
                        <div class="d-flex align-items-start justify-content-between">
                            <span class="text-gray-500 mt-8">{{trans('admin/main.finished_appointments')}}</span>
                            <div class="d-flex-center size-48 bg-success-30 rounded-12">
                                <x-iconsax-bul-calendar-tick class="icons text-success" width="24px" height="24px"/>
                            </div>
                        </div>
                        <h5 class="font-24 mt-12 line-height-1 text-black">{{ $finishedAppointments }}</h5>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card-statistic">
                    <div class="card-statistic__mask"></div>
                    <div class="card-statistic__wrap">
                        <div class="d-flex align-items-start justify-content-between">
                            <span class="text-gray-500 mt-8">{{trans('admin/main.total_reservatores')}}</span>
                            <div class="d-flex-center size-48 bg-accent-30 rounded-12">
                                <x-iconsax-bul-profile-2user class="icons text-accent" width="24px" height="24px"/>
                            </div>
                        </div>
                        <h5 class="font-24 mt-12 line-height-1 text-black">{{ $totalConsultants }}</h5>
                    </div>
                </div>
            </div>
        </div>

        <div class="section-body">
            <section class="card mt-32">
                <div class="card-body pb-4">
                    <form method="get" class="mb-0">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="input-label">{{trans('admin/main.search')}}</label>
                                    <input type="text" class="form-control" name="search" value="{{ request()->get('search') }}">
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
                                        <option value="{{ \App\Models\ReserveMeeting::$open }}" @if(request()->get('status') == \App\Models\ReserveMeeting::$open) selected @endif>Open</option>
                                        <option value="{{ \App\Models\ReserveMeeting::$finished }}" @if(request()->get('status') == \App\Models\ReserveMeeting::$finished) selected @endif>Finished</option>
                                        <option value="{{ \App\Models\ReserveMeeting::$canceled }}" @if(request()->get('status') == \App\Models\ReserveMeeting::$canceled) selected @endif>Canceled</option>
                                        <option value="{{ \App\Models\ReserveMeeting::$pending }}" @if(request()->get('status') == \App\Models\ReserveMeeting::$pending) selected @endif>Pending</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="input-label">{{trans('admin/main.filters')}}</label>
                                    <select name="sort" data-plugin-selectTwo class="form-control populate">
                                        <option value="">{{trans('admin/main.filter_type')}}</option>
                                        <option value="has_discount" @if(request()->get('sort') == 'has_discount') selected @endif>{{trans('admin/main.discounted_appointments')}}</option>
                                        <option value="free" @if(request()->get('sort') == 'free') selected @endif>{{trans('admin/main.free_appointments')}}</option>
                                        <option value="amount_asc" @if(request()->get('sort') == 'amount_asc') selected @endif>{{trans('admin/main.cost_ascending')}}</option>
                                        <option value="amount_desc" @if(request()->get('sort') == 'amount_desc') selected @endif>{{trans('admin/main.cost_descending')}}</option>
                                        <option value="date_asc" @if(request()->get('sort') == 'date_asc') selected @endif>{{trans('admin/main.date_ascending')}}</option>
                                        <option value="date_desc" @if(request()->get('sort') == 'date_desc') selected @endif>{{trans('admin/main.date_descending')}}</option>
                                    </select>
                                </div>
                            </div>


                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="input-label">{{trans('admin/main.consultant')}}</label>

                                    <select name="consultant_ids[]" multiple="multiple" data-search-option="consultants" class="form-control search-user-select2"
                                            data-placeholder="Search Consultants">

                                        @if(!empty($consultants) and $consultants->count() > 0)
                                            @foreach($consultants as $teacher)
                                                <option value="{{ $teacher->id }}" selected>{{ $teacher->full_name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>


                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="input-label">{{trans('admin/main.reservatore')}}</label>

                                    <select name="user_ids[]" multiple="multiple" class="form-control search-user-select2"
                                            data-placeholder="Search Reservatores">

                                        @if(!empty($users) and $users->count() > 0)
                                            @foreach($users as $user)
                                                <option value="{{ $user->id }}" selected>{{ $user->full_name }}</option>
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

            <section class="card">
                <div class="card-body">
                    <div>
                        <table class="table custom-table text-center font-14">

                            <tr>
                                <th class="text-left">{{trans('admin/main.consultant')}}</th>
                                <th class="text-left">{{trans('admin/main.reservatore')}}</th>
                                <th class="text-center">{{ trans('update.meeting_type') }}</th>
                                <th class="text-center">{{trans('admin/main.cost')}}</th>
                                <th class="text-center">{{trans('admin/main.date')}}</th>
                                <th class="text-center">{{trans('admin/main.time')}}</th>
                                <th class="text-center">{{ trans('update.students_count') }}</th>
                                <th class="text-center">{{trans('admin/main.status')}}</th>
                                <th class="text-center">{{trans('admin/main.actions')}}</th>
                            </tr>

                            @foreach($appointments as $appointment)
                                <tr>
                                    <td class="text-left">
                                        <a href="{{ $appointment->meeting->creator->getProfileUrl() }}" class="text-dark" target="_blank">{{ $appointment->meeting->creator->full_name }}</a>
                                    </td>

                                    <td class="text-left">
                                        <a href="{{ $appointment->user->getProfileUrl() }}" class="text-dark" target="_blank">{{ $appointment->user->full_name }}</a>
                                    </td>

                                    <td class="text-center">
                                        <span class="">{{ trans('update.'.$appointment->meeting_type) }}</span>
                                    </td>

                                    <td>
                                        <div class="media-body">
                                            <div class=" mt-0 mb-1">{{ handlePrice($appointment->paid_amount) }}</div>
                                        </div>
                                    </td>

                                    <td class="text-center">{{ dateTimeFormat($appointment->start_at, 'j M Y') }}</td>

                                    <td class="text-center">
                                        <div class="d-inline-flex align-items-center">
                                            <span class="">{{ dateTimeFormat($appointment->start_at, 'H:i') }}</span>
                                            <span class="mx-1">-</span>
                                            <span class="">{{ dateTimeFormat($appointment->end_at, 'H:i') }}</span>
                                        </div>
                                    </td>

                                    <td class="align-middle font-weight-500">
                                        {{ $appointment->student_count ?? 1 }}
                                    </td>

                                    <td class="text-center">
                                        @switch($appointment->status)
                                            @case(\App\Models\ReserveMeeting::$pending)
                                                <span class="badge-status text-warning bg-warning-30">{{ trans('public.pending') }}</span>
                                                @break
                                            @case(\App\Models\ReserveMeeting::$open)
                                                <span class="badge-status text-primary bg-primary-30">{{ trans('public.open') }}</span>
                                                @break
                                            @case(\App\Models\ReserveMeeting::$finished)
                                                <span class="badge-status text-success bg-success-30">{{ trans('public.finished') }}</span>
                                                @break
                                            @case(\App\Models\ReserveMeeting::$canceled)
                                                <span class="badge-status text-danger bg-danger-30">{{ trans('public.canceled') }}</span>
                                                @break
                                        @endswitch
                                    </td>

                                    <td class="text-center" width="50">
                                    <div class="btn-group dropdown table-actions position-relative">
                                        <input type="hidden" class="js-meeting-password" value="{{ $appointment->password }}">
                                        <input type="hidden" class="js-meeting-link" value="{{ $appointment->link }}">

                                        <button type="button" class="btn-transparent dropdown-toggle" data-toggle="dropdown">
                                            <x-iconsax-lin-more class="icons text-gray-500" width="20px" height="20px"/>
                                        </button>

                                        <div class="dropdown-menu dropdown-menu-right">
                                            @can('admin_appointments_join')
                                                @if(!empty($appointment->link) and $appointment->status == \App\Models\ReserveMeeting::$open)
                                                    <button type="button"
                                                            data-reserve-id="{{ $appointment->id }}"
                                                            class="dropdown-item d-flex align-items-center mb-3 py-3 px-0 gap-4 js-show-join-modal">
                                                        <x-iconsax-lin-link class="icons text-gray-500 mr-2" width="18px" height="18px"/>
                                                        <span class="text-gray-500 font-14">{{ trans('admin/main.join_link') }}</span>
                                                    </button>
                                                @endif
                                            @endcan

                                            @can('admin_appointments_send_reminder')
                                                <button type="button"
                                                        data-reserve-id="{{ $appointment->id }}"
                                                        class="dropdown-item d-flex align-items-center mb-3 py-3 px-0 gap-4 js-send-reminder">
                                                    <x-iconsax-lin-notification class="icons text-gray-500 mr-2" width="18px" height="18px"/>
                                                    <span class="text-gray-500 font-14">{{ trans('admin/main.send_appointment_reminder') }}</span>
                                                </button>
                                            @endcan

                                            @can('admin_appointments_cancel')
                                                @if($appointment->status != \App\Models\ReserveMeeting::$canceled)
                                                    @include('admin.includes.delete_button',[
                                                        'url' => getAdminPanelUrl().'/appointments/'.$appointment->id.'/cancel',
                                                        'btnClass' => 'dropdown-item text-danger mb-0 py-3 px-0 font-14',
                                                        'btnText' => trans('admin/main.cancel'),
                                                        'btnIcon' => 'close-square',
                                                        'iconType' => 'lin',
                                                        'iconClass' => 'text-danger mr-2'
                                                    ])
                                                @endif
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
                    {{ $appointments->appends(request()->input())->links() }}
                </div>
            </section>
        </div>
    </section>


    <section class="card">
        <div class="card-body">
            <div class="section-title ml-0 mt-0 mb-3"><h5>{{trans('admin/main.hints')}}</h5></div>
            <div class="row">
                <div class="col-md-4">
                    <div class="media-body">
                        <div class="text-primary mt-0 mb-1 font-weight-bold">{{trans('admin/main.appointments_hint_title_1')}}</div>
                        <div class=" text-small font-600-bold">{{trans('admin/main.appointments_hint_description_1')}}</div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="media-body">
                        <div class="text-primary mt-0 mb-1 font-weight-bold">{{trans('admin/main.appointments_hint_title_2')}}</div>
                        <div class=" text-small font-600-bold">{{trans('admin/main.appointments_hint_description_2')}}</div>
                    </div>
                </div>


                <div class="col-md-4">
                    <div class="media-body">
                        <div class="text-primary mt-0 mb-1 font-weight-bold">{{trans('admin/main.appointments_hint_title_3')}}</div>
                        <div class="text-small font-600-bold">{{trans('admin/main.appointments_hint_description_3')}}</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="modal fade" id="joinModal" tabindex="-1" aria-labelledby="contactMessageLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="contactMessageLabel">{{trans('admin/main.join_appointment')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12 col-md-8">
                            <div class="form-group">
                                <label class="input-label">{{trans('admin/main.url')}}</label>
                                <input type="text" name="link" class="form-control" disabled/>
                            </div>
                        </div>

                        <div class="col-12 col-md-4">
                            <div class="form-group">
                                <label class="input-label">{{trans('admin/main.password')}}</label>
                                <input type="text" name="password" class="form-control" disabled/>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <a href="" target="_blank" class="js-join-btn btn btn-primary">{{trans('admin/main.join')}}</a>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ trans('admin/main.close') }}</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="sendReminderModal" tabindex="-1" aria-labelledby="contactMessageLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="contactMessageLabel">{{trans('admin/main.send_reminder')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="">
                        <strong>{{trans('admin/main.consultant')}}:</strong>
                        <span class="js-consultant"></span>
                    </div>

                    <div class="mt-2">
                        <strong>{{trans('admin/main.reservatore')}}:</strong>
                        <span class="js-reservatore"></span>
                    </div>

                    <div class="mt-2">
                        <strong>{{trans('admin/main.remind_title')}}:</strong>
                        <span class="js-title"></span>
                    </div>

                    <div class="mt-2">
                        <strong>{{trans('admin/main.remind_message')}}:</strong>
                        <span class="js-message"></span>
                    </div>
                </div>

                <div class="modal-footer">
                    <a href="" class="js-send-reminder-btn btn btn-primary">{{trans('admin/main.send')}}</a>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ trans('admin/main.close') }}</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts_bottom')
    <script src="/assets/admin/js/parts/appointments.min.js"></script>
@endpush
