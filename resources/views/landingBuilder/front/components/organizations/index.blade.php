@if(!empty($landingComponent) and $landingComponent->enable)
    @php
        $contents = [];
        if (!empty($landingComponent->content)) {
            $contents = json_decode($landingComponent->content, true);
        }

        $frontComponentsDataMixins = (new \App\Mixins\LandingBuilder\FrontComponentsDataMixins());
    @endphp


    @push('styles_top')
        <link rel="stylesheet" href="{{ getLandingComponentStylePath("organizations") }}">
    @endpush

    <div class="organizations-section position-relative" @if(!empty($contents['background'])) style="background-image: url({{ $contents['background'] }})" @endif>
        <div class="container">
            <div class="d-flex-center flex-column text-center">
                @if(!empty($contents['main_content']) and !empty($contents['main_content']['pre_title']))
                    <div class="d-inline-flex-center py-8 px-16 rounded-8 border-primary bg-primary-20 font-12 text-primary">{{ $contents['main_content']['pre_title'] }}</div>
                @endif

                @if(!empty($contents['main_content']) and !empty($contents['main_content']['title']))
                    <h2 class="font-32 text-dark mt-16">{{ $contents['main_content']['title'] }}</h2>
                @endif

                @if(!empty($contents['main_content']) and !empty($contents['main_content']['description']))
                    <p class="mt-16 font-16 text-gray-500">{!! nl2br($contents['main_content']['description']) !!}</p>
                @endif
            </div>
        </div>

        {{-- List --}}
        @php
            $ids = [];
            if (!empty($contents['specific_organizations']) and is_array($contents['specific_organizations'])) {
                foreach ($contents['specific_organizations'] as $organizationData) {
                    if (!empty($organizationData['organization_id'])) {
                        $ids[] = $organizationData['organization_id'];
                    }
                }
            }

            $organizations = $frontComponentsDataMixins->getUsersByIds($ids, \App\Models\Role::$organization);
        @endphp

        @if($organizations->isNotEmpty())
            <div class="swiper-container js-make-swiper organizations-swiper-rtl pt-48 px-24"
                 data-item="organizations-swiper-rtl"
                 data-autoplay="{{ ($organizations->count() > 5) ? 'true' : 'false' }}"
                 data-loop="{{ ($organizations->count() > 5) ? 'true' : 'false' }}"
                 data-free-mode="{{ ($organizations->count() > 5) ? 'true' : 'false' }}"
                 data-reverse-direction="false"
                 data-autoplay-delay="1500"
                 data-breakpoints="1200:5,991:3,660:2"
            >
                <div class="swiper-wrapper pb-8">
                    @include('design_1.web.organizations.components.cards.grids.index', ['organizations' => $organizations, 'gridCardClassName' => "swiper-slide"])
                </div>
            </div>
        @endif

        {{-- Primary Button --}}
        @if(!empty($contents['main_content']['button']) and !empty($contents['main_content']['button']['label']))
            <div class="container d-flex-center mt-24">
                <a href="{{ !empty($contents['main_content']['button']['url']) ? $contents['main_content']['button']['url'] : '' }}" class="btn-flip-effect btn btn-primary text-white btn-xlg gap-8" data-text="{{ $contents['main_content']['button']['label'] }}">
                    @if(!empty($contents['main_content']['button']['icon']))
                        @svg("iconsax-{$contents['main_content']['button']['icon']}", ['width' => '24px', 'height' => '24px', 'class' => "icons"])
                    @endif

                    <span class="btn-flip-effect__text text-white">{{ $contents['main_content']['button']['label'] }}</span>
                </a>
            </div>
        @endif

    </div>
@endif
