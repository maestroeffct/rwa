<div class="js-links-info-item-card ">
    <x-landingBuilder-input
        label="{{ trans('public.title') }}"
        name="contents[specific_links][{{ !empty($itemKey) ? $itemKey : 'record' }}][title]"
        value="{{ (!empty($linkData) and !empty($linkData['title'])) ? $linkData['title'] : '' }}"
        placeholder=""
        hint=""
        className=""
    />

    <x-landingBuilder-file
        label="{{ trans('update.image') }}"
        name="contents[specific_links][{{ !empty($itemKey) ? $itemKey : 'record' }}][image]"
        value="{{ (!empty($linkData) and !empty($linkData['image'])) ? $linkData['image'] : '' }}"
        placeholder="{{ (!empty($linkData) and !empty($linkData['image'])) ? getFileNameByPath($linkData['image']) : '' }}"
        hint="{{ trans('update.preferred_size') }} 324x324px"
        icon="export"
        accept="image/*"
        className=""
    />


    <x-landingBuilder-input
        label="{{ trans('panel.url') }}"
        name="contents[specific_links][{{ !empty($itemKey) ? $itemKey : 'record' }}][url]"
        value="{{ (!empty($linkData) and !empty($linkData['url'])) ? $linkData['url'] : '' }}"
        placeholder=""
        hint=""
        icon="link-1"
        className=""
    />

    <x-landingBuilder-switch
        label="{{ trans('update.enable_item') }}"
        id="enable_link_{{ !empty($itemKey) ? $itemKey : 'record' }}"
        name="contents[specific_links][{{ !empty($itemKey) ? $itemKey : 'record' }}][enable]"
        checked="{{ !!(!empty($linkData) and !empty($linkData['enable']) and $linkData['enable'] == 'on') }}"
        hint=""
        className="mb-0"
    />
</div>

<div class="d-flex align-items-center justify-content-end mt-24">
    <button type="button" class="js-addable-accordion-remove-btn btn btn-danger">{{ trans('public.delete') }}</button>
</div>

