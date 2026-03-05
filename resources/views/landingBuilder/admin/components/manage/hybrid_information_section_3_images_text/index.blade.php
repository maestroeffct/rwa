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
                className=""
            />

            <x-landingBuilder-switch
                label="{{ trans('update.reverse_direction') }}"
                id="reverse_direction"
                name="contents[reverse_direction]"
                checked="{{ !!(!empty($contents['reverse_direction']) and !empty($contents['reverse_direction']) == 'on') }}"
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

            <x-landingBuilder-input
                label="{{ trans('update.badge_title') }}"
                name="contents[main_content][badge_title]"
                value="{{ (!empty($contents['main_content']) and !empty($contents['main_content']['badge_title'])) ? $contents['main_content']['badge_title'] : '' }}"
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

    <div class="col-12 col-lg-6 mt-20">

        {{-- Statistics --}}
        <div class="p-16 rounded-16 border-gray-200 ">
            <h3 class="font-14 mb-24">{{ trans('update.statistics') }}</h3>

            @foreach(['statistic_1', 'statistic_2'] as $statistic)
                <div class="mt-16">
                    <h5 class="font-14 mb-24 text-gray-500">{{ trans("update.{$statistic}") }}</h5>

                    <x-landingBuilder-input
                        label="{{ trans('public.title') }}"
                        name="contents[{{ $statistic }}][title]"
                        value="{{ (!empty($contents[$statistic]) and !empty($contents[$statistic]['title'])) ? $contents[$statistic]['title'] : '' }}"
                        placeholder=""
                        hint=""
                        className=""
                    />

                    <x-landingBuilder-input
                        label="{{ trans('update.subtitle') }}"
                        name="contents[{{ $statistic }}][subtitle]"
                        value="{{ (!empty($contents[$statistic]) and !empty($contents[$statistic]['subtitle'])) ? $contents[$statistic]['subtitle'] : '' }}"
                        placeholder=""
                        hint=""
                        className=""
                    />

                    <x-landingBuilder-textarea
                        label="{{ trans('public.description') }}"
                        name="contents[{{ $statistic }}][description]"
                        value="{{ (!empty($contents[$statistic]) and !empty($contents[$statistic]['description'])) ? $contents[$statistic]['description'] : '' }}"
                        placeholder=""
                        rows="2"
                        hint="{{ trans('update.suggested_about_120_characters') }}"
                        className="mb-0"
                    />
                </div>
            @endforeach
        </div>

        {{-- Image Content --}}
        <div class="p-16 rounded-16 border-gray-200 mt-20">
            <h3 class="font-14 mb-24">{{ trans('update.images') }}</h3>

            <x-landingBuilder-file
                label="{{ trans('update.image_1') }}"
                name="contents[image_content][image_1]"
                value="{{ (!empty($contents['image_content']) and !empty($contents['image_content']['image_1'])) ? $contents['image_content']['image_1'] : '' }}"
                placeholder="{{ (!empty($contents['image_content']) and !empty($contents['image_content']['image_1'])) ? getFileNameByPath($contents['image_content']['image_1']) : '' }}"
                hint="{{ trans('update.preferred_size') }} 280x576px"
                icon="export"
                accept="image/*"
                className=""
            />

            <x-landingBuilder-file
                label="{{ trans('update.image_2') }}"
                name="contents[image_content][image_2]"
                value="{{ (!empty($contents['image_content']) and !empty($contents['image_content']['image_2'])) ? $contents['image_content']['image_2'] : '' }}"
                placeholder="{{ (!empty($contents['image_content']) and !empty($contents['image_content']['image_2'])) ? getFileNameByPath($contents['image_content']['image_2']) : '' }}"
                hint="{{ trans('update.preferred_size') }} 280x576px"
                icon="export"
                accept="image/*"
                className=""
            />

            <x-landingBuilder-file
                label="{{ trans('update.revolver_image') }}"
                name="contents[image_content][revolver_image]"
                value="{{ (!empty($contents['image_content']) and !empty($contents['image_content']['revolver_image'])) ? $contents['image_content']['revolver_image'] : '' }}"
                placeholder="{{ (!empty($contents['image_content']) and !empty($contents['image_content']['revolver_image'])) ? getFileNameByPath($contents['image_content']['revolver_image']) : '' }}"
                hint="{{ trans('update.preferred_size') }} 132x132px"
                icon="export"
                accept="image/*"
                className="mb-0"
            />

        </div>

    </div>{{-- End Col --}}

</div>{{-- End Row --}}
