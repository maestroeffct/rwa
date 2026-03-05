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

            <div class="row">
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card-statistic">
                        <div class="card-statistic__mask"></div>
                        <div class="card-statistic__wrap">
                            <div class="d-flex align-items-start justify-content-between">
                                <span class="text-gray-500 mt-8">{{trans('update.total_physical_products')}}</span>
                                <div class="d-flex-center size-48 bg-primary-30 rounded-12">
                                    <x-iconsax-bul-box class="icons text-primary" width="24px" height="24px"/>
                                </div>
                            </div>
                            <h5 class="font-24 mt-12 line-height-1 text-black">{{ $totalPhysicalProducts }}</h5>
                            <span class="text-gray-500 font-14">{{ trans('admin/main.sales') }}: {{ $totalPhysicalSales }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card-statistic">
                        <div class="card-statistic__mask"></div>
                        <div class="card-statistic__wrap">
                            <div class="d-flex align-items-start justify-content-between">
                                <span class="text-gray-500 mt-8">{{trans('update.total_virtual_products')}}</span>
                                <div class="d-flex-center size-48 bg-success-30 rounded-12">
                                    <x-iconsax-bul-document-download class="icons text-success" width="24px" height="24px"/>
                                </div>
                            </div>
                            <h5 class="font-24 mt-12 line-height-1 text-black">{{ $totalVirtualProducts }}</h5>
                            <span class="text-gray-500 font-14">{{ trans('admin/main.sales') }}: {{ $totalVirtualSales }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card-statistic">
                        <div class="card-statistic__mask"></div>
                        <div class="card-statistic__wrap">
                            <div class="d-flex align-items-start justify-content-between">
                                <span class="text-gray-500 mt-8">{{trans('update.total_sellers')}}</span>
                                <div class="d-flex-center size-48 bg-accent-30 rounded-12">
                                    <x-iconsax-bul-shop class="icons text-accent" width="24px" height="24px"/>
                                </div>
                            </div>
                            <h5 class="font-24 mt-12 line-height-1 text-black">{{ $totalSellers }}</h5>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card-statistic">
                        <div class="card-statistic__mask"></div>
                        <div class="card-statistic__wrap">
                            <div class="d-flex align-items-start justify-content-between">
                                <span class="text-gray-500 mt-8">{{trans('update.total_buyers')}}</span>
                                <div class="d-flex-center size-48 bg-secondary-30 rounded-12">
                                    <x-iconsax-bul-profile-2user class="icons text-secondary" width="24px" height="24px"/>
                                </div>
                            </div>
                            <h5 class="font-24 mt-12 line-height-1 text-black">{{ $totalBuyers }}</h5>
                        </div>
                    </div>
                </div>
            </div>

            <section class="card mt-32">
                <div class="card-body pb-4">
                    <form method="get" class="mb-0">
                        <input type="hidden" name="type" value="{{ request()->get('type') }}">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="input-label">{{trans('admin/main.search')}}</label>
                                    <input name="title" type="text" class="form-control" value="{{ request()->get('title') }}">
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
                                    <label class="input-label">{{trans('admin/main.filters')}}</label>
                                    <select name="sort" data-plugin-selectTwo class="form-control populate">
                                        <option value="">{{trans('admin/main.filter_type')}}</option>
                                        <option value="has_discount" @if(request()->get('sort') == 'has_discount') selected @endif>{{trans('admin/main.discounted_classes')}}</option>
                                        <option value="sales_asc" @if(request()->get('sort') == 'sales_asc') selected @endif>{{trans('admin/main.sales_ascending')}}</option>
                                        <option value="sales_desc" @if(request()->get('sort') == 'sales_desc') selected @endif>{{trans('admin/main.sales_descending')}}</option>
                                        <option value="price_asc" @if(request()->get('sort') == 'price_asc') selected @endif>{{trans('admin/main.Price_ascending')}}</option>
                                        <option value="price_desc" @if(request()->get('sort') == 'price_desc') selected @endif>{{trans('admin/main.Price_descending')}}</option>
                                        <option value="income_asc" @if(request()->get('sort') == 'income_asc') selected @endif>{{trans('admin/main.Income_ascending')}}</option>
                                        <option value="income_desc" @if(request()->get('sort') == 'income_desc') selected @endif>{{trans('admin/main.Income_descending')}}</option>
                                        <option value="created_at_asc" @if(request()->get('sort') == 'created_at_asc') selected @endif>{{trans('admin/main.create_date_ascending')}}</option>
                                        <option value="created_at_desc" @if(request()->get('sort') == 'created_at_desc') selected @endif>{{trans('admin/main.create_date_descending')}}</option>
                                        <option value="updated_at_asc" @if(request()->get('sort') == 'updated_at_asc') selected @endif>{{trans('admin/main.update_date_ascending')}}</option>
                                        <option value="updated_at_desc" @if(request()->get('sort') == 'updated_at_desc') selected @endif>{{trans('admin/main.update_date_descending')}}</option>
                                        <option value="inventory_asc" @if(request()->get('sort') == 'inventory_asc') selected @endif>{{trans('update.inventory_asc')}}</option>
                                        <option value="inventory_desc" @if(request()->get('sort') == 'inventory_desc') selected @endif>{{trans('update.inventory_desc')}}</option>
                                        <option value="no_inventory" @if(request()->get('sort') == 'no_inventory') selected @endif>{{trans('update.no_inventory')}}</option>
                                    </select>
                                </div>
                            </div>


                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="input-label">{{trans('update.seller')}}</label>
                                    <select name="creator_ids[]" multiple="multiple" class="form-control search-user-select2"
                                            data-placeholder="{{trans('update.search_seller')}}">

                                        @if(!empty($teachers) and $teachers->count() > 0)
                                            @foreach($teachers as $teacher)
                                                <option value="{{ $teacher->id }}" selected>{{ $teacher->full_name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>


                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="input-label">{{trans('admin/main.category')}}</label>
                                    <select name="category_id" data-plugin-selectTwo class="form-control populate">
                                        <option value="">{{trans('admin/main.all_categories')}}</option>

                                        @foreach($categories as $category)
                                            @if(!empty($category->subCategories) and count($category->subCategories))
                                                <optgroup label="{{  $category->title }}">
                                                    @foreach($category->subCategories as $subCategory)
                                                        <option value="{{ $subCategory->id }}" @if(request()->get('category_id') == $subCategory->id) selected="selected" @endif>{{ $subCategory->title }}</option>
                                                    @endforeach
                                                </optgroup>
                                            @else
                                                <option value="{{ $category->id }}" @if(request()->get('category_id') == $category->id) selected="selected" @endif>{{ $category->title }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>


                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="input-label">{{trans('admin/main.status')}}</label>
                                    <select name="status" data-plugin-selectTwo class="form-control populate">
                                        <option value="">{{trans('admin/main.all_status')}}</option>
                                        <option value="pending" @if(request()->get('status') == 'pending') selected @endif>{{trans('admin/main.pending')}}</option>
                                        <option value="inactive" @if(request()->get('status') == 'inactive') selected @endif>{{trans('admin/main.rejected')}}</option>
                                        <option value="draft" @if(request()->get('status') == 'draft') selected @endif>{{trans('admin/main.draft')}}</option>
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
                               <p class="font-12 mt-4 mb-0 text-gray-500">{{ trans('update.manage_all_products_in_a_single_place') }}</p>
                           </div>

                           
                                <div class="d-flex align-items-center gap-12">
                                @can('admin_store_export_products')
                                    <a href="{{ getAdminPanelUrl() }}/store/products/excel?{{ http_build_query(request()->all()) }}" class="btn bg-white bg-hover-gray-100 border-gray-400 text-gray-500">
                                        <x-iconsax-lin-import-2 class="icons text-gray-500" width="18px" height="18px"/>
                                        <span class="ml-4 font-12">{{ trans('admin/main.export_xls') }}</span>
                                    </a>
                                @endcan 

                                @if(!empty($inHouseProducts))
                                @can('admin_store_new_product')
                                <a href="{{ getAdminPanelUrl() }}/store/products/create?in_house_product=true" target="_blank" class="btn btn-primary">
                                       <x-iconsax-lin-add class="icons text-white" width="18px" height="18px"/>
                                       <span class="ml-4 font-12">{{ trans('update.create_new_product') }}</span>
                                   </a>
                                @endcan
                                @endif

                                </div>
                           

                          
                        </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table custom-table font-14 ">
                                    <tr>
                                        <th>{{trans('admin/main.id')}}</th>
                                        <th class="text-left">{{trans('admin/main.title')}}</th>
                                        <th class="text-left">{{trans('admin/main.creator')}}</th>
                                        <th>{{trans('admin/main.type')}}</th>
                                        <th>{{trans('update.inventory')}}</th>
                                        <th>{{trans('admin/main.price')}}</th>
                                        <th>{{trans('update.delivery_fee')}}</th>
                                        <th>{{trans('admin/main.sales')}}</th>
                                        <th>{{trans('admin/main.income')}}</th>
                                        <th>{{trans('admin/main.updated_at')}}</th>
                                        <th>{{trans('admin/main.created_at')}}</th>
                                        <th>{{trans('admin/main.status')}}</th>
                                        <th>{{trans('admin/main.actions')}}</th>
                                    </tr>

                                    @foreach($products as $product)
                                        <tr class="text-center">
                                            <td>{{ $product->id }}</td>
                                            <td width="18%" class="text-left">
                                                <a class="text-dark mt-0 mb-1 font-weight-bold" href="{{ $product->getUrl() }}">{{ $product->title }}</a>
                                                @if(!empty($product->category->title))
                                                    <div class="text-small text-gray-500">{{ $product->category->title }}</div>
                                                @else
                                                    <div class="text-small text-warning">{{trans('admin/main.no_category')}}</div>
                                                @endif
                                            </td>

                                            <td class="text-left">{{ $product->creator->full_name }}</td>

                                            <td>
                                                {{ trans('update.'.$product->type) }}
                                            </td>

                                            <td>
                                                <span class="text-dark mt-0 mb-1 font-weight-bold">
                                                    @php
                                                        $getAvailability = $product->getAvailability();
                                                    @endphp

                                                    {{ ($getAvailability == 99999) ? trans('update.unlimited') : $getAvailability }}
                                                </span>
                                            </td>

                                            <td>
                                                {{ !empty($product->price) ? handlePrice($product->price, true, true, false, null, true, 'store') : '-' }}
                                            </td>

                                            <td>
                                                {{ $product->delivery_fee ? handlePrice($product->delivery_fee) : '-' }}
                                            </td>

                                            <td>
                                                <span class="mt-0 mb-1 font-weight-bold">
                                                    {{ $product->salesCount() }}
                                                </span>
                                            </td>

                                            <td>{{ handlePrice($product->sales()->sum('total_amount')) }}</td>

                                            <td>{{ dateTimeFormat($product->updated_at, 'Y M j | H:i') }}</td>

                                            <td>{{ dateTimeFormat($product->created_at, 'Y M j | H:i') }}</td>

                                            <td>
                                                @switch($product->status)
                                                    @case(\App\Models\Product::$active)
                                                        <span class="badge-status text-success bg-success-30">{{ trans('admin/main.published') }}</span>
                                                        @break
                                                    @case(\App\Models\Product::$draft)
                                                        <span class="badge-status text-dark bg-dark-30">{{ trans('admin/main.is_draft') }}</span>
                                                        @break
                                                    @case(\App\Models\Product::$pending)
                                                        <span class="badge-status text-warning bg-warning-30">{{ trans('admin/main.waiting') }}</span>
                                                        @break
                                                    @case(\App\Models\Product::$inactive)
                                                        <span class="badge-status text-danger bg-danger-30">{{ trans('public.rejected') }}</span>
                                                        @break
                                                @endswitch
                                            </td>

                                            <td>
                                            <div class="btn-group dropdown table-actions position-relative">
                                                    <button type="button" class="btn-transparent dropdown-toggle" data-toggle="dropdown">
                                                        <x-iconsax-lin-more class="icons text-gray-500" width="20px" height="20px"/>
                                                    </button>

                                                    <div class="dropdown-menu dropdown-menu-right">

                                                        
                                                         @if(in_array($product->status, [\App\Models\Product::$pending, \App\Models\Product::$inactive]))
                                                       
                                                            @include('admin.includes.delete_button',[
                                                            'url' => getAdminPanelUrl("/store/products/{$product->id}/approve"),
                                                           'btnClass' => 'dropdown-item text-success mb-3 py-3 px-0 font-14',
                                                           'btnText' => trans("admin/main.approve"),
                                                           'btnIcon' => 'tick-square',
                                                           'iconType' => 'lin',
                                                           'iconClass' => 'text-success mr-2',
                                                        ])
                                                        @endif
                                                        @if($product->status == \App\Models\Product::$pending)
                                                        @include('admin.includes.delete_button',[
                                                            'url' => getAdminPanelUrl("/store/products/{$product->id}/reject"),
                                                           'btnClass' => 'dropdown-item  text-danger mb-3 py-3 px-0 font-14',
                                                           'btnText' => trans("admin/main.reject"),
                                                           'btnIcon' => 'close-square',
                                                           'iconType' => 'lin',
                                                           'iconClass' => 'text-danger mr-2',
                                                        ])
                                                        @endif
                                                        @if($product->status == \App\Models\Product::$active)
                                                    
                                                            @include('admin.includes.delete_button',[
                                                            'url' => getAdminPanelUrl("/store/products/{$product->id}/unpublish"),
                                                           'btnClass' => 'dropdown-item text-danger mb-3 py-3 px-0 font-14',
                                                           'btnText' => trans("admin/main.unpublish"),
                                                           'btnIcon' => 'gallery-slash',
                                                           'iconType' => 'lin',
                                                           'iconClass' => 'text-danger mr-2',
                                                        ])

                                                        @endif
                                                


                                                        @can('admin_store_edit_product')
                                                            <a href="{{ getAdminPanelUrl() }}/store/products/{{ $product->id }}/edit" class="dropdown-item d-flex align-items-center mb-3 py-3 px-0 gap-4">
                                                            <x-iconsax-lin-edit-2 class="icons text-gray-500 mr-2" width="18px" height="18px"/>
                                                                <span class="text-gray-500 font-14">{{ trans('admin/main.edit') }}</span>
                                                            </a>
                                                        @endcan

                                                        @can('admin_store_delete_product')

                                                            @include('admin.includes.delete_button',[
                                                            'url' => getAdminPanelUrl().'/store/products/'.$product->id.'/delete',
                                                           'btnClass' => 'dropdown-item text-danger mb-0 py-3 px-0 font-14',
                                                           'btnText' => trans("admin/main.delete"),
                                                           'btnIcon' => 'trash',
                                                           'iconType' => 'lin',
                                                           'iconClass' => 'text-danger mr-2',
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
                            {{ $products->appends(request()->input())->links() }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts_bottom')

@endpush
