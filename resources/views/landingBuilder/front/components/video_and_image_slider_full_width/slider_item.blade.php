@if(!empty($sliderData['url']))
    <a href="{{ $sliderData['url'] }}" target="_blank" class="">
        @endif

        <div class="video-and-image-slider-full-width-section__slider-item position-relative rounded-32">
            @if(!empty($sliderData['image']))
                <img src="{{ $sliderData['image'] }}" alt="cover image" class="slider-item-cover-img img-cover rounded-32">
            @endif

            @if(!empty($sliderData['content_type']) and $sliderData['content_type'] == "video" and !empty($sliderData['video_source']))
                @php
                    $videoPath = null;

                    if (in_array($sliderData['video_source'], ['youtube', 'vimeo', 'external', 'iframe']) and !empty($sliderData['video_path'])) {
                        $videoPath = $sliderData['video_path'];
                    } elseif (in_array($sliderData['video_source'], ['upload']) and !empty($sliderData['video_file'])) {
                        $videoPath = $sliderData['video_file'];
                    }
                @endphp

                @if(!empty($videoPath))
                    <div class="video-and-image-slider-full-width-section__slider-item-video-btn d-flex-center rounded-circle size-92 cursor-pointer"
                         data-path="{{ $videoPath }}"
                         data-source="{{ $sliderData['video_source'] }}"
                         data-id="{{ random_str(6) }}"
                    >
                        <x-iconsax-bol-play class="icons text-white" width="32px" height="32px"/>
                    </div>
                @endif
            @endif


            <div class="video-and-image-slider-full-width-section__slider-item-footer">
                <div class="row w-100 h-100">
                    <div class="col-12 col-lg-7 h-100 d-flex flex-column justify-content-end align-items-start p-24">
                        @if(!empty($sliderData['pre_title']))
                            <div class="d-inline-flex-center py-8 px-16 rounded-8 border-white bg-white-20 font-12 text-white">{{ $sliderData['pre_title'] }}</div>
                        @endif

                        @if(!empty($sliderData['title']))
                            <h2 class="mt-12 font-44 text-white">{{ $sliderData['title'] }}</h2>
                        @endif

                        @if(!empty($sliderData['description']))
                            <p class="mt-12 font-16 text-white opacity-70">{!! nl2br($sliderData['description']) !!}</p>
                        @endif

                    </div>
                </div>
            </div>
        </div>

        @if(!empty($sliderData['url']))
    </a>
@endif

