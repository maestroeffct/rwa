<form action="{{ getAdminPanelUrl('/forums/settings') }}" method="post">
    {{ csrf_field() }}
    <input type="hidden" name="name" value="{{ \App\Models\Setting::$forumsHomepageRevolverSettingsName }}">

    <div class="row">
        <div class="col-12 col-md-6">

            @if(!empty(getGeneralSettings('content_translate')))
                <div class="form-group">
                    <label class="input-label">{{ trans('auth.language') }}</label>
                    <select name="locale" class="form-control js-edit-content-locale">
                        @foreach($userLanguages as $lang => $language)
                            <option value="{{ $lang }}" @if(mb_strtolower(request()->get('locale', (!empty($settingValues) and !empty($settingValues['locale'])) ? $settingValues['locale'] : app()->getLocale())) == mb_strtolower($lang)) selected @endif>{{ $language }}</option>
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
                <label class="input-label">{{ trans('update.separator_image') }}</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <button type="button" class="input-group-text admin-file-manager" data-input="separator_image" data-preview="holder">
                            <i class="fa fa-chevron-up"></i>
                        </button>
                    </div>
                    <input type="text" name="value[separator_image]" id="separator_image" value="{{ (!empty($settingValues) and !empty($settingValues['separator_image'])) ? $settingValues['separator_image'] : old('separator_image') }}" class="form-control"/>
                </div>
            </div>

            <div id="homepageRevolverItems" class="ml-0">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <strong class="d-block">{{ trans('admin/main.items') }}</strong>

                    <button type="button" data-parent="homepageRevolverItems" data-main-row="revolverItemsMainRow" class="btn btn-success add-btn"><i class="fa fa-plus"></i> {{ trans('admin/main.add') }}</button>
                </div>

                @if(!empty($settingValues) and !empty($settingValues['revolver_items']))
                    @foreach($settingValues['revolver_items'] as $itemKey => $revolverValue)


                         <div class="form-group p-3 border rounded-lg mb-3 mt-3 position-relative">
                        <button type="button" class="btn remove-btn position-absolute" style="right: 10px; top: 10px;">
                            <x-iconsax-lin-close-square class="icons text-danger" width="24px" height="24px"/>
                        </button>

                        <label class="input-label">{{ trans('admin/main.title') }}</label>
                        <div class="input-group">
                            <input type="text" name="value[revolver_items][{{ $itemKey }}][title]" required
                                   class="form-control w-auto flex-grow-1"
                                   placeholder="{{ trans('admin/main.choose_title') }}"
                                   value="{{ $revolverValue['title'] ?? '' }}"
                            />
                        </div>

                        <div class="form-group mb-0 mt-2">
                            <label class="input-label mb-0">{{ trans('admin/main.link') }}</label>
                            <input type="text" name="value[revolver_items][{{ $itemKey }}][link]" required
                                   class="form-control w-100 flex-grow-1"
                                   placeholder="{{ trans('admin/main.link') }}"
                                   value="{{ $revolverValue['link'] ?? '' }}"
                            />
                        </div>

                    </div>
                    @endforeach
                @endif

            </div>

        </div>
    </div>

    <button type="submit" class="btn btn-primary mt-1">{{ trans('admin/main.submit') }}</button>
</form>


                  <div id="revolverItemsMainRow" class="form-group p-3 border rounded-lg mb-3 mt-3 position-relative d-none">
                        <button type="button" class="btn remove-btn position-absolute" style="right: 10px; top: 10px;">
                            <x-iconsax-lin-close-square class="icons text-danger" width="24px" height="24px"/>
                        </button>

                        <label class="input-label">{{ trans('admin/main.title') }}</label>
                        <div class="input-group">
                            <input type="text" name="value[revolver_items][record][title]" required
                                   class="form-control w-auto flex-grow-1"
                                   placeholder="{{ trans('admin/main.choose_title') }}"
                            />
                        </div>

                        <div class="form-group mb-0 mt-2">
                            <label class="input-label mb-0">{{ trans('admin/main.link') }}</label>
                            <input type="text" name="value[revolver_items][record][link]" required
                                   class="form-control w-100 flex-grow-1"
                                   placeholder="{{ trans('admin/main.link') }}"

                            />
                        </div>

                    </div>



@push('scripts_bottom')

    <script src="/assets/admin/js/parts/settings/cookie_settings.min.js"></script>
@endpush
