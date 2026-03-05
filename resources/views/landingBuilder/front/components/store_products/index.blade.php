@if(!empty($landingComponent) and $landingComponent->enable)
    @php
        $contents = [];
        if (!empty($landingComponent->content)) {
            $contents = json_decode($landingComponent->content, true);
        }

        $frontComponentsDataMixins = (new \App\Mixins\LandingBuilder\FrontComponentsDataMixins());
        $newProducts = $frontComponentsDataMixins->getStoreProductsData();
    @endphp

    @if($newProducts->isNotEmpty())
        @push('styles_top')
            <link rel="stylesheet" href="{{ getLandingComponentStylePath("store_products") }}">
        @endpush

        <div class="store-products-section position-relative" @if(!empty($contents['background'])) style="background-image: url({{ $contents['background'] }})" @endif>
            <div class="container position-relative h-100">
                <div class="d-flex-center flex-column text-center">
                    @if(!empty($contents['main_content']) and !empty($contents['main_content']['pre_title']))
                        <div class="d-inline-flex-center py-8 px-16 rounded-8 border-primary bg-primary-20 font-12 text-primary">{{ $contents['main_content']['pre_title'] }}</div>
                    @endif

                    @if(!empty($contents['main_content']) and !empty($contents['main_content']['title']))
                        <h2 class="mt-8 font-32 text-dark">{{ $contents['main_content']['title'] }}</h2>
                    @endif

                    @if(!empty($contents['main_content']) and !empty($contents['main_content']['subtitle']))
                        <p class="mt-16 font-16 text-gray-500">{!! nl2br($contents['main_content']['subtitle']) !!}</p>
                    @endif
                </div>


                <div class="row">
                    @include('design_1.web.products.components.cards.grids.index',['products' => $newProducts, 'gridCardClassName' => "col-12 col-md-6 col-lg-4 mt-24"])
                </div>

                {{-- Button --}}
                @if(!empty($contents['main_content']) and !empty($contents['main_content']['button']) and !empty($contents['main_content']['button']['label']))
                    <div class="d-flex-center mt-32">
                        <a href="{{ !empty($contents['main_content']['button']['url']) ? $contents['main_content']['button']['url'] : '' }}" target="_blank" class="btn-flip-effect btn btn-primary btn-xlg gap-8 text-white" data-text="{{ $contents['main_content']['button']['label'] }}">
                            @if(!empty($contents['main_content']['button']['icon']))
                                @svg("iconsax-{$contents['main_content']['button']['icon']}", ['width' => '24px', 'height' => '24px', 'class' => "icons"])
                            @endif

                            <span class="btn-flip-effect__text">{{ $contents['main_content']['button']['label'] }}</span>
                        </a>
                    </div>
                @endif

            </div>
        </div>
    @endif
@endif
