@extends('admin.layouts.app')

@push('styles_top')
    <link rel="stylesheet" href="/assets/admin/vendor/bootstrap-colorpicker/bootstrap-colorpicker.min.css">
@endpush

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{ trans('admin/main.blog_categories') }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ getAdminPanelUrl() }}">{{ trans('admin/main.dashboard') }}</a></div>
                <div class="breadcrumb-item">{{ trans('admin/main.blog_categories') }}</div>
            </div>
        </div>

        <div class="section-body">

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">

                            <ul class="nav nav-pills" id="myTab3" role="tablist">
                                @can('admin_blog_categories')
                                    @if(!empty($blogCategories))
                                        <li class="nav-item">
                                            <a class="nav-link {{ (!empty($errors) and $errors->has('title')) ? '' : 'active' }}" id="categories-tab" data-toggle="tab" href="#categories" role="tab" aria-controls="categories" aria-selected="true">{{ trans('admin/main.categories') }}</a>
                                        </li>
                                    @endif
                                @endcan

                                @can('admin_blog_categories_create')
                                    <li class="nav-item">
                                        <a class="nav-link {{ ((!empty($errors) and $errors->has('title')) or !empty($editCategory)) ? 'active' : '' }}" id="newCategory-tab" data-toggle="tab" href="#newCategory" role="tab" aria-controls="newCategory" aria-selected="true">{{ trans('admin/main.create_category') }}</a>
                                    </li>
                                @endcan
                            </ul>

                            <div class="tab-content" id="myTabContent2">
                                @can('admin_blog_categories')
                                    @if(!empty($blogCategories))
                                        <div class="tab-pane mt-3 fade {{ (!empty($errors) and $errors->has('title')) ? '' : 'active show' }}" id="categories" role="tabpanel" aria-labelledby="categories-tab">
                                            <div class="table-responsive">
                                                <table class="table custom-table font-14">
                                                    <tr>
                                                        <th class="text-left">{{ trans('admin/main.title') }}</th>
                                                        <th class="text-center">{{ trans('admin/main.posts') }}</th>
                                                        <th>{{ trans('admin/main.action') }}</th>
                                                    </tr>

                                                    @foreach($blogCategories as $category)
                                                        <tr>
                                                            <td class="text-left">{{ $category->title }}</td>
                                                            <td class="text-center">{{ $category->blog_count }}</td>
                                                            <td width="80px">
                                                                <div class="btn-group dropdown table-actions position-relative">
                                                                    <button type="button" class="btn-transparent dropdown-toggle" data-toggle="dropdown">
                                                                        <x-iconsax-lin-more class="icons text-gray-500" width="20px" height="20px"/>
                                                                    </button>

                                                                    <div class="dropdown-menu dropdown-menu-right">
                                                                        @can('admin_edit_trending_categories')
                                                                            <a href="{{ getAdminPanelUrl() }}/blog/categories/{{ $category->id }}/edit"
                                                                               class="dropdown-item d-flex align-items-center mb-3 py-3 px-0 gap-4">
                                                                                <x-iconsax-lin-edit-2 class="icons text-gray-500 mr-2" width="18px" height="18px"/>
                                                                                <span class="text-gray-500 font-14">{{ trans('admin/main.edit') }}</span>
                                                                            </a>
                                                                        @endcan

                                                                        @can('admin_delete_trending_categories')
                                                                            @include('admin.includes.delete_button',[
                                                                                'url' => getAdminPanelUrl().'/blog/categories/'.$category->id.'/delete',
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
                                @endcan

                                @can('admin_blog_categories_create')
                                    <div class="tab-pane mt-3 fade {{ ((!empty($errors) and $errors->has('title')) or !empty($editCategory)) ? 'active show' : '' }}" id="newCategory" role="tabpanel" aria-labelledby="newCategory-tab">
                                        <div class="row">
                                            <div class="col-12 col-md-6">
                                                <form action="{{ getAdminPanelUrl() }}/blog/categories/{{ !empty($editCategory) ? $editCategory->id.'/update' : 'store' }}" method="post">
                                                    {{ csrf_field() }}

                                                    @if(!empty(getGeneralSettings('content_translate')) and !empty($userLanguages))
                                                        <div class="form-group">
                                                            <label class="input-label">{{ trans('auth.language') }}</label>
                                                            <select name="locale" class="form-control {{ !empty($editCategory) ? 'js-edit-content-locale' : '' }}">
                                                                @foreach($userLanguages as $lang => $language)
                                                                    <option value="{{ $lang }}" @if(mb_strtolower(request()->get('locale', app()->getLocale())) == mb_strtolower($lang)) selected @endif>{{ $language }}</option>
                                                                @endforeach
                                                            </select>
                                                            @error('locale')
                                                            <div class="invalid-feedback">
                                                                {{ $message }}
                                                            </div>
                                                            @enderror
                                                        </div>
                                                    @else
                                                        <input type="hidden" name="locale" value="{{ getDefaultLocale() }}">
                                                    @endif

                                                    <div class="form-group">
                                                        <label>{{ trans('admin/main.title') }}</label>
                                                        <input type="text" name="title"
                                                               class="form-control  @error('title') is-invalid @enderror"
                                                               value="{{ !empty($editCategory) ? $editCategory->title : '' }}"
                                                               placeholder="{{ trans('admin/main.choose_title') }}"/>
                                                        @error('title')
                                                        <div class="invalid-feedback">
                                                            {{ $message }}
                                                        </div>
                                                        @enderror
                                                    </div>

                                                    <div class="form-group">
                                                        <label>{{ trans('update.subtitle') }}</label>
                                                        <input type="text" name="subtitle"
                                                               class="form-control  @error('subtitle') is-invalid @enderror"
                                                               value="{{ !empty($editCategory) ? $editCategory->subtitle : old('subtitle') }}"
                                                               placeholder="{{ trans('admin/main.choose_subtitle') }}"/>
                                                        @error('subtitle')
                                                        <div class="invalid-feedback">
                                                            {{ $message }}
                                                        </div>
                                                        @enderror
                                                    </div>

                                                    <div class="form-group">
                                                        <label>{{ trans('admin/main.url') }}</label>
                                                        <input type="text" name="slug"
                                                               class="form-control  @error('slug') is-invalid @enderror"
                                                               value="{{ !empty($editCategory) ? $editCategory->slug : old('slug') }}"/>
                                                        <div class="text-gray-500 text-small mt-1">{{ trans('update.category_url_hint') }}</div>
                                                        @error('slug')
                                                        <div class="invalid-feedback">
                                                            {{ $message }}
                                                        </div>
                                                        @enderror
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="input-label">{{ trans('admin/main.cover_image') }}</label>
                                                        <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <button type="button" class="input-group-text admin-file-manager " data-input="cover_image" data-preview="holder">
                                                                    <i class="fa fa-upload"></i>
                                                                </button>
                                                            </div>
                                                            <input type="text" name="cover_image" id="cover_image" value="{{ !empty($editCategory) ? $editCategory->cover_image : old('cover_image') }}" class="form-control @error('cover_image') is-invalid @enderror"/>
                                                            <div class="invalid-feedback">@error('cover_image') {{ $message }} @enderror</div>
                                                        </div>
                                                    </div>

                                                    {{--<div class="form-group">
                                                        <label class="input-label">{{ trans('admin/main.icon') }}</label>
                                                        <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <button type="button" class="input-group-text admin-file-manager " data-input="icon" data-preview="holder">
                                                                    <i class="fa fa-upload"></i>
                                                                </button>
                                                            </div>
                                                            <input type="text" name="icon" id="icon" value="{{ !empty($editCategory) ? $editCategory->icon : old('icon') }}" class="form-control @error('icon') is-invalid @enderror"/>
                                                            <div class="invalid-feedback">@error('icon') {{ $message }} @enderror</div>
                                                        </div>
                                                    </div>--}}

                                                    <div class="row">
                                                        <div class="col-6">
                                                            <div class="form-group">
                                                                <label class="input-label">{{ trans('admin/main.icon') }}</label>
                                                                <div class="input-group">
                                                                    <div class="input-group-prepend">
                                                                        <button type="button" class="input-group-text admin-file-manager " data-input="icon2" data-preview="holder">
                                                                            <i class="fa fa-upload"></i>
                                                                        </button>
                                                                    </div>
                                                                    <input type="text" name="icon2" id="icon2" value="{{ !empty($editCategory) ? $editCategory->icon2 : old('icon2') }}" class="form-control @error('icon2') is-invalid @enderror"/>
                                                                    <div class="invalid-feedback">@error('icon2') {{ $message }} @enderror</div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-6">
                                                            <div class="form-group">
                                                                <label>{{ trans('update.icon2_box_color') }}</label>

                                                                <div class="input-group colorpickerinput">
                                                                    <input type="text" name="icon2_box_color"
                                                                           autocomplete="off"
                                                                           class="form-control  @error('icon2_box_color') is-invalid @enderror"
                                                                           value="{{ !empty($editCategory) ? $editCategory->icon2_box_color : old('icon2_box_color') }}"
                                                                    />
                                                                    <div class="input-group-append">
                                                                        <div class="input-group-text">
                                                                            <i class="fas fa-fill-drip"></i>
                                                                        </div>
                                                                    </div>

                                                                    @error('icon2_box_color')
                                                                    <div class="invalid-feedback">
                                                                        {{ $message }}
                                                                    </div>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="input-label">{{ trans('update.overlay_image') }}</label>
                                                        <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <button type="button" class="input-group-text admin-file-manager " data-input="overlay_image" data-preview="holder">
                                                                    <i class="fa fa-upload"></i>
                                                                </button>
                                                            </div>
                                                            <input type="text" name="overlay_image" id="overlay_image" value="{{ !empty($editCategory) ? $editCategory->overlay_image : old('overlay_image') }}" class="form-control @error('overlay_image') is-invalid @enderror"/>
                                                            <div class="invalid-feedback">@error('overlay_image') {{ $message }} @enderror</div>
                                                        </div>
                                                    </div>


                                                    <div class="text-right col-12">
                                                        <button type="submit" class="btn btn-primary">{{ trans('admin/main.save_change') }}</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endcan
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
