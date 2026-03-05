@if(!empty($landingComponent) and $landingComponent->enable)
    @php
        $contents = [];
        if (!empty($landingComponent->content)) {
            $contents = json_decode($landingComponent->content, true);
        }
    @endphp


    @push('styles_top')
        <link rel="stylesheet" href="{{ getLandingComponentStylePath("single_video_section") }}">
    @endpush

    <div class="single-video-section container position-relative">
        <div class="position-relative z-index-2  pt-80">
            <div class="d-flex-center flex-column text-center">
                @if(!empty($contents['main_content']) and !empty($contents['main_content']['pre_title']))
                    <div class="d-inline-flex-center py-8 px-16 rounded-8 border-primary bg-primary-20 font-12 text-primary">{{ $contents['main_content']['pre_title'] }}</div>
                @endif

                @if(!empty($contents['main_content']) and !empty($contents['main_content']['title']))
                    <h2 class="mt-16 font-32 text-dark">{{ $contents['main_content']['title'] }}</h2>
                @endif

                @if(!empty($contents['main_content']) and !empty($contents['main_content']['description']))
                    <p class="mt-20 font-16 text-gray-500">{!! nl2br($contents['main_content']['description']) !!}</p>
                @endif
            </div>

            @if(!empty($contents['video_content']))
                <div class="single-video-section__video-box position-relative mt-48">
                    <div class="position-relative w-100 h-100 z-index-3">
                        @if(!empty($contents['video_content']['video_file']))
                            <video class="img-cover rounded-32" data-value-1="1" data-value-2="1.1" autoplay="autoplay" loop="loop" muted="" playsinline="" oncontextmenu="return false" preload="auto">
                                <source src="{{ $contents['video_content']['video_file'] }}" type="video/mp4"/>
                            </video>
                        @endif

                        <div class="single-video-section__center-box d-flex-center">
                            @if(!empty($contents['video_content']['revolver_icon']))
                                <div class="single-video-section__revolver-icon d-flex-center">
                                    <img src="{{ $contents['video_content']['revolver_icon'] }}" alt="revolver_icon" class="">
                                </div>
                            @endif

                            @if(!empty($contents['video_content']['display_play_button']) and $contents['video_content']['display_play_button'] == "on")
                                <div class="single-video-section__play-button d-flex-center size-80 bg-primary">
                                    <x-iconsax-bol-play class="icons text-white" width="32px" height="32px"/>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{--back_image --}}
                    @if(!empty($contents['main_content']) and !empty($contents['main_content']['back_image']))
                        <div class="single-video-section__back-image d-flex-center">
                            <img src="{{ $contents['main_content']['back_image'] }}" alt="back_image" class="">
                        </div>
                    @endif

                    {{--back_image --}}
                    @if(!empty($contents['main_content']) and !empty($contents['main_content']['badge_title']))
                        <div class="single-video-section__badge d-inline-flex-center">{{ $contents['main_content']['badge_title'] }}</div>
                    @endif
                </div>
            @endif
        </div>
    </div>
@endif
