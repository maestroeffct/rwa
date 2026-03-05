<div class="js-links-info-item-card ">
    <x-landingBuilder-input
        label="{{ trans('update.pre_title') }}"
        name="contents[specific_sliders][{{ !empty($itemKey) ? $itemKey : 'record' }}][pre_title]"
        value="{{ (!empty($sliderData) and !empty($sliderData['pre_title'])) ? $sliderData['pre_title'] : '' }}"
        placeholder=""
        hint=""
        className=""
    />

    <x-landingBuilder-input
        label="{{ trans('public.title') }}"
        name="contents[specific_sliders][{{ !empty($itemKey) ? $itemKey : 'record' }}][title]"
        value="{{ (!empty($sliderData) and !empty($sliderData['title'])) ? $sliderData['title'] : '' }}"
        placeholder=""
        hint=""
        className=""
    />

    <x-landingBuilder-textarea
        label="{{ trans('public.description') }}"
        name="contents[specific_sliders][{{ !empty($itemKey) ? $itemKey : 'record' }}][description]"
        value="{{ (!empty($sliderData) and !empty($sliderData['description'])) ? $sliderData['description'] : '' }}"
        placeholder=""
        rows="3"
        hint="{{ trans('update.suggested_about_120_characters') }}"
        className=""
    />

    <x-landingBuilder-file
        label="{{ trans('update.image') }}"
        name="contents[specific_sliders][{{ !empty($itemKey) ? $itemKey : 'record' }}][image]"
        value="{{ (!empty($sliderData) and !empty($sliderData['image'])) ? $sliderData['image'] : '' }}"
        placeholder="{{ (!empty($sliderData) and !empty($sliderData['image'])) ? getFileNameByPath($sliderData['image']) : '' }}"
        hint="{{ trans('update.preferred_size') }} 324x324px"
        icon="export"
        accept="image/*"
        className=""
    />

    <x-landingBuilder-select
        label="{{ trans('update.content_type') }}"
        name="contents[specific_sliders][{{ !empty($itemKey) ? $itemKey : 'record' }}][content_type]"
        value="{{ (!empty($sliderData) and !empty($sliderData['content_type'])) ? $sliderData['content_type'] : '' }}"
        :items="['image', 'video']"
        hint=""
        className=""
        selectClassName="js-select-change-for-action"
        changeActionEls="js-slider-content-type-fields"
    />

    @php
        $contentTypeFieldImage = (empty($sliderData) or (!empty($sliderData['content_type']) and $sliderData['content_type'] == "image"));
        $contentTypeFieldVideo = (!empty($sliderData) and !empty($sliderData['content_type']) and $sliderData['content_type'] == "video");
    @endphp

    <x-landingBuilder-input
        label="{{ trans('panel.url') }}"
        name="contents[specific_sliders][{{ !empty($itemKey) ? $itemKey : 'record' }}][url]"
        value="{{ (!empty($sliderData) and !empty($sliderData['url'])) ? $sliderData['url'] : '' }}"
        placeholder=""
        hint=""
        icon="link-1"
        className="js-slider-content-type-fields js-select-change-for-action-field-image {{ $contentTypeFieldImage ? '' : 'd-none' }}"
    />

    <x-landingBuilder-video-content
        inputNamePrefix="contents[specific_sliders][{{ !empty($itemKey) ? $itemKey : 'record' }}]"
        videoSource="{{ (!empty($sliderData) and !empty($sliderData['video_source'])) ? $sliderData['video_source'] : '' }}"
        videoPath="{{ (!empty($sliderData) and !empty($sliderData['video_path'])) ? $sliderData['video_path'] : '' }}"
        videoFile="{{ (!empty($sliderData) and !empty($sliderData['video_file'])) ? $sliderData['video_file'] : '' }}"
        className="js-slider-content-type-fields js-select-change-for-action-field-video {{ $contentTypeFieldVideo ? '' : 'd-none' }}"
    />

    <x-landingBuilder-switch
        label="{{ trans('update.enable_item') }}"
        id="enable_link_{{ !empty($itemKey) ? $itemKey : 'record' }}"
        name="contents[specific_sliders][{{ !empty($itemKey) ? $itemKey : 'record' }}][enable]"
        checked="{{ !!(!empty($sliderData) and !empty($sliderData['enable']) and $sliderData['enable'] == 'on') }}"
        hint=""
        className="mb-0"
    />
</div>

<div class="d-flex align-items-center justify-content-end mt-24">
    <button type="button" class="js-addable-accordion-remove-btn btn btn-danger">{{ trans('public.delete') }}</button>
</div>

