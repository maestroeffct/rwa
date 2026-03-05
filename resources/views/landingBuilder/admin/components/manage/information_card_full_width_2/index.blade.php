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
                label="{{ trans('update.reverse_direction') }}"
                id="reverse_direction"
                name="contents[reverse_direction]"
                checked="{{ !!(!empty($contents['reverse_direction']) and !empty($contents['reverse_direction']) == 'on') }}"
                hint=""
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
            <h3 class="font-14 mb-24">{{ trans('update.card_information') }}</h3>

            <x-landingBuilder-icons-select
                label="{{ trans('update.pre_title_icon') }}"
                name="contents[main_content][pre_title_icon]"
                value="{{ (!empty($contents['main_content']) and !empty($contents['main_content']['pre_title_icon'])) ? $contents['main_content']['pre_title_icon'] : '' }}"
                placeholder="{{ trans('update.search_icons') }}"
                hint=""
                selectClassName="js-icons-select2"
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
                rows="3"
                hint="{{ trans('update.suggested_about_120_characters') }}"
                className=""
            />


            <x-landingBuilder-addable-text-input
                title="{{ trans('update.badges') }}"
                inputLabel="{{ trans('public.title') }}"
                inputName="contents[checked_items][record]"
                :items="(!empty($contents['checked_items'])) ? $contents['checked_items'] : []"
                className="mt-28"
                titleClassName="text-gray-500"
            />


            <x-landingBuilder-make-button
                title="{{ trans('update.button') }}"
                inputNamePrefix="contents[main_content][button]"
                :buttonData="(!empty($contents['main_content']) and !empty($contents['main_content']['button'])) ? $contents['main_content']['button'] : []"
                className="mt-24 mb-0"
            />

        </div>

    </div>{{-- End Col --}}

    <div class="col-12 col-lg-6 mt-20">

        <div class="p-16 rounded-16 border-gray-200">
            <h3 class="font-14 mb-24">{{ trans('update.images') }}</h3>


            <x-landingBuilder-file
                label="{{ trans('update.main_image') }}"
                name="contents[images][main_image]"
                value="{{ (!empty($contents['images']) and !empty($contents['images']['main_image'])) ? $contents['images']['main_image'] : '' }}"
                placeholder="{{ (!empty($contents['images']) and !empty($contents['images']['main_image'])) ? getFileNameByPath($contents['images']['main_image']) : '' }}"
                hint="{{ trans('update.preferred_size') }} 64x64px"
                icon="export"
                accept="image/*"
                className="mb-0"
            />

        </div>

    </div>{{-- End Col --}}

</div>{{-- End Row --}}
