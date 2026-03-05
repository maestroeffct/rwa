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
                rows="3"
                hint="{{ trans('update.suggested_about_120_characters') }}"
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

    <div class="col-12 col-lg-6 mt-20">

        {{-- Checked Items --}}
        <div class="p-16 rounded-16 border-gray-200 ">

            <x-landingBuilder-addable-text-input
                title="{{ trans('update.checked_items') }}"
                inputLabel="{{ trans('public.title') }}"
                inputName="contents[checked_items][record]"
                :items="(!empty($contents['checked_items'])) ? $contents['checked_items'] : []"
                className=""
                titleClassName="text-dark"
            />
        </div>

        {{-- Image Content --}}
        <div class="p-16 rounded-16 border-gray-200 mt-20">
            <h3 class="font-14 mb-24">{{ trans('update.images') }}</h3>

            <x-landingBuilder-file
                label="{{ trans('update.main_image') }}"
                name="contents[image_content][main_image]"
                value="{{ (!empty($contents['image_content']) and !empty($contents['image_content']['main_image'])) ? $contents['image_content']['main_image'] : '' }}"
                placeholder="{{ (!empty($contents['image_content']) and !empty($contents['image_content']['main_image'])) ? getFileNameByPath($contents['image_content']['main_image']) : '' }}"
                hint="{{ trans('update.preferred_size') }} 640x640px"
                icon="export"
                accept="image/*"
                className=""
            />

            <x-landingBuilder-file
                label="{{ trans('update.overlay_image') }}"
                name="contents[image_content][overlay_image]"
                value="{{ (!empty($contents['image_content']) and !empty($contents['image_content']['overlay_image'])) ? $contents['image_content']['overlay_image'] : '' }}"
                placeholder="{{ (!empty($contents['image_content']) and !empty($contents['image_content']['overlay_image'])) ? getFileNameByPath($contents['image_content']['overlay_image']) : '' }}"
                hint="{{ trans('update.preferred_size') }} 120x480px"
                icon="export"
                accept="image/*"
                className="mb-0"
            />
        </div>

    </div>{{-- End Col --}}

</div>{{-- End Row --}}
