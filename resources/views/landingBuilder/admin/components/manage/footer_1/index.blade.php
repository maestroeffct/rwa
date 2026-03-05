<div class="row">
    <div class="col-12 col-lg-6 mt-20">
        {{-- General Information --}}
        <div class="p-16 rounded-16 border-gray-200">
            <h3 class="font-14 mb-24">{{ trans('update.general_information') }}</h3>

            @include('design_1.panel.includes.locale.locale_select',[
                'itemRow' => !empty($landingComponent) ? $landingComponent : null,
                'withoutReloadLocale' => false,
                'extraClass' => ''
            ])

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

            <x-landingBuilder-switch
                    label="{{ trans('update.enable_component') }}"
                    id="enable"
                    name="enable"
                    checked="{{ !!($landingComponent->enable) }}"
                    hint=""
                    className="mb-0"
            />

        </div>

        <div class="p-16 rounded-16 border-gray-200 mt-20">
            <h3 class="font-14 mb-24">{{ trans('update.newsletter') }}</h3>

            <x-landingBuilder-switch
                label="{{ trans('update.enable') }}"
                id="newsletter_enable"
                name="contents[newsletter][enable]"
                checked="{{ !!(!empty($contents['newsletter']) and !empty($contents['newsletter']['enable'])) }}"
                hint=""
                className=""
            />


            <x-landingBuilder-input
                    label="{{ trans('public.title') }}"
                    name="contents[newsletter][title]"
                    value="{{ (!empty($contents['newsletter']) and !empty($contents['newsletter']['title'])) ? $contents['newsletter']['title'] : '' }}"
                    placeholder=""
                    hint=""
                    icon=""
                    className=""
            />

            <x-landingBuilder-input
                    label="{{ trans('update.subtitle') }}"
                    name="contents[newsletter][subtitle]"
                    value="{{ (!empty($contents['newsletter']) and !empty($contents['newsletter']['subtitle'])) ? $contents['newsletter']['subtitle'] : '' }}"
                    placeholder=""
                    hint=""
                    icon=""
                    className=""
            />

            <x-landingBuilder-file
                label="{{ trans('update.emoji') }}"
                name="contents[newsletter][emoji]"
                value="{{ (!empty($contents['newsletter']) and !empty($contents['newsletter']['emoji'])) ? $contents['newsletter']['emoji'] : '' }}"
                placeholder="{{ (!empty($contents['newsletter']) and !empty($contents['newsletter']['emoji'])) ? getFileNameByPath($contents['newsletter']['emoji']) : '' }}"
                hint="{{ trans('update.footer_newsletter_emoji_input_hint') }}"
                icon="export"
                accept="image/*"
                className=""
            />

            <x-landingBuilder-input
                label="{{ trans('update.button_text') }}"
                name="contents[newsletter][button_text]"
                value="{{ (!empty($contents['newsletter']) and !empty($contents['newsletter']['button_text'])) ? $contents['newsletter']['button_text'] : '' }}"
                placeholder=""
                hint=""
                icon=""
                className="mb-0"
            />
        </div>

        <div class="p-16 rounded-16 border-gray-200 mt-20">
            <h3 class="font-14 mb-24">{{ trans('update.cta_section') }}</h3>

            <x-landingBuilder-input
                label="{{ trans('update.pre_title') }}"
                name="contents[cta][pre_title]"
                value="{{ (!empty($contents['cta']) and !empty($contents['cta']['pre_title'])) ? $contents['cta']['pre_title'] : '' }}"
                placeholder=""
                hint=""
                icon=""
                className=""
            />

            <x-landingBuilder-file
                label="{{ trans('update.pre_title_emoji') }}"
                name="contents[cta][emoji]"
                value="{{ (!empty($contents['cta']) and !empty($contents['cta']['emoji'])) ? $contents['cta']['emoji'] : '' }}"
                placeholder="{{ (!empty($contents['cta']) and !empty($contents['cta']['emoji'])) ? getFileNameByPath($contents['cta']['emoji']) : '' }}"
                hint="{{ trans('update.footer_pre_title_emoji_input_hint') }}"
                icon="export"
                accept="image/*"
                className=""
            />

            <x-landingBuilder-input
                    label="{{ trans('public.title') }}"
                    name="contents[cta][title]"
                    value="{{ (!empty($contents['cta']) and !empty($contents['cta']['title'])) ? $contents['cta']['title'] : '' }}"
                    placeholder=""
                    hint=""
                    icon=""
                    className=""
            />

            <x-landingBuilder-make-button
                title="{{ trans('update.button') }}"
                inputNamePrefix="contents[cta][button]"
                :buttonData="(!empty($contents['cta']) and !empty($contents['cta']['button'])) ? $contents['cta']['button'] : []"
                className="mt-24 mb-0"
            />
        </div>

        <div class="p-16 rounded-16 border-gray-200 mt-20">
            <h3 class="font-14 mb-24">{{ trans('update.contact_info') }}</h3>

            <x-landingBuilder-input
                label="{{ trans('update.section_title') }}"
                name="contents[contact][section_title]"
                value="{{ (!empty($contents['contact']) and !empty($contents['contact']['section_title'])) ? $contents['contact']['section_title'] : '' }}"
                placeholder=""
                hint=""
                icon=""
                className=""
            />

            <x-landingBuilder-input
                label="{{ trans('update.address') }}"
                name="contents[contact][address]"
                value="{{ (!empty($contents['contact']) and !empty($contents['contact']['address'])) ? $contents['contact']['address'] : '' }}"
                placeholder=""
                hint=""
                icon="location"
                className=""
            />

            <x-landingBuilder-input
                label="{{ trans('public.phone') }}"
                name="contents[contact][phone]"
                value="{{ (!empty($contents['contact']) and !empty($contents['contact']['phone'])) ? $contents['contact']['phone'] : '' }}"
                placeholder=""
                hint=""
                icon="call-calling"
                className=""
            />

            <x-landingBuilder-input
                label="{{ trans('public.email') }}"
                name="contents[contact][email]"
                value="{{ (!empty($contents['contact']) and !empty($contents['contact']['email'])) ? $contents['contact']['email'] : '' }}"
                placeholder=""
                hint=""
                icon="sms"
                className=""
            />

            <x-landingBuilder-input
                label="{{ trans('public.mobile') }}"
                name="contents[contact][mobile]"
                value="{{ (!empty($contents['contact']) and !empty($contents['contact']['mobile'])) ? $contents['contact']['mobile'] : '' }}"
                placeholder=""
                hint=""
                icon="mobile"
                className="mb-0"
            />

        </div>

    </div>{{-- End Col --}}

    <div class="col-12 col-lg-6 mt-20">

        {{-- links --}}
        <div class="p-16 rounded-16 border-gray-200 ">
            <h3 class="font-14 mb-24">{{ trans('update.links') }} #1</h3>

            <x-landingBuilder-input
                label="{{ trans('update.section_title') }}"
                name="contents[links_1_section_title]"
                value="{{ (!empty($contents['links_1_section_title'])) ? $contents['links_1_section_title'] : '' }}"
                placeholder=""
                hint=""
                icon=""
                className="mb-24"
            />

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
                                @include('landingBuilder.admin.components.manage.footer_1.link_item',['inputNamePrefix' => "contents[specific_links][{$sKey}]", 'footerLinkData' => $footerLinkItemData])
                            </x-landingBuilder-accordion>
                        @endif
                    @endforeach
                @endif
            </x-landingBuilder-addable-accordions>
        </div>

        {{-- links 2 --}}
        <div class="p-16 rounded-16 border-gray-200 mt-20">
            <h3 class="font-14 mb-24">{{ trans('update.links') }} #2</h3>

            <x-landingBuilder-input
                label="{{ trans('update.section_title') }}"
                name="contents[links_2_section_title]"
                value="{{ (!empty($contents['links_2_section_title'])) ? $contents['links_2_section_title'] : '' }}"
                placeholder=""
                hint=""
                icon=""
                className="mb-24"
            />

            <x-landingBuilder-addable-accordions
                title="{{ trans('update.links') }}"
                addText="{{ trans('update.add_link') }}"
                className="mb-0"
                mainRow="js-footer-link-2-main-card"
            >
                @if(!empty($contents) and !empty($contents['specific_links_2']) and count($contents['specific_links_2']))
                    @foreach($contents['specific_links_2'] as $bKey => $footerLink2ItemData)
                        @if($bKey != 'record')
                            <x-landingBuilder-accordion
                                title="{{ (!empty($footerLink2ItemData) and !empty($footerLink2ItemData['title'])) ? $footerLink2ItemData['title'] : trans('update.new_link') }}"
                                id="link_2_{{ $bKey }}"
                                className=""
                                show=""
                            >
                                @include('landingBuilder.admin.components.manage.footer_1.link_item',['inputNamePrefix' => "contents[specific_links_2][{$bKey}]", 'footerLinkData' => $footerLink2ItemData])
                            </x-landingBuilder-accordion>
                        @endif
                    @endforeach
                @endif
            </x-landingBuilder-addable-accordions>
        </div>

        {{-- links 2 --}}
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


            <div class="form-group select2-bg-white">
                <label class="form-group-label bg-white">{{ trans('update.assign_social_media') }}</label>

                <select name="contents[social_media][]" class="js-make-select2 form-control bg-white" multiple="multiple">
                    <option value="">{{ trans('update.select_social_media') }}</option>

                    @foreach(['instagram', 'telegram', 'twitter', 'facebook', 'whatsapp'] as $social)
                        <option value="{{ $social }}" {{ (!empty($contents['social_media']) and is_array($contents['social_media']) and in_array($social, $contents['social_media'])) ? 'selected' : '' }}>{{ $social }}</option>
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
        @include('landingBuilder.admin.components.manage.footer_1.link_item', ['inputNamePrefix' => "contents[specific_links][record]"])
    </x-landingBuilder-accordion>
</div>


<div class="js-footer-link-2-main-card d-none">
    <x-landingBuilder-accordion
            title="{{ trans('update.new_link') }}"
            id="record"
            className=""
            show="true"
    >
        @include('landingBuilder.admin.components.manage.footer_1.link_item', ['inputNamePrefix' => "contents[specific_links_2][record]"])
    </x-landingBuilder-accordion>
</div>
