@if(!empty($landingComponent) and $landingComponent->enable)
    @php
        $contents = [];
        if (!empty($landingComponent->content)) {
            $contents = json_decode($landingComponent->content, true);
        }

    @endphp


    @push('styles_top')
        <link rel="stylesheet" href="{{ getLandingComponentStylePath("hybrid_information_section_2_images_check_items_text") }}">
    @endpush

    <div class="hybrid-information-section-2-images-check-items-text-section position-relative ">
        <div class="container position-relative h-100">
            <div class="row h-100 {{ (!empty($contents['reverse_direction']) and $contents['reverse_direction'] == "on") ? 'flex-row-reverse' : '' }} ">
                <div class="col-12 col-lg-6 h-100 px-24 px-lg-48">
                    <div class="hybrid-information-section-2-images-check-items-text-section__main-image position-relative rounded-32">
                        @if(!empty($contents['image_content']))
                            @if(!empty($contents['image_content']['main_image']))
                                <img src="{{ $contents['image_content']['main_image'] }}" alt="main_image" class="img-cover rounded-32">
                            @endif

                            @if(!empty($contents['image_content']['overlay_image']))
                                <div class="hybrid-information-section-2-images-check-items-text-section__overlay-image">
                                    <img src="{{ $contents['image_content']['overlay_image'] }}" alt="overlay_image" class="img-cover rounded-16">
                                </div>
                            @endif
                        @endif
                    </div>
                </div>

                <div class="col-lg-1"></div>

                <div class="col-12 col-lg-5 mt-32 mt-lg-0">
                    <div class="d-flex justify-content-center align-items-start flex-column h-100">
                        @if(!empty($contents['main_content']) and !empty($contents['main_content']['pre_title']))
                            <div class="d-inline-flex-center py-8 px-16 rounded-8 border-primary bg-primary-20 font-12 text-primary">{{ $contents['main_content']['pre_title'] }}</div>
                        @endif

                        @if(!empty($contents['main_content']) and !empty($contents['main_content']['title']))
                            <h2 class="mt-12 font-32 text-dark">{{ $contents['main_content']['title'] }}</h2>
                        @endif

                        @if(!empty($contents['main_content']) and !empty($contents['main_content']['description']))
                            <p class="mt-20 font-16 text-gray-500">{!! nl2br($contents['main_content']['description']) !!}</p>
                        @endif

                        {{-- Checked Items --}}
                        @if(!empty($contents['checked_items']) and is_array($contents['checked_items']))
                            @foreach($contents['checked_items'] as $checkedItem)
                                <div class="d-flex align-items-center gap-4 {{ $loop->first ? 'mt-24' : 'mt-12' }}">
                                    <x-tick-icon class="icons text-success" width="16px" height="16px"/>
                                    <span class="font-14 text-gray-500">{{ $checkedItem }}</span>
                                </div>
                            @endforeach
                        @endif

                        {{-- Button --}}
                        @if(!empty($contents['main_content']) and !empty($contents['main_content']['button']) and !empty($contents['main_content']['button']['label']))
                            <a href="{{ !empty($contents['main_content']['button']['url']) ? $contents['main_content']['button']['url'] : '' }}" target="_blank" class="btn-flip-effect btn btn-primary btn-xlg gap-8 text-white mt-24" data-text="{{ $contents['main_content']['button']['label'] }}">
                                @if(!empty($contents['main_content']['button']['icon']))
                                    @svg("iconsax-{$contents['main_content']['button']['icon']}", ['width' => '24px', 'height' => '24px', 'class' => "icons"])
                                @endif

                                <span class="btn-flip-effect__text">{{ $contents['main_content']['button']['label'] }}</span>
                            </a>
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div>
@endif
