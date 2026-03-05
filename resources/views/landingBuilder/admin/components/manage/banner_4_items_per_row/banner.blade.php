<x-landingBuilder-file
    label="{{ trans('update.image') }}"
    name="contents[specific_banners][{{ !empty($itemKey) ? $itemKey : 'record' }}][image]"
    value="{{ (!empty($bannerData) and !empty($bannerData['image'])) ? $bannerData['image'] : '' }}"
    placeholder="{{ (!empty($bannerData) and !empty($bannerData['image'])) ? getFileNameByPath($bannerData['image']) : '' }}"
    hint="{{ trans('update.preferred_size') }} 324x324px"
    icon="export"
    accept="image/*"
    className=""
/>

<x-landingBuilder-input
    label="{{ trans('panel.url') }}"
    name="contents[specific_banners][{{ !empty($itemKey) ? $itemKey : 'record' }}][url]"
    value="{{ (!empty($bannerData) and !empty($bannerData['url'])) ? $bannerData['url'] : '' }}"
    placeholder=""
    hint=""
    icon="link-1"
    className="mb-0"
/>


<div class="d-flex align-items-center justify-content-end mt-24">
    <button type="button" class="js-addable-accordion-remove-btn btn btn-danger">{{ trans('public.delete') }}</button>
</div>

