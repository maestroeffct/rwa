<div class="{{ $className ?? '' }}">
    <h5 class="font-14 text-gray-500 mb-24">{{ $title }}</h5>

    <x-landingBuilder-input
        label="{{ trans('update.label') }}"
        name="{{ $inputNamePrefix }}[label]"
        value="{{ (!empty($buttonData) and !empty($buttonData['label'])) ? $buttonData['label'] : '' }}"
        placeholder=""
        hint=""
    />

    <x-landingBuilder-icons-select
        label="{{ trans('update.icon') }}"
        name="{{ $inputNamePrefix }}[icon]"
        value="{{ (!empty($buttonData) and !empty($buttonData['icon'])) ? $buttonData['icon'] : '' }}"
        placeholder="{{ trans('update.search_icons') }}"
        hint=""
        selectClassName="js-icons-select2"
        className=""
    />

    <x-landingBuilder-input
        label="{{ trans('panel.url') }}"
        name="{{ $inputNamePrefix }}[url]"
        value="{{ (!empty($buttonData) and !empty($buttonData['url'])) ? $buttonData['url'] : '' }}"
        placeholder=""
        hint=""
        icon="link-1"
        className="mb-0"
    />
</div>
