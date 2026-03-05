<div class="row">
    <div class="col-12 col-lg-6 mt-20">
        {{-- General Information --}}
        <div class="p-16 rounded-16 border-gray-200">
            <h3 class="font-14 mb-24">{{ trans('update.general_information') }}</h3>

            @include('design_1.panel.includes.locale.locale_select',[
                'itemRow' => !empty($footerItem) ? $footerItem : null,
                'withoutReloadLocale' => false,
                'extraClass' => ''
            ])

            <x-landingBuilder-input
                label="{{ trans('public.title') }}"
                name="title"
                value="{{ (!empty($footerItem->translate($locale)) and !empty($footerItem->translate($locale)->title)) ? $footerItem->translate($locale)->title : '' }}"
                placeholder=""
                hint=""
                icon=""
                className=""
            />

            <x-landingBuilder-select
                label="{{ trans('update.background_color') }}"
                name="contents[background_color]"
                value="{{ (!empty($contents['background_color'])) ? $contents['background_color'] : '' }}"
                :items="['primary', 'secondary']"
                hint=""
                className=""
                selectClassName=""
                changeActionEls=""
            />

            <x-landingBuilder-file
                label="{{ trans('update.background') }}"
                name="contents[background]"
                value="{{ !empty($contents['background']) ? $contents['background'] : '' }}"
                placeholder="{{ !empty($contents['background']) ? getFileNameByPath($contents['background']) : '' }}"
                hint="{{ trans('update.preferred_size') }} 1600x520px"
                icon="export"
                accept="image/*"
                className=""
            />

            <x-landingBuilder-file
                label="{{ trans('update.dark_mode_background') }} ({{ trans('public.optional') }})"
                name="contents[dark_mode_background]"
                value="{{ !empty($contents['dark_mode_background']) ? $contents['dark_mode_background'] : '' }}"
                placeholder="{{ !empty($contents['dark_mode_background']) ? getFileNameByPath($contents['dark_mode_background']) : '' }}"
                hint="{{ trans('update.preferred_size') }} 1600x520px"
                icon="export"
                accept="image/*"
                className=""
            />

        </div>

        {{-- Main Content --}}
        <div class="p-16 rounded-16 border-gray-200 mt-20">
            <h3 class="font-14 mb-24">{{ trans('update.main_content') }}</h3>


            <x-landingBuilder-input
                label="{{ trans('public.title') }}"
                name="contents[title]"
                value="{{ (!empty($contents['title'])) ? $contents['title'] : '' }}"
                placeholder=""
                hint=""
                icon=""
                className=""
            />

            <x-landingBuilder-textarea
                label="{{ trans('admin/main.description') }}"
                name="contents[description]"
                value="{{ (!empty($contents['description'])) ? $contents['description'] : '' }}"
                placeholder=""
                rows="3"
                hint="{{ trans('update.suggested_about_120_characters') }}"
                className=""
            />

            <x-landingBuilder-switch
                label="{{ trans('update.enable_logo') }}"
                id="enable_logo"
                name="contents[enable_logo]"
                checked="{{ !!(!empty($contents['enable_logo'])) }}"
                hint=""
                className="custom-switch-design-2"
            />

            <x-landingBuilder-switch
                label="{{ trans('update.enable_back_to_top') }}"
                id="enable_back_to_top"
                name="contents[enable_back_to_top]"
                checked="{{ !!(!empty($contents['enable_back_to_top'])) }}"
                hint=""
                className="custom-switch-design-2 mb-0"
            />

        </div>

    </div>{{-- End Col --}}

    <div class="col-12 col-lg-6 mt-20">

        {{-- links --}}
        <div class="p-16 rounded-16 border-gray-200 ">
            <x-landingBuilder-addable-accordions
                title="{{ trans('update.links') }}"
                addText="{{ trans('update.add_link') }}"
                className="mb-0"
                mainRow="js-footer-link-main-card"
            >
                @if(!empty($contents) and !empty($contents['specific_links']) and count($contents['specific_links']))
                    @foreach($contents['specific_links'] as $sKey => $footerLinkItemData)
                        @if($sKey != 'record')
                            <x-landingBuilder-accordion
                                title="{{ (!empty($footerLinkItemData) and !empty($footerLinkItemData['title'])) ? $footerLinkItemData['title'] : trans('update.new_link') }}"
                                id="link_1_{{ $sKey }}"
                                className=""
                                show=""
                            >
                                @include('admin.theme.footers.components.footer_2.link_item',['inputNamePrefix' => "contents[specific_links][{$sKey}]", 'footerLinkData' => $footerLinkItemData])
                            </x-landingBuilder-accordion>
                        @endif
                    @endforeach
                @endif
            </x-landingBuilder-addable-accordions>
        </div>


        {{-- Bottom --}}
        <div class="p-16 rounded-16 border-gray-200 mt-20">
            <h3 class="font-14 mb-24">{{ trans('update.bottom') }}</h3>

            <x-landingBuilder-input
                label="{{ trans('update.copyright_text') }}"
                name="contents[copyright_text]"
                value="{{ (!empty($contents['copyright_text'])) ? $contents['copyright_text'] : '' }}"
                placeholder=""
                hint=""
                icon=""
                className="mb-24"
            />


            @php
                $socials = getSocials();
                if (!empty($socials) and count($socials)) {
                    $socials = collect($socials)->sortBy('order')->toArray();
                }
            @endphp

            <div class="form-group select2-bg-white">
                <label class="form-group-label bg-white">{{ trans('update.assign_social_media') }}</label>

                <select name="contents[social_media][]" class="js-make-select2 form-control bg-white" multiple="multiple">
                    <option value="">{{ trans('update.select_social_media') }}</option>

                    @foreach($socials as $socialKey => $socialValue)
                        <option value="{{ $socialKey }}" {{ (!empty($contents['social_media']) and is_array($contents['social_media']) and in_array($socialKey, $contents['social_media'])) ? 'selected' : '' }}>{{ $socialValue['title'] }}</option>
                    @endforeach
                </select>
            </div>


        </div>

    </div>{{-- End Col --}}

</div>{{-- End Row --}}


<div class="js-footer-link-main-card d-none">
    <x-landingBuilder-accordion
        title="{{ trans('update.new_link') }}"
        id="record"
        className=""
        show="true"
    >
        @include('admin.theme.footers.components.footer_2.link_item', ['inputNamePrefix' => "contents[specific_links][record]"])
    </x-landingBuilder-accordion>
</div>
