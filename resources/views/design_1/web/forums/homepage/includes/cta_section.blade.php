@php
    $ctaSectionSettings = getForumsCtaSectionSettings();
@endphp

@if(!empty($ctaSectionSettings))
    <div class="container">
        <div class="forum-cta-section position-relative d-flex flex-column flex-lg-row align-items-start gap-24 px-24 px-lg-48 rounded-32 section_bg">
            <div class="forum-cta-section__content flex-1 py-48 pr-lg-48">
                @if(!empty($ctaSectionSettings['badge']) and !empty($ctaSectionSettings['badge']['title']) and !empty($ctaSectionSettings['badge']['color']))
                    <div class="forum-extra-badge d-inline-flex-center px-16 py-8 rounded-8 font-12"
                         style="color: {{ $ctaSectionSettings['badge']['color'] }}; border-color: {{ $ctaSectionSettings['badge']['color'] }}"
                    >
                        <span class="forum-extra-badge__mask" style="background-color: {{ $ctaSectionSettings['badge']['color'] }}"></span>
                        <span class="z-index-2">{{ $ctaSectionSettings['badge']['title'] }}</span>
                    </div>
                @endif

                @if(!empty($ctaSectionSettings['title']))
                    <h1 class="forum-hero-title mt-8">{{ $ctaSectionSettings['title'] }}</h1>
                @endif

                @if(!empty($ctaSectionSettings['description']))
                    <div class="mt-20 font-16 text-gray-500">{!! nl2br($ctaSectionSettings['description']) !!}</div>
                @endif

                <div class="d-flex flex-column flex-lg-row align-items-lg-center mt-20 gap-24">
                    @if(!empty($ctaSectionSettings['button1']) and !empty($ctaSectionSettings['button1']['title']))
                        <a href="{{ !empty($ctaSectionSettings['button1']['link']) ? $ctaSectionSettings['button1']['link'] : '#!' }}" class="btn btn-primary btn-xlg">
                            {{ $ctaSectionSettings['button1']['title'] }}
                        </a>
                    @endif

                    @if(!empty($ctaSectionSettings['button2']) and !empty($ctaSectionSettings['button2']['title']))
                        <a href="{{ !empty($ctaSectionSettings['button2']['link']) ? $ctaSectionSettings['button2']['link'] : '#!' }}" class="btn bg-transparent btn-xlg border-gray-200 text-gray-500">
                            {{ $ctaSectionSettings['button2']['title'] }}
                        </a>
                    @endif
                </div>
            </div>

            @if(!empty($ctaSectionSettings['image']))
                <div class="forum-cta-section__image">
                    <img src="{{ $ctaSectionSettings['image'] }}" alt="{{ trans('update.cta_section') }}" class="img-cover">
                </div>
            @endif
        </div>
    </div>
@endif
