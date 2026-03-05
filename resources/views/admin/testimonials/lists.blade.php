@extends('admin.layouts.app')

@push('libraries_top')

@endpush

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{ trans('admin/main.testimonials') }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ getAdminPanelUrl() }}">{{trans('admin/main.dashboard')}}</a>
                </div>
                <div class="breadcrumb-item">{{ trans('admin/main.testimonials') }}</div>
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

                            @can('admin_testimonials_create')
                                   <a href="{{ getAdminPanelUrl("/testimonials/create") }}" target="_blank" class="btn btn-primary">
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
                                        <th>{{ trans('admin/main.user_name') }}</th>
                                        <th>{{ trans('admin/main.rate') }}</th>
                                        <th class="text-center">{{ trans('admin/main.content') }}</th>
                                        <th class="text-center">{{ trans('admin/main.status') }}</th>
                                        <th>{{ trans('admin/main.created_at') }}</th>
                                        <th>{{ trans('admin/main.action') }}</th>
                                    </tr>
                                    @foreach($testimonials as $testimonial)
                                        <tr>

                                        <td class="text-left">
                                                   <div class="d-flex align-items-center">
                                                       <figure class="avatar mr-2">
                                                           <img src="{{ $testimonial->user_avatar }}" alt="{{ $testimonial->user_name }}">
                                                       </figure>
                                                       <div class="media-body ml-1">
                                                           <div class="mt-0 mb-1">{{ $testimonial->user_name }}</div>                                       
                                                       </div>
                                                   </div>
                                        </td>          
                                            <td>{{ $testimonial->rate }}</td>
                                            <td class="text-center" width="30%">{{ nl2br(truncate($testimonial->comment, 150, true)) }}</td>

                                            <td class="text-center">
                                                @if($testimonial->status == 'active')
                                                    <span class="badge-status text-success bg-success-30">{{ trans('admin/main.active') }}</span>
                                                @else
                                                    <span class="badge-status text-danger bg-danger-30">{{ trans('admin/main.disable') }}</span>
                                                @endif
                                            </td>
                                            <td>{{ dateTimeFormat($testimonial->created_at, 'j M Y | H:i') }}</td>
                                            <td width="150px">
    <div class="btn-group dropdown table-actions position-relative">
        <button type="button" class="btn-transparent dropdown-toggle" data-toggle="dropdown">
            <x-iconsax-lin-more class="icons text-gray-500" width="20px" height="20px"/>
        </button>

        <div class="dropdown-menu dropdown-menu-right">
            @can('admin_supports_reply')
                <a href="{{ getAdminPanelUrl() }}/testimonials/{{ $testimonial->id }}/edit"
                   class="dropdown-item d-flex align-items-center mb-3 py-3 px-0 gap-4">
                    <x-iconsax-lin-edit-2 class="icons text-gray-500 mr-2" width="18px" height="18px"/>
                    <span class="text-gray-500 font-14">{{ trans('admin/main.edit') }}</span>
                </a>
            @endcan

            @can('admin_supports_delete')
                @include('admin.includes.delete_button',[
                    'url' => getAdminPanelUrl().'/testimonials/'.$testimonial->id.'/delete',
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
                            {{ $testimonials->appends(request()->input())->links() }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts_bottom')

@endpush
