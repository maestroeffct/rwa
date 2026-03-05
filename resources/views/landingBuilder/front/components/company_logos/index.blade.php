@if(!empty($landingComponent) and $landingComponent->enable)
    @php
        $contents = [];
        if (!empty($landingComponent->content)) {
            $contents = json_decode($landingComponent->content, true);
        }

    @endphp

    @push('styles_top')
        <link rel="stylesheet" href="/assets/vendors/aos-animate/aos.css">
        <link rel="stylesheet" href="{{ getLandingComponentStylePath("company_logos") }}">
    @endpush

    <div class="container">
        <div class="company-logos-section position-relative">
            <div class="row align-items-center h-100">
                <div class="col-12 col-lg-5">
                    @if(!empty($contents['main_content']) and !empty($contents['main_content']['pre_title']))
                        <div class="d-inline-flex-center py-8 px-16 rounded-8 border-primary bg-primary-20 font-12 text-primary">{{ $contents['main_content']['pre_title'] }}</div>
                    @endif

                    @if(!empty($contents['main_content']) and !empty($contents['main_content']['title']))
                        <h2 class="mt-12 font-32 text-dark">{{ $contents['main_content']['title'] }}</h2>
                    @endif

                    @if(!empty($contents['main_content']) and !empty($contents['main_content']['description']))
                        <p class="mt-20 font-16 text-gray-500">{!! nl2br($contents['main_content']['description']) !!}</p>
                    @endif

                    {{-- Button --}}
                    @if(!empty($contents['main_content']) and !empty($contents['main_content']['button']) and !empty($contents['main_content']['button']['label']))
                        <a href="{{ !empty($contents['main_content']['button']['url']) ? $contents['main_content']['button']['url'] : '' }}" target="_blank" class="btn-flip-effect btn btn-primary btn-xlg gap-8 text-white mt-32" data-text="{{ $contents['main_content']['button']['label'] }}">
                            @if(!empty($contents['main_content']['button']['icon']))
                                @svg("iconsax-{$contents['main_content']['button']['icon']}", ['width' => '24px', 'height' => '24px', 'class' => "icons"])
                            @endif

                            <span class="btn-flip-effect__text">{{ $contents['main_content']['button']['label'] }}</span>
                        </a>
                    @endif
                </div>

                <div class="col-lg-1"></div>

                <div class="col-12 col-lg-6 mt-28 mt-lg-0">
                    <div class="d-grid grid-columns-3 gap-24 w-100">
                        @if(!empty($contents['companies_widget_logos']) and is_array($contents['companies_widget_logos']))
                            @foreach($contents['companies_widget_logos'] as $logo)
                                <div class="company-logos-section__logo-card d-flex-center text-center bg-white rounded-16 p-24 aos-item" data-aos="zoom-in" data-aos-delay="{{ (($loop->iteration * 100) + 200) }}">
                                    <img src="{{ $logo }}" alt="logo" class="">
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>
@endif

@push('scripts_bottom')
    <script src="/assets/vendors/aos-animate/aos.js"></script>
    <script>
        AOS.init({
            easing: 'ease-in-out-sine'
        });
    </script>
@endpush
