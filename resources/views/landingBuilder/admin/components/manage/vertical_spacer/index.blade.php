<div class="row">
    <div class="col-12 col-lg-6 mt-20">
        {{-- General Information --}}
        <div class="p-16 rounded-16 border-gray-200">
            <h3 class="font-14 mb-24">{{ trans('update.general_information') }}</h3>


            <x-landingBuilder-input
                label="{{ trans('update.space_number') }}"
                name="contents[space_number]"
                value="{{ (!empty($contents['space_number'])) ? $contents['space_number'] : '' }}"
                type="number"
                placeholder=""
                hint="{{ trans('update.space_number_input_hint') }}"
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

    </div>{{-- End Col --}}


</div>{{-- End Row --}}

