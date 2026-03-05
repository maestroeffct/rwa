@if(!empty($landingComponent) and $landingComponent->enable)
    @php
        $contents = [];
        if (!empty($landingComponent->content)) {
            $contents = json_decode($landingComponent->content, true);
        }
    @endphp

    @push('styles_top')
        <link rel="stylesheet" href="{{ getLandingComponentStylePath("single_instructor_hero") }}">
    @endpush

    <div class="single-instructor-hero-section " @if(!empty($contents['background'])) style="background-image: url({{ $contents['background'] }})" @endif>
        <div class="container h-100">
            <div class="row h-100">
                <div class="col-12 col-lg-6 single-instructor-hero-section__contents">

                    <div class="d-flex align-items-center gap-8">
                        {{-- Welcome --}}
                        @if(!empty($contents['main_content']) and !empty($contents['main_content']['welcome_text']))
                            <div class="d-flex align-items-center gap-8">
                                @if(!empty($contents['main_content']['welcome_icon']))
                                    <img src="{{ $contents['main_content']['welcome_icon'] }}" alt="welcome_icon" class="single-instructor-hero-section__welcome-icon img-fluid" width="24px" height="24px">
                                @endif

                                <span class="text-dark">{{ $contents['main_content']['welcome_text'] }}</span>
                            </div>
                        @endif

                        {{-- Seperator --}}
                        @if(!empty($contents['upper_cta']))
                            <div class="single-instructor-hero-section__welcome-separator"></div>
                        @endif

                        {{-- Upper Call to Action --}}
                        @if(!empty($contents['upper_cta']))
                            <a href="{{ !empty($contents['upper_cta']['url']) ? $contents['upper_cta']['url'] : '' }}" target="_blank" class="">
                                <div class="d-inline-flex align-items-center gap-8 p-8 pr-16 rounded-32 border-2 border-primary">
                                    @if(!empty($contents['upper_cta']['badge_text']))
                                        <div class="d-flex-center gap-4 px-8 py-4 rounded-32 bg-primary">
                                            <span class="font-14 text-white">{{ $contents['upper_cta']['badge_text'] }}</span>

                                            @if(!empty($contents['upper_cta']['icon']))
                                                @svg("iconsax-{$contents['upper_cta']['icon']}", ['width' => '20px', 'height' => '20px', 'class' => "icons text-white"])
                                            @endif
                                        </div>
                                    @endif

                                    @if(!empty($contents['upper_cta']['main_text']))
                                        <span class="font-14 text-primary">{{ $contents['upper_cta']['main_text'] }}</span>
                                    @endif

                                    <x-iconsax-lin-arrow-right class="icons text-primary" width="16px" height="16px"/>
                                </div>
                            </a>
                        @endif
                    </div>

                    {{-- Title --}}
                    @if(!empty($contents['main_content']))
                        <h1 class="d-inline-flex flex-column font-64 mt-16">
                            @if(!empty($contents['main_content']['title_line_1']))
                                <span class="text-dark">{{ $contents['main_content']['title_line_1'] }}</span>
                            @endif

                            <div class="d-inline-flex align-items-center gap-12 font-64 mt-4">
                                @if(!empty($contents['main_content']['title_line_2']))
                                    <span class="text-dark">{{ $contents['main_content']['title_line_2'] }}</span>
                                @endif


                                @if(!empty($contents['main_content']['highlight_words']) and is_array($contents['main_content']['highlight_words']))
                                    @if(count($contents['main_content']['highlight_words']) > 1)
                                        @push('scripts_bottom')
                                            <script>
                                                var singleInstructorHeroHighlightWords = @json(array_values($contents['main_content']['highlight_words']));

                                                $(document).ready(function () {
                                                    handleHighlightWords(singleInstructorHeroHighlightWords, 'js-single-instructor-hero-highlight-words-card')
                                                })
                                            </script>
                                        @endpush

                                        <div
                                            class="js-single-instructor-hero-highlight-words-card text-primary"
                                            data-type-speed="50"
                                            data-back-speed="25"
                                            data-delay="1500"
                                        >{{ array_values($contents['main_content']['highlight_words'])[0] }}</div>
                                    @else
                                        @foreach($contents['main_content']['highlight_words'] as $highlightWord)
                                            <span class="text-primary">{{ $highlightWord }}</span>
                                        @endforeach
                                    @endif
                                @endif


                            </div>
                        </h1>
                    @endif

                    {{-- Title --}}
                    @if(!empty($contents['main_content']) and $contents['main_content']['description'])
                        <div class="single-instructor-hero-section__description d-flex align-items-start gap-8 mt-16">
                            <div class="d-flex align-items-center gap-8 mt-4">
                                <span class="circle-dot"></span>
                                <span class="line-separator"></span>
                            </div>

                            <span class="font-16">{!! nl2br($contents['main_content']['description']) !!}</span>
                        </div>
                    @endif

                    {{-- Buttons --}}
                    @if(!empty($contents['main_content']) and !empty($contents['main_content']['primary_button']) or !empty($contents['main_content']['secondary_button']))
                        <div class="d-flex flex-column flex-lg-row align-items-lg-center mt-40 gap-16">
                            {{-- Primary Button --}}
                            @if(!empty($contents['main_content']['primary_button']) and !empty($contents['main_content']['primary_button']['label']))
                                <a href="{{ !empty($contents['main_content']['primary_button']['url']) ? $contents['main_content']['primary_button']['url'] : '' }}" class="btn-flip-effect btn btn-primary btn-xlg gap-8 rounded-20 text-white" data-text="{{ $contents['main_content']['primary_button']['label'] }}">
                                    @if(!empty($contents['main_content']['primary_button']['icon']))
                                        @svg("iconsax-{$contents['main_content']['primary_button']['icon']}", ['width' => '24px', 'height' => '24px', 'class' => "icons"])
                                    @endif

                                    <span class="btn-flip-effect__text text-white">{{ $contents['main_content']['primary_button']['label'] }}</span>
                                </a>
                            @endif

                            {{-- Secondary Button --}}
                            @if(!empty($contents['main_content']['secondary_button']) and !empty($contents['main_content']['secondary_button']['label']))
                                <a href="{{ !empty($contents['main_content']['secondary_button']['url']) ? $contents['main_content']['secondary_button']['url'] : '' }}" class="btn-flip-effect btn btn-secondary btn-xlg gap-8 rounded-20 text-white" data-text="{{ $contents['main_content']['secondary_button']['label'] }}">
                                    @if(!empty($contents['main_content']['secondary_button']['icon']))
                                        @svg("iconsax-{$contents['main_content']['secondary_button']['icon']}", ['width' => '24px', 'height' => '24px', 'class' => "icons"])
                                    @endif

                                    <span class="btn-flip-effect__text text-white">{{ $contents['main_content']['secondary_button']['label'] }}</span>
                                </a>
                            @endif
                        </div>
                    @endif

                    {{-- Students --}}
                    {{-- Students Widget --}}
                    @if(!empty($contents['students_widget']))
                        <div class="d-flex align-items-center gap-12 mt-80">
                            @if(!empty($contents['students_widget_avatars']) and is_array($contents['students_widget_avatars']))
                                <div class="d-flex align-items-center overlay-avatars overlay-avatars-20">
                                    @foreach($contents['students_widget_avatars'] as $avatar)
                                        <div class="overlay-avatars__item size-40 rounded-circle border-0">
                                            <img src="{{ $avatar }}" alt="avatar" class="img-cover rounded-circle">
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                            <div class="single-instructor-hero-section__welcome-separator"></div>

                            <div class="">
                                @if(!empty($contents['students_widget']['title']))
                                    <h4 class="font-16">{{ $contents['students_widget']['title'] }}</h4>
                                @endif

                                @if(!empty($contents['students_widget']['subtitle']))
                                    <p class="font-14 mt-4 text-gray-500">{{ $contents['students_widget']['subtitle'] }}</p>
                                @endif
                            </div>
                        </div>
                    @endif

                </div>

                <div class="col-12 col-lg-6 mt-20 mt-lg-0">
                    @if(!empty($contents['image_content']))
                        <div class="position-relative d-flex align-items-lg-end justify-content-lg-end justify-content-center w-100 h-100">
                            @if(!empty($contents['image_content']['main_image']))
                                <div class="d-flex-center single-instructor-hero-section__main-img">
                                    <img src="{{ $contents['image_content']['main_image'] }}" alt="hero" class="img-cover">
                                </div>
                            @endif

                            @if(!empty($contents['image_content']['spinning_image']))
                                <div class="d-flex-center single-instructor-hero-section__spinning-img">
                                    <img src="{{ $contents['image_content']['spinning_image'] }}" alt="spinning_image" class="img-cover">
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @if(!empty($contents['companies_widget']))
        <div class="single-instructor-hero-section__companies-logos  bg-primary">
            <div class="container d-flex align-items-center gap-44 py-16">
                <div class="d-flex align-items-center gap-8">
                    @if(!empty($contents['companies_widget']['icon']))
                        @svg("iconsax-{$contents['companies_widget']['icon']}", ['width' => '24px', 'height' => '24px', 'class' => "icons text-white"])
                    @else
                        <x-iconsax-bul-shield-tick class="icons text-white" width="24px" height="24px"/>
                    @endif

                    <div class="single-instructor-hero-section__companies-logos-title-separator"></div>

                    <div class="">
                        @if(!empty($contents['companies_widget']['title']))
                            <h4 class="font-16 text-white">{{ $contents['companies_widget']['title'] }}</h4>
                        @endif

                        @if(!empty($contents['companies_widget']['subtitle']))
                            <p class="font-14 mt-4 text-white opacity-70">{{ $contents['companies_widget']['subtitle'] }}</p>
                        @endif
                    </div>
                </div>

                @if(!empty($contents['companies_widget_logos']) and is_array($contents['companies_widget_logos']))
                    <div class="d-flex align-items-center flex-wrap flex-lg-nowrap gap-24 gap-lg-60">
                        @foreach($contents['companies_widget_logos'] as $logo)
                            <div class="single-instructor-hero-section__companies-logos-item">
                                <img src="{{ $logo }}" alt="company logo" class="img-fluid">
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    @endif

@endif
