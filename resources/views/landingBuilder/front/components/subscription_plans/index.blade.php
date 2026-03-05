@if(!empty($landingComponent) and $landingComponent->enable)
    @php
        $contents = [];
        if (!empty($landingComponent->content)) {
            $contents = json_decode($landingComponent->content, true);
        }

        $frontComponentsDataMixins = (new \App\Mixins\LandingBuilder\FrontComponentsDataMixins());
    @endphp


    @push('styles_top')
        <link rel="stylesheet" href="{{ getLandingComponentStylePath("subscription_plans") }}">
    @endpush

    <div class="subscription-plans-section position-relative">
        <div class="container position-relative z-index-2 h-100">
            <div class="row h-100">
                <div class="col-12 col-lg-3 pt-64">
                    @if(!empty($contents['main_content']) and !empty($contents['main_content']['title']))
                        <h2 class="mt-16 font-32 text-dark">{{ $contents['main_content']['title'] }}</h2>
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

                    {{-- floating_image --}}
                    @if(!empty($contents['main_content']) and !empty($contents['main_content']['floating_image']))
                        <div class="subscription-plans-section__floating-image d-flex-center">
                            <img src="{{ $contents['main_content']['floating_image'] }}" alt="floating_image" class="">
                        </div>
                    @endif
                </div>

                <div class="col-12 col-lg-9">
                    @php
                        $ids = [];

                        if (!empty($contents['subscriptions_plans']) and is_array($contents['subscriptions_plans'])) {
                            foreach ($contents['subscriptions_plans'] as $planData) {
                                if (!empty($planData['plan_id'])) {
                                    $ids[] = $planData['plan_id'];
                                }
                            }
                        }

                        $subscriptionsPlans = $frontComponentsDataMixins->getSubscriptionsPlansByIds($ids);
                    @endphp

                    @if($subscriptionsPlans->isNotEmpty())
                        @if(count($subscriptionsPlans) > 3)
                            <div class="swiper-container js-make-swiper subscribe-plans-swiper-rtl pt-48 px-24"
                                 data-item="subscribe-plans-swiper-rtl"
                                 data-autoplay="true"
                                 data-loop="true"
                                 data-autoplay-delay="5000"
                                 data-breakpoints="1200:3,991:1.9,660:1.2"
                            >
                                <div class="swiper-wrapper">
                                    @foreach($subscriptionsPlans as $subscribeRow)
                                        <div class="swiper-slide pb-16">
                                            @include('landingBuilder.front.components.subscription_plans.plan_card', ['subscribe' => $subscribeRow])
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @else
                            <div class="row">
                                @foreach($subscriptionsPlans as $subscribeRow)
                                    <div class="col-12 col-md-6 col-lg-4 mt-32 mt-lg-0">
                                        @include('landingBuilder.front.components.subscription_plans.plan_card', ['subscribe' => $subscribeRow])
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
@endif
