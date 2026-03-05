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

        {{-- Information Cards --}}
        <div class="p-16 rounded-16 border-gray-200">
            <x-landingBuilder-addable-accordions
                title="{{ trans('update.cards') }}"
                addText="{{ trans('update.add_card') }}"
                className="mb-0"
                mainRow="js-cards-item-main-card"
            >
                @if(!empty($contents) and !empty($contents['information_cards']) and count($contents['information_cards']))
                    @foreach($contents['information_cards'] as $sKey => $itemData)
                        @if($sKey != 'record')
                            <x-landingBuilder-accordion
                                title="{{ (!empty($itemData) and !empty($itemData['title'])) ? $itemData['title'] : trans('update.card') }}"
                                id="card_item_{{ $sKey }}"
                                className=""
                                show=""
                            >
                                @include('landingBuilder.admin.components.manage.information_cards.item_card',['itemKey' => $sKey, 'cardData' => $itemData])
                            </x-landingBuilder-accordion>
                        @endif
                    @endforeach
                @endif
            </x-landingBuilder-addable-accordions>
        </div>

    </div>{{-- End Col --}}

</div> {{-- End Row --}}



<div class="js-cards-item-main-card d-none">
    <x-landingBuilder-accordion
        title="{{ trans('update.new_card') }}"
        id="record"
        className=""
        show="true"
    >
        @include('landingBuilder.admin.components.manage.information_cards.item_card')
    </x-landingBuilder-accordion>
</div>
