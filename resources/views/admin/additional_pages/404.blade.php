@extends('admin.layouts.app')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{ trans("admin/main.{$name}_page_setting") }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ getAdminPanelUrl() }}">{{ trans('admin/main.dashboard') }}</a></div>
                <div class="breadcrumb-item">404</div>
            </div>
        </div>

        <div class="section-body">

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">

                            <ul class="nav nav-pills" id="myTab3" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link {{ ($name == "404") ? 'active' : '' }}" href="{{ getAdminPanelUrl("/additional_page/404") }}">{{ trans('admin/main.error_404') }}</a>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link {{ ($name == "500") ? 'active' : '' }}" href="{{ getAdminPanelUrl("/additional_page/500") }}">{{ trans('admin/main.error_500') }}</a>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link {{ ($name == "419") ? 'active' : '' }}" href="{{ getAdminPanelUrl("/additional_page/419") }}">{{ trans('admin/main.error_419') }}</a>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link {{ ($name == "403") ? 'active' : '' }}" href="{{ getAdminPanelUrl("/additional_page/403") }}">{{ trans('admin/main.error_403') }}</a>
                                </li>
                            </ul>

                            <div class="tab-content" id="myTabContent2">
                                <div class="tab-pane mt-3 fade show active">
                                    <form action="{{ getAdminPanelUrl() }}/additional_page/{{ $name }}" method="post">
                                        {{ csrf_field() }}

                                        <div class="row">

                                            <div class="col-12 col-md-6">

                                                @if(!empty(getGeneralSettings('content_translate')))
                                                    <div class="form-group">
                                                        <label class="input-label">{{ trans('auth.language') }}</label>
                                                        <select name="locale" class="form-control js-edit-content-locale">
                                                            @foreach($userLanguages as $lang => $language)
                                                                <option value="{{ $lang }}" @if(mb_strtolower(request()->get('locale', $selectedLocal)) == mb_strtolower($lang)) selected @endif>{{ $language }}</option>
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
                                                    <label class="input-label">{{ trans("admin/main.{$name}_error_image") }}</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <button type="button" class="input-group-text admin-file-manager" data-input="image" data-preview="holder">
                                                                <i class="fa fa-chevron-up"></i>
                                                            </button>
                                                        </div>
                                                        <input type="text" name="value[image]" id="image" value="{{ (!empty($value) and !empty($value['image'])) ? $value['image'] : '' }}" class="form-control"/>
                                                    </div>
                                                </div>


                                                <div class="form-group">
                                                    <label class="input-label">{{ trans('admin/main.title') }}</label>
                                                    <input type="text" name="value[title]" value="{{ (!empty($value) and !empty($value['title'])) ? $value['title'] : '' }}" class="form-control"/>
                                                </div>

                                                <div class="form-group">
                                                    <label class="input-label">{{ trans('admin/main.description') }}</label>
                                                    <textarea name="value[description]" rows="5" class="form-control">{{ (!empty($value) and !empty($value['description'])) ? $value['description'] : '' }}</textarea>
                                                </div>

                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-6">
                                                            <label class="input-label mb-0">{{ trans('update.button_title') }}</label>
                                                            <input type="text" name="value[button][title]" class="form-control w-100 flex-grow-1" value="{{ (!empty($value) and !empty($value['button']) and !empty($value['button']['title'])) ? $value['button']['title'] : '' }}"/>
                                                        </div>

                                                        <div class="col-6">
                                                            <label class="input-label mb-0">{{ trans('update.button_link') }}</label>
                                                            <input type="text" name="value[button][link]" class="form-control w-100 flex-grow-1" value="{{ (!empty($value) and !empty($value['button']) and !empty($value['button']['link'])) ? $value['button']['link'] : '' }}"/>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="input-label">{{ trans("update.right_float_image") }}</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <button type="button" class="input-group-text admin-file-manager" data-input="image_right_float_image" data-preview="holder">
                                                                <i class="fa fa-chevron-up"></i>
                                                            </button>
                                                        </div>
                                                        <input type="text" name="value[right_float_image]" id="image_right_float_image" value="{{ (!empty($value) and !empty($value["right_float_image"])) ? $value["right_float_image"] : "" }}" class="form-control"/>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>

                                        <button type="submit" class="btn btn-primary">{{ trans('admin/main.save_change') }}</button>
                                    </form>
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

@endpush
