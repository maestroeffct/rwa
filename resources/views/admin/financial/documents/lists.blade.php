@extends('admin.layouts.app')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{ trans('admin/main.balances') }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ getAdminPanelUrl() }}">{{trans('admin/main.dashboard')}}</a>
                </div>
                <div class="breadcrumb-item">{{ trans('admin/main.balances') }}</div>
            </div>
        </div>
        <div class="section-filters">
            <section class="card">
                <div class="card-body">
                    <div class="mt-3">
                        <form action="{{ getAdminPanelUrl() }}/financial/documents" method="get" class="row mb-0">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="input-label">{{trans('admin/main.start_date')}}</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                        <span class="input-group-text" id="dateInputGroupPrepend">
                                            <i class="fa fa-calendar-alt"></i>
                                        </span>
                                        </div>
                                        <input type="text" name="from" autocomplete="off" class="form-control datefilter"
                                               aria-describedby="dateInputGroupPrepend"
                                               value="{{ request()->get('from',null) }}"/>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="input-label">{{trans('admin/main.end_date')}}</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                        <span class="input-group-text" id="dateInputGroupPrepend">
                                            <i class="fa fa-calendar-alt"></i>
                                        </span>
                                        </div>
                                        <input type="text" name="to" autocomplete="off" class="form-control datefilter"
                                               aria-describedby="dateInputGroupPrepend"
                                               value="{{ request()->get('to',null) }}"/>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label
                                        class="input-label d-block">{{ trans('/admin/main.user') }}</label>
                                    <select name="user[]" multiple="" class="form-control search-user-select2"
                                            data-placeholder="{{ trans('/admin/main.search_user_or_instructor') }}">
                                        @if( request()->get('user',null))
                                            @foreach(request()->get('user') as $userId)
                                                <option value="{{ $userId }}"
                                                        selected="selected">{{ $users[$userId]->full_name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="input-label d-block">{{ trans('admin/main.class') }}</label>
                                    <select name="webinar" class="form-control search-webinar-select2"
                                            data-placeholder="{{ trans('admin/main.search_webinar') }}">
                                        @if(request()->get('webinar',null))
                                            <option value="{{ request()->get('webinar',null) }}"
                                                    selected="selected">{{ $webinar ? $webinar->title : ''}}</option>
                                        @endif
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="input-label d-block">{{ trans('admin/main.type') }}</label>
                                    <select name="type" class="form-control">
                                        <option value="all"
                                                @if(request()->get('type',null) == 'all') selected="selected" @endif>{{ trans('public.all') }}</option>
                                        <option value="addiction"
                                                @if(request()->get('type',null) == 'addiction') selected="selected" @endif>{{ trans('admin/main.addiction') }}</option>
                                        <option value="deduction"
                                                @if(request()->get('type',null) == 'deduction') selected="selected" @endif>{{ trans('admin/main.deduction') }}</option>

                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="input-label d-block">{{ trans('admin/main.type_account') }}</label>
                                    <select name="type_account" class="form-control">
                                        <option value="all"
                                                @if(request()->get('type_account',null) == 'all') selected="selected" @endif>{{ trans('public.all') }}</option>
                                        <option value="asset"
                                                @if(request()->get('type_account',null) == 'asset') selected="selected" @endif>{{ trans('admin/main.asset') }}</option>
                                        <option value="income"
                                                @if(request()->get('type_account',null) == 'income') selected="selected" @endif>{{ trans('admin/main.income') }}</option>

                                    </select>
                                </div>
                            </div>

                            <div class="col-12 col-md-3 d-flex align-items-center justify-content-end">
                                <button type="submit" class="btn btn-primary w-100">{{ trans('admin/main.show_results') }}</button>
                            </div>

                        </form>
                    </div>
                </div>
            </section>
        </div>

        <div class="section-body">


            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="card">

                        <div class="card-header justify-content-between">

                            <div>
                                <h5 class="font-14 mb-0">{{ trans('admin/main.balances') }}</h5>
                                <p class="font-12 mt-4 mb-0 text-gray-500">{{ trans('update.manage_all_transactions_in_a_single_place') }}</p>
                            </div>

                            <div class="d-flex align-items-center gap-12">

                                <a href="{{ getAdminPanelUrl() }}/financial/documents/excel?{{ http_build_query(request()->all()) }}" class="btn bg-white bg-hover-gray-100 border-gray-400 text-gray-500">
                                    <x-iconsax-lin-import-2 class="icons text-gray-500" width="18px" height="18px"/>
                                    <span class="ml-4 font-12">{{ trans('admin/main.export_xls') }}</span>
                                </a>

                                @can('admin_documents_create')
                                    <a href="{{ getAdminPanelUrl() }}/financial/documents/new" target="_blank" class="btn btn-primary">
                                        <x-iconsax-lin-add class="icons text-white" width="18px" height="18px"/>
                                        <span class="ml-4 font-12">{{ trans('admin/main.add_new') }}</span>
                                    </a>
                                @endcan

                            </div>

                        </div>


                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table custom-table font-14">
                                    <tr>
                                        <th class="text-left">{{ trans('admin/main.title') }}</th>
                                        <th class="text-left">{{ trans('admin/main.user') }}</th>
                                        <th class="text-center">{{ trans('admin/main.tax') }}</th>
                                        <th class="text-center">{{ trans('admin/main.system') }}</th>
                                        <th>{{ trans('admin/main.amount') }}</th>
                                        <th>{{ trans('admin/main.type') }}</th>
                                        <th>{{ trans('admin/main.creator') }}</th>
                                        <th>{{ trans('admin/main.type_account') }}</th>
                                        <th>{{ trans('public.date_time') }}</th>
                                        <th>{{ trans('public.controls') }}</th>
                                    </tr>


                                    @if($documents->count() > 0)
                                        @foreach($documents as $document)
                                            <tr>
                                                <td class="text-left">
                                                    <div class="text-left">
                                                        @if($document->is_cashback)
                                                            <span class="d-block">{{ trans('update.cashback') }}</span>
                                                        @endif

                                                        @if(!empty($document->webinar_id))
                                                            @if(!$document->is_cashback)
                                                                <span class="d-block ">{{ trans('admin/main.item_purchased') }}</span>
                                                            @endif

                                                            <a href="{{ !empty($document->webinar) ? $document->webinar->getUrl() : '' }}"
                                                               target="_blank" class="font-12 text-gray-500">#{{ $document->webinar_id }}-{{ !empty($document->webinar) ? $document->webinar->title : '' }}</a>
                                                        @elseif(!empty($document->bundle_id))
                                                            @if(!$document->is_cashback)
                                                                <span class="d-block ">{{ trans('update.bundle_purchased') }}</span>
                                                            @endif

                                                            <a href="{{ !empty($document->bundle) ? $document->bundle->getUrl() : '' }}"
                                                               target="_blank" class="font-12 text-gray-500">#{{ $document->bundle_id }}-{{ !empty($document->bundle) ? $document->bundle->title : '' }}</a>
                                                        @elseif(!empty($document->event_ticket_id) and !empty($document->eventTicket))
                                                            @php
                                                                $event = $document->eventTicket->event;
                                                            @endphp

                                                            @if(!$document->is_cashback)
                                                                <span class="d-block ">{{ trans('update.event_ticket') }}</span>
                                                            @endif

                                                            <a href="{{ $event->getUrl() }}"
                                                               target="_blank" class="font-12 text-gray-500">#{{ $document->event_ticket_id }}-{{ $document->eventTicket->title }}</a>
                                                        @elseif(!empty($document->meeting_package_id))
                                                            @if(!$document->is_cashback)
                                                                <span class="d-block ">{{ trans('update.meeting_package') }}</span>
                                                            @endif

                                                            @if(!empty($document->meetingPackage))
                                                                <div class="font-12 text-gray-500">#{{ $document->meeting_package_id }}-{{ $document->meetingPackage->title }}</div>
                                                            @endif
                                                        @elseif(!empty($document->product_id))
                                                            @if(!$document->is_cashback)
                                                                <span class="d-block ">{{ trans('update.product_purchased') }}</span>
                                                            @endif

                                                            <a href="{{ !empty($document->product) ? $document->product->getUrl() : '' }}"
                                                               target="_blank" class="font-12 text-gray-500">#{{ $document->product_id }}-{{ !empty($document->product) ? $document->product->title : '' }}</a>
                                                        @elseif(!empty($document->meeting_time_id))
                                                            @if(!$document->is_cashback)
                                                                <span class="d-block ">{{ trans('admin/main.item_purchased') }}</span>
                                                            @endif

                                                            <a href="" target="_blank" class="font-12 text-gray-500">#{{ $document->meeting_time_id }} {{ trans('admin/main.meeting') }}</a>
                                                        @elseif(!empty($document->subscribe_id))
                                                            <span class="{{ (!$document->is_cashback) ? 'd-block ' : 'font-12 text-gray-500' }}">{{ trans('admin/main.purchased_subscribe') }}</span>
                                                        @elseif(!empty($document->promotion_id))
                                                            <span class="{{ (!$document->is_cashback) ? 'd-block ' : 'font-12 text-gray-500' }}">{{ trans('admin/main.purchased_promotion') }}</span>
                                                        @elseif(!empty($document->registration_package_id))
                                                            <span class="{{ (!$document->is_cashback) ? 'd-block ' : 'font-12 text-gray-500' }}">{{ trans('update.purchased_registration_package') }}</span>
                                                        @elseif($document->store_type == \App\Models\Accounting::$storeManual)
                                                            <span class="{{ (!$document->is_cashback) ? 'd-block ' : 'font-12 text-gray-500' }}">{{ trans('admin/main.manual_document') }}</span>
                                                        @else
                                                            @if($document->is_cashback)
                                                                <span class="font-12 text-gray-500">{{ $document->description }}</span>
                                                            @else
                                                                <span class="d-block">{{ trans('admin/main.automatic_document') }}</span>
                                                            @endif
                                                        @endif
                                                    </div>
                                                </td>

                                                <td class="text-left">
                                                    @if(!empty($document->user))
                                                        <a href="{{ getAdminPanelUrl() }}/users/{{ $document->user_id }}/edit" target="_blank"
                                                           class="text-dark">{{ $document->user->full_name }}</a>
                                                    @endif
                                                </td>

                                                <td class="text-center">
                                                    @if($document->tax)
                                                        <span class="fas fa-check"></span>
                                                    @endif
                                                </td>

                                                <td class="text-center">
                                                    @if($document->system)
                                                        <span class="fas fa-check"></span>
                                                    @endif
                                                </td>

                                                <td>
                                                    <span>{{ handlePrice($document->amount) }}</span>
                                                </td>

                                                <td>
                                                    @switch($document->type)
                                                        @case(\App\Models\Accounting::$addiction)
                                                            <span class="badge-status text-success bg-success-30">{{ trans('admin/main.addiction') }}</span>
                                                            @break
                                                        @case(\App\Models\Accounting::$deduction)
                                                            <span class="badge-status text-danger bg-danger-30">{{ trans('admin/main.deduction') }}</span>
                                                            @break
                                                    @endswitch
                                                </td>

                                                <td>
                                                    @if($document->creator_id)
                                                        <span>{{ trans('admin/main.admin') }}</span>
                                                    @else
                                                        <span>{{ trans('admin/main.automatic') }}</span>
                                                    @endif
                                                </td>

                                                <td width="20%">
                                                    {{ trans('admin/main.'.$document->type_account) }}
                                                </td>

                                                <td>{{ dateTimeFormat($document->created_at, 'j F Y H:i') }}</td>

                                                <td>
                                                    <div class="btn-group dropdown table-actions position-relative">
                                                        <button type="button" class="btn-transparent dropdown-toggle" data-toggle="dropdown">
                                                            <x-iconsax-lin-more class="icons text-gray-500" width="20px" height="20px"/>
                                                        </button>

                                                        <div class="dropdown-menu dropdown-menu-right">
                                                            @can('admin_documents_print')
                                                                <a href="{{ getAdminPanelUrl() }}/financial/documents/{{ $document->id }}/print"
                                                                   class="dropdown-item d-flex align-items-center mb-0 py-3 px-0 gap-4">
                                                                    <x-iconsax-lin-printer class="icons text-gray-500 mr-2" width="18px" height="18px"/>
                                                                    <span class="text-gray-500 font-14">{{ trans('admin/main.print') }}</span>
                                                                </a>
                                                            @endcan
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif

                                </table>
                            </div>
                        </div>

                        <div class="card-footer text-center">
                            {{ $documents->appends(request()->input())->links() }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

