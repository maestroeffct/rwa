<x-landingBuilder-input
    label="{{ trans('update.link_title') }}"
    name="{{ $inputNamePrefix }}[title]"
    value="{{ (!empty($footerLinkData) and !empty($footerLinkData['title'])) ? $footerLinkData['title'] : '' }}"
    placeholder=""
    hint=""
    className=""
/>

<x-landingBuilder-input
    label="{{ trans('panel.url') }}"
    name="{{ $inputNamePrefix }}[url]"
    value="{{ (!empty($footerLinkData) and !empty($footerLinkData['url'])) ? $footerLinkData['url'] : '' }}"
    placeholder=""
    hint=""
    icon="link-1"
    className="mb-0"
/>


<div class="d-flex align-items-center justify-content-end mt-24">
    <button type="button" class="js-addable-accordion-remove-btn btn btn-danger">{{ trans('public.delete') }}</button>
</div>

