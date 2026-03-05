@extends('admin.layouts.app')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{ $pageTitle }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ getAdminPanelUrl() }}">{{ trans('admin/main.dashboard') }}</a>
                </div>
                <div class="breadcrumb-item active">
                    {{ $pageTitle }}
                </div>
            </div>
        </div>

        <div class="section-body">

            <div class="row">
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card-statistic">
                        <div class="card-statistic__mask"></div>
                        <div class="card-statistic__wrap">
                            <div class="d-flex align-items-start justify-content-between">
                                <span class="text-gray-500 mt-8">{{trans('update.total_gifts')}}</span>
                                <div class="d-flex-center size-48 bg-primary-30 rounded-12">
                                    <x-iconsax-bul-gift class="icons text-primary" width="24px" height="24px"/>
                                </div>
                            </div>
                            <h5 class="font-24 mt-12 line-height-1 text-black">{{ $totalGifts }}</h5>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card-statistic">
                        <div class="card-statistic__mask"></div>
                        <div class="card-statistic__wrap">
                            <div class="d-flex align-items-start justify-content-between">
                                <span class="text-gray-500 mt-8">{{trans('update.total_gift_amount')}}</span>
                                <div class="d-flex-center size-48 bg-success-30 rounded-12">
                                    <x-iconsax-bul-dollar-square class="icons text-success" width="24px" height="24px"/>
                                </div>
                            </div>
                            <h5 class="font-24 mt-12 line-height-1 text-black">{{ handlePrice($totalGiftAmount) }}</h5>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card-statistic">
                        <div class="card-statistic__mask"></div>
                        <div class="card-statistic__wrap">
                            <div class="d-flex align-items-start justify-content-between">
                                <span class="text-gray-500 mt-8">{{trans('update.total_senders')}}</span>
                                <div class="d-flex-center size-48 bg-accent-30 rounded-12">
                                    <x-iconsax-bul-send-2 class="icons text-accent" width="24px" height="24px"/>
                                </div>
                            </div>
                            <h5 class="font-24 mt-12 line-height-1 text-black">{{ $totalSenders }}</h5>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card-statistic">
                        <div class="card-statistic__mask"></div>
                        <div class="card-statistic__wrap">
                            <div class="d-flex align-items-start justify-content-between">
                                <span class="text-gray-500 mt-8">{{trans('update.total_receipts')}}</span>
                                <div class="d-flex-center size-48 bg-secondary-30 rounded-12">
                                    <x-iconsax-bul-import class="icons text-secondary" width="24px" height="24px"/>
                                </div>
                            </div>
                            <h5 class="font-24 mt-12 line-height-1 text-black">{{ $totalReceipts }}</h5>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Filters --}}
            <section class="card mt-32">
                <div class="card-body pb-4">
                    <form method="get" class="mb-0">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="input-label">{{trans('admin/main.search')}}</label>
                                    <input name="search" type="text" class="form-control" value="{{ request()->get('search') }}">
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


                            @php
                                $filters = ['amount_asc', 'amount_desc', 'submit_date_asc', 'submit_date_desc', 'receive_date_asc', 'receive_date_desc'];
                            @endphp
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="input-label">{{trans('admin/main.filters')}}</label>
                                    <select name="sort" data-plugin-selectTwo class="form-control populate">
                                        <option value="">{{trans('admin/main.all')}}</option>

                                        @foreach($filters as $filter)
                                            <option value="{{ $filter }}" @if(request()->get('sort') == $filter) selected @endif>{{trans('update.'.$filter)}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="input-label">{{trans('admin/main.user')}}</label>
                                    <select name="user_ids[]" multiple="multiple" class="form-control search-user-select2"
                                            data-placeholder="Search users">

                                        @if(!empty($selectedUsers) and $selectedUsers->count() > 0)
                                            @foreach($selectedUsers as $user)
                                                <option value="{{ $user->id }}" selected>{{ $user->full_name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="input-label">{{trans('update.receipt_status')}}</label>
                                    <select name="receipt_status" class="form-control">
                                        <option value="">{{ trans('admin/main.all') }}</option>
                                        <option value="registered" {{ request()->get('receipt_status') == "registered" ? 'selected' : '' }}>{{ trans('update.registered') }}</option>
                                        <option value="unregistered" {{ request()->get('receipt_status') == "unregistered" ? 'selected' : '' }}>{{ trans('update.unregistered') }}</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="input-label">{{trans('update.gift_status')}}</label>
                                    <select name="gift_status" class="form-control">
                                        <option value="">{{ trans('admin/main.all') }}</option>
                                        <option value="pending" {{ request()->get('gift_status') == "pending" ? 'selected' : '' }}>{{ trans('admin/main.pending') }}</option>
                                        <option value="sent" {{ request()->get('gift_status') == "sent" ? 'selected' : '' }}>{{ trans('update.sent') }}</option>
                                        <option value="canceled" {{ request()->get('gift_status') == "canceled" ? 'selected' : '' }}>{{ trans('public.canceled') }}</option>
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

            {{-- Lists --}}
            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="card">


                        <div class="card-header justify-content-between">
                            <div>
                               <h5 class="font-14 mb-0">{{ $pageTitle }}</h5>
                               <p class="font-12 mt-4 mb-0 text-gray-500">{{ trans('update.manage_all_items_in_a_single_place') }}</p>
                           </div>

                            <div class="d-flex align-items-center gap-12">

                            @can('admin_gift_export')
                                <div class="d-flex align-items-center gap-12">
                                    <a href="{{ getAdminPanelUrl() }}/gifts/excel?{{ http_build_query(request()->all()) }}" class="btn bg-white bg-hover-gray-100 border-gray-400 text-gray-500">
                                        <x-iconsax-lin-import-2 class="icons text-gray-500" width="18px" height="18px"/>
                                        <span class="ml-4 font-12">{{ trans('admin/main.export_xls') }}</span>
                                    </a>
                                </div>
                            @endcan
                            </div>
                            </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table custom-table font-14 ">
                                    <tr>
                                        <th class="text-left">{{ trans('admin/main.title') }}</th>
                                        <th class="text-left">{{ trans('admin/main.sender') }}</th>
                                        <th>{{ trans('update.receipt') }}</th>
                                        <th>{{ trans('update.receipt_status') }}</th>
                                        <th>{{ trans('update.gift_message') }}</th>
                                        <th>{{ trans('admin/main.amount') }}</th>
                                        <th>{{ trans('update.submit_date') }}</th>
                                        <th>{{ trans('update.receive_date') }}</th>
                                        <th>{{ trans('update.gift_status') }}</th>
                                        <th width="120">{{ trans('admin/main.actions') }}</th>
                                    </tr>

                                    @foreach($gifts as $gift)
                                        <tr class="text-center">

                                            <td class="text-left">
                                                {{ $gift->getItemTitle() }}
                                            </td>

                                            <td class="text-left">
                                                <div class="mt-0 mb-1">{{ $gift->user->full_name }}</div>

                                                @if($gift->user->mobile)
                                                    <div class="text-gray-500 text-small">{{ $gift->user->mobile }}</div>
                                                @endif

                                                @if($gift->user->email)
                                                    <div class="text-gray-500 text-small">{{ $gift->user->email }}</div>
                                                @endif
                                            </td>

                                            <td>
                                                @if(!empty($gift->receipt))
                                                    <div class="mt-0 mb-1">{{ $gift->receipt->full_name }}</div>
                                                @else
                                                    <div class="mt-0 mb-1">{{ $gift->name }}</div>
                                                @endif
                                                <div class="text-gray-500 text-small ">{{ $gift->email }}</div>
                                            </td>

                                            <td class="">
                                                <span class="">{{ $gift->receipt_status ? trans('update.registered') : trans('update.unregistered') }}</span>
                                            </td>

                                            <td>
                                                <div class="d-flex">
                                                    <button type="button" class="js-show-gift-message btn btn-outline-primary">{{ trans('update.message') }}</button>
                                                    <input type="hidden" value="{{ nl2br($gift->description) }}">
                                                </div>
                                            </td>

                                            <td class="">
                                                @if(!empty($gift->sale) and $gift->sale->total_amount > 0)
                                                    {{ handlePrice($gift->sale->total_amount) }}
                                                @else
                                                    {{ trans('admin/main.free') }}
                                                @endif
                                            </td>

                                            <td class="">
                                                {{ dateTimeFormat($gift->created_at, 'j M Y H:i') }}
                                            </td>

                                            <td class="">
                                                @if(!empty($gift->date))
                                                    {{ dateTimeFormat($gift->date, 'j M Y H:i') }}
                                                @else
                                                    {{ trans('update.instantly') }}
                                                @endif
                                            </td>

                                            <td class="">
                                                @if(!empty($gift->date) and $gift->date > time())
                                                    <span class="badge-status text-warning bg-warning-30">{{ trans('admin/main.pending') }}</span>
                                                @elseif($gift->status == 'cancel')
                                                    <span class="badge-status text-danger bg-danger-30">{{ trans('admin/main.cancel') }}</span>
                                                @else
                                                    <span class="badge-status text-success bg-success-30">{{ trans('update.sent') }}</span>
                                                @endif
                                            </td>

                                            <td class="text-center mb-2" width="120">
    @if($gift->status != 'cancel')
    <div class="btn-group dropdown table-actions position-relative">
        <button type="button" class="btn-transparent dropdown-toggle" data-toggle="dropdown">
            <x-iconsax-lin-more class="icons text-gray-500" width="20px" height="20px"/>
        </button>

        <div class="dropdown-menu dropdown-menu-right">
            @if(empty($gift->date) or $gift->date < time())
                @can('admin_gift_send_reminder')
                    @include('admin.includes.delete_button',[
                        'url' => getAdminPanelUrl("/gifts/{$gift->id}/send_reminder"),
                        'btnClass' => 'dropdown-item text-gray-500 mb-3 py-3 px-0 font-14',
                        'btnText' => trans('admin/main.send_reminder'),
                        'btnIcon' => 'notification',
                        'iconType' => 'lin',
                        'iconClass' => 'text-gray-500 mr-2',
                    ])
                @endcan
            @endif

            @can('admin_gift_cancel')
                @include('admin.includes.delete_button',[
                    'url' => getAdminPanelUrl("/gifts/{$gift->id}/cancel"),
                    'btnClass' => 'dropdown-item text-danger mb-0 py-3 px-0 font-14',
                    'btnText' => trans('admin/main.cancel'),
                    'btnIcon' => 'close-square',
                    'iconType' => 'lin',
                    'iconClass' => 'text-danger mr-2',
                ])
            @endcan
        </div>
    </div>
    @endif
</td>
                                        </tr>
                                    @endforeach
                                </table>
                            </div>
                        </div>

                        <div class="card-footer text-center">
                            {{ $gifts->appends(request()->input())->links() }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Modal -->
    <div class="modal fade" id="giftMessage" tabindex="-1" aria-labelledby="giftMessageLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="giftMessageLabel">{{ trans('admin/main.message') }}</h5>
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
    <script src="/assets/admin/js/parts/gifts.min.js"></script>
@endpush
