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
                hint="{{ trans('update.preferred_size') }} 1464x1268px"
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

        {{-- Main Content  --}}
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

             {{-- CTA Section --}}
            <h3 class="font-14 mb-24 text-gray-500">{{ trans('update.cta_section') }}</h3>

            <x-landingBuilder-input
                label="{{ trans('update.title_bold_text') }}"
                name="contents[cta_section][title_bold_text]"
                value="{{ (!empty($contents['cta_section']) and !empty($contents['cta_section']['title_bold_text'])) ? $contents['cta_section']['title_bold_text'] : '' }}"
                placeholder=""
                hint=""
                className=""
            />

            <x-landingBuilder-input
                label="{{ trans('update.title_regular_text') }}"
                name="contents[cta_section][title_regular_text]"
                value="{{ (!empty($contents['cta_section']) and !empty($contents['cta_section']['title_regular_text'])) ? $contents['cta_section']['title_regular_text'] : '' }}"
                placeholder=""
                hint=""
                className=""
            />

            <x-landingBuilder-textarea
                label="{{ trans('public.description') }}"
                name="contents[cta_section][description]"
                value="{{ (!empty($contents['cta_section']) and !empty($contents['cta_section']['description'])) ? $contents['cta_section']['description'] : '' }}"
                placeholder=""
                rows="3"
                hint="{{ trans('update.suggested_about_120_characters') }}"
                className=""
            />

            <x-landingBuilder-icons-select
                label="{{ trans('update.icon') }}"
                name="contents[cta_section][icon]"
                value="{{ (!empty($contents['cta_section']) and !empty($contents['cta_section']['icon'])) ? $contents['cta_section']['icon'] : '' }}"
                placeholder="{{ trans('update.search_icons') }}"
                hint=""
                selectClassName="js-icons-select2"
                className="mb-0"
            />

            {{-- Button --}}
            <x-landingBuilder-make-button
                title="{{ trans('update.button') }}"
                inputNamePrefix="contents[main_content][button]"
                :buttonData="(!empty($contents['main_content']) and !empty($contents['main_content']['button'])) ? $contents['main_content']['button'] : []"
                className="mt-24 mb-0"
            />

            <h5 class="font-14 text-gray-500 my-24">{{ trans('update.floating_images') }}</h5>

            <x-landingBuilder-file
                label="{{ trans('update.image') }} #1"
                name="contents[floating_images][image_1]"
                value="{{ (!empty($contents['floating_images']) and !empty($contents['floating_images']['image_1'])) ? $contents['floating_images']['image_1'] : '' }}"
                placeholder="{{ (!empty($contents['floating_images']) and !empty($contents['floating_images']['image_1'])) ? getFileNameByPath($contents['floating_images']['image_1']) : '' }}"
                hint="{{ trans('update.preferred_size') }} 208x208px"
                icon="export"
                accept="image/*"
                className=""
            />

            <x-landingBuilder-file
                label="{{ trans('update.image') }} #2"
                name="contents[floating_images][image_2]"
                value="{{ (!empty($contents['floating_images']) and !empty($contents['floating_images']['image_2'])) ? $contents['floating_images']['image_2'] : '' }}"
                placeholder="{{ (!empty($contents['floating_images']) and !empty($contents['floating_images']['image_2'])) ? getFileNameByPath($contents['floating_images']['image_2']) : '' }}"
                hint="{{ trans('update.preferred_size') }} 208x208px"
                icon="export"
                accept="image/*"
                className="mb-0"
            />

        </div>
    </div> {{-- End Col --}}

    <div class="col-12 col-lg-6 mt-20">
        {{-- Featured Courses --}}
        <div class="p-16 rounded-16 border-gray-200">
            <x-landingBuilder-addable-accordions
                title="{{ trans('update.meeting_packages') }}"
                addText="{{ trans('update.add_a_meeting_package') }}"
                className="mb-0"
                mainRow="js-featured-meeting-package-main-card"
            >
                @if(!empty($contents) and !empty($contents['featured_packages']) and count($contents['featured_packages']))
                    @foreach($contents['featured_packages'] as $sKey => $featuredData)
                        @if($sKey != 'record')
                            @php
                                $meetingPackage = \App\Models\MeetingPackage::query()->find($featuredData['meeting_package']);
                            @endphp

                            <x-landingBuilder-accordion
                                title="{{ !empty($meetingPackage) ? $meetingPackage->title : trans('update.meeting_package') }}"
                                id="featured_meeting_package_{{ $sKey }}"
                                className=""
                                show=""
                            >
                                @include('landingBuilder.admin.components.manage.meeting_packages_grid.meeting_package_item',['itemKey' => $sKey, 'featuredPackageData' => $featuredData])
                            </x-landingBuilder-accordion>
                        @endif
                    @endforeach
                @endif
            </x-landingBuilder-addable-accordions>
        </div>
    </div>{{-- End Col --}}
</div>



<div class="js-featured-meeting-package-main-card d-none">
    <x-landingBuilder-accordion
        title="{{ trans('update.meeting_package') }}"
        id="record"
        className=""
        show="true"
    >
        @include('landingBuilder.admin.components.manage.meeting_packages_grid.meeting_package_item')
    </x-landingBuilder-accordion>
</div>
