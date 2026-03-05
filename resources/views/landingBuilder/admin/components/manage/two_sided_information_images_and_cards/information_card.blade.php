<div class="js-links-info-item-card ">
    <x-landingBuilder-input
        label="{{ trans('public.title') }}"
        name="contents[specific_information][{{ !empty($itemKey) ? $itemKey : 'record' }}][title]"
        value="{{ (!empty($informationData) and !empty($informationData['title'])) ? $informationData['title'] : '' }}"
        placeholder=""
        hint=""
        className=""
    />

    <x-landingBuilder-input
        label="{{ trans('update.subtitle') }}"
        name="contents[specific_information][{{ !empty($itemKey) ? $itemKey : 'record' }}][subtitle]"
        value="{{ (!empty($informationData) and !empty($informationData['subtitle'])) ? $informationData['subtitle'] : '' }}"
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

    <x-landingBuilder-file
        label="{{ trans('update.icon') }}"
        name="contents[specific_information][{{ !empty($itemKey) ? $itemKey : 'record' }}][icon]"
        value="{{ (!empty($informationData) and !empty($informationData['icon'])) ? $informationData['icon'] : '' }}"
        placeholder="{{ (!empty($informationData) and !empty($informationData['icon'])) ? getFileNameByPath($informationData['icon']) : '' }}"
        hint="{{ trans('update.preferred_size') }} 40x40px"
        icon="export"
        accept="image/*"
        className=""
    />

    <x-landingBuilder-input
        label="{{ trans('update.link_title') }}"
        name="contents[specific_information][{{ !empty($itemKey) ? $itemKey : 'record' }}][link_title]"
        value="{{ (!empty($informationData) and !empty($informationData['link_title'])) ? $informationData['link_title'] : '' }}"
        placeholder=""
        hint=""
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

