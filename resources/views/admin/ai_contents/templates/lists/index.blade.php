@extends('admin.layouts.app')

@push('libraries_top')

@endpush

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{ trans('update.service_templates') }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ getAdminPanelUrl() }}">{{trans('admin/main.dashboard')}}</a>
                </div>
                <div class="breadcrumb-item">{{ trans('update.service_templates') }}</div>
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
                            @can('admin_ai_contents_templates_create')
                                   <a href="{{ getAdminPanelUrl() }}/ai-contents/templates/create" target="_blank" class="btn btn-primary">
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
                                        <th>{{ trans('admin/main.title') }}</th>
                                        <th>{{ trans('update.service_type') }}</th>
                                        <th>{{ trans('update.generated_contents') }}</th>
                                        <th>{{ trans('admin/main.status') }}</th>
                                        <th>{{ trans('admin/main.actions') }}</th>
                                    </tr>
                                    @foreach($templates as $template)
                                        <tr>
                                            <td>{{ $template->title }}</td>

                                            <td>{{ trans($template->type) }}</td>

                                            <td>{{ $template->contents_count ?? 0 }}</td>

                                            <td>
                                                @if($template->enable)
                                                    <span class="text-success">{{ trans('admin/main.active') }}</span>
                                                @else
                                                    <span class="text-danger">{{ trans('admin/main.inactive') }}</span>
                                                @endif
                                            </td>

                                            <td>
    <div class="btn-group dropdown table-actions position-relative">
        <button type="button" class="btn-transparent dropdown-toggle" data-toggle="dropdown">
            <x-iconsax-lin-more class="icons text-gray-500" width="20px" height="20px"/>
        </button>

        <div class="dropdown-menu dropdown-menu-right">
            @can('admin_ai_contents_templates_edit')
                <a href="{{ getAdminPanelUrl() }}/ai-contents/templates/{{ $template->id }}/edit"
                   class="dropdown-item d-flex align-items-center mb-3 py-3 px-0 gap-4">
                    <x-iconsax-lin-edit-2 class="icons text-gray-500 mr-2" width="18px" height="18px"/>
                    <span class="text-gray-500 font-14">{{ trans('admin/main.edit') }}</span>
                </a>

                <div class="dropdown-item d-flex align-items-center mb-3 py-3 px-0 gap-4">
                    @include('admin.includes.delete_button',[
                        'url' => getAdminPanelUrl().'/ai-contents/templates/'.$template->id.'/statusToggle',
                        'btnClass' => $template->enable ? 'text-warning' : 'text-success',
                        'btnText' => $template->enable ? trans('admin/main.inactive') : trans('admin/main.active'),
                        'btnIcon' => $template->enable ? 'close-circle' : 'tick-circle',
                        'iconType' => 'lin',
                        'iconClass' => $template->enable ? 'text-warning mr-2' : 'text-success mr-2'
                    ])
                </div>
            @endcan

            @can('admin_ai_contents_templates_delete')
                @include('admin.includes.delete_button',[
                    'url' => getAdminPanelUrl().'/ai-contents/templates/'.$template->id.'/delete',
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
                            {{ $templates->appends(request()->input())->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts_bottom')

@endpush
