@if(!empty($landingComponent) and $landingComponent->enable)
    @php
        $contents = [];
        if (!empty($landingComponent->content)) {
            $contents = json_decode($landingComponent->content, true);
        }

    @endphp

    @push('styles_top')
        <link rel="stylesheet" href="{{ getLandingComponentStylePath("multi_tab_image_video_placeholder") }}">
    @endpush

    @push('scripts_bottom')
        <script>
            var bolPlayIcon = `<x-iconsax-bol-play class="icons text-white" width="32px" height="32px"/>`;
        </script>
        <script src="{{ getLandingComponentScriptPath("multi_tab_image_video_placeholder") }}"></script>
    @endpush

    <div class="multi-tab-image-video-section position-relative">
        <div class="container">
            <div class="row justify-content-lg-center">
                <div class="col-12 col-lg-10">

                    @if(!empty($contents['main_content']))
                        <div class="d-flex-center flex-column text-center">
                            @if(!empty($contents['main_content']['pre_title']))
                                <div class="d-inline-flex-center py-8 px-16 rounded-8 border-primary bg-primary-20 font-12 text-primary">{{ $contents['main_content']['pre_title'] }}</div>
                            @endif

                            @if(!empty($contents['main_content']['title']))
                                <h2 class="mt-8 font-32 text-dark">{{ $contents['main_content']['title'] }}</h2>
                            @endif

                            @if(!empty($contents['main_content']['description']))
                                <p class="mt-16 text-gray-500">{!! nl2br($contents['main_content']['description']) !!}</p>
                            @endif
                        </div>
                    @endif

                    {{-- Image/Video --}}
                    @php
                        $allMultiTabImageVideoContents = (!empty($contents['image_video_content']) and count($contents['image_video_content'])) ? $contents['image_video_content'] : [];

                        if (!empty($allMultiTabImageVideoContents['record'])) {
                            unset($allMultiTabImageVideoContents['record']);
                        }

                        if (!empty($contents['checked_items']) and !empty($contents['checked_items']['record'])) {
                            unset($contents['checked_items']['record']);
                        }
                    @endphp

                    @if(!empty($allMultiTabImageVideoContents) and count($allMultiTabImageVideoContents))
                        @php
                            $firstMultiTabImageVideoContent = $allMultiTabImageVideoContents[array_key_first($allMultiTabImageVideoContents)];
                        @endphp

                        <div class="js-multi-tab-image-video-content-container position-relative mt-44">
                            <div class="multi-tab-image-video-section__content-background" @if(!empty($contents['background'])) style="background-image: url({{ $contents['background'] }})" @endif></div>

                            <div class="position-relative z-index-3 bg-white p-16 rounded-24 ">
                                <div class="multi-tab-image-video-section__content-box bg-secondary">
                                    @if(!empty($firstMultiTabImageVideoContent) and !empty($firstMultiTabImageVideoContent['content_type']))
                                        @if($firstMultiTabImageVideoContent['content_type'] == "image" and !empty($firstMultiTabImageVideoContent['image']))
                                            @if(!empty($firstMultiTabImageVideoContent['url']))
                                                <a href="{{ $firstMultiTabImageVideoContent['url'] }}" target="_blank" class="">
                                                    @endif

                                                    <img src="{{ $firstMultiTabImageVideoContent['image'] }}" alt="{{ $firstMultiTabImageVideoContent['title'] }}" class="img-cover">

                                                    @if(!empty($firstMultiTabImageVideoContent['url']))
                                                </a>
                                            @endif
                                        @elseif($firstMultiTabImageVideoContent['content_type'] == "video" and !empty($firstMultiTabImageVideoContent['video_file']))
                                            @if(!empty($firstMultiTabImageVideoContent['video_cover']))
                                                <img src="{{ $firstMultiTabImageVideoContent['video_cover'] }}" alt="cover image" class="img-cover">
                                            @endif

                                            <div class="multi-tab-image-video-section__content-video-btn d-flex-center rounded-circle size-92 cursor-pointer"
                                                 data-path="{{ $firstMultiTabImageVideoContent['video_file'] }}"
                                                 data-source="upload"
                                                 data-id="{{ random_str(6) }}"
                                            >
                                                <x-iconsax-bol-play class="icons text-white" width="32px" height="32px"/>
                                            </div>
                                        @endif
                                    @endif
                                </div>

                                {{-- Tab --}}
                                @if(count($allMultiTabImageVideoContents) > 1)
                                    @php
                                        $multiTabImageVideoContentColumnNum = (count($allMultiTabImageVideoContents) < 4) ? count($allMultiTabImageVideoContents) : 4;
                                    @endphp

                                    <div class="multi-tab-image-video-section__content-tabs mt-16 {{ (count($allMultiTabImageVideoContents) > 4) ? 'has-more-then-four-item' : "less-then-four-item" }}" @if((count($allMultiTabImageVideoContents) > 4)) data-simplebar @endif>
                                        <div class="d-grid gap-16">
                                            @foreach($allMultiTabImageVideoContents as $multiTabImageVideoContent)
                                                @if(
                                                    !empty($multiTabImageVideoContent['title'])
                                                    and (
                                                        ($multiTabImageVideoContent['content_type'] == "image" and !empty($multiTabImageVideoContent['image'])) or
                                                        ($multiTabImageVideoContent['content_type'] == "video" and !empty($multiTabImageVideoContent['video_file']))
                                                        )
                                                    )
                                                    <div class="multi-tab-image-video-section__content-tabs-item d-flex align-items-center gap-8 p-16 rounded-16 font-16 font-weight-bold cursor-pointer {{ $loop->first ? 'active' : '' }}"
                                                        data-title="{{ $multiTabImageVideoContent['title'] }}"
                                                        data-type="{{ $multiTabImageVideoContent['content_type'] }}"
                                                        data-image="{{ $multiTabImageVideoContent['image'] ?? '' }}"
                                                        data-url="{{ $multiTabImageVideoContent['url'] ?? '' }}"
                                                        data-video="{{ $multiTabImageVideoContent['video_file'] ?? '' }}"
                                                        data-cover="{{ $multiTabImageVideoContent['video_cover'] ?? '' }}"
                                                    >
                                                        @if(!empty($multiTabImageVideoContent['icon']))
                                                            @svg("iconsax-{$multiTabImageVideoContent['icon']}", ['width' => '32px', 'height' => '32px', 'class' => "icons text-primary"])
                                                        @endif

                                                        <span class="">{{ $multiTabImageVideoContent['title'] }}</span>
                                                    </div>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif

                    {{-- Checked Items --}}
                    @if(!empty($contents['checked_items']) and count($contents['checked_items']))
                        <div class="d-flex flex-column flex-lg-row flex-lg-wrap align-items-lg-center justify-content-lg-center gap-20 gap-lg-80 mt-32">
                            @foreach($contents['checked_items'] as $multiTabImageVideoCheckedItem)
                                @if(!empty($multiTabImageVideoCheckedItem['title']) and !empty($multiTabImageVideoCheckedItem['subtitle']))
                                    <div class="d-flex align-items-center">
                                        <x-tick-icon class="icons text-success" width="16px" height="16px"/>
                                        <div class="ml-8">
                                            <h5 class="font-16 text-dark">{{ $multiTabImageVideoCheckedItem['title'] }}</h5>
                                            <p class="text-gray-500 mt-2">{{ $multiTabImageVideoCheckedItem['subtitle'] }}</p>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    @endif

                </div>
            </div>
        </div>

    </div>
@endif
