<div class="row">
    <div class="col-12 col-md-6">

        @php
            $authenticationPageStyles = ['theme_1'];
        @endphp

        <div class="form-group mb-3">
            <label class="input-label">{{ trans("update.authentication_page_style") }}</label>
            <select name="contents[authentication_pages][style]" class="form-control">
                @foreach($authenticationPageStyles as $styleName)
                    <option value="{{ $styleName }}" {{ (!empty($themeContents) and !empty($themeContents['authentication_pages']) and !empty($themeContents['authentication_pages']['style']) and $themeContents['authentication_pages']['style'] == $styleName) ? 'selected' : '' }}>{{ trans("update.{$styleName}") }}</option>
                @endforeach
            </select>
        </div>


        <div class="form-group">
            <label class="input-label mb-0">{{ trans('update.slider_background_image') }}</label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <button type="button" class="input-group-text admin-file-manager" data-input="auth_theme_slider_background_image" data-preview="holder">
                        <x-iconsax-lin-export class="icons text-gray-500" width="18px" height="18px"/>
                    </button>
                </div>
                <input type="text" name="contents[authentication_pages][slider_background_image]" required id="auth_theme_slider_background_image" value="{{ (!empty($themeContents) and !empty($themeContents['authentication_pages']) and !empty($themeContents['authentication_pages']['slider_background_image'])) ? $themeContents['authentication_pages']['slider_background_image'] : '' }}" class="form-control br-0" accept="image/*"/>

                <div class="input-group-append">
                    <button type="button" class="input-group-text admin-file-view" data-input="auth_theme_slider_background_image">
                        <x-iconsax-lin-eye class="icons text-gray-500" width="18px" height="18px"/>
                    </button>
                </div>
            </div>

            <div class="mt-1 font-12 text-gray-500">{{ trans('update.slider_background_image_hint') }}</div>
        </div>


        <div id="auth_theme_sliders" class="ml-0">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h4 class="d-block font-16">{{ trans('update.slider_content') }}</h4>

                <button type="button" data-parent="auth_theme_sliders" data-main-row="authThemeSliderMainRow" class="btn btn-success add-btn">
                    <x-iconsax-lin-add class="icons text-white" width="20px" height="20px"/>
                    {{ trans('admin/main.add') }}
                </button>
            </div>

            @if(!empty($themeContents) and !empty($themeContents['authentication_pages']) and !empty($themeContents['authentication_pages']['slider_contents']))
                @foreach($themeContents['authentication_pages']['slider_contents'] as $sliderKey => $sliderValue)
                    <div class="form-group p-3 border rounded-lg mb-3 mt-3 position-relative">
                        <button type="button" class="btn remove-btn position-absolute" style="right: 10px; top: 10px;">
                            <x-iconsax-lin-close-square class="icons text-danger" width="24px" height="24px"/>
                        </button>

                        <label class="input-label">{{ trans('admin/main.title') }}</label>
                        <div class="input-group">
                            <input type="text" name="contents[authentication_pages][slider_contents][{{ $sliderKey }}][title]" required
                                   class="form-control w-auto flex-grow-1"
                                   placeholder="{{ trans('admin/main.choose_title') }}"
                                   value="{{ !empty($sliderValue['title']) ? $sliderValue['title'] : '' }}"
                            />
                        </div>

                        <div class="form-group mb-0 mt-2">
                            <label class="input-label mb-0">{{ trans('admin/main.subtitle') }}</label>
                            <input type="text" name="contents[authentication_pages][slider_contents][{{ $sliderKey }}][subtitle]" required
                                   class="form-control w-100 flex-grow-1"
                                   placeholder="{{ trans('admin/main.subtitle') }}"
                                   value="{{ !empty($sliderValue['subtitle']) ? $sliderValue['subtitle'] : '' }}"
                            />
                        </div>

                        <div class="form-group mb-1 mt-2">
                            <label class="input-label mb-0">{{ trans('admin/main.image') }}</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <button type="button" class="input-group-text admin-file-manager" data-input="auth_theme_slider_image_{{ $sliderKey }}" data-preview="holder">
                                        <x-iconsax-lin-export class="icons text-gray-500" width="18px" height="18px"/>
                                    </button>
                                </div>
                                <input type="text" name="contents[authentication_pages][slider_contents][{{ $sliderKey }}][image]" required id="auth_theme_slider_image_{{ $sliderKey }}" class="form-control br-0" placeholder="{{ trans('update.auth_theme_slider_image_placeholder') }}" value="{{ !empty($sliderValue['image']) ? $sliderValue['image'] : '' }}"/>
                                <div class="input-group-append">
                                    <button type="button" class="input-group-text admin-file-view" data-input="auth_theme_slider_image_{{ $sliderKey }}">
                                        <x-iconsax-lin-eye class="icons text-gray-500" width="18px" height="18px"/>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>

      

    </div>
</div>
