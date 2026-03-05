@if(!empty($landingComponent) and $landingComponent->enable)
    @php
        $contents = [];
        if (!empty($landingComponent->content)) {
            $contents = json_decode($landingComponent->content, true);
        }

        $frontComponentsDataMixins = (new \App\Mixins\LandingBuilder\FrontComponentsDataMixins());
    @endphp


    @push('styles_top')
        <link rel="stylesheet" href="{{ getLandingComponentStylePath("meeting_packages_grid") }}">
    @endpush

    <div class="meeting-packages-grid-section position-relative" @if(!empty($contents['background'])) style="background-image: url({{ $contents['background'] }})" @endif>
        <div class="container position-relative">
            <div class="d-flex-center flex-column text-center">
                @if(!empty($contents['main_content']) and !empty($contents['main_content']['pre_title']))
                    <div class="d-inline-flex-center py-8 px-16 rounded-8 border-primary bg-primary-20 font-12 text-primary">{{ $contents['main_content']['pre_title'] }}</div>
                @endif

                @if(!empty($contents['main_content']) and !empty($contents['main_content']['title']))
                    <h2 class="mt-12 font-32 text-dark">{{ $contents['main_content']['title'] }}</h2>
                @endif

                @if(!empty($contents['main_content']) and !empty($contents['main_content']['description']))
                    <p class="mt-16 font-16 text-gray-500">{!! nl2br($contents['main_content']['description']) !!}</p>
                @endif
            </div>

            {{-- List --}}
            @php
                $ids = [];
                if(!empty($contents['featured_packages']) and is_array($contents['featured_packages'])) {
                    foreach ($contents['featured_packages'] as $meetingPackageData) {
                        if (!empty($meetingPackageData['meeting_package'])){
                            $ids[] = $meetingPackageData['meeting_package'];
                        }
                    }
                }

                $meetingPackagesGrid = $frontComponentsDataMixins->getMeetingPackagesByIds($ids);
            @endphp

            @if($meetingPackagesGrid->isNotEmpty())
                <div class="row">
                    @include('design_1.web.meeting_packages.components.cards.grids.index',['meetingPackages' => $meetingPackagesGrid, 'gridCardClassName' => "col-12 col-md-6 col-lg-3 mt-24"])
                </div>
            @endif

            {{-- CTA --}}
            <div class="d-flex-center flex-column text-lg-center mt-48">
                @if(!empty($contents['cta_section']))
                    <div class="d-flex align-items-lg-center gap-4">
                        @if(!empty($contents['cta_section']['icon']))
                            <div class="d-flex-center size-24">
                                @svg("iconsax-{$contents['cta_section']['icon']}", ['width' => '24px', 'height' => '24px', 'class' => "icons text-dark"])
                            </div>
                        @endif

                        <div class="d-flex flex-column flex-lg-row align-items-lg-center gap-4">
                            @if(!empty($contents['cta_section']['title_bold_text']))
                                <h5 class="font-14">{{ $contents['cta_section']['title_bold_text'] }}</h5>
                            @endif

                            @if(!empty($contents['cta_section']['title_regular_text']))
                                <div class="">{{ $contents['cta_section']['title_regular_text'] }}</div>
                            @endif
                        </div>
                    </div>

                    @if(!empty($contents['cta_section']['title_regular_text']))
                        <div class="font-16 text-gray-500 mt-16">{{ $contents['cta_section']['title_regular_text'] }}</div>
                    @endif
                @endif
            </div>

            {{-- Primary Button --}}
            @if(!empty($contents['main_content']['button']) and !empty($contents['main_content']['button']['label']))
                <div class="d-flex-center flex-column text-center mt-24">

                    <a href="{{ !empty($contents['main_content']['button']['url']) ? $contents['main_content']['button']['url'] : '' }}" class="btn-flip-effect btn btn-primary btn-xlg gap-8 text-white" data-text="{{ $contents['main_content']['button']['label'] }}">
                        @if(!empty($contents['main_content']['button']['icon']))
                            @svg("iconsax-{$contents['main_content']['button']['icon']}", ['width' => '24px', 'height' => '24px', 'class' => "icons"])
                        @endif

                        <span class="btn-flip-effect__text text-white">{{ $contents['main_content']['button']['label'] }}</span>
                    </a>

                </div>
            @endif

            {{-- floating_images --}}
            @if(!empty($contents['floating_images']))
                @if(!empty($contents['floating_images']['image_1']))
                    <div class="meeting-packages-grid-section__floating-image-1 d-flex-center">
                        <img src="{{ $contents['floating_images']['image_1'] }}" alt="floating image 1">
                    </div>
                @endif

                @if(!empty($contents['floating_images']['image_2']))
                    <div class="meeting-packages-grid-section__floating-image-2 d-flex-center">
                        <img src="{{ $contents['floating_images']['image_2'] }}" alt="floating image 2">
                    </div>
                @endif
            @endif

        </div>
    </div>
@endif
