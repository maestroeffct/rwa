
<x-landingBuilder-input
    label="{{ trans('public.title') }}"
    name="contents[checked_items][{{ !empty($itemKey) ? $itemKey : 'record' }}][title]"
    value="{{ (!empty($checkedItemData) and !empty($checkedItemData['title'])) ? $checkedItemData['title'] : '' }}"
    placeholder=""
    hint=""
    className=""
/>

<x-landingBuilder-input
    label="{{ trans('update.subtitle') }}"
    name="contents[checked_items][{{ !empty($itemKey) ? $itemKey : 'record' }}][subtitle]"
    value="{{ (!empty($checkedItemData) and !empty($checkedItemData['subtitle'])) ? $checkedItemData['subtitle'] : '' }}"
    placeholder=""
    hint=""
    className="mb-0"
/>


<div class="d-flex align-items-center justify-content-end mt-24">
    <button type="button" class="js-addable-accordion-remove-btn btn btn-danger">{{ trans('public.delete') }}</button>
</div>

