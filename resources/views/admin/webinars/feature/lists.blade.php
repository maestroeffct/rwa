@extends('admin.layouts.app')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{ trans('admin/main.feature_webinars') }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ getAdminPanelUrl() }}">{{trans('admin/main.dashboard')}}</a>
                </div>
                <div class="breadcrumb-item">{{ trans('admin/main.feature_webinars') }}</div>
            </div>
        </div>

        <div class="section-body">

            <section class="card">
                <div class="card-body">
                    <form action="{{ getAdminPanelUrl() }}/webinars/features" method="get" class="row mb-0">
                        <div class="col-12 col-lg-4">
                            <div class="row">
                                <div class="col-12 col-md-6">
                                    <div class="form-group">
                                        <label class="input-label">{{ trans('admin/main.page') }}</label>
                                        <select class="custom-select" name="page">
                                            <option selected disabled>{{ trans('admin/main.select_page') }}</option>
                                            <option value="">{{ trans('admin/main.all') }}</option>
                                            @foreach(\App\Models\FeatureWebinar::$pages as $page)
                                                <option value="{{ $page }}" @if(request()->get('page', null) == $page) selected="selected" @endif>{{ trans('admin/main.page_'.$page) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="form-group">
                                        <label class="input-label">{{ trans('admin/main.status') }}</label>
                                        <select class="custom-select" name="status">
                                            <option selected disabled>{{ trans('admin/main.status') }}</option>
                                            <option value="">{{ trans('admin/main.all') }}</option>
                                            <option value="pending" @if(request()->get('status', null) == 'pending') selected="selected" @endif>{{ trans('admin/main.pending') }}</option>
                                            <option value="publish" @if(request()->get('status', null) == 'publish') selected="selected" @endif>{{ trans('admin/main.published') }}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-lg-6">
                            <div class="row">
                                <div class="col-12 col-lg-6">
                                    <div class="form-group">
                                        <label class="input-label">{{ trans('admin/main.webinar_title') }}</label>
                                        <input type="text" name="webinar_title" class="form-control" value="{{ request()->get('webinar_title',null) }}"/>
                                    </div>
                                </div>
                                <div class="col-12 col-lg-6">
                                    <div class="form-group">
                                        <label class="input-label">{{ trans('public.category') }}</label>

                                        <select id="categories" class="custom-select" name="category_id">
                                            <option {{ !empty($webinar) ? '' : 'selected' }} disabled>{{ trans('public.choose_category') }}</option>
                                            <option value="">{{ trans('admin/main.all') }}</option>
                                            @foreach($categories as $category)
                                                @if(!empty($category->subCategories) and count($category->subCategories))
                                                    <optgroup label="{{  $category->title }}">
                                                        @foreach($category->subCategories as $subCategory)
                                                            <option value="{{ $subCategory->id }}" {{ (request()->get('category_id',null) == $subCategory->id) ? 'selected' : '' }}>{{ $subCategory->title }}</option>
                                                        @endforeach
                                                    </optgroup>
                                                @else
                                                    <option value="{{ $category->id }}" {{ (!empty($webinar) and $webinar->category_id == $category->id) ? 'selected' : '' }}>{{ $category->title }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2 d-flex align-items-center ">
                     <button type="submit" class="btn btn-primary btn-block btn-lg">{{trans('admin/main.show_results')}}</button>
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

                            @can('admin_feature_webinars_export_excel')
                                <div class="d-flex align-items-center gap-12">
                                    <a href="{{ getAdminPanelUrl() }}/webinars/features/excel?{{ http_build_query(request()->all()) }}" class="btn bg-white bg-hover-gray-100 border-gray-400 text-gray-500">
                                        <x-iconsax-lin-import-2 class="icons text-gray-500" width="18px" height="18px"/>
                                        <span class="ml-4 font-12">{{ trans('admin/main.export_xls') }}</span>
                                    </a>
                                </div>
                            @endcan

                            @can('admin_feature_webinars_create')
                                   <a href="{{ getAdminPanelUrl() }}/webinars/features/create" target="_blank" class="btn btn-primary">
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
                                        <th>{{ trans('admin/main.webinar_title') }}</th>
                                        <th>{{ trans('admin/main.webinar_status') }}</th>
                                        <th class="text-center">{{ trans('public.date') }}</th>
                                        <th class="text-center">{{ trans('admin/main.instructor') }}</th>
                                        <th class="text-center">{{ trans('admin/main.category') }}</th>
                                        <th class="text-center">{{ trans('admin/main.page') }}</th>
                                        <th class="text-center">{{ trans('admin/main.status') }}</th>
                                        <th>{{ trans('admin/main.actions') }}</th>
                                    </tr>

                                    @foreach($features as $feature)

                                        <tr>
                                            <td>
                                                <a class="text-dark" href="{{ $feature->webinar->getUrl() }}" target="_blank">{{ $feature->webinar->title }}</a>
                                            </td>

                                            <td class="text-center">{{ trans('admin/main.'.$feature->webinar->status) }}</td>

                                            <td class="text-center">{{ dateTimeFormat($feature->updated_at, 'j M Y | H:i') }}</td>
                                            <td class="text-center">{{ $feature->webinar->teacher->full_name }}</td>
                                            <td class="text-center">{{ $feature->webinar->category->title }}</td>
                                            <td class="text-center">{{ trans('admin/main.page_'.$feature->page) }}</td>
                                            <td class="text-center">
                                            <span class="badge-status {{ ($feature->status == 'publish') ? 'text-success bg-success-30' : 'text-warning bg-warning-30' }}">{{ ($feature->status == 'publish') ? trans('admin/main.published') : trans('admin/main.pending') }}</span>
                                            </td>
                                            <td width="150">
    <div class="btn-group dropdown table-actions position-relative">
        <button type="button" class="btn-transparent dropdown-toggle" data-toggle="dropdown">
            <x-iconsax-lin-more class="icons text-gray-500" width="20px" height="20px"/>
        </button>

        <div class="dropdown-menu dropdown-menu-right">
            <!-- Publish/Pending Toggle -->
            <a href="{{ getAdminPanelUrl() }}/webinars/features/{{ $feature->id }}/{{ ($feature->status == 'publish') ? 'pending' : 'publish' }}" 
               class="dropdown-item d-flex align-items-center mb-3 py-3 px-0 gap-4">
                @if($feature->status == 'publish')
                    <x-iconsax-lin-eye-slash class="icons text-warning mr-2" width="18px" height="18px"/>
                    <span class="text-warning">{{ trans('admin/main.pending') }}</span>
                @else
                    <x-iconsax-lin-eye class="icons text-success mr-2" width="18px" height="18px"/>
                    <span class="text-success">{{ trans('admin/main.publish') }}</span>
                @endif
            </a>

            <!-- Edit Action -->
            <a href="{{ getAdminPanelUrl() }}/webinars/features/{{ $feature->id }}/edit" 
               class="dropdown-item d-flex align-items-center mb-3 py-3 px-0 gap-4">
                <x-iconsax-lin-edit-2 class="icons text-gray-500 mr-2" width="18px" height="18px"/>
                <span class="text-gray-500 font-14">{{ trans('admin/main.edit') }}</span>
            </a>

            <!-- Delete Action -->
            @include('admin.includes.delete_button',[
                'url' => getAdminPanelUrl().'/webinars/features/'. $feature->id .'/delete',
                'btnClass' => 'dropdown-item text-danger mb-0 py-3 px-0 font-14',
                'btnText' => trans("admin/main.delete"),
                'btnIcon' => 'trash',
                'iconType' => 'lin',
                'iconClass' => 'text-danger mr-2',
            ])
        </div>
    </div>
</td>
                                        </tr>
                                    @endforeach

                                </table>
                            </div>
                        </div>

                        <div class="card-footer text-center">
                            {{ $features->appends(request()->input())->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts_bottom')

@endpush
