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
                hint="{{ trans('update.preferred_size') }} 1920x840px"
                icon="export"
                accept="image/*"
                className=""
            />

            <x-landingBuilder-file
                label="{{ trans('update.floating_icon') }} #1"
                name="contents[floating_icon_1]"
                value="{{ !empty($contents['floating_icon_1']) ? $contents['floating_icon_1'] : '' }}"
                placeholder="{{ !empty($contents['floating_icon_1']) ? getFileNameByPath($contents['floating_icon_1']) : '' }}"
                hint="{{ trans('update.preferred_size') }} 72x88px"
                icon="export"
                accept="image/*"
                className=""
            />

            <x-landingBuilder-file
                label="{{ trans('update.floating_icon') }} #2"
                name="contents[floating_icon_2]"
                value="{{ !empty($contents['floating_icon_2']) ? $contents['floating_icon_2'] : '' }}"
                placeholder="{{ !empty($contents['floating_icon_2']) ? getFileNameByPath($contents['floating_icon_2']) : '' }}"
                hint="{{ trans('update.preferred_size') }} 208x208px"
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

    </div>{{-- End Col --}}

    <div class="col-12 col-lg-6 mt-20">
        <div class="p-16 rounded-16 border-gray-200">
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
                label="{{ trans('update.subtitle') }}"
                name="contents[main_content][subtitle]"
                value="{{ (!empty($contents['main_content']) and !empty($contents['main_content']['subtitle'])) ? $contents['main_content']['subtitle'] : '' }}"
                placeholder=""
                rows="3"
                hint="{{ trans('update.suggested_about_120_characters') }}"
                className=""
            />

            <h3 class="font-14 my-24 text-gray-500">{{ trans('update.statistics') }}</h3>

            <x-landingBuilder-input
                label="{{ trans('public.title') }}"
                name="contents[statistics][title]"
                value="{{ (!empty($contents['statistics']) and !empty($contents['statistics']['title'])) ? $contents['statistics']['title'] : '' }}"
                placeholder=""
                hint=""
                className=""
            />

            <x-landingBuilder-input
                label="{{ trans('update.subtitle') }}"
                name="contents[statistics][subtitle]"
                value="{{ (!empty($contents['statistics']) and !empty($contents['statistics']['subtitle'])) ? $contents['statistics']['subtitle'] : '' }}"
                placeholder=""
                hint=""
                className=""
            />


            <x-landingBuilder-make-button
                title="{{ trans('update.button') }}"
                inputNamePrefix="contents[main_content][button]"
                :buttonData="(!empty($contents['main_content']) and !empty($contents['main_content']['button'])) ? $contents['main_content']['button'] : []"
                className="mt-24 mb-0"
            />

        </div>

    </div>{{-- End Col --}}

</div>{{-- End Row --}}
