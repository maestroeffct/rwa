<x-landingBuilder-input
    label="{{ trans('update.link_title') }}"
    name="contents[specific_links][{{ !empty($itemKey) ? $itemKey : 'record' }}][title]"
    value="{{ (!empty($navbarLinkData) and !empty($navbarLinkData['title'])) ? $navbarLinkData['title'] : '' }}"
    placeholder=""
    hint=""
    className=""
/>

<x-landingBuilder-input
    label="{{ trans('panel.url') }}"
    name="contents[specific_links][{{ !empty($itemKey) ? $itemKey : 'record' }}][url]"
    value="{{ (!empty($navbarLinkData) and !empty($navbarLinkData['url'])) ? $navbarLinkData['url'] : '' }}"
    placeholder=""
    hint=""
    icon="link-1"
    className="mb-0"
/>


<div class="d-flex align-items-center justify-content-end mt-24">
    <button type="button" class="js-addable-accordion-remove-btn btn btn-danger btn-lg">{{ trans('public.delete') }}</button>
</div>

