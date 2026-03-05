<x-landingBuilder-input
    label="{{ trans('public.title') }}"
    name="contents[title_items][{{ !empty($itemKey) ? $itemKey : 'record' }}][title]"
    value="{{ (!empty($titleItem) and !empty($titleItem['title'])) ? $titleItem['title'] : '' }}"
    placeholder=""
    hint=""
    className=""
/>

<x-landingBuilder-input
    label="{{ trans('panel.url') }}"
    name="contents[title_items][{{ !empty($itemKey) ? $itemKey : 'record' }}][url]"
    value="{{ (!empty($titleItem) and !empty($titleItem['url'])) ? $titleItem['url'] : '' }}"
    placeholder=""
    hint=""
    icon="link"
    className="mb-0"
/>

<div class="d-flex align-items-center justify-content-end mt-24">
    <button type="button" class="js-addable-accordion-remove-btn btn btn-danger btn-lg">{{ trans('public.delete') }}</button>
</div>
