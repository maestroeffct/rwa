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


            <x-landingBuilder-switch
                label="{{ trans('update.enable_component') }}"
                id="enable"
                name="enable"
                checked="{{ !!($landingComponent->enable) }}"
                hint=""
                className="mb-0"
            />
        </div>

        {{-- Main Content --}}
        <div class="p-16 rounded-16 border-gray-200 mt-20">
            <h3 class="font-14 mb-24">{{ trans('update.main_content') }}</h3>


            <x-landingBuilder-input
                label="{{ trans('update.pre_title') }}"
                name="contents[main_content][pre_title]"
                value="{{ (!empty($contents['main_content']) and !empty($contents['main_content']['pre_title'])) ? $contents['main_content']['pre_title'] : '' }}"
                placeholder=""
                hint=""
                className=""
            />

            <x-landingBuilder-input
                label="{{ trans('public.title') }}"
                name="contents[main_content][title]"
                value="{{ (!empty($contents['main_content']) and !empty($contents['main_content']['title'])) ? $contents['main_content']['title'] : '' }}"
                placeholder=""
                hint=""
                className=""
            />

            <x-landingBuilder-textarea
                label="{{ trans('public.description') }}"
                name="contents[main_content][description]"
                value="{{ (!empty($contents['main_content']) and !empty($contents['main_content']['description'])) ? $contents['main_content']['description'] : '' }}"
                placeholder=""
                rows="2"
                hint="{{ trans('update.suggested_about_120_characters') }}"
                className="mb-0"
            />
        </div>

        {{-- Title  --}}
        <div class="p-16 rounded-16 border-gray-200 mt-20">
            <h3 class="font-14 text-dark mb-24">{{ trans('update.cta') }} #1</h3>

            <x-landingBuilder-input
                label="{{ trans('public.title') }}"
                name="contents[cta_1][title]"
                value="{{ (!empty($contents['cta_1']) and !empty($contents['cta_1']['title'])) ? $contents['cta_1']['title'] : '' }}"
                placeholder=""
                hint=""
                className=""
            />

            <x-landingBuilder-textarea
                label="{{ trans('public.description') }}"
                name="contents[cta_1][description]"
                value="{{ (!empty($contents['cta_1']) and !empty($contents['cta_1']['description'])) ? $contents['cta_1']['description'] : '' }}"
                placeholder=""
                rows="2"
                hint="{{ trans('update.suggested_about_120_characters') }}"
                className=""
            />

            <x-landingBuilder-icons-select
                label="{{ trans('update.icon') }}"
                name="contents[cta_1][icon]"
                value="{{ (!empty($contents['cta_1']) and !empty($contents['cta_1']['icon'])) ? $contents['cta_1']['icon'] : '' }}"
                placeholder="{{ trans('update.search_icons') }}"
                hint=""
                selectClassName="js-icons-select2"
                className=""
            />

            <x-landingBuilder-input
                label="{{ trans('update.link_title') }}"
                name="contents[cta_1][link_title]"
                value="{{ (!empty($contents['cta_1']) and !empty($contents['cta_1']['link_title'])) ? $contents['cta_1']['link_title'] : '' }}"
                placeholder=""
                hint=""
                className=""
            />

            <x-landingBuilder-input
                label="{{ trans('panel.url') }}"
                name="contents[cta_1][url]"
                value="{{ (!empty($contents['cta_1']) and !empty($contents['cta_1']['url'])) ? $contents['cta_1']['url'] : '' }}"
                placeholder=""
                hint=""
                icon="link-1"
                className=""
            />

            <h5 class="font-14 text-gray-500 mb-24 mt-16">{{ trans('update.confirmation_section') }}</h5>

            <x-landingBuilder-input
                label="{{ trans('public.title') }}"
                name="contents[cta_1_confirmation_section][title]"
                value="{{ (!empty($contents['cta_1_confirmation_section']) and !empty($contents['cta_1_confirmation_section']['title'])) ? $contents['cta_1_confirmation_section']['title'] : '' }}"
                placeholder=""
                hint=""
                className=""
            />

            <x-landingBuilder-input
                label="{{ trans('update.subtitle') }}"
                name="contents[cta_1_confirmation_section][subtitle]"
                value="{{ (!empty($contents['cta_1_confirmation_section']) and !empty($contents['cta_1_confirmation_section']['subtitle'])) ? $contents['cta_1_confirmation_section']['subtitle'] : '' }}"
                placeholder=""
                hint=""
                className=""
            />

            <x-landingBuilder-file
                label="{{ trans('update.image') }} #1"
                name="contents[cta_1_confirmation_section][image_1]"
                value="{{ (!empty($contents['cta_1_confirmation_section']) and !empty($contents['cta_1_confirmation_section']['image_1'])) ? $contents['cta_1_confirmation_section']['image_1'] : '' }}"
                placeholder="{{ (!empty($contents['cta_1_confirmation_section']) and !empty($contents['cta_1_confirmation_section']['image_1'])) ? getFileNameByPath($contents['cta_1_confirmation_section']['image_1']) : '' }}"
                hint="{{ trans('update.preferred_size') }} 40x40px"
                icon="export"
                accept="image/*"
                className=""
            />

            <x-landingBuilder-file
                label="{{ trans('update.image') }} #2"
                name="contents[cta_1_confirmation_section][image_2]"
                value="{{ (!empty($contents['cta_1_confirmation_section']) and !empty($contents['cta_1_confirmation_section']['image_2'])) ? $contents['cta_1_confirmation_section']['image_2'] : '' }}"
                placeholder="{{ (!empty($contents['cta_1_confirmation_section']) and !empty($contents['cta_1_confirmation_section']['image_2'])) ? getFileNameByPath($contents['cta_1_confirmation_section']['image_2']) : '' }}"
                hint="{{ trans('update.preferred_size') }} 40x40px"
                icon="export"
                accept="image/*"
                className="mb-0"
            />


        </div>

    </div>{{-- End Col --}}

    <div class="col-12 col-lg-6 mt-20">

        {{-- Title  --}}
        <div class="p-16 rounded-16 border-gray-200 ">
            <h3 class="font-14 text-dark mb-24">{{ trans('update.cta') }} #2</h3>

            <x-landingBuilder-input
                label="{{ trans('public.title') }}"
                name="contents[cta_2][title]"
                value="{{ (!empty($contents['cta_2']) and !empty($contents['cta_2']['title'])) ? $contents['cta_2']['title'] : '' }}"
                placeholder=""
                hint=""
                className=""
            />

            <x-landingBuilder-textarea
                label="{{ trans('public.description') }}"
                name="contents[cta_2][description]"
                value="{{ (!empty($contents['cta_2']) and !empty($contents['cta_2']['description'])) ? $contents['cta_2']['description'] : '' }}"
                placeholder=""
                rows="2"
                hint="{{ trans('update.suggested_about_120_characters') }}"
                className=""
            />


            <x-landingBuilder-icons-select
                label="{{ trans('update.icon') }}"
                name="contents[cta_2][icon]"
                value="{{ (!empty($contents['cta_2']) and !empty($contents['cta_2']['icon'])) ? $contents['cta_2']['icon'] : '' }}"
                placeholder="{{ trans('update.search_icons') }}"
                hint=""
                selectClassName="js-icons-select2"
                className=""
            />

            <x-landingBuilder-input
                label="{{ trans('update.link_title') }}"
                name="contents[cta_2][link_title]"
                value="{{ (!empty($contents['cta_2']) and !empty($contents['cta_2']['link_title'])) ? $contents['cta_2']['link_title'] : '' }}"
                placeholder=""
                hint=""
                className=""
            />

            <x-landingBuilder-input
                label="{{ trans('panel.url') }}"
                name="contents[cta_2][url]"
                value="{{ (!empty($contents['cta_2']) and !empty($contents['cta_2']['url'])) ? $contents['cta_2']['url'] : '' }}"
                placeholder=""
                hint=""
                icon="link-1"
                className=""
            />

            <h5 class="font-14 text-gray-500 mb-24 mt-16">{{ trans('update.confirmation_section') }}</h5>

            <x-landingBuilder-input
                label="{{ trans('public.title') }}"
                name="contents[cta_2_confirmation_section][title]"
                value="{{ (!empty($contents['cta_2_confirmation_section']) and !empty($contents['cta_2_confirmation_section']['title'])) ? $contents['cta_2_confirmation_section']['title'] : '' }}"
                placeholder=""
                hint=""
                className=""
            />

            <x-landingBuilder-input
                label="{{ trans('update.subtitle') }}"
                name="contents[cta_2_confirmation_section][subtitle]"
                value="{{ (!empty($contents['cta_2_confirmation_section']) and !empty($contents['cta_2_confirmation_section']['subtitle'])) ? $contents['cta_2_confirmation_section']['subtitle'] : '' }}"
                placeholder=""
                hint=""
                className=""
            />


            <x-landingBuilder-file
                label="{{ trans('update.image') }}"
                name="contents[cta_2_confirmation_section][image]"
                value="{{ (!empty($contents['cta_2_confirmation_section']) and !empty($contents['cta_2_confirmation_section']['image'])) ? $contents['cta_2_confirmation_section']['image'] : '' }}"
                placeholder="{{ (!empty($contents['cta_2_confirmation_section']) and !empty($contents['cta_2_confirmation_section']['image'])) ? getFileNameByPath($contents['cta_2_confirmation_section']['image']) : '' }}"
                hint="{{ trans('update.preferred_size') }} 40x40px"
                icon="export"
                accept="image/*"
                className="mb-0"
            />

        </div>

    </div>{{-- End Col --}}

</div>{{-- End Row --}}
