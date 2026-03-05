@extends('admin.layouts.app')

@push('styles_top')
    <link rel="stylesheet" href="/assets/admin/vendor/bootstrap-colorpicker/bootstrap-colorpicker.min.css">
@endpush

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{ trans('update.top_categories') }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ getAdminPanelUrl() }}">{{ trans('admin/main.dashboard') }}</a></div>
                <div class="breadcrumb-item">{{ trans('update.top_categories') }}</div>
            </div>
        </div>

        <div class="section-body">

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">

                            <ul class="nav nav-pills" id="myTab3" role="tablist">

                                @if(!empty($featuredCategories))
                                    <li class="nav-item">
                                        <a class="nav-link {{ (!empty($errors) and count($errors)) ? '' : 'active' }}" id="featuredCategories-tab" data-toggle="tab" href="#featuredCategories" role="tab" aria-controls="featuredCategories" aria-selected="true">{{ trans('home.featured') }}</a>
                                    </li>
                                @endif

                                <li class="nav-item">
                                    <a class="nav-link {{ ((!empty($errors) and count($errors)) or !empty($editFeaturedCategory)) ? 'active' : '' }}" id="newFeaturedCategory-tab" data-toggle="tab" href="#newFeaturedCategory" role="tab" aria-controls="newFeaturedCategory" aria-selected="true">{{ trans('update.add_top_category') }}</a>
                                </li>
                            </ul>

                            <div class="tab-content" id="myTabContent2">

                                @if(!empty($featuredCategories))
                                    <div class="tab-pane mt-3 fade {{ (!empty($errors) and count($errors)) ? '' : 'active show' }}" id="featuredCategories" role="tabpanel" aria-labelledby="featuredCategories-tab">
                                        <div class="table-responsive">
                                            <table class="table custom-table font-14">
                                                <tr>
                                                    <th class="text-left">{{ trans('admin/main.category') }}</th>
                                                    <th>{{ trans('admin/main.action') }}</th>
                                                </tr>

                                                @foreach($featuredCategories as $featuredCategory)
                                                    <tr>
                                                        <td class="text-left">
                                                            <div class="d-flex align-items-center">
                                                                <div class="size-32 rounded-sm">
                                                                    <img src="{{ $featuredCategory->image }}" alt="" class="img-cover rounded-16">
                                                                </div>
                                                                <div class="ml-2">
                                                                    {{ $featuredCategory->category->title }}
                                                                </div>
                                                            </div>
                                                        </td>

                                                        <td width="80px">
    <div class="btn-group dropdown table-actions position-relative">
        <button type="button" class="btn-transparent dropdown-toggle" data-toggle="dropdown">
            <x-iconsax-lin-more class="icons text-gray-500" width="20px" height="20px"/>
        </button>

        <div class="dropdown-menu dropdown-menu-right">
            @can('admin_edit_trending_categories')
                <a href="{{ getAdminPanelUrl() }}/store/top-categories/{{ $featuredCategory->id }}/edit"
                   class="dropdown-item d-flex align-items-center mb-3 py-3 px-0 gap-4">
                    <x-iconsax-lin-edit-2 class="icons text-gray-500 mr-2" width="18px" height="18px"/>
                    <span class="text-gray-500 font-14">{{ trans('admin/main.edit') }}</span>
                </a>
            @endcan

            @can('admin_delete_trending_categories')
                @include('admin.includes.delete_button',[
                    'url' => getAdminPanelUrl().'/store/top-categories/'.$featuredCategory->id.'/delete',
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
                                @endif

                                <div class="tab-pane mt-3 fade {{ ((!empty($errors) and count($errors)) or !empty($editFeaturedCategory)) ? 'active show' : '' }}" id="newFeaturedCategory" role="tabpanel" aria-labelledby="newFeaturedCategory-tab">
                                    <div class="row">
                                        <div class="col-12 col-md-6">
                                            <form action="{{ getAdminPanelUrl() }}/store/top-categories/{{ !empty($editFeaturedCategory) ? $editFeaturedCategory->id.'/update' : 'store' }}" method="post">
                                                {{ csrf_field() }}

 
                                                <div class="form-group">
                                                    <label>{{ trans('admin/main.category') }}</label>
                                                    <select name="category_id" class="form-control  @error('category_id') is-invalid @enderror">
                                                        <option value="">{{ trans('admin/main.choose_category') }}</option>

                                                        @foreach($productCategories as $productCategory)
                                                            @if(!empty($productCategory->subCategories) and $productCategory->subCategories->count() > 0)
                                                                <optgroup label="{{  $productCategory->title }}">
                                                                    @foreach($productCategory->subCategories as $subCategory)
                                                                        <option value="{{ $subCategory->id }}" {{ ((!empty($editFeaturedCategory) and $editFeaturedCategory->category_id == $subCategory->id) or old('category_id') == $subCategory->id) ? 'selected' : '' }}>{{ $subCategory->title }}</option>
                                                                    @endforeach
                                                                </optgroup>
                                                            @else
                                                                <option value="{{ $productCategory->id }}" {{ ((!empty($editFeaturedCategory) and $editFeaturedCategory->category_id == $productCategory->id) or old('category_id') == $productCategory->id) ? 'selected' : '' }}>{{ $productCategory->title }}</option>
                                                            @endif
                                                        @endforeach
                                                    </select>

                                                    @error('category_id')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                    @enderror
                                                </div>

                                                <div class="form-group">
                                                    <label class="input-label">{{ trans('public.thumbnail_image') }}</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <button type="button" class="input-group-text admin-file-manager " data-input="image" data-preview="holder">
                                                                <i class="fa fa-upload"></i>
                                                            </button>
                                                        </div>
                                                        <input type="text" name="image" id="image" value="{{ !empty($editFeaturedCategory) ? $editFeaturedCategory->image : old('image') }}" class="form-control @error('image') is-invalid @enderror"/>
                                                        <div class="invalid-feedback">@error('image') {{ $message }} @enderror</div>
                                                    </div>
                                                </div>

                                                <div class="text-right mt-4">
                                                    <button type="submit"  class="btn btn-primary">{{ trans('admin/main.save_change') }}</button>
                                                </div>

                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts_bottom')
    <script src="/assets/admin/vendor/bootstrap-colorpicker/bootstrap-colorpicker.min.js"></script>
@endpush
