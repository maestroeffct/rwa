@extends('admin.layouts.app')


@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{ trans('home.trending_categories') }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ getAdminPanelUrl() }}">{{trans('admin/main.dashboard')}}</a>
                </div>
                <div class="breadcrumb-item">{{ trans('home.trending_categories') }}</div>
            </div>
        </div>

        <div class="section-body">

            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="card">

                            <div class="card-header justify-content-between">
                            
                            <div>
                               <h5 class="font-14 mb-0">{{ $pageTitle }}</h5>
                               <p class="font-12 mt-4 mb-0 text-gray-500">{{ trans('update.manage_all_items_in_a_single_place') }}</p>
                           </div>
                           
                            <div class="d-flex align-items-center gap-12">

                           @can('admin_create_trending_categories')
                                   <a href="{{ getAdminPanelUrl() }}/categories/trends/create" target="_blank" class="btn btn-primary">
                                       <x-iconsax-lin-add class="icons text-white" width="18px" height="18px"/>
                                       <span class="ml-4 text-small font-small font-12">{{ trans('admin/main.create_trend_category') }}</span>
                                   </a>
                               @endcan

                            </div>
                           
                       </div>



                        <div class="card-body">
                         
                            <div class="table-responsive">
                                <table class="table custom-table font-14">
                                    <tr>
                                        <th>{{ trans('admin/main.title') }}</th>
                                        <th>{{ trans('admin/main.trend_color') }}</th>
                                        <th>{{ trans('admin/main.action') }}</th>
                                    </tr>

                                    @foreach($trends as $trend)
                                        <tr>
                                            <td>{{ $trend->category->title }}</td>
                                            <td>
                                                <span class="badge text-white" style="background-color: {{ $trend->color }}">
                                                    {{ $trend->color }}
                                                </span>
                                            </td>
                                              
                                            <td>
                                                <div class="btn-group dropdown table-actions position-relative">
                                                    <button type="button" class="btn-transparent dropdown-toggle" data-toggle="dropdown">
                                                        <x-iconsax-lin-more class="icons text-gray-500" width="20px" height="20px"/>
                                                    </button>
                                            
                                                    <div class="dropdown-menu dropdown-menu-right">
                                                        @can('admin_edit_trending_categories')
                                                            <a href="{{ getAdminPanelUrl() }}/categories/trends/{{ $trend->id }}/edit" 
                                                               class="dropdown-item d-flex align-items-center mb-3 py-3 px-0 gap-4">
                                                                <x-iconsax-lin-edit-2 class="icons text-gray-500 mr-2" width="18px" height="18px"/>
                                                                <span class="text-gray-500 font-14">{{ trans('admin/main.edit') }}</span>
                                                            </a>
                                                        @endcan
                                            
                                                        @can('admin_delete_trending_categories')
                                                            @include('admin.includes.delete_button',[
                                                                'url' => getAdminPanelUrl().'/categories/trends/'.$trend->id.'/delete',
                                                                'btnClass' => 'dropdown-item text-danger mb-0 py-3 px-0 font-14',
                                                                'btnText' => trans('admin/main.delete'),
                                                                'btnIcon' => 'trash',
                                                                'iconType' => 'lin',
                                                                'iconClass' => 'text-danger mr-2',
                                                                'tooltip' => false
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
                        {{ $trends->appends(request()->input())->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

<section class="card">
        <div class="card-body">
           <div class="section-title ml-0 mt-0 mb-3"> <h5>{{trans('admin/main.hints')}}</h5> </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="media-body">
                        <div class="text-primary mt-0 mb-1 font-weight-bold">{{ trans('admin/main.trend_hint_title_1') }}</div>
                        <div class=" text-small font-600-bold">{{ trans('admin/main.trend_hint_description_1') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </section>


@endsection

