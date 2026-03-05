<x-landingBuilder-input
    label="{{ trans('public.title') }}"
    name="contents[{{ $itemKey }}][title]"
    value="{{ (!empty($bannerData) and !empty($bannerData['title'])) ? $bannerData['title'] : '' }}"
    placeholder=""
    hint=""
    className=""
/>

<x-landingBuilder-textarea
    label="{{ trans('update.subtitle') }}"
    name="contents[{{ $itemKey }}][subtitle]"
    value="{{ (!empty($bannerData) and !empty($bannerData['subtitle'])) ? $bannerData['subtitle'] : '' }}"
    placeholder=""
    rows="3"
    hint="{{ trans('update.suggested_about_120_characters') }}"
    className=""
/>

@if($itemKey != "cta_section")
    <x-landingBuilder-file
        label="{{ trans('update.image') }}"
        name="contents[{{ $itemKey }}][image]"
        value="{{ (!empty($bannerData) and !empty($bannerData['image'])) ? $bannerData['image'] : '' }}"
        placeholder="{{ (!empty($bannerData) and !empty($bannerData['image'])) ? getFileNameByPath($bannerData['image']) : '' }}"
        hint="{{ trans('update.preferred_size') }} {{ ($itemKey == 'banner_1') ? '672x672px' : (($itemKey == 'banner_2') ? '672x324px' : '324x324px') }}"
        icon="export"
        accept="image/*"
        className=""
    />
@else
    <x-landingBuilder-file
        label="{{ trans('update.background') }}"
        name="contents[{{ $itemKey }}][background]"
        value="{{ (!empty($bannerData) and !empty($bannerData['background'])) ? $bannerData['background'] : '' }}"
        placeholder="{{ (!empty($bannerData) and !empty($bannerData['background'])) ? getFileNameByPath($bannerData['background']) : '' }}"
        hint="{{ trans('update.preferred_size') }} 324x324px"
        icon="export"
        accept="image/*"
        className=""
    />
@endif

<x-landingBuilder-input
    label="{{ trans('panel.url') }}"
    name="contents[{{ $itemKey }}][url]"
    value="{{ (!empty($bannerData) and !empty($bannerData['url'])) ? $bannerData['url'] : '' }}"
    placeholder=""
    hint=""
    icon="link-1"
    className="mb-0"
/>
