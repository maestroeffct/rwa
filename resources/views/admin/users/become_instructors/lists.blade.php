@extends('admin.layouts.app')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{ $pageTitle }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ getAdminPanelUrl() }}">{{trans('admin/main.dashboard')}}</a>
                </div>
                <div class="breadcrumb-item">{{ $pageTitle}}</div>
            </div>
        </div>

        <div class="section-body">

            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="">
                                <table class="table custom-table font-14">
                                    <tr>
                                        <th>{{ trans('admin/main.user') }}</th>
                                        <th class="text-center">{{ trans('update.package') }}</th>
                                        <th class="text-center">{{ trans('admin/main.status') }}</th>
                                        <th class="text-center">{{ trans('admin/main.created_at') }}</th>
                                        <th>{{ trans('admin/main.actions') }}</th>
                                    </tr>

                                    @foreach($becomeInstructors as $become)
                                        <tr>

                                            <td>
                                                {{ !empty($become->user) ? $become->user->full_name : trans('update.deleted_user') }}
                                            </td>

                                            <td>
                                                @if(!empty($become->registrationPackage))
                                                    {{ $become->registrationPackage->title }}
                                                @else
                                                    ---
                                                @endif
                                            </td>

                                            <td class="text-center">
                                                @if($become->status == 'accept')
                                                    <span class="badge-status text-success bg-success-30">{{ trans('admin/main.accepted') }}</span>
                                                @elseif($become->status == 'reject')
                                                    <span class="badge-status text-danger bg-danger-30">{{ trans('public.rejected') }}</span>
                                                @elseif($become->status == 'pending')
                                                    <span class="badge-status text-warning bg-warning-30">{{ trans('admin/main.waiting') }}</span>
                                                @elseif($become->status == 'pending_pay_package')
                                                    <span class="badge-status text-dark bg-dark-30">{{ trans('update.pending_pay_package') }}</span>
                                                @endif
                                            </td>

                                            <td class="font-12 text-center">{{ dateTimeFormat($become->created_at, 'Y M j | H:i') }}</td>

                                            <td>
                                                <div class="btn-group dropdown table-actions position-relative">
                                                    <button type="button" class="btn-transparent dropdown-toggle" data-toggle="dropdown">
                                                        <x-iconsax-lin-more class="icons text-gray-500" width="20px" height="20px"/>
                                                    </button>

                                                    <div class="dropdown-menu dropdown-menu-right">
                                                        @can('admin_become_instructors_reject')
                                                            @if(!in_array($become->status, ['accept', 'pending_pay_package']))
                                                                <div class="dropdown-item d-flex align-items-center mb-3 py-3 px-0 gap-4">
                                                                    @include('admin.includes.delete_button',[
                                                                        'url' => getAdminPanelUrl().'/users/'.$become->user_id.'/acceptRequestToInstructor',
                                                                        'btnClass' => 'text-success font-14',
                                                                        'btnText' => trans('admin/main.accept_request'),
                                                                        'btnIcon' => 'tick-circle',
                                                                        'iconType' => 'lin',
                                                                        'iconClass' => 'text-success mr-2'
                                                                    ])
                                                                </div>

                                                                <div class="dropdown-item d-flex align-items-center mb-3 py-3 px-0 gap-4">
                                                                    @include('admin.includes.delete_button',[
                                                                        'url' => getAdminPanelUrl().'/users/become-instructors/'.$become->id.'/reject',
                                                                        'btnClass' => 'text-danger font-14',
                                                                        'btnText' => trans('admin/main.reject_request'),
                                                                        'btnIcon' => 'close-circle',
                                                                        'iconType' => 'lin',
                                                                        'iconClass' => 'text-danger mr-2'
                                                                    ])
                                                                </div>
                                                            @endif
                                                        @endcan

                                                        @if($become->status != "pending_pay_package")
                                                            @can('admin_users_edit')
                                                                <a href="{{ getAdminPanelUrl() }}/users/{{ $become->user_id }}/edit?type=check_instructor_request"
                                                                   class="dropdown-item d-flex align-items-center mb-3 py-3 px-0 gap-4">
                                                                    <x-iconsax-lin-profile-2user class="icons text-gray-500 mr-2" width="18px" height="18px"/>
                                                                    <span class="text-gray-500 font-14">{{ trans('admin/main.check') }}</span>
                                                                </a>
                                                            @endcan
                                                        @endif

                                                        @can('admin_become_instructors_delete')
                                                            @include('admin.includes.delete_button',[
                                                                'url' => getAdminPanelUrl().'/users/become-instructors/'.$become->id.'/delete',
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
                            {{ $becomeInstructors->appends(request()->input())->links() }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>


    <section class="card">
        <div class="card-body">
            <div class="section-title ml-0 mt-0 mb-3"><h5>{{trans('admin/main.hints')}}</h5></div>
            <div class="row">
                <div class="col-md-6">
                    <div class="media-body">
                        <div class="text-primary mt-0 mb-1 font-weight-bold">{{trans('admin/main.become_instructor_hint_title_1')}}</div>
                        <div class=" text-small font-600-bold">{{trans('admin/main.become_instructor_hint_description_1')}}</div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="media-body">
                        <div class="text-primary mt-0 mb-1 font-weight-bold">{{trans('admin/main.become_instructor_hint_title_2')}}</div>
                        <div class=" text-small font-600-bold">{{trans('admin/main.become_instructor_hint_description_2')}}</div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts_bottom')

@endpush
