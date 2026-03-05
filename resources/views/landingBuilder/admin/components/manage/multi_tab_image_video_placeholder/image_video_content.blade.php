<x-landingBuilder-input
    label="{{ trans('public.title') }}"
    name="contents[image_video_content][{{ !empty($itemKey) ? $itemKey : 'record' }}][title]"
    value="{{ (!empty($contentItemData) and !empty($contentItemData['title'])) ? $contentItemData['title'] : '' }}"
    placeholder=""
    hint=""
    className=""
/>

<x-landingBuilder-icons-select
    label="{{ trans('update.icon') }}"
    name="contents[image_video_content][{{ !empty($itemKey) ? $itemKey : 'record' }}][icon]"
    value="{{ (!empty($contentItemData) and !empty($contentItemData['icon'])) ? $contentItemData['icon'] : '' }}"
    placeholder="{{ trans('update.search_icons') }}"
    hint=""
    selectClassName="{{ !empty($itemKey) ? 'js-icons-select2' : 'js-make-icons-select2' }}"
    className=""
/>

<x-landingBuilder-select
    label="{{ trans('update.content_type') }}"
    name="contents[image_video_content][{{ !empty($itemKey) ? $itemKey : 'record' }}][content_type]"
    value="{{ (!empty($contentItemData) and !empty($contentItemData['content_type'])) ? $contentItemData['content_type'] : '' }}"
    :items="['image', 'video']"
    hint=""
    className=""
    selectClassName="js-select-change-for-action"
    changeActionEls="js-img-video-content-type-fields"
/>

@php
    $contentTypeFieldImage = (empty($contentItemData) or (!empty($contentItemData['content_type']) and $contentItemData['content_type'] == "image"));
    $contentTypeFieldVideo = (!empty($contentItemData) and !empty($contentItemData['content_type']) and $contentItemData['content_type'] == "video");
@endphp

<x-landingBuilder-file
    label="{{ trans('update.image') }}"
    name="contents[image_video_content][{{ !empty($itemKey) ? $itemKey : 'record' }}][image]"
    value="{{ (!empty($contentItemData) and !empty($contentItemData['image'])) ? $contentItemData['image'] : '' }}"
    placeholder="{{ (!empty($contentItemData) and !empty($contentItemData['image'])) ? getFileNameByPath($contentItemData['image']) : '' }}"
    hint="{{ trans('update.preferred_size') }} 1064x700px"
    icon="export"
    accept="image/*"
    className="js-img-video-content-type-fields js-select-change-for-action-field-image {{ $contentTypeFieldImage ? '' : 'd-none' }}"
/>


<x-landingBuilder-file
    label="{{ trans('update.video_file') }}"
    name="contents[image_video_content][{{ !empty($itemKey) ? $itemKey : 'record' }}][video_file]"
    value="{{ (!empty($contentItemData) and !empty($contentItemData['video_file'])) ? $contentItemData['video_file'] : '' }}"
    placeholder="{{ (!empty($contentItemData) and !empty($contentItemData['video_file'])) ? getFileNameByPath($contentItemData['video_file']) : '' }}"
    hint=""
    icon="export"
    accept="video/*"
    className="js-img-video-content-type-fields js-select-change-for-action-field-video {{ $contentTypeFieldVideo ? '' : 'd-none' }}"
/>


<x-landingBuilder-file
    label="{{ trans('update.video_cover') }}"
    name="contents[image_video_content][{{ !empty($itemKey) ? $itemKey : 'record' }}][video_cover]"
    value="{{ (!empty($contentItemData) and !empty($contentItemData['video_cover'])) ? $contentItemData['video_cover'] : '' }}"
    placeholder="{{ (!empty($contentItemData) and !empty($contentItemData['video_cover'])) ? getFileNameByPath($contentItemData['video_cover']) : '' }}"
    hint="{{ trans('update.preferred_size') }} 1064x700px"
    icon="export"
    accept="image/*"
    className="js-img-video-content-type-fields js-select-change-for-action-field-video {{ $contentTypeFieldVideo ? '' : 'd-none' }} mb-0"
/>


<x-landingBuilder-input
    label="{{ trans('panel.url') }}"
    name="contents[image_video_content][{{ !empty($itemKey) ? $itemKey : 'record' }}][url]"
    value="{{ (!empty($contentItemData) and !empty($contentItemData['url'])) ? $contentItemData['url'] : '' }}"
    placeholder=""
    hint=""
    icon="link-1"
    className="js-img-video-content-type-fields js-select-change-for-action-field-image {{ $contentTypeFieldImage ? '' : 'd-none' }} mb-0"
/>

<div class="d-flex align-items-center justify-content-end mt-24">
    <button type="button" class="js-addable-accordion-remove-btn btn btn-danger">{{ trans('public.delete') }}</button>
</div>

