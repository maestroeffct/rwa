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

        <div class="section-body">

            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table custom-table font-14" id="datatable-basic">

                            <tr>
                                <th class="text-left">{{ trans('admin/main.user_name') }}</th>
                                <th class="text-left">{{ trans('admin/main.email') }}</th>
                                <th class="text-center">{{ trans('public.phone') }}</th>
                                <th class="text-left">{{ trans('site.subject') }}</th>
                                <th class="text-center">{{ trans('site.message') }}</th>
                                <th class="text-center">{{ trans('admin/main.status') }}</th>
                                <th class="text-center">{{ trans('admin/main.created_at') }}</th>
                                <th>{{ trans('public.controls') }}</th>
                            </tr>

                            @foreach($contacts as $contact)
                                <tr>
                                    <td>{{ $contact->name }}</td>
                                    <td class="text-left">{{ $contact->email }}</td>
                                    <td class="text-center">{{ $contact->phone }}</td>
                                    <td class="text-left">{{ $contact->subject }}</td>

                                    <td class="text-center">
                                        <button type="button" class="js-show-description btn btn-sm btn-outline-primary">{{ trans('admin/main.show') }}</button>
                                        <input type="hidden" value="{{ nl2br($contact->message) }}">
                                    </td>

                                    <td class="text-center">
                                        @if($contact->status =='replied')
                                            <span class="badge-status text-success bg-success-30">{{ trans('admin/main.replied') }}</span>
                                        @else
                                            <span class="badge-status text-danger bg-danger-30">{{ trans('panel.not_replied') }}</span>
                                        @endif
                                    </td>

                                    <td class="text-center">{{ dateTimeFormat($contact->created_at,'Y M j | H:i') }}</td>

                                    <td width="100">
    <div class="btn-group dropdown table-actions position-relative">
        <button type="button" class="btn-transparent dropdown-toggle" data-toggle="dropdown">
            <x-iconsax-lin-more class="icons text-gray-500" width="20px" height="20px"/>
        </button>

        <div class="dropdown-menu dropdown-menu-right">
            @can('admin_contacts_reply')
                <a href="{{ getAdminPanelUrl() }}/contacts/{{ $contact->id }}/reply"
                   class="dropdown-item d-flex align-items-center mb-3 py-3 px-0 gap-4">
                    <x-iconsax-lin-messages-2 class="icons text-gray-500 mr-2" width="18px" height="18px"/>
                    <span class="text-gray-500 font-14">{{ trans('admin/main.reply') }}</span>
                </a>
            @endcan

            @can('admin_contacts_delete')
                @include('admin.includes.delete_button',[
                    'url' => getAdminPanelUrl().'/contacts/'.$contact->id.'/delete',
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
                    {{ $contacts->appends(request()->input())->links() }}
                </div>
            </div>
        </div>
    </section>

    <!-- Modal -->
    <div class="modal fade" id="contactMessage" tabindex="-1" aria-labelledby="contactMessageLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="contactMessageLabel">{{ trans('admin/main.contacts_message') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ trans('admin/main.close') }}</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts_bottom')
    <script src="/assets/admin/js/parts/contacts.min.js"></script>
@endpush
