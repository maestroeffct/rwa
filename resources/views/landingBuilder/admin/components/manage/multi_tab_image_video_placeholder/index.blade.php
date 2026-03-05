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
                placeholder="{{ !empty($contents['background']) ? getFileNameByPath($contents['background']) : '' }}"
                value="{{ !empty($contents['background']) ? $contents['background'] : '' }}"
                hint="{{ trans('update.preferred_size') }} 64x64px"
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
                className=""
            />
        </div>

        <div class="p-16 rounded-16 border-gray-200 mt-20">
            <h3 class="font-14 mb-24">{{ trans('update.section_title') }}</h3>

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
                className="mb-0"
            />
        </div>

        {{-- Checked Items --}}
        <div class="p-16 rounded-16 border-gray-200 mt-20">

            <x-landingBuilder-addable-accordions
                title="{{ trans('update.checked_items') }}"
                addText="{{ trans('update.add_items') }}"
                className="mb-0"
                mainRow="js-checked-items-main-card"
            >
                @if(!empty($contents) and !empty($contents['checked_items']) and count($contents['checked_items']))
                    @foreach($contents['checked_items'] as $sKey => $itemData)
                        @if($sKey != 'record')
                            <x-landingBuilder-accordion
                                title="{{ (!empty($itemData) and !empty($itemData['title'])) ? $itemData['title'] : trans('update.checked_item') }}"
                                id="checked_item_{{ $sKey }}"
                                className=""
                                show=""
                            >
                                @include('landingBuilder.admin.components.manage.multi_tab_image_video_placeholder.checked_item',['itemKey' => $sKey, 'checkedItemData' => $itemData])
                            </x-landingBuilder-accordion>
                        @endif
                    @endforeach
                @endif
            </x-landingBuilder-addable-accordions>

        </div>

    </div> {{-- End Col --}}

    <div class="col-12 col-lg-6 mt-20">

        <div class="p-16 rounded-16 border-gray-200">
            <x-landingBuilder-addable-accordions
                title="{{ trans('update.image_video_content') }}"
                addText="{{ trans('update.add_content') }}"
                className="mb-0"
                mainRow="js-image-video-content-main-card"
            >
                @if(!empty($contents) and !empty($contents['image_video_content']) and count($contents['image_video_content']))
                    @foreach($contents['image_video_content'] as $sKey => $contentItemDataRow)
                        @if($sKey != 'record')
                            <x-landingBuilder-accordion
                                title="{{ (!empty($contentItemDataRow) and !empty($contentItemDataRow['title'])) ? $contentItemDataRow['title'] : trans('update.add_content') }}"
                                id="image_video_content_{{ $sKey }}"
                                className=""
                                show=""
                            >
                                @include('landingBuilder.admin.components.manage.multi_tab_image_video_placeholder.image_video_content',['itemKey' => $sKey, 'contentItemData' => $contentItemDataRow])
                            </x-landingBuilder-accordion>
                        @endif
                    @endforeach
                @endif
            </x-landingBuilder-addable-accordions>
        </div>
    </div>
</div>


{{-- checked Items --}}
<div class="js-checked-items-main-card d-none">
    <x-landingBuilder-accordion
        title="{{ trans('update.checked_item') }}"
        id="record"
        className=""
        show="true"
    >
        @include('landingBuilder.admin.components.manage.multi_tab_image_video_placeholder.checked_item')
    </x-landingBuilder-accordion>
</div>


{{-- Image/Video Content --}}
<div class="js-image-video-content-main-card d-none">
    <x-landingBuilder-accordion
        title="{{ trans('update.add_content') }}"
        id="record"
        className=""
        show="true"
    >
        @include('landingBuilder.admin.components.manage.multi_tab_image_video_placeholder.image_video_content')
    </x-landingBuilder-accordion>
</div>
