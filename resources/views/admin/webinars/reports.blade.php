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

        <div class="section-body">

            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="card">

                        <div class="card-body">
                            <div>
                                <table class="table custom-table font-14">
                                    <tr>
                                        <th>{{ trans('admin/main.user') }}</th>
                                        <th class="text-left">{{ trans('admin/main.class') }}</th>
                                        <th class="text-center">{{ trans('product.reason') }}</th>
                                        <th class="text-center">{{ trans('public.date') }}</th>
                                        <th>{{ trans('admin/main.actions') }}</th>
                                    </tr>
                                    @foreach($reports as $report)
                                        <tr>
                                            @if (!empty($report->user->id))

                                            <td>{{ $report->user->id .' - '.$report->user->full_name }}</td>

                                            @else

                                            <td class="text-danger">Deleted User</td>


                                            @endif

                                            <td class="text-left" width="30%">
                                                <a href="{{ $report->webinar->getUrl() }}" target="_blank">
                                                    {{ $report->webinar->title }}
                                                </a>
                                            </td>

                                            <td class="text-center">
                                                <button type="button" class="js-show-description btn btn-sm btn-outline-primary">{{ trans('admin/main.show') }}</button>
                                                <input type="hidden" class="report-reason" value="{{ nl2br($report->reason) }}">
                                                <input type="hidden" class="report-description" value="{{ nl2br($report->message) }}">
                                            </td>

                                            <td class="text-center">{{ dateTimeFormat($report->created_at, 'j M Y | H:i') }}</td>

                                            <td width="150px" class="text-center">
    <div class="btn-group dropdown table-actions position-relative">
        <button type="button" class="btn-transparent dropdown-toggle" data-toggle="dropdown">
            <x-iconsax-lin-more class="icons text-gray-500" width="20px" height="20px"/>
        </button>

        <div class="dropdown-menu dropdown-menu-right">
            @can('admin_webinar_reports_delete')
                @include('admin.includes.delete_button',[
                    'url' => getAdminPanelUrl().'/reports/webinars/'.$report->id.'/delete',
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
                            {{ $reports->appends(request()->input())->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Modal -->
    <div class="modal fade" id="reportMessage" tabindex="-1" aria-labelledby="reportMessageLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="reportMessageLabel">{{ trans('panel.report') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="">
                        <h5 class="font-weight-bold js-reason">{{ trans('product.reason') }}: <span class="font-weight-light"></span></h5>

                        <div class="mt-2 js-description">
                            <h5 class="font-weight-bold js-reason">{{ trans('site.message') }} :</h5>
                            <p class="mt-2">

                            </p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ trans('admin/main.close') }}</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts_bottom')
    <script src="/assets/admin/js/parts/webinar_reports.min.js"></script>
@endpush
