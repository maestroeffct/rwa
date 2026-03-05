@extends('admin.layouts.app')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{ $pageTitle }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ getAdminPanelUrl() }}">{{trans('admin/main.dashboard')}}</a></div>
                <div class="breadcrumb-item">{{ $pageTitle }}</div>
            </div>
        </div>


        <div class="section-body">
            {{-- Top Stats --}}
            @include('admin.meeting_packages.sold.top_stats')

            {{-- Filters --}}
            @include('admin.meeting_packages.sold.filters')


            <div class="card">
                <div class="card-header justify-content-between">
                    <div>
                        <h5 class="font-14 mb-0">{{ trans('update.sold_packages') }}</h5>
                        <p class="font-12 mt-4 mb-0 text-gray-500">{{ trans('update.view_and_manage_all_sold_meeting_packages') }}</p>
                    </div>


                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table custom-table font-14">
                            <tr>
                                <th class="text-left">{{ trans('quiz.student') }}</th>
                                <th class="text-left">{{ trans('update.instructor') }}</th>
                                <th class="text-center">{{ trans('update.meeting_package') }}</th>
                                <th class="text-center">{{ trans('public.paid_amount') }}</th>
                                <th class="text-center">{{ trans('update.total_sessions') }}</th>
                                <th class="text-center">{{ trans('update.ended') }}</th>
                                <th class="text-center">{{ trans('update.scheduled') }}</th>
                                <th class="text-center">{{ trans('update.not_scheduled') }}</th>
                                <th class="text-center">{{ trans('update.purchase_date') }}</th>
                                <th class="text-center">{{ trans('update.expiry_date') }}</th>
                                <th class="text-center">{{ trans('public.status') }}</th>
                                <th class="text-right">{{ trans('update.actions') }}</th>
                            </tr>

                            @foreach($meetingPackagesSold as $meetingPackageSold)
                                <tr>
                                    {{-- Student --}}
                                    <td class="text-left">
                                        <div class="user-inline-avatar d-flex align-items-center">
                                            <div class="avatar size-48 bg-gray-200 rounded-circle">
                                                <img src="{{ $meetingPackageSold->user->getAvatar(48) }}" class="js-avatar-img img-cover rounded-circle" alt="">
                                            </div>
                                            <div class=" ml-12">
                                                <span class="d-block ">{{ $meetingPackageSold->user->full_name }}</span>

                                                @if(!empty($meetingPackageSold->user->email))
                                                    <span class="mt-4 font-12 text-gray-500 d-block">{{ $meetingPackageSold->user->email }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </td>

                                    {{-- Instructor --}}
                                    <td class="text-left">
                                        <div class="user-inline-avatar d-flex align-items-center">
                                            <div class="avatar size-48 bg-gray-200 rounded-circle">
                                                <img src="{{ $meetingPackageSold->meetingPackage->creator->getAvatar(48) }}" class="js-avatar-img img-cover rounded-circle" alt="">
                                            </div>
                                            <div class=" ml-12">
                                                <span class="d-block ">{{ $meetingPackageSold->meetingPackage->creator->full_name }}</span>

                                                @if(!empty($meetingPackageSold->meetingPackage->creator->email))
                                                    <span class="mt-4 font-12 text-gray-500 d-block">{{ $meetingPackageSold->meetingPackage->creator->email }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </td>

                                    {{-- Meeting Package --}}
                                    <td class="text-center">
                                        {{ $meetingPackageSold->meetingPackage->title }}
                                    </td>

                                    {{-- Paid Amount --}}
                                    <td class="text-center">
                                        {{ ($meetingPackageSold->paid_amount > 0) ? handlePrice($meetingPackageSold->paid_amount) : trans('update.free') }}
                                    </td>

                                    {{-- Total Sessions --}}
                                    <td class="text-center">
                                        {{ $meetingPackageSold->sessions_count }}
                                    </td>

                                    {{-- Ended --}}
                                    <td class="text-center">
                                        {{ $meetingPackageSold->ended }}
                                    </td>

                                    {{-- Scheduled --}}
                                    <td class="text-center">
                                        {{ $meetingPackageSold->scheduled }}
                                    </td>

                                    {{-- Not Scheduled --}}
                                    <td class="text-center">
                                        {{ $meetingPackageSold->notScheduled }}
                                    </td>

                                    {{-- Purchase Date --}}
                                    <td class="text-center">
                                        <span class="">{{ dateTimeFormat($meetingPackageSold->paid_at, 'j M Y H:i') }}</span>
                                    </td>

                                    {{-- Expiry Date --}}
                                    <td class="text-center">
                                        <span class="">{{ dateTimeFormat($meetingPackageSold->expire_at, 'j M Y H:i') }}</span>
                                    </td>

                                    {{-- Status --}}
                                    <td class="text-center">
                                        @if($meetingPackageSold->status == "finished")
                                            <div class="d-inline-flex-center py-6 px-8 rounded-8 font-12 text-success bg-success-30">{{ trans('public.finished') }}</div>
                                        @else
                                            <div class="d-inline-flex-center py-6 px-8 rounded-8 font-12 text-warning bg-warning-30">{{ trans('public.open') }}</div>
                                        @endif
                                    </td>

                                    <td class="text-right" width="150px">
                                        <div class="btn-group dropdown table-actions position-relative">
                                            <button type="button" class="btn-transparent dropdown-toggle" data-toggle="dropdown">
                                                <x-iconsax-lin-more class="icons text-gray-500" width="20px" height="20px"/>
                                            </button>

                                            <div class="dropdown-menu dropdown-menu-right">

                                                <a href="{{ getAdminPanelUrl("/meeting-packages/sold/{$meetingPackageSold->id}/sessions") }}" target="_blank"
                                                   class="dropdown-item d-flex align-items-center mb-3 py-3 px-0 gap-4">
                                                    <x-iconsax-lin-eye class="icons text-gray-500 mr-2" width="18px" height="18px"/>
                                                    <span class="text-gray-500 font-14">{{ trans('update.view_sessions') }}</span>
                                                </a>

                                                <a href="{{ getAdminPanelUrl("/meeting-packages/sold/{$meetingPackageSold->id}/get-student-detail") }}"
                                                   data-title="{{ trans('update.student_information') }}"
                                                   class="js-meeting-sold-package-student-detail dropdown-item d-flex align-items-center mb-3 py-3 px-0 gap-4"
                                                >
                                                    <x-iconsax-lin-profile class="icons text-gray-500 mr-2" width="18px" height="18px"/>
                                                    <span class="text-gray-500 font-14">{{ trans('update.student_information') }}</span>
                                                </a>


                                                <a href="{{ getAdminPanelUrl("/meeting-packages/sold/{$meetingPackageSold->id}/invoice") }}" target="_blank"
                                                   class="dropdown-item d-flex align-items-center mb-3 py-3 px-0 gap-4">
                                                    <x-iconsax-lin-money-recive class="icons text-gray-500 mr-2" width="18px" height="18px"/>
                                                    <span class="text-gray-500 font-14">{{ trans('public.invoice') }}</span>
                                                </a>

                                            </div>
                                        </div>
                                    </td>

                                </tr>
                            @endforeach
                        </table>
                    </div>
                </div>

                <div class="card-footer text-center">
                    {{ $meetingPackagesSold->appends(request()->input())->links() }}
                </div>

            </div>

        </div>
    </section>
@endsection


@push('scripts_bottom')

    <script src="/assets/admin/js/parts/meeting_sold_packages.min.js"></script>
@endpush
