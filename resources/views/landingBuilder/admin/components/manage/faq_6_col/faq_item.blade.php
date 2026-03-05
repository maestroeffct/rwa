<div class="js-faq-item-card ">
    <x-landingBuilder-input
        label="{{ trans('public.title') }}"
        name="contents[faq_items][{{ !empty($itemKey) ? $itemKey : 'record' }}][title]"
        value="{{ (!empty($faqItemData) and !empty($faqItemData['title'])) ? $faqItemData['title'] : '' }}"
        placeholder=""
        hint=""
        className=""
    />

    <x-landingBuilder-textarea
        label="{{ trans('public.description') }}"
        name="contents[faq_items][{{ !empty($itemKey) ? $itemKey : 'record' }}][description]"
        value="{{ (!empty($faqItemData) and !empty($faqItemData['description'])) ? $faqItemData['description'] : '' }}"
        placeholder=""
        rows="3"
        hint="{{ trans('update.suggested_about_120_characters') }}"
        className=""
    />


    <x-landingBuilder-icons-select
        label="{{ trans('update.icon') }}"
        name="contents[faq_items][{{ !empty($itemKey) ? $itemKey : 'record' }}][icon]"
        value="{{ (!empty($faqItemData) and !empty($faqItemData['icon'])) ? $faqItemData['icon'] : '' }}"
        placeholder="{{ trans('update.search_icons') }}"
        hint=""
        selectClassName="{{ !empty($itemKey) ? 'js-icons-select2' : 'js-make-icons-select2' }}"
        className=""
    />

    <x-landingBuilder-switch
        label="{{ trans('update.enable_item') }}"
        id="enable_faq_{{ !empty($itemKey) ? $itemKey : 'record' }}"
        name="contents[faq_items][{{ !empty($itemKey) ? $itemKey : 'record' }}][enable]"
        checked="{{ !!(!empty($faqItemData) and !empty($faqItemData['enable']) and $faqItemData['enable'] == 'on') }}"
        hint=""
        className="mb-0"
    />
</div>

<div class="d-flex align-items-center justify-content-end mt-24">
    <button type="button" class="js-addable-accordion-remove-btn btn btn-danger">{{ trans('public.delete') }}</button>
</div>
