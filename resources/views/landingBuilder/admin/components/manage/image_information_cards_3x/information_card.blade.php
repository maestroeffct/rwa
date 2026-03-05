<div class="js-links-info-item-card ">
    <x-landingBuilder-input
        label="{{ trans('public.title') }}"
        name="contents[specific_information][{{ !empty($itemKey) ? $itemKey : 'record' }}][title]"
        value="{{ (!empty($informationData) and !empty($informationData['title'])) ? $informationData['title'] : '' }}"
        placeholder=""
        hint=""
        className=""
    />

    <x-landingBuilder-textarea
        label="{{ trans('update.subtitle') }}"
        name="contents[specific_information][{{ !empty($itemKey) ? $itemKey : 'record' }}][subtitle]"
        value="{{ (!empty($informationData) and !empty($informationData['subtitle'])) ? $informationData['subtitle'] : '' }}"
        placeholder=""
        rows="3"
        hint="{{ trans('update.suggested_about_120_characters') }}"
        className=""
    />

    <x-landingBuilder-file
        label="{{ trans('update.image') }}"
        name="contents[specific_information][{{ !empty($itemKey) ? $itemKey : 'record' }}][image]"
        value="{{ (!empty($informationData) and !empty($informationData['image'])) ? $informationData['image'] : '' }}"
        placeholder="{{ (!empty($informationData) and !empty($informationData['image'])) ? getFileNameByPath($informationData['image']) : '' }}"
        hint="{{ trans('update.preferred_size') }} 324x324px"
        icon="export"
        accept="image/*"
        className=""
    />


    <x-landingBuilder-input
        label="{{ trans('panel.url') }}"
        name="contents[specific_information][{{ !empty($itemKey) ? $itemKey : 'record' }}][url]"
        value="{{ (!empty($informationData) and !empty($informationData['url'])) ? $informationData['url'] : '' }}"
        placeholder=""
        hint=""
        icon="link-1"
        className=""
    />

    <x-landingBuilder-switch
        label="{{ trans('update.enable_item') }}"
        id="enable_link_{{ !empty($itemKey) ? $itemKey : 'record' }}"
        name="contents[specific_information][{{ !empty($itemKey) ? $itemKey : 'record' }}][enable]"
        checked="{{ !!(!empty($informationData) and !empty($informationData['enable']) and $informationData['enable'] == 'on') }}"
        hint=""
        className="mb-0"
    />
</div>

<div class="d-flex align-items-center justify-content-end mt-24">
    <button type="button" class="js-addable-accordion-remove-btn btn btn-danger">{{ trans('public.delete') }}</button>
</div>

