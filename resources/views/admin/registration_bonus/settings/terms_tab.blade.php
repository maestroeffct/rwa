@php
    $termsSetting = $settings->where('name', \App\Models\Setting::$registrationBonusTermsSettingsName)->first();

    $termsValue = (!empty($termsSetting) and !empty($termsSetting->translate($selectedLocale))) ? $termsSetting->translate($selectedLocale)->value : null;

    if (!empty($termsValue)) {
        $termsValue = json_decode($termsValue, true);
    }
@endphp


<form action="{{ getAdminPanelUrl('/registration_bonus/settings') }}" method="post">
    {{ csrf_field() }}
    <input type="hidden" name="page" value="general">
    <input type="hidden" name="name" value="{{ \App\Models\Setting::$registrationBonusTermsSettingsName }}">

    <div class="row">
        <div class="col-12 col-md-6">
            @if(!empty(getGeneralSettings('content_translate')))
                <div class="form-group">
                    <label class="input-label">{{ trans('auth.language') }}</label>
                    <select name="locale" class="form-control js-edit-content-locale">
                        @foreach($userLanguages as $lang => $language)
                            <option value="{{ $lang }}" @if(mb_strtolower(request()->get('locale', (!empty($termsValue) and !empty($termsValue['locale'])) ? $termsValue['locale'] : app()->getLocale())) == mb_strtolower($lang)) selected @endif>{{ $language }}</option>
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


            <div class="form-group mt-15">
                <label class="input-label">{{ trans('public.image') }}</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <button type="button" class="input-group-text admin-file-manager" data-input="term_image" data-preview="holder">
                            <x-iconsax-lin-export class="icons text-gray-500" width="18px" height="18px"/>
                        </button>
                    </div>
                    <input type="text" name="value[term_image]" id="term_image" value="{{ (!empty($termsValue) and !empty($termsValue['term_image'])) ? $termsValue['term_image'] : old('term_image') }}" class="form-control @error('image')  is-invalid @enderror"/>
                    <div class="input-group-append">
                        <button type="button" class="input-group-text admin-file-view" data-input="term_image">
                            <x-iconsax-lin-eye class="icons text-gray-500" width="18px" height="18px"/>
                        </button>
                    </div>
                </div>
            </div>

            <div id="addAccountTypes">

              <div class="d-flex align-items-center justify-content-between mb-6">
                <h4 class="d-block font-16">{{ trans('navbar.items') }}</h4>

                 <button type="button" data-parent="auth_theme_sliders" data-main-row="authThemeSliderMainRow" class="btn btn-success add-btn">
                    <x-iconsax-lin-add class="icons text-white" width="20px" height="20px"/>
                    {{ trans('admin/main.add') }}
                </button>
            </div>



                @if(!empty($termsValue) and !empty($termsValue['items']))

                    @if(!empty($termsValue) and is_array($termsValue['items']))
                        @foreach($termsValue['items'] as $key => $item)
                        <div class="form-group p-3 border rounded-lg mb-3 mt-3 position-relative">
                                <div class="flex-grow-1">
                                    <div class="form-group mb-1">
                                        <label class="mb-1">{{ trans('admin/main.icon') }}</label>
                                        <div class="input-group">
                                <div class="input-group-prepend">
                                    <button type="button" class="input-group-text admin-file-manager" data-input="icon_record" data-preview="holder">
                                        <x-iconsax-lin-export class="icons text-gray-500" width="18px" height="18px"/>
                                    </button>
                                </div>
                                <input type="text" name="value[items][{{ $key }}][icon]" id="icon_{{ $key }}" value="{{ $item['icon'] ?? '' }}" class="form-control" />

                            </div>
                        </div>


                                    <div class="form-group mb-1">
                                        <label class="mb-1">{{ trans('admin/main.title') }}</label>
                                        <input type="text" name="value[items][{{ $key }}][title]"
                                               class="form-control"
                                               value="{{ $item['title'] ?? '' }}"
                                        />
                                    </div>

                                    <div class="form-group mb-1">
                                        <label class="mb-1">{{ trans('public.description') }}</label>
                                        <input type="text" name="value[items][{{ $key }}][description]"
                                               class="form-control"
                                               value="{{ $item['description'] ?? '' }}"
                                        />
                                    </div>
                                </div>
                                <button type="button" class="btn mr-4 p-0 remove-btn position-absolute" style="right: 10px; top: 10px;">
                                    <x-iconsax-lin-close-square class="icons text-danger" width="24px" height="24px"/>
                                </button>
                            </div>
                        @endforeach
                    @endif
                @endif
            </div>

        </div>
    </div>

    <button type="submit" class="btn btn-primary mt-1">{{ trans('admin/main.submit') }}</button>
</form>

<div class="main-row d-none">
    <div class="form-group p-3 border rounded-lg mb-3 mt-3 position-relative">
        <div class="flex-grow-1">

            <div class="form-group mb-1">
                <label class="mb-1">{{ trans('admin/main.icon') }}</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <button type="button" class="input-group-text admin-file-manager" data-input="icon_record" data-preview="holder">
                            <x-iconsax-lin-export class="icons text-gray-500" width="18px" height="18px"/>
                    </div>
                    <input type="text" name="value[items][record][icon]" id="icon_record" value="" class="form-control"/>
                </div>
            </div>

            <div class="form-group mb-1">
                <label class="mb-1">{{ trans('admin/main.title') }}</label>
                <input type="text" name="value[items][record][title]"
                       class="form-control"
                />
            </div>

            <div class="form-group mb-1">
                <label class="mb-1">{{ trans('public.description') }}</label>
                <input type="text" name="value[items][record][description]"
                       class="form-control"
                />
            </div>
        </div>
        <button type="button" class="btn p-0 remove-btn mr-4 position-absolute" style="right: 10px; top: 10px;">
            <x-iconsax-lin-close-square class="icons text-danger" width="24px" height="24px"/>
        </button>
    </div>
</div>

@push('scripts_bottom')
    <script src="/assets/admin/js/parts/settings/site_bank_accounts.min.js"></script>
@endpush
