@extends('admin.layouts.app')

@push('styles_top')

@endpush

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{ $pageTitle }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a>{{ trans('admin/main.users') }}</a></div>
                <div class="breadcrumb-item"><a href="#">{{ $pageTitle }}</a></div>
            </div>
        </div>
    </section>

    <div class="card">

        <div class="card-header justify-content-between">

                    <div>
                        <h5 class="font-14 mb-0">{{ $pageTitle }}</h5>
                        <p class="font-12 mt-4 mb-0 text-gray-500">{{ trans('update.manage_all_items_in_a_single_place') }}</p>
                    </div>

                    <div class="d-flex align-items-center gap-12">

                    @can("admin_user_ip_restriction_create")

                    <a data-path="{{ getAdminPanelUrl("/users/ip-restriction/get-form") }}"  class="js-add-restriction btn btn-primary">
                        <x-iconsax-lin-add class="icons text-white" width="18px" height="18px"/>
                        <span class="text-white ml-4 font-12">{{ trans('admin/main.add_new') }}</span>
                    </a>

                    @endcan

                    </div>
            </div>

        <div class="card-body">
            <div class="table-responsive text-center">
                <table class="table custom-table font-14">
                    <tr>
                        <th width="120">{{ trans('admin/main.type') }}</th>
                        <th class="text-left" width="200">{{ trans('update.value') }}</th>
                        <th class="text-left">{{ trans('product.reason') }}</th>
                        <th class="text-left">{{ trans('update.blocked_date') }}</th>
                        <th width="120">{{ trans('admin/main.actions') }}</th>
                    </tr>

                    @foreach($restrictions as $restriction)

                        <tr>
                            <td width="120">{{ trans("update.{$restriction->type}") }}</td>


                            <td class="text-left" width="200">
                                @if($restriction->type == "country")
                                    {{ getCountriesLists($restriction->value) }}
                                @else
                                    {{ $restriction->value }}
                                @endif
                            </td>

                            <td class="text-left">{{ $restriction->reason }}</td>

                            <td class="text-left font-12">{{ dateTimeFormat($restriction->created_at, 'j M Y H:i') }}</td>


                            <td class="text-center" width="120">
    <div class="btn-group dropdown table-actions position-relative">
        <button type="button" class="btn-transparent dropdown-toggle" data-toggle="dropdown">
            <x-iconsax-lin-more class="icons text-gray-500" width="20px" height="20px"/>
        </button>

        <div class="dropdown-menu dropdown-menu-right">
            @can('admin_user_ip_restriction_create')
                <a href="{{ getAdminPanelUrl() }}/users/ip-restriction/{{ $restriction->id }}/edit"
                   class="dropdown-item d-flex align-items-center mb-3 py-3 px-0 gap-4 js-edit-restriction">
                    <x-iconsax-lin-edit-2 class="icons text-gray-500 mr-2" width="18px" height="18px"/>
                    <span class="text-gray-500 font-14">{{ trans('admin/main.edit') }}</span>
                </a>
            @endcan

            @can('admin_user_ip_restriction_delete')
                @include('admin.includes.delete_button',[
                    'url' => getAdminPanelUrl().'/users/ip-restriction/'.$restriction->id.'/delete',
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
            {{ $restrictions->appends(request()->input())->links() }}
        </div>
    </div>


    <section class="card">
        <div class="card-body">
            <div class="section-title ml-0 mt-0 mb-3"><h5>{{trans('admin/main.hints')}}</h5></div>
            <div class="row">
                <div class="col-md-4">
                    <div class="media-body">
                        <div class="text-primary mt-0 mb-1 font-weight-bold">{{trans('update.restrictions_hint_title_1')}}</div>
                        <div class=" text-small font-600-bold">{{trans('update.restrictions_hint_description_1')}}</div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="media-body">
                        <div class="text-primary mt-0 mb-1 font-weight-bold">{{trans('update.restrictions_hint_title_2')}}</div>
                        <div class=" text-small font-600-bold">{{trans('update.restrictions_hint_description_2')}}</div>
                    </div>
                </div>


                <div class="col-md-4">
                    <div class="media-body">
                        <div class="text-primary mt-0 mb-1 font-weight-bold">{{trans('update.restrictions_hint_title_3')}}</div>
                        <div class="text-small font-600-bold">{{trans('update.restrictions_hint_description_3')}}</div>
                    </div>
                </div>


            </div>
        </div>
    </section>
@endsection

@push('scripts_bottom')


    <script src="/assets/admin/js/parts/ip-restriction.min.js"></script>
@endpush
