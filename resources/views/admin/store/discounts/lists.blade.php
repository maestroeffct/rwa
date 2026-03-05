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

            <section class="card">
                <div class="card-body">
                    <form method="get" class="mb-0">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="input-label">{{ trans('admin/main.search') }}</label>
                                    <input type="text" class="form-control text-center" name="name" value="{{ request()->get('name') }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="input-label">{{ trans('admin/main.expiration_from') }}</label>
                                    <div class="input-group">
                                        <input type="date" id="fsdate" class="text-center form-control" name="from" value="{{ request()->get('from') }}" placeholder="Start Date">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="input-label">{{ trans('admin/main.expiration_to') }}</label>
                                    <div class="input-group">
                                        <input type="date" id="lsdate" class="text-center form-control" name="to" value="{{ request()->get('to') }}" placeholder="End Date">
                                    </div>
                                </div>
                            </div>


                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="input-label">{{ trans('admin/main.filters') }}</label>
                                    <select name="sort" data-plugin-selectTwo class="form-control populate">
                                        <option value="">{{ trans('admin/main.all_users_discount') }}</option>
                                        <option value="percent_asc" @if(request()->get('sort') == 'percent_asc') selected @endif>{{ trans('admin/main.percentage_ascending') }}</option>
                                        <option value="percent_desc" @if(request()->get('sort') == 'percent_desc') selected @endif>{{ trans('admin/main.percentage_descending') }}</option>
                                        <option value="created_at_asc" @if(request()->get('sort') == 'created_at_asc') selected @endif>{{ trans('admin/main.create_date_ascending') }}</option>
                                        <option value="created_at_desc" @if(request()->get('sort') == 'created_at_desc') selected @endif>{{ trans('admin/main.create_date_descending') }}</option>
                                        <option value="expire_at_asc" @if(request()->get('sort') == 'expire_at_asc') selected @endif>{{ trans('admin/main.expire_date_ascending') }}</option>
                                        <option value="expire_at_desc" @if(request()->get('sort') == 'expire_at_desc') selected @endif>{{ trans('admin/main.expire_date_descending') }}</option>
                                    </select>
                                </div>
                            </div>


                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="input-label">{{ trans('update.product') }}</label>
                                    <select name="product_ids[]" multiple="multiple" class="form-control search-product-select2"
                                            data-placeholder="{{ trans('update.search_product') }}">

                                        @if(!empty($products) and $products->count() > 0)
                                            @foreach($products as $product)
                                                <option value="{{ $product->id }}" selected>{{ $product->title }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>


                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="input-label">{{ trans('admin/main.status') }}</label>
                                    <select name="status" data-plugin-selectTwo class="form-control populate">
                                        <option value="">{{ trans('admin/main.all_status') }}</option>
                                        <option value="active" @if(request()->get('status') == 'active') selected @endif>{{ trans('admin/main.active') }}</option>
                                        <option value="inactive" @if(request()->get('status') == 'inactive') selected @endif>{{ trans('admin/main.inactive') }}</option>
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

                        <div class="card-header justify-content-between">
                            
                            <div>
                               <h5 class="font-14 mb-0">{{ $pageTitle }}</h5>
                               <p class="font-12 mt-4 mb-0 text-gray-500">{{ trans('update.manage_all_items_in_a_single_place') }}</p>
                           </div>
                           
                            <div class="d-flex align-items-center gap-12">

                            @can('admin_store_discounts_create')
                                   <a href="{{ getAdminPanelUrl("/store/discounts/create") }}" target="_blank" class="btn btn-primary">
                                       <x-iconsax-lin-add class="icons text-white" width="18px" height="18px"/>
                                       <span class="ml-4 font-12">{{ trans('admin/main.add_new') }}</span>
                                   </a>
                               @endcan

                            </div>
                       </div>
                        

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table custom-table font-14 text-center">
                                    <tr>
                                        <th>{{ trans('admin/main.title') }}</th>
                                        <th class="text-left">{{ trans('update.product') }}</th>
                                        <th>{{ trans('admin/main.percentage') }}</th>
                                        <th>{{ trans('admin/main.start_date') }}</th>
                                        <th>{{ trans('admin/main.end_date') }}</th>
                                        <th width="150">{{ trans('admin/main.usable_times') }}</th>
                                        <th>{{ trans('admin/main.status') }}</th>
                                        <th>{{ trans('admin/main.actions') }}</th>
                                    </tr>

                                    @foreach($discounts as $discount)
                                        <tr>
                                            <td>{{ $discount->name }}</td>
                                            <td class="text-left">
                                                <a class="text-dark" href="{{ $discount->product->getUrl() }}" target="_blank">{{ $discount->product->title }}</a>
                                            </td>

                                            <td>{{  $discount->percent ?  $discount->percent . '%' : '-' }}</td>

                                            <td class="font-12">{{  dateTimeFormat($discount->start_date, 'Y/m/d h:i:s') }}</td>

                                            <td class="font-12">{{  dateTimeFormat($discount->end_date, 'Y/m/d h:i:s') }}</td>

                                            <td>
                                                @if(!empty($discount->count))
                                                    <div class="media-body">
                                                        <div class=" mt-0 mb-1">{{ $discount->count }}</div>
                                                        <div class="text-gray-500 text-small">{{ trans('admin/main.remain') }} : {{ $discount->discountRemain() }}</div>
                                                    </div>
                                                @else
                                                    {{ trans('update.unlimited') }}
                                                @endif
                                            </td>

                                            <td>
                                                @if($discount->start_date > time())
                                                <span class="badge-status text-warning bg-warning-30">{{ trans('admin/main.pending') }}</span>
                                                @elseif($discount->end_date < time())
                                                <span class="badge-status text-danger bg-danger-30">{{ trans('panel.expired') }}</span>
                                                @else
                                                <span class="{{ ($discount->status == 'active') ? 'badge-status text-success bg-success-30' : 'badge-status text-danger bg-danger-30' }}">{{ trans('admin/main.'.$discount->status) }}</span>
                                                @endif
                                            </td>

                                            <td width="80px">
    <div class="btn-group dropdown table-actions position-relative">
        <button type="button" class="btn-transparent dropdown-toggle" data-toggle="dropdown">
            <x-iconsax-lin-more class="icons text-gray-500" width="20px" height="20px"/>
        </button>

        <div class="dropdown-menu dropdown-menu-right">
            @can('admin_store_discounts_edit')
                <a href="{{ getAdminPanelUrl() }}/store/discounts/{{ $discount->id }}/edit"
                   class="dropdown-item d-flex align-items-center mb-3 py-3 px-0 gap-4">
                    <x-iconsax-lin-edit-2 class="icons text-gray-500 mr-2" width="18px" height="18px"/>
                    <span class="text-gray-500 font-14">{{ trans('admin/main.edit') }}</span>
                </a>
            @endcan

            @can('admin_store_discounts_delete')
                @include('admin.includes.delete_button',[
                    'url' => getAdminPanelUrl().'/store/discounts/'.$discount->id.'/delete',
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
                            {{ $discounts->appends(request()->input())->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

