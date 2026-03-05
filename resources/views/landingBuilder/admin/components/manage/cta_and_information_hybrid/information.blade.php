<x-landingBuilder-input
    label="{{ trans('public.title') }}"
    name="contents[specific_information][{{ !empty($itemKey) ? $itemKey : 'record' }}][title]"
    value="{{ (!empty($informationData) and !empty($informationData['title'])) ? $informationData['title'] : '' }}"
    placeholder=""
    hint=""
    className=""
/>

<x-landingBuilder-textarea
    label="{{ trans('public.description') }}"
    name="contents[specific_information][{{ !empty($itemKey) ? $itemKey : 'record' }}][description]"
    value="{{ (!empty($informationData) and !empty($informationData['description'])) ? $informationData['description'] : '' }}"
    placeholder=""
    rows="3"
    hint="{{ trans('update.suggested_about_120_characters') }}"
    className=""
/>

<div class="d-flex align-items-center justify-content-end mt-24">
    <button type="button" class="js-addable-accordion-remove-btn btn btn-danger">{{ trans('public.delete') }}</button>
</div>

