@if(!empty($landingComponent) and $landingComponent->enable)
    @php
        $statisticsBackgroundColor = "secondary";
        $contents = [];
        if (!empty($landingComponent->content)) {
            $contents = json_decode($landingComponent->content, true);
        }

        if (!empty($contents['background_color'])) {
            $statisticsBackgroundColor = $contents['background_color'];
        }
    @endphp

    @push('styles_top')
        <link rel="stylesheet" href="{{ getLandingComponentStylePath("statistics") }}">
    @endpush

    <div class="container">
        <div class="statistics-section">
            <div class="statistics-section__mask"></div>

            <div class="statistics-section__contents position-relative z-index-2" style="background-color: var({{ "--".$statisticsBackgroundColor }}); {{ (!empty($contents['background']) ? "background-image: url({$contents['background']}); " : '') }}">
                <div class="row ">
                    @if(!empty($contents['statistics']) and is_array($contents['statistics']))
                        @foreach($contents['statistics'] as $statistic)
                            @if(!empty($statistic['title']) and !empty($statistic['data_type']) and !empty($statistic['data_source']))
                                @php
                                    $statisticComponentFrontMixins = (new \App\Mixins\LandingBuilder\StatisticComponentFrontMixins());
                                    $statisticValue = $statisticComponentFrontMixins->calculateStatisticData($statistic);
                                    $isNumber = is_numeric($statisticValue);
                                @endphp

                                <div class="statistic-col col-6 col-lg-3 ">
                                    <div class="d-flex align-items-center gap-12">
                                        @if(!empty($statistic['icon']))
                                            <div class="statistics-section__icon-{{ $loop->iteration }} d-flex-center size-72 rounded-circle">
                                                @svg("iconsax-{$statistic['icon']}", ['width' => '32px', 'height' => '32px', 'class' => "icons"])
                                            </div>
                                        @endif

                                        <div class="">
                                            @if($isNumber)
                                                <h4 class="js-statistic-value-counterup statistics-section__counter-value font-24 text-white" data-from="0" data-to="{{ $statisticValue }}" data-speed="1000">0</h4>
                                            @else
                                                <h4 class="statistics-section__counter-value font-24 text-white">{{ $statisticValue }}</h4>
                                            @endif
                                            <p class="font-16 text-white mt-4">{{ $statistic['title'] }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
@endif

@push('scripts_bottom')
    <script src="/assets/vendors/counterup/jquery.counterup.min.js"></script>

    <script src="{{ getLandingComponentScriptPath("statistics") }}"></script>
@endpush

