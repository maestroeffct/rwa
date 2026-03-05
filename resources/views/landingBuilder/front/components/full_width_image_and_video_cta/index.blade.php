@if(!empty($landingComponent) and $landingComponent->enable)
    @php
        $contents = [];
        if (!empty($landingComponent->content)) {
            $contents = json_decode($landingComponent->content, true);
        }

    @endphp

    @push('styles_top')
        <link rel="stylesheet" href="{{ getLandingComponentStylePath("full_width_image_and_video_cta") }}">
    @endpush

    <div class="full-width-image-and-video-cta-section position-relative">
        <div class="full-width-image-and-video-cta-section__contents">
            <div class="container position-relative z-index-2">
                <div class="row">
                    <div class="col-12 col-lg-6 ">
                        @if(!empty($contents['main_content']))
                            @if(!empty($contents['main_content']['pre_title']))
                                <div class="d-inline-flex-center gap-8 py-12 px-16 rounded-32 border-white border-2 bg-white-10 font-12 text-white">
                                    @if(!empty($contents['main_content']['pre_title_icon']))
                                        <div class="d-flex-center size-24">
                                            @svg("iconsax-{$contents['main_content']['pre_title_icon']}", ['width' => '24px', 'height' => '24px', 'class' => "icons text-white"])
                                        </div>
                                    @endif

                                    <span class="font-12">{{ $contents['main_content']['pre_title'] }}</span>
                                </div>
                            @endif

                            @if(!empty($contents['main_content']['title']))
                                <h2 class="mt-16 font-44 text-white">{{ $contents['main_content']['title'] }}</h2>
                            @endif

                            @if(!empty($contents['main_content']['description']))
                                <p class="mt-16 font-16 text-white opacity-70">{!! nl2br($contents['main_content']['description']) !!}</p>
                            @endif

                            {{-- Button --}}
                            @if(!empty($contents['main_content']['button']) and !empty($contents['main_content']['button']['label']))
                                <a href="{{ !empty($contents['main_content']['button']['url']) ? $contents['main_content']['button']['url'] : '' }}" target="_blank" class="btn-flip-effect btn btn-primary btn-xlg gap-8 text-white mt-32" data-text="{{ $contents['main_content']['button']['label'] }}">
                                    @if(!empty($contents['main_content']['button']['icon']))
                                        @svg("iconsax-{$contents['main_content']['button']['icon']}", ['width' => '24px', 'height' => '24px', 'class' => "icons"])
                                    @endif

                                    <span class="btn-flip-effect__text">{{ $contents['main_content']['button']['label'] }}</span>
                                </a>
                            @endif
                        @endif

                        {{-- Statistics --}}
                        @if((!empty($contents['statistic_1']) and !empty($contents['statistic_1']['title'])) or (!empty($contents['statistic_2']) and !empty($contents['statistic_2']['title'])))
                            <div class="d-flex align-items-center mt-28 gap-32">
                                @if(!empty($contents['statistic_1']) and !empty($contents['statistic_1']['title']))
                                    <div class="full-width-image-and-video-cta-section__statistic-item">
                                        <div class="font-24 font-weight-bold text-white">{{ $contents['statistic_1']['title'] }}</div>

                                        @if(!empty($contents['statistic_1']['subtitle']))
                                            <p class="mt-4 font-16 text-white opacity-70">{{ $contents['statistic_1']['subtitle'] }}</p>
                                        @endif
                                    </div>
                                @endif

                                @if((!empty($contents['statistic_1']) and !empty($contents['statistic_1']['title'])) and (!empty($contents['statistic_2']) and !empty($contents['statistic_2']['title'])))
                                    <div class="full-width-image-and-video-cta-section__statistic-divider"></div>
                                @endif

                                @if(!empty($contents['statistic_2']) and !empty($contents['statistic_2']['title']))
                                    <div class="full-width-image-and-video-cta-section__statistic-item">
                                        <div class="font-24 font-weight-bold text-white">{{ $contents['statistic_2']['title'] }}</div>

                                        @if(!empty($contents['statistic_2']['subtitle']))
                                            <p class="mt-4 font-16 text-white opacity-70">{{ $contents['statistic_2']['subtitle'] }}</p>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        @endif

                    </div>
                </div>
            </div>
        </div>


        {{-- Images --}}
        @if(!empty($contents['media_contents']))
            <div class="full-width-image-and-video-cta-section__media-contents">
                @if(!empty($contents['enable_overlay']))
                    <div class="full-width-image-and-video-cta-section__media-contents-overlay"></div>
                @endif

                @if(!empty($contents['media_contents']['content_type']) and $contents['media_contents']['content_type'] == "video")
                    @php
                        $videoPath = null;

                        if (!empty($contents['media_contents']['video_file'])) {
                            $videoPath = $contents['media_contents']['video_file'];
                        }
                    @endphp

                    @if(!empty($videoPath))
                        <video class="img-cover" data-value-1="1" data-value-2="1.1" autoplay="autoplay" loop="loop" muted="" playsinline="" oncontextmenu="return false" preload="auto">
                            <source src="{{ $videoPath }}" type="video/mp4"/>
                        </video>
                    @endif
                @elseif(!empty($contents['media_contents']['content_type']) and $contents['media_contents']['content_type'] == "image" and !empty($contents['media_contents']['image']))
                    <img src="{{ $contents['media_contents']['image'] }}" alt="image" class="img-cover">
                @endif

            </div>
        @endif

    </div>
@endif
