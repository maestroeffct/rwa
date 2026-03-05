@extends('admin.layouts.app')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{ trans('admin/main.comments_reports') }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ getAdminPanelUrl() }}">{{trans('admin/main.dashboard')}}</a>
                </div>
                <div class="breadcrumb-item">{{ trans('admin/main.comments_reports') }}</div>
            </div>
        </div>

        <div class="section-body">

            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table custom-table font-14">
                                    <tr>
                                        <th>{{ trans('admin/main.user') }}</th>
                                        <th class="text-left">{{ trans('admin/main.post_or_webinar') }}</th>
                                        <th>{{ trans('site.message') }}</th>
                                        <th>{{ trans('public.date') }}</th>
                                        <th>{{ trans('admin/main.action') }}</th>
                                    </tr>
                                    @foreach($reports as $report)
                                        <tr>
                                            <td>{{ $report->user->id .' - '.$report->user->full_name }}</td>
                                            <td class="text-left" width="20%">
                                                <a href="{{ $report->$itemRelation->getUrl() }}" target="_blank">
                                                    {{ $report->$itemRelation->title }}
                                                </a>
                                            </td>
                                            <td width="25%">{{ nl2br($report->message) }}</td>
                                            <td>{{ dateTimeFormat($report->created_at, 'Y M j | H:i') }}</td>

                                            <td width="150px">
    <div class="btn-group dropdown table-actions position-relative">
        <button type="button" class="btn-transparent dropdown-toggle" data-toggle="dropdown">
            <x-iconsax-lin-more class="icons text-gray-500" width="20px" height="20px"/>
        </button>

        <div class="dropdown-menu dropdown-menu-right">
            <a href="{{ getAdminPanelUrl() }}/comments/{{ $page }}/{{ $report->comment_id }}/edit"
               class="dropdown-item d-flex align-items-center mb-3 py-3 px-0 gap-4">
                <x-iconsax-lin-eye class="icons text-gray-500 mr-2" width="18px" height="18px"/>
                <span class="text-gray-500 font-14">{{ trans('admin/main.show') }}</span>
            </a>

            @include('admin.includes.delete_button',[
                'url' => getAdminPanelUrl().'/comments/'.$page.'/reports/'.$report->id.'/delete',
                'btnClass' => 'dropdown-item text-danger mb-0 py-3 px-0 font-14',
                'btnText' => trans('admin/main.delete'),
                'btnIcon' => 'trash',
                'iconType' => 'lin',
                'iconClass' => 'text-danger mr-2'
            ])
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
@endsection

