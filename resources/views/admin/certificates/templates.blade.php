@extends('admin.layouts.app')

@push('libraries_top')

@endpush

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{ trans('admin/main.certificates_templates') }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ getAdminPanelUrl() }}">{{trans('admin/main.dashboard')}}</a>
                </div>
                <div class="breadcrumb-item">{{ trans('admin/main.certificates_templates') }}</div>
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

                          
                               @can('admin_certificate_template_create')
                                   <a href="{{ getAdminPanelUrl() }}/certificates/templates/new" target="_blank" class="btn btn-primary">
                                       <x-iconsax-lin-add class="icons text-white" width="18px" height="18px"/>
                                       <span class="ml-4 font-12">{{ trans('admin/main.new_template') }}</span>
                                   </a>
                               @endcan

                            </div>
                           
                       </div>



                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table custom-table font-14">
                                    <tr>
                                        <th class="text-left">{{ trans('admin/main.title') }}</th>
                                        <th>{{ trans('admin/main.type') }}</th>
                                        <th>{{ trans('admin/main.status') }}</th>
                                        <th>{{ trans('admin/main.action') }}</th>
                                    </tr>

                                    @foreach($templates as $template)
                                        <tr>
                                            <td>
                                                <span>{{ $template->title }}</span>
                                            </td>

                                            <td>
                                                @if($template->type == 'quiz')
                                                    <span class="">{{ trans('update.quiz_related') }}</span>
                                                @else
                                                    <span class="">{{ trans('update.course_completion') }}</span>
                                                @endif
                                            </td>

                                            <td>
                                                <span class="badge-status {{ ($template->status == 'publish') ? 'text-success bg-success-30' : 'text-dark bg-dark-30' }}">{{ trans('admin/main.'.$template->status) }}</span>
                                            </td>

                                          
                                            <td>
                                            <div class="btn-group dropdown table-actions position-relative">
                                                    <button type="button" class="btn-transparent dropdown-toggle" data-toggle="dropdown">
                                                        <x-iconsax-lin-more class="icons text-gray-500" width="20px" height="20px"/>
                                                    </button>

                                                    <div class="dropdown-menu dropdown-menu-right">

                                                        @can('admin_certificate_template_edit')
                                                            <a href="{{ getAdminPanelUrl() }}/certificates/templates/{{ $template->id }}/edit" class="dropdown-item d-flex align-items-center mb-3 py-3 px-0 gap-4">
                                                            <x-iconsax-lin-edit-2 class="icons text-gray-500 mr-2" width="18px" height="18px"/>
                                                                <span class="text-gray-500 font-14">{{ trans('admin/main.edit') }}</span>
                                                            </a>
                                                        @endcan

                                                        @can('admin_certificate_template_delete')

                                                            @include('admin.includes.delete_button',[
                                                           'url' => getAdminPanelUrl().'/certificates/templates/'. $template->id .'/delete',
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
                            {{ $templates->appends(request()->input())->links() }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

