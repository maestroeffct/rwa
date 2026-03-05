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

        @foreach(['banner_1', 'banner_2'] as $banner)
            <div class="p-16 rounded-16 border-gray-200 mt-20">
                <h3 class="font-14 mb-24">{{ trans("update.{$banner}") }}</h3>

                @include('landingBuilder.admin.components.manage.banners_grid_3_in_different_sizes.banner_fields',['itemKey' => $banner, 'bannerData' => (!empty($contents) and !empty($contents[$banner])) ? $contents[$banner] : null])
            </div>
        @endforeach


    </div>{{-- End Col --}}

    <div class="col-12 col-lg-6 mt-20">

        @foreach(['banner_3', 'cta_section'] as $banner2)
            <div class="p-16 rounded-16 border-gray-200 {{ $loop->first ? '' : 'mt-20' }}">
                <h3 class="font-14 mb-24">{{ trans("update.{$banner2}") }}</h3>

                @include('landingBuilder.admin.components.manage.banners_grid_3_in_different_sizes.banner_fields',['itemKey' => $banner2, 'bannerData' => (!empty($contents) and !empty($contents[$banner2])) ? $contents[$banner2] : null])
            </div>
        @endforeach

    </div>{{-- End Col --}}

</div>{{-- End Row --}}
