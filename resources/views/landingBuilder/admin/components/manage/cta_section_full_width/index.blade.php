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
                hint="{{ trans('update.preferred_size') }} 1920x780px"
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

        {{-- Title  --}}
        <div class="p-16 rounded-16 border-gray-200 mt-20">
            <h3 class="font-14 text-dark">{{ trans('public.title') }}</h3>

            <h5 class="font-14 text-gray-500 mb-24 mt-16">{{ trans('update.first_line') }}</h5>

            <x-landingBuilder-input
                label="{{ trans('update.text') }} #1"
                name="contents[first_line][text_1]"
                value="{{ (!empty($contents['first_line']) and !empty($contents['first_line']['text_1'])) ? $contents['first_line']['text_1'] : '' }}"
                placeholder=""
                hint=""
                className=""
            />

            <x-landingBuilder-file
                label="{{ trans('update.image') }} #1"
                name="contents[first_line][image_1]"
                value="{{ (!empty($contents['first_line']) and !empty($contents['first_line']['image_1'])) ? $contents['first_line']['image_1'] : '' }}"
                placeholder="{{ (!empty($contents['first_line']) and !empty($contents['first_line']['image_1'])) ? getFileNameByPath($contents['first_line']['image_1']) : '' }}"
                hint="{{ trans('update.preferred_size') }} 240x120px"
                icon="export"
                accept="image/*"
                className=""
            />

            <x-landingBuilder-input
                label="{{ trans('update.text') }} #2"
                name="contents[first_line][text_2]"
                value="{{ (!empty($contents['first_line']) and !empty($contents['first_line']['text_2'])) ? $contents['first_line']['text_2'] : '' }}"
                placeholder=""
                hint=""
                className=""
            />

            <h5 class="font-14 text-gray-500 my-24">{{ trans('update.second_line') }}</h5>

            <x-landingBuilder-input
                label="{{ trans('update.text') }} #1"
                name="contents[second_line][text_1]"
                value="{{ (!empty($contents['second_line']) and !empty($contents['second_line']['text_1'])) ? $contents['second_line']['text_1'] : '' }}"
                placeholder=""
                hint=""
                className=""
            />

            <x-landingBuilder-file
                label="{{ trans('update.image') }} #1"
                name="contents[second_line][image_1]"
                value="{{ (!empty($contents['second_line']) and !empty($contents['second_line']['image_1'])) ? $contents['second_line']['image_1'] : '' }}"
                placeholder="{{ (!empty($contents['second_line']) and !empty($contents['second_line']['image_1'])) ? getFileNameByPath($contents['second_line']['image_1']) : '' }}"
                hint="{{ trans('update.preferred_size') }} 64x64px"
                icon="export"
                accept="image/*"
                className=""
            />

            <x-landingBuilder-file
                label="{{ trans('update.image') }} #2"
                name="contents[second_line][image_2]"
                value="{{ (!empty($contents['second_line']) and !empty($contents['second_line']['image_2'])) ? $contents['second_line']['image_2'] : '' }}"
                placeholder="{{ (!empty($contents['second_line']) and !empty($contents['second_line']['image_2'])) ? getFileNameByPath($contents['second_line']['image_2']) : '' }}"
                hint="{{ trans('update.preferred_size') }} 64x64px"
                icon="export"
                accept="image/*"
                className=""
            />

            <x-landingBuilder-input
                label="{{ trans('update.text') }} #2"
                name="contents[second_line][text_2]"
                value="{{ (!empty($contents['second_line']) and !empty($contents['second_line']['text_2'])) ? $contents['second_line']['text_2'] : '' }}"
                placeholder=""
                hint=""
                className="mb-0"
            />
        </div>

    </div>{{-- End Col --}}

    <div class="col-12 col-lg-6 mt-20">

        {{-- Additional Information --}}
        <div class="p-16 rounded-16 border-gray-200">
            <h3 class="font-14 mb-24">{{ trans('update.additional_information') }}</h3>


            <x-landingBuilder-textarea
                label="{{ trans('update.text') }} #1"
                name="contents[additional_information][text_1]"
                value="{{ (!empty($contents['additional_information']) and !empty($contents['additional_information']['text_1'])) ? $contents['additional_information']['text_1'] : '' }}"
                placeholder=""
                rows="3"
                hint="{{ trans('update.suggested_about_120_characters') }}"
                className=""
            />

            <h5 class="font-14 text-gray-500 my-24">{{ trans('update.floating_images') }}</h5>

            <x-landingBuilder-file
                label="{{ trans('update.image') }} #1"
                name="contents[floating_images][image_1]"
                value="{{ (!empty($contents['floating_images']) and !empty($contents['floating_images']['image_1'])) ? $contents['floating_images']['image_1'] : '' }}"
                placeholder="{{ (!empty($contents['floating_images']) and !empty($contents['floating_images']['image_1'])) ? getFileNameByPath($contents['floating_images']['image_1']) : '' }}"
                hint="{{ trans('update.preferred_size') }} 150x54px"
                icon="export"
                accept="image/*"
                className=""
            />

            <x-landingBuilder-file
                label="{{ trans('update.image') }} #2"
                name="contents[floating_images][image_2]"
                value="{{ (!empty($contents['floating_images']) and !empty($contents['floating_images']['image_2'])) ? $contents['floating_images']['image_2'] : '' }}"
                placeholder="{{ (!empty($contents['floating_images']) and !empty($contents['floating_images']['image_2'])) ? getFileNameByPath($contents['floating_images']['image_2']) : '' }}"
                hint="{{ trans('update.preferred_size') }} 157x54px"
                icon="export"
                accept="image/*"
                className=""
            />


            <h5 class="font-14 text-gray-500 my-24">{{ trans('update.side_images') }}</h5>

            <x-landingBuilder-file
                label="{{ trans('update.image') }} #1"
                name="contents[side_images][image_1]"
                value="{{ (!empty($contents['side_images']) and !empty($contents['side_images']['image_1'])) ? $contents['side_images']['image_1'] : '' }}"
                placeholder="{{ (!empty($contents['side_images']) and !empty($contents['side_images']['image_1'])) ? getFileNameByPath($contents['side_images']['image_1']) : '' }}"
                hint="{{ trans('update.preferred_size') }} 368x184px"
                icon="export"
                accept="image/*"
                className=""
            />

            <x-landingBuilder-file
                label="{{ trans('update.image') }} #2"
                name="contents[side_images][image_2]"
                value="{{ (!empty($contents['side_images']) and !empty($contents['side_images']['image_2'])) ? $contents['side_images']['image_2'] : '' }}"
                placeholder="{{ (!empty($contents['side_images']) and !empty($contents['side_images']['image_2'])) ? getFileNameByPath($contents['side_images']['image_2']) : '' }}"
                hint="{{ trans('update.preferred_size') }} 297x185px"
                icon="export"
                accept="image/*"
                className=""
            />

            <x-landingBuilder-make-button
                title="{{ trans('update.primary_button') }}"
                inputNamePrefix="contents[primary_button]"
                :buttonData="(!empty($contents['primary_button'])) ? $contents['primary_button'] : []"
                className="mt-24"
            />

            <x-landingBuilder-make-button
                title="{{ trans('update.secondary_button') }}"
                inputNamePrefix="contents[secondary_button]"
                :buttonData="(!empty($contents['secondary_button'])) ? $contents['secondary_button'] : []"
                className="mt-24"
            />

        </div>

    </div>{{-- End Col --}}

</div>{{-- End Row --}}
