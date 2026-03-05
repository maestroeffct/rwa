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

            <x-landingBuilder-file
                label="{{ trans('update.floating_image') }}"
                name="contents[cta_section][floating_image]"
                value="{{ (!empty($contents['cta_section']) and !empty($contents['cta_section']['floating_image'])) ? $contents['cta_section']['floating_image'] : '' }}"
                placeholder="{{ (!empty($contents['cta_section']) and !empty($contents['cta_section']['floating_image'])) ? getFileNameByPath($contents['cta_section']['floating_image']) : '' }}"
                hint="{{ trans('update.preferred_size') }} 160x160px"
                icon="export"
                accept="image/*"
                className="mb-0"
            />

        </div>

    </div>{{-- End Col --}}

    <div class="col-12 col-lg-6 mt-20">

        {{-- Features  --}}
        <div class="p-16 rounded-16 border-gray-200 ">
            <x-landingBuilder-addable-accordions
                title="{{ trans('update.testimonials') }}"
                addText="{{ trans('update.add_testimonial') }}"
                className="mb-0"
                mainRow="js-testimonial-item-main-card"
            >
                @if(!empty($contents) and !empty($contents['testimonials']) and count($contents['testimonials']))
                    @foreach($contents['testimonials'] as $sKey => $itemData)
                        @if($sKey != 'record')
                            @php
                                $selectedTestimonialId = (!empty($itemData) and !empty($itemData['testimonial_id'])) ? $itemData['testimonial_id'] : null;
                                $selectedTestimonial = (!empty($selectedTestimonialId) and !empty($testimonials) and count($testimonials)) ? $testimonials->where('id', $selectedTestimonialId)->first() : null;
                            @endphp

                            <x-landingBuilder-accordion
                                title="{{ (!empty($selectedTestimonial)) ? $selectedTestimonial->user_name : trans('update.new_testimonial') }}"
                                id="testimonial_{{ $sKey }}"
                                className=""
                                show=""
                            >
                                @include('landingBuilder.admin.components.manage.sliding_testimonials_2_rows.testimonial',['itemKey' => $sKey, 'selectedTestimonialItem' => $selectedTestimonial])
                            </x-landingBuilder-accordion>
                        @endif
                    @endforeach
                @endif
            </x-landingBuilder-addable-accordions>
        </div>

    </div>{{-- End Col --}}

</div>{{-- End Row --}}


<div class="js-testimonial-item-main-card d-none">
    <x-landingBuilder-accordion
        title="{{ trans('update.new_testimonial') }}"
        id="record"
        className=""
        show="true"
    >
        @include('landingBuilder.admin.components.manage.sliding_testimonials_2_rows.testimonial')
    </x-landingBuilder-accordion>
</div>
