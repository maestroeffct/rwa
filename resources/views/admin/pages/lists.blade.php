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
                <div class="col-12 col-md-12">
                    <div class="card">
                        
                        <div class="card-header justify-content-between">
                            
                            <div>
                               <h5 class="font-14 mb-0">{{ $pageTitle }}</h5>
                               <p class="font-12 mt-4 mb-0 text-gray-500">{{ trans('update.manage_all_items_in_a_single_place') }}</p>
                           </div>
                           
                            <div class="d-flex align-items-center gap-12">

                            @can('admin_pages_create')
                                   <a href="{{ getAdminPanelUrl("/pages/create") }}" target="_blank" class="btn btn-primary">
                                       <x-iconsax-lin-add class="icons text-white" width="18px" height="18px"/>
                                       <span class="ml-4 font-12">{{ trans('admin/main.add_new') }}</span>
                                   </a>
                               @endcan

                            </div>
                           
                       </div>

                        <div class="card-body">
                            <div>
                                <table class="table custom-table font-14">
                                    <tr>
                                        <th>{{ trans('admin/main.name') }}</th>
                                        <th>{{ trans('admin/main.link') }}</th>
                                        <th class="text-center">{{ trans('admin/main.robot') }}</th>
                                        <th class="text-center">{{ trans('admin/main.status') }}</th>
                                        <th>{{ trans('admin/main.created_at') }}</th>
                                        <th>{{ trans('admin/main.action') }}</th>
                                    </tr>
                                    @foreach($pages as $page)
                                        <tr>
                                            <td>{{ $page->name }}</td>
                                            <td>{{ $page->link }}</td>
                                            <td class="text-center">
                                                @if($page->robot)
                                                    <span class="text-success">{{ trans('admin/main.follow') }}</span>
                                                @else
                                                    <span class="text-danger">{{ trans('admin/main.no_follow') }}</span>
                                                @endif
                                            </td>

                                            <td class="text-center">
                                                @if($page->status == 'publish')
                                                    <span class="badge-status text-success bg-success-30">{{ trans('admin/main.published') }}</span>
                                                @else
                                                    <span class="badge-status text-dark bg-dark-30">{{ trans('admin/main.is_draft') }}</span>
                                                @endif
                                            </td>
                                            <td>{{ dateTimeFormat($page->created_at, 'j M Y | H:i') }}</td>
                                            <td width="150px">
    <div class="btn-group dropdown table-actions position-relative">
        <button type="button" class="btn-transparent dropdown-toggle" data-toggle="dropdown">
            <x-iconsax-lin-more class="icons text-gray-500" width="20px" height="20px"/>
        </button>

        <div class="dropdown-menu dropdown-menu-right">
            @can('admin_pages_edit')
                <a href="{{ getAdminPanelUrl() }}/pages/{{ $page->id }}/edit"
                   class="dropdown-item d-flex align-items-center mb-3 py-3 px-0 gap-4">
                    <x-iconsax-lin-edit-2 class="icons text-gray-500 mr-2" width="18px" height="18px"/>
                    <span class="text-gray-500 font-14">{{ trans('admin/main.edit') }}</span>
                </a>
            @endcan

            @can('admin_pages_toggle')
                <a href="{{ getAdminPanelUrl() }}/pages/{{ $page->id }}/toggle"
                   class="dropdown-item d-flex align-items-center mb-3 py-3 px-0 gap-4">
                    @if($page->status == 'draft')
                        <x-iconsax-lin-arrow-up class="icons text-success mr-2" width="18px" height="18px"/>
                        <span class="text-success">{{ trans('admin/main.publish') }}</span>
                    @else
                        <x-iconsax-lin-eye-slash class="icons text-warning mr-2" width="18px" height="18px"/>
                        <span class="text-warning">{{ trans('admin/main.draft') }}</span>
                    @endif
                </a>
            @endcan

            @can('admin_pages_delete')
                @include('admin.includes.delete_button',[
                    'url' => getAdminPanelUrl().'/pages/'.$page->id.'/delete',
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
                            {{ $pages->appends(request()->input())->links() }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

