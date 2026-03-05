<x-landingBuilder-input
    label="{{ trans('public.title') }}"
    name="contents[information_cards][{{ !empty($itemKey) ? $itemKey : 'record' }}][title]"
    value="{{ (!empty($cardData) and !empty($cardData['title'])) ? $cardData['title'] : '' }}"
    placeholder=""
    hint=""
    className=""
/>

<x-landingBuilder-textarea
    label="{{ trans('update.subtitle') }}"
    name="contents[information_cards][{{ !empty($itemKey) ? $itemKey : 'record' }}][subtitle]"
    value="{{ (!empty($cardData) and !empty($cardData['subtitle'])) ? $cardData['subtitle'] : '' }}"
    placeholder=""
    rows="3"
    hint="{{ trans('update.suggested_about_120_characters') }}"
    className=""
/>

<x-landingBuilder-file
    label="{{ trans('update.image') }}"
    name="contents[information_cards][{{ !empty($itemKey) ? $itemKey : 'record' }}][image]"
    value="{{ (!empty($cardData) and !empty($cardData['image'])) ? $cardData['image'] : '' }}"
    placeholder="{{ (!empty($cardData) and !empty($cardData['image'])) ? getFileNameByPath($cardData['image']) : '' }}"
    hint="{{ trans('update.preferred_size') }} 64x64px"
    icon="export"
    accept="image/*"
    className=""
/>

<x-landingBuilder-input
    label="{{ trans('panel.url') }}"
    name="contents[information_cards][{{ !empty($itemKey) ? $itemKey : 'record' }}][url]"
    value="{{ (!empty($cardData) and !empty($cardData['url'])) ? $cardData['url'] : '' }}"
    placeholder=""
    hint=""
    icon="link"
    className=""
/>

<x-landingBuilder-switch
    label="{{ trans('update.enable_image_padding') }}"
    id="enable_image_padding_{{ !empty($itemKey) ? $itemKey : 'record' }}"
    name="contents[information_cards][{{ !empty($itemKey) ? $itemKey : 'record' }}][enable_image_padding]"
    checked="{{ !!(!empty($cardData) and !empty($cardData['enable_image_padding']) and $cardData['enable_image_padding'] == 'on') }}"
    hint="{{ trans('update.enable_image_padding_switch_hint') }}"
    className="mb-0"
/>

<div class="d-flex align-items-center justify-content-end mt-24">
    <button type="button" class="js-addable-accordion-remove-btn btn btn-danger">{{ trans('public.delete') }}</button>
</div>
