<div class="js-feature-item-card ">
    <x-landingBuilder-input
        label="{{ trans('public.title') }}"
        name="contents[features_cards][{{ !empty($itemKey) ? $itemKey : 'record' }}][title]"
        value="{{ (!empty($featureItemData) and !empty($featureItemData['title'])) ? $featureItemData['title'] : '' }}"
        placeholder=""
        hint=""
        className=""
    />

    <x-landingBuilder-textarea
        label="{{ trans('public.description') }}"
        name="contents[features_cards][{{ !empty($itemKey) ? $itemKey : 'record' }}][description]"
        value="{{ (!empty($featureItemData) and !empty($featureItemData['description'])) ? $featureItemData['description'] : '' }}"
        placeholder=""
        rows="3"
        hint="{{ trans('update.suggested_about_120_characters') }}"
        className=""
    />

    <x-landingBuilder-icons-select
        label="{{ trans('update.icon') }}"
        name="contents[features_cards][{{ !empty($itemKey) ? $itemKey : 'record' }}][icon]"
        value="{{ (!empty($featureItemData) and !empty($featureItemData['icon'])) ? $featureItemData['icon'] : '' }}"
        placeholder="{{ trans('update.search_icons') }}"
        hint=""
        selectClassName="{{ !empty($itemKey) ? 'js-icons-select2' : 'js-make-icons-select2' }}"
        className=""
    />


    <x-landingBuilder-input
        label="{{ trans('panel.url') }}"
        name="contents[features_cards][{{ !empty($itemKey) ? $itemKey : 'record' }}][url]"
        value="{{ (!empty($featureItemData) and !empty($featureItemData['url'])) ? $featureItemData['url'] : '' }}"
        placeholder=""
        hint=""
        icon="link-1"
        className=""
    />

</div>

<div class="d-flex align-items-center justify-content-end mt-24">
    <button type="button" class="js-addable-accordion-remove-btn btn btn-danger">{{ trans('public.delete') }}</button>
</div>
