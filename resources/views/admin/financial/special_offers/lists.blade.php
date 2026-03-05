@extends('admin.layouts.app')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{ trans('admin/main.special_offers') }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ getAdminPanelUrl() }}">{{trans('admin/main.dashboard')}}</a>
                </div>
                <div class="breadcrumb-item">{{ trans('admin/main.special_offers') }}</div>
            </div>
        </div>

        <div class="section-body">

            <section class="card">
                <div class="card-body">
                    <form method="get" class="mb-0">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="input-label">{{ trans('admin/main.search') }}</label>
                                    <input type="text" class="form-control text-center" name="name" value="{{ request()->get('name') }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="input-label">{{ trans('admin/main.expiration_from') }}</label>
                                    <div class="input-group">
                                        <input type="date" id="fsdate" class="text-center form-control" name="from" value="{{ request()->get('from') }}" placeholder="Start Date">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
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

                            @php
                                $types = [
                                    'courses' => 'webinar_id',
                                    'bundles' => 'bundle_id',
                                    'subscription_packages' => 'subscribe_id',
                                    'registration_packages' => 'registration_package_id',
                                ];
                            @endphp

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="input-label">{{ trans('admin/main.type') }}</label>
                                    <select name="type" class="form-control ">
                                        <option value="">{{ trans('update.select_type') }}</option>

                                        @foreach($types as $type => $typeItem)
                                            <option value="{{ $typeItem }}" {{ (request()->get('type') == $typeItem) ? 'selected' : '' }}>{{ trans('update.'.$type) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="input-label">{{ trans('admin/main.class') }}</label>
                                    <select name="webinar_ids[]" multiple="multiple" class="form-control search-webinar-select2"
                                            data-placeholder="Search classes">

                                        @if(!empty($webinars) and $webinars->count() > 0)
                                            @foreach($webinars as $webinar)
                                                <option value="{{ $webinar->id }}" selected>{{ $webinar->title }}</option>
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
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table custom-table font-14 text-center">
                                    <tr>
                                        <th>{{ trans('admin/main.title') }}</th>
                                        <th class="text-left">{{ trans('admin/main.item') }}</th>
                                        <th>{{ trans('admin/main.percentage') }}</th>
                                        <th>{{ trans('admin/main.from_date') }}</th>
                                        <th>{{ trans('admin/main.to_date') }}</th>
                                        <th>{{ trans('admin/main.status') }}</th>
                                        <th>{{ trans('admin/main.actions') }}</th>
                                    </tr>

                                    @foreach($specialOffers as $specialOffer)
                                        <tr> 
                                            <td>{{ $specialOffer->name }}</td>

                                            <td class="text-left">
                                                @if(!empty($specialOffer->webinar_id))
                                                    <span class="d-block font-14">{{ $specialOffer->webinar->title }}</span>
                                                    <span class="d-block font-12 text-gray-500">{{ trans('admin/main.course') }}</span>
                                                @elseif($specialOffer->bundle_id)
                                                    <span class="d-block font-14">{{ $specialOffer->bundle->title }}</span>
                                                    <span class="d-block font-12 text-gray-500">{{ trans('update.bundle') }}</span>
                                                @elseif($specialOffer->subscribe_id)
                                                    <span class="d-block font-14">{{ $specialOffer->subscribe->title }}</span>
                                                    <span class="d-block font-12 text-gray-500">{{ trans('public.subscribe') }}</span>
                                                @elseif($specialOffer->registration_package_id)
                                                    <span class="d-block font-14">{{ $specialOffer->registrationPackage->title }}</span>
                                                    <span class="d-block font-12 text-gray-500">{{ trans('update.registration_package') }}</span>
                                                @endif
                                            </td>

                                            <td>{{  $specialOffer->percent ?  $specialOffer->percent . '%' : '-' }}</td>

                                            <td>{{  dateTimeFormat($specialOffer->from_date, 'Y/m/d h:i:s') }}</td>

                                            <td>{{  dateTimeFormat($specialOffer->to_date, 'Y/m/d h:i:s') }}</td>

                                            <td>
                                                <span class="badge-status {{ ($specialOffer->status == 'active') ? 'text-success bg-success-30' : 'text-danger bg-danger-30' }}">{{ trans('admin/main.'.$specialOffer->status) }}</span>
                                            </td>

                                            <td>
    <div class="btn-group dropdown table-actions position-relative">
        <button type="button" class="btn-transparent dropdown-toggle" data-toggle="dropdown">
            <x-iconsax-lin-more class="icons text-gray-500" width="20px" height="20px"/>
        </button>

        <div class="dropdown-menu dropdown-menu-right">
            @can('admin_product_discount_edit')
                <a href="{{ getAdminPanelUrl() }}/financial/special_offers/{{ $specialOffer->id }}/edit" class="dropdown-item d-flex align-items-center mb-3 py-3 px-0 gap-4">
                    <x-iconsax-lin-edit-2 class="icons text-gray-500 mr-2" width="18px" height="18px"/>
                    <span class="text-gray-500 font-14">{{ trans('admin/main.edit') }}</span>
                </a>
            @endcan

            @can('admin_product_discount_delete')
                @include('admin.includes.delete_button',[
                    'url' => getAdminPanelUrl().'/financial/special_offers/'.$specialOffer->id.'/delete',
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
                            {{ $specialOffers->appends(request()->input())->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

