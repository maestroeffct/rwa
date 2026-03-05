<x-landingBuilder-searchable-meeting-package
    label="{{ trans('update.package') }}"
    name="contents[featured_packages][{{ !empty($itemKey) ? $itemKey : 'record' }}][meeting_package]"
    value="{{ (!empty($featuredPackageData) and !empty($featuredPackageData['meeting_package'])) ? $featuredPackageData['meeting_package'] : '' }}"
    placeholder="{{ trans('update.search_a_meeting_package') }}"
    className=""
    hint=""
    selectClassName=""
    changeActionEls=""
    changeActionParent=""
/>

<div class="d-flex align-items-center justify-content-end mt-24">
    <button type="button" class="js-addable-accordion-remove-btn btn btn-danger">{{ trans('public.delete') }}</button>
</div>
