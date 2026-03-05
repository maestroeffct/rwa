@extends('admin.layouts.app')

@push('styles_top')
    <link href="/assets/default/vendors/sortable/jquery-ui.min.css"/>
    <link rel="stylesheet" href="/assets/admin/vendor/bootstrap-colorpicker/bootstrap-colorpicker.min.css">
@endpush

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{!empty($category) ?trans('/admin/main.edit'): trans('admin/main.new') }} {{ trans('admin/main.category') }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ getAdminPanelUrl() }}">{{ trans('admin/main.dashboard') }}</a>
                </div>
                <div class="breadcrumb-item active">
                    <a href="{{ getAdminPanelUrl() }}/categories">{{ trans('admin/main.categories') }}</a>
                </div>
                <div class="breadcrumb-item">{{!empty($category) ?trans('/admin/main.edit'): trans('admin/main.new') }}</div>
            </div>
        </div>

        <div class="section-body">

            <div class="row">
                <div class="col-12 col-md-6 col-lg-6">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ getAdminPanelUrl() }}/categories/{{ !empty($category) ? $category->id.'/update' : 'store' }}"
                                  method="Post">
                                {{ csrf_field() }}

                                @if(!empty(getGeneralSettings('content_translate')))
                                    <div class="form-group">
                                        <label class="input-label">{{ trans('auth.language') }}</label>
                                        <select name="locale" class="form-control {{ !empty($category) ? 'js-edit-content-locale' : '' }}">
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
                                    <label>{{ trans('/admin/main.title') }}</label>
                                    <input type="text" name="title"
                                           class="form-control  @error('title') is-invalid @enderror"
                                           value="{{ !empty($category) ? $category->title : old('title') }}"
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
                                           value="{{ !empty($category) ? $category->subtitle : old('subtitle') }}"
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
                                           value="{{ !empty($category) ? $category->slug : old('slug') }}"/>
                                    <div class="text-gray-500 text-small mt-1">{{ trans('update.category_url_hint') }}</div>
                                    @error('slug')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label>{{ trans('update.order') }}</label>
                                    <input type="text" name="order"
                                           class="form-control  @error('order') is-invalid @enderror"
                                           value="{{ !empty($category) ? $category->order : old('order') }}"/>
                                    <div class="text-gray-500 text-small mt-1">{{ trans('update.category_order_hint') }}</div>
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
                                        <input type="text" name="cover_image" id="cover_image" value="{{ !empty($category) ? $category->cover_image : old('cover_image') }}" class="form-control @error('cover_image') is-invalid @enderror"/>
                                        <div class="invalid-feedback">@error('cover_image') {{ $message }} @enderror</div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="input-label">{{ trans('admin/main.icon') }}</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <button type="button" class="input-group-text admin-file-manager " data-input="icon" data-preview="holder">
                                                <i class="fa fa-upload"></i>
                                            </button>
                                        </div>
                                        <input type="text" name="icon" id="icon" value="{{ !empty($category) ? $category->icon : old('icon') }}" class="form-control @error('icon') is-invalid @enderror"/>
                                        <div class="invalid-feedback">@error('icon') {{ $message }} @enderror</div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label class="input-label">{{ trans('update.icon2') }}</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <button type="button" class="input-group-text admin-file-manager " data-input="icon2" data-preview="holder">
                                                        <i class="fa fa-upload"></i>
                                                    </button>
                                                </div>
                                                <input type="text" name="icon2" id="icon2" value="{{ !empty($category) ? $category->icon2 : old('icon2') }}" class="form-control @error('icon2') is-invalid @enderror"/>
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
                                                       value="{{ !empty($category) ? $category->icon2_box_color : old('icon2_box_color') }}"
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
                                        <input type="text" name="overlay_image" id="overlay_image" value="{{ !empty($category) ? $category->overlay_image : old('overlay_image') }}" class="form-control @error('overlay_image') is-invalid @enderror"/>
                                        <div class="invalid-feedback">@error('overlay_image') {{ $message }} @enderror</div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="input-label">{{ trans('update.bottom_seo_title') }}</label>
                                    <input type="text" name="bottom_seo_title" class="form-control" value="{{ !empty($category) ? $category->bottom_seo_title : old('bottom_seo_title') }}">
                                </div>

                                <div class="form-group">
                                    <label class="input-label">{{ trans('update.bottom_seo_content') }}</label>
                                    <textarea name="bottom_seo_content" class="form-control" rows="5">{{ !empty($category) ? $category->bottom_seo_content : old('bottom_seo_title') }}</textarea>
                                </div>

                                <div class="form-group custom-switches-stacked">
                                    <label class="custom-switch pl-0">
                                        <input type="hidden" name="enable" value="">
                                        <input type="checkbox" name="enable" id="enableSwitch" value="on" {{ (!empty($category) and $category->enable) ? 'checked="checked"' : '' }} class="custom-switch-input"/>
                                        <span class="custom-switch-indicator"></span>
                                        <label class="custom-switch-description mb-0 cursor-pointer" for="enableSwitch">{{ trans('admin/main.active') }}</label>
                                    </label>
                                </div>

                                <div class="form-group">
                                    <div class="custom-control custom-checkbox">
                                        <input id="hasSubCategory" type="checkbox" name="has_sub"
                                               class="custom-control-input" {{ (!empty($subCategories) and !$subCategories->isEmpty()) ? 'checked' : '' }}>
                                        <label class="custom-control-label"
                                               for="hasSubCategory">{{ trans('admin/main.has_sub_category') }}</label>
                                    </div>
                                </div>

                                <div id="subCategories" class="ml-0 {{ (!empty($subCategories) and !$subCategories->isEmpty()) ? '' : ' d-none' }}">
                                    <div class="d-flex align-items-center justify-content-between mb-4">
                                        <strong class="d-block">{{ trans('admin/main.add_sub_categories') }}</strong>

                                        <button type="button" class="btn btn-success add-btn"><i class="fa fa-plus"></i> Add</button>
                                    </div>

                                    <ul class="draggable-lists list-group">

                                        @if((!empty($subCategories) and !$subCategories->isEmpty()))
                                            @foreach($subCategories as $key => $subCategory)
                                                <li class="form-group list-group">

                                                    <div class="p-2 border rounded-sm">
                                                        <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <div class="input-group-text cursor-pointer move-icon">
                                                                    <i class="fa fa-arrows-alt"></i>
                                                                </div>
                                                            </div>

                                                            <input type="text" name="sub_categories[{{ $subCategory->id }}][title]"
                                                                   class="form-control w-auto flex-grow-1"
                                                                   value="{{ $subCategory->title }}"
                                                                   placeholder="{{ trans('admin/main.choose_title') }}"/>

                                                            <div class="input-group-append">
                                                                @include('admin.includes.delete_button',[
                                                                         'url' => getAdminPanelUrl("/categories/{$subCategory->id}/delete"),
                                                                         'deleteConfirmMsg' => trans('update.category_delete_confirm_msg'),
                                                                         'btnClass' => 'input-group-text btn-danger text-white',
                                                                         'btnIcon' => 'trash',
                                                                         'iconClass' => 'text-white',
                                                                         'noBtnTransparent' => true
                                                                     ])
                                                            </div>
                                                        </div>

                                                        <div class="input-group w-100 mt-1">
                                                            <input type="text" name="sub_categories[{{ $subCategory->id }}][slug]"
                                                                   class="form-control w-auto flex-grow-1"
                                                                   value="{{ $subCategory->slug }}"
                                                                   placeholder="{{ trans('admin/main.choose_url') }}"/>
                                                        </div>

                                                        <div class="input-group w-100 mt-1">
                                                            <input type="text" name="sub_categories[{{ $subCategory->id }}][subtitle]"
                                                                   class="form-control w-auto flex-grow-1"
                                                                   value="{{ $subCategory->subtitle }}"
                                                                   placeholder="{{ trans('update.choose_subtitle') }}"/>
                                                        </div>

                                                        <div class="input-group mt-1">
                                                            <div class="input-group-prepend">
                                                                <button type="button" class="input-group-text admin-file-manager " data-input="cover_image_{{ $subCategory->id }}" data-preview="holder">
                                                                    <i class="fa fa-upload"></i>
                                                                </button>
                                                            </div>
                                                            <input type="text" name="sub_categories[{{ $subCategory->id }}][cover_image]" id="cover_image_{{ $subCategory->id }}" class="form-control" value="{{ $subCategory->cover_image }}" placeholder="{{ trans('admin/main.cover_image') }}"/>
                                                        </div>

                                                        <div class="input-group mt-1">
                                                            <div class="input-group-prepend">
                                                                <button type="button" class="input-group-text admin-file-manager " data-input="icon_{{ $subCategory->id }}" data-preview="holder">
                                                                    <i class="fa fa-upload"></i>
                                                                </button>
                                                            </div>
                                                            <input type="text" name="sub_categories[{{ $subCategory->id }}][icon]" id="icon_{{ $subCategory->id }}" class="form-control" value="{{ $subCategory->icon }}" placeholder="{{ trans('admin/main.icon') }}"/>
                                                        </div>

                                                        <div class="row">
                                                            <div class="col-6">
                                                                <div class="input-group mt-1">
                                                                    <div class="input-group-prepend">
                                                                        <button type="button" class="input-group-text admin-file-manager " data-input="icon2_{{ $subCategory->id }}" data-preview="holder">
                                                                            <i class="fa fa-upload"></i>
                                                                        </button>
                                                                    </div>
                                                                    <input type="text" name="sub_categories[{{ $subCategory->id }}][icon2]" id="icon2_{{ $subCategory->id }}" class="form-control" value="{{ $subCategory->icon2 }}" placeholder="{{ trans('update.icon2') }}"/>
                                                                </div>
                                                            </div>
                                                            <div class="col-6">
                                                                <div class="input-group mt-1 colorpickerinput">
                                                                    <input type="text" name="sub_categories[{{ $subCategory->id }}][icon2_box_color]"
                                                                           autocomplete="off"
                                                                           class="form-control"
                                                                           value="{{ $subCategory->icon2_box_color }}"
                                                                           placeholder="{{ trans('update.icon2_box_color') }}"
                                                                    />
                                                                    <div class="input-group-append">
                                                                        <div class="input-group-text">
                                                                            <i class="fas fa-fill-drip"></i>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="input-group mt-1">
                                                            <div class="input-group-prepend">
                                                                <button type="button" class="input-group-text admin-file-manager " data-input="overlay_image_{{ $subCategory->id }}" data-preview="holder">
                                                                    <i class="fa fa-upload"></i>
                                                                </button>
                                                            </div>
                                                            <input type="text" name="sub_categories[{{ $subCategory->id }}][overlay_image]" id="overlay_image_{{ $subCategory->id }}" class="form-control" value="{{ $subCategory->overlay_image }}" placeholder="{{ trans('update.overlay_image') }}"/>
                                                        </div>

                                                        <div class="input-group mt-1">
                                                            <input type="text" name="sub_categories[{{ $subCategory->id }}][bottom_seo_title]" class="form-control" value="{{ $subCategory->bottom_seo_title }}" placeholder="{{ trans('update.bottom_seo_title') }}">
                                                        </div>

                                                        <div class="input-group mt-1">
                                                            <textarea name="sub_categories[{{ $subCategory->id }}][bottom_seo_content]" class="form-control" rows="5" placeholder="{{ trans('update.bottom_seo_content') }}">{{ $subCategory->bottom_seo_content }}</textarea>
                                                        </div>

                                                        <div class="form-group custom-switches-stacked mb-0">
                                                            <label class="custom-switch pl-0">
                                                                <input type="hidden" name="sub_categories[{{ $subCategory->id }}][enable]" value="">
                                                                <input type="checkbox" name="sub_categories[{{ $subCategory->id }}][enable]" id="enableSwitch_{{ $subCategory->id }}" value="on" {{ ($subCategory->enable) ? 'checked="checked"' : '' }} class="custom-switch-input"/>
                                                                <span class="custom-switch-indicator"></span>
                                                                <label class="custom-switch-description mb-0 cursor-pointer" for="enableSwitch_{{ $subCategory->id }}">{{ trans('admin/main.active') }}</label>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </li>
                                            @endforeach
                                        @endif
                                    </ul>
                                </div>

                                <div class="text-right mt-4">
                                    <button class="btn btn-primary">{{ trans('admin/main.submit') }}</button>
                                </div>
                            </form>

                            <li class="form-group main-row list-group d-none">
                                <div class="p-2 border rounded-sm">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text cursor-pointer move-icon">
                                                <i class="fa fa-arrows-alt"></i>
                                            </div>
                                        </div>

                                        <input type="text" name="sub_categories[record][title]"
                                               class="form-control w-auto flex-grow-1"
                                               placeholder="{{ trans('admin/main.choose_title') }}"/>

                                        <div class="input-group-append">
                                            <button type="button" class="remove-btn input-group-text btn-danger text-white">
                                                <x-iconsax-lin-trash class="text-white" width="18px" height="18px"/>
                                            </button>
                                        </div>
                                    </div>

                                    <div class="input-group mt-1">
                                        <input type="text" name="sub_categories[record][slug]"
                                               class="form-control w-auto flex-grow-1"
                                               placeholder="{{ trans('admin/main.choose_url') }}"/>
                                    </div>

                                    <div class="input-group w-100 mt-1">
                                        <input type="text" name="sub_categories[record][subtitle]"
                                               class="form-control w-auto flex-grow-1"
                                               value=""
                                               placeholder="{{ trans('update.choose_subtitle') }}"/>
                                    </div>

                                    <div class="input-group mt-1">
                                        <div class="input-group-prepend">
                                            <button type="button" class="input-group-text admin-file-manager " data-input="icon_record" data-preview="holder">
                                                <i class="fa fa-upload"></i>
                                            </button>
                                        </div>
                                        <input type="text" name="sub_categories[record][icon]" id="icon_record" class="form-control" placeholder="{{ trans('admin/main.icon') }}"/>
                                    </div>

                                    <div class="input-group mt-1">
                                        <div class="input-group-prepend">
                                            <button type="button" class="input-group-text admin-file-manager " data-input="cover_image_record" data-preview="holder">
                                                <i class="fa fa-upload"></i>
                                            </button>
                                        </div>
                                        <input type="text" name="sub_categories[record][cover_image]" id="cover_image_record" class="form-control" placeholder="{{ trans('admin/main.cover_image') }}"/>
                                    </div>

                                    <div class="row">
                                        <div class="col-6">
                                            <div class="input-group mt-1">
                                                <div class="input-group-prepend">
                                                    <button type="button" class="input-group-text admin-file-manager " data-input="icon2_record" data-preview="holder">
                                                        <i class="fa fa-upload"></i>
                                                    </button>
                                                </div>
                                                <input type="text" name="sub_categories[record][icon2]" id="icon2_record" class="form-control" value="" placeholder="{{ trans('update.icon2') }}"/>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="input-group mt-1 colorpickerinput">
                                                <input type="text" name="sub_categories[record][icon2_box_color]"
                                                       autocomplete="off"
                                                       class="form-control"
                                                       value=""
                                                       placeholder="{{ trans('update.icon2_box_color') }}"
                                                />
                                                <div class="input-group-append">
                                                    <div class="input-group-text">
                                                        <i class="fas fa-fill-drip"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="input-group mt-1">
                                        <div class="input-group-prepend">
                                            <button type="button" class="input-group-text admin-file-manager " data-input="overlay_image_record" data-preview="holder">
                                                <i class="fa fa-upload"></i>
                                            </button>
                                        </div>
                                        <input type="text" name="sub_categories[record][overlay_image]" id="overlay_image_record" class="form-control" value="" placeholder="{{ trans('update.overlay_image') }}"/>
                                    </div>

                                    <div class="input-group mt-1">
                                        <input type="text" name="sub_categories[record][bottom_seo_title]" class="form-control" value="" placeholder="{{ trans('update.bottom_seo_title') }}">
                                    </div>

                                    <div class="input-group mt-1">
                                        <textarea name="sub_categories[record][bottom_seo_content]" class="form-control" rows="5" placeholder="{{ trans('update.bottom_seo_content') }}"></textarea>
                                    </div>

                                    <div class="form-group custom-switches-stacked mb-0">
                                        <label class="custom-switch pl-0">
                                            <input type="hidden" name="sub_categories[record][enable]" value="">
                                            <input type="checkbox" name="sub_categories[record][enable]" id="enableSwitch_record" value="on" class="custom-switch-input"/>
                                            <span class="custom-switch-indicator"></span>
                                            <label class="custom-switch-description mb-0 cursor-pointer" for="enableSwitch_record">{{ trans('admin/main.active') }}</label>
                                        </label>
                                    </div>

                                </div>
                            </li>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts_bottom')
    <script src="/assets/default/vendors/sortable/jquery-ui.min.js"></script>
    <script src="/assets/admin/vendor/bootstrap-colorpicker/bootstrap-colorpicker.min.js"></script>

    <script src="/assets/admin/js/parts/categories.min.js"></script>
@endpush
