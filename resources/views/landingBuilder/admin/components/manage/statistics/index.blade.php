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

            <x-landingBuilder-select
                label="{{ trans('update.background_color') }}"
                name="contents[background_color]"
                value="{{ (!empty($contents['background_color'])) ? $contents['background_color'] : '' }}"
                :items="['primary', 'secondary']"
                hint=""
                className=""
                selectClassName=""
                changeActionEls=""
            />

            <x-landingBuilder-file
                label="{{ trans('update.background') }}"
                name="contents[background]"
                value="{{ !empty($contents['background']) ? $contents['background'] : '' }}"
                placeholder="{{ !empty($contents['background']) ? getFileNameByPath($contents['background']) : '' }}"
                hint="{{ trans('update.preferred_size') }} 64x64px"
                icon="export"
                accept="image/*"
                className=""
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
        {{-- Statistics --}}
        <div class="p-16 rounded-16 border-gray-200">
            <x-landingBuilder-addable-accordions
                title="{{ trans('update.statistics') }}"
                className="mb-0"
                mainRow="js-statistic-item-main-card"
            >
                @if(!empty($contents) and !empty($contents['statistics']) and count($contents['statistics']))
                    @foreach($contents['statistics'] as $sKey => $statisticData)
                        @if($sKey != 'record')
                            <x-landingBuilder-accordion
                                title="{{ (!empty($statisticData) and !empty($statisticData['title'])) ? $statisticData['title'] : trans('update.statistic_item') }}"
                                id="statistic_{{ $sKey }}"
                                className=""
                                show=""
                            >
                                @include('landingBuilder.admin.components.manage.statistics.statistic_item',['itemKey' => $sKey,'statistic' => $statisticData])
                            </x-landingBuilder-accordion>
                        @endif
                    @endforeach
                @endif
            </x-landingBuilder-addable-accordions>
        </div>
    </div>{{-- End Col --}}
</div> {{-- End Row --}}


<div class="js-statistic-item-main-card d-none">
    <x-landingBuilder-accordion
        title="{{ trans('update.statistic_item') }}"
        id="record"
        className=""
        show="true"
    >
        @include('landingBuilder.admin.components.manage.statistics.statistic_item')
    </x-landingBuilder-accordion>
</div>
