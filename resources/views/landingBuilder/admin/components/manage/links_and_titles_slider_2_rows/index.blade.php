<div class="row">
    <div class="col-12 col-lg-6 mt-20">
        {{-- General Information --}}
        <div class="p-16 rounded-16 border-gray-200">
            <h3 class="font-14 mb-24">{{ trans('update.general_information') }}</h3>

            @include('design_1.panel.includes.locale.locale_select',[
                'itemRow' => !empty($landingComponent) ? $landingComponent : null,
                'withoutReloadLocale' => false,
                'extraClass' => ''
            ])

            <x-landingBuilder-icons-select
                label="{{ trans('update.separator_icon') }}"
                name="contents[separator_icon]"
                value="{{ !empty($contents['separator_icon']) ? $contents['separator_icon'] : '' }}"
                placeholder="{{ trans('update.search_icons') }}"
                hint=""
                selectClassName="js-icons-select2"
                className=""
            />

            <x-landingBuilder-select
                label="{{ trans('update.card_style') }}"
                name="contents[card_style]"
                value="{{ (!empty($contents['card_style'])) ? $contents['card_style'] : '' }}"
                :items="['rotate', 'normal']"
                hint=""
                className=""
                selectClassName=""
                changeActionEls=""
            />

            <x-landingBuilder-switch
                label="{{ trans('update.enable_component') }}"
                id="enable"
                name="enable"
                checked="{{ !!($landingComponent->enable) }}"
                hint=""
                className="mb-0"
            />

        </div>

    </div> {{-- End Col --}}

    <div class="col-12 col-lg-6 mt-20">

        {{-- Title Items --}}
        <div class="p-16 rounded-16 border-gray-200">
            <x-landingBuilder-addable-accordions
                title="{{ trans('update.title_items') }}"
                className="mb-0"
                mainRow="js-title-item-main-card"
            >
                @if(!empty($contents) and !empty($contents['title_items']) and count($contents['title_items']))
                    @foreach($contents['title_items'] as $sKey => $itemData)
                        @if($sKey != 'record')
                            <x-landingBuilder-accordion
                                title="{{ (!empty($itemData) and !empty($itemData['title'])) ? $itemData['title'] : trans('update.title_item') }}"
                                id="title_item_{{ $sKey }}"
                                className=""
                                show=""
                            >
                                @include('landingBuilder.admin.components.manage.links_and_titles_slider_2_rows.item_card',['itemKey' => $sKey, 'titleItem' => $itemData])
                            </x-landingBuilder-accordion>
                        @endif
                    @endforeach
                @endif
            </x-landingBuilder-addable-accordions>
        </div>

    </div>{{-- End Col --}}

</div> {{-- End Row --}}


<div class="js-title-item-main-card d-none">
    <x-landingBuilder-accordion
        title="{{ trans('update.title_item') }}"
        id="record"
        className=""
        show="true"
    >
        @include('landingBuilder.admin.components.manage.links_and_titles_slider_2_rows.item_card')
    </x-landingBuilder-accordion>
</div>
