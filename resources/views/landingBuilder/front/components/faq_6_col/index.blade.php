@if(!empty($landingComponent) and $landingComponent->enable)
    @php
        $contents = [];
        if (!empty($landingComponent->content)) {
            $contents = json_decode($landingComponent->content, true);
        }
    @endphp

    @push('styles_top')
        <link rel="stylesheet" href="{{ getLandingComponentStylePath("faq_6_col") }}">
    @endpush

    <div class="faq-6-col-section position-relative" @if(!empty($contents['background'])) style="background-image: url({{ $contents['background'] }})" @endif>
        <div class="container h-100 py-40 position-relative">
            <div class="row h-100">
                <div class="col-12 col-lg-4 d-flex justify-content-center align-items-start flex-column">
                    @if(!empty($contents['additional_information']) and !empty($contents['additional_information']['pre_title']))
                        <div class="d-inline-flex-center py-8 px-16 rounded-8 border-primary bg-primary-20 font-12 text-primary">{{ $contents['additional_information']['pre_title'] }}</div>
                    @endif

                    @if(!empty($contents['additional_information']) and !empty($contents['additional_information']['title']))
                        <h2 class="mt-12 font-32 text-dark position-relative z-index-2">{{ $contents['additional_information']['title'] }}</h2>
                    @endif

                    @if(!empty($contents['additional_information']) and !empty($contents['additional_information']['description']))
                        <p class="mt-20 font-16 text-gray-500 position-relative z-index-2">{!! nl2br($contents['additional_information']['description']) !!}</p>
                    @endif

                    {{-- Button --}}
                    @if(!empty($contents['additional_information']) and !empty($contents['additional_information']['button']) and !empty($contents['additional_information']['button']['label']))
                        <a href="{{ !empty($contents['additional_information']['button']['url']) ? $contents['additional_information']['button']['url'] : '' }}" target="_blank" class="btn-flip-effect btn btn-primary btn-xlg gap-8 text-white mt-24 position-relative z-index-2" data-text="{{ $contents['additional_information']['button']['label'] }}">
                            @if(!empty($contents['additional_information']['button']['icon']))
                                @svg("iconsax-{$contents['additional_information']['button']['icon']}", ['width' => '24px', 'height' => '24px', 'class' => "icons"])
                            @endif

                            <span class="btn-flip-effect__text">{{ $contents['additional_information']['button']['label'] }}</span>
                        </a>
                    @endif

                    @if(!empty($contents['floating_image_2']))
                        <div class="faq-6-col-section__floating-image-2">
                            <img src="{{ $contents['floating_image_2'] }}" alt="floating_image_2">
                        </div>
                    @endif

                    @if(!empty($contents['floating_image']))
                        <div class="faq-6-col-section__floating-image">
                            <img src="{{ $contents['floating_image'] }}" alt="floating_image">
                        </div>
                    @endif
                </div>

                <div class="col-lg-2"></div>

                <div id="faqParent_{{ $landingComponent->id }}" class="col-12 col-lg-6 mt-32 mt-lg-0 d-flex justify-content-center align-items-start flex-column">
                    @if(!empty($contents['faq_items']) and is_array($contents['faq_items']))
                        @foreach($contents['faq_items'] as $faqKey => $faqData)
                            @if(!empty($faqData['title']) and !empty($faqData['enable']) and $faqData['enable'] == "on")
                                <div class="accordion p-20 rounded-16 bg-white w-100 {{ $loop->first ? '' : 'mt-20' }}">
                                    <div class="accordion__title d-flex align-items-center justify-content-between">
                                        <div class="d-flex align-items-center cursor-pointer" href="#faq_{{ $faqKey }}" data-parent="#faqParent_{{ $landingComponent->id }}" role="button" data-toggle="collapse" data-collapse="one">
                                            <div class="size-24">
                                                @if(!empty($faqData['icon']))
                                                    @svg("iconsax-{$faqData['icon']}", ['width' => '24px', 'height' => '24px', 'class' => "icons text-primary"])
                                                @else
                                                    <x-iconsax-bul-message-question class="icons text-primary" width="24px" height="24px"/>
                                                @endif
                                            </div>

                                            <div class="font-16 font-weight-bold ml-8">
                                                {{ $faqData['title'] }}
                                            </div>
                                        </div>

                                        <div class="collapse-arrow-icon d-inline-flex-center size-24 border-gray-100 rounded-circle cursor-pointer" href="#faq_{{ $faqKey }}" data-parent="#faqParent_{{ $landingComponent->id }}" role="button" data-toggle="collapse" data-collapse="one">
                                            <x-iconsax-lin-arrow-up-1 class="icons text-dark" width="16px" height="16px"/>
                                        </div>
                                    </div>

                                    <div id="faq_{{ $faqKey }}" class="accordion__collapse pt-0 border-0 " role="tabpanel">
                                        <div class="p-16 rounded-8 border-gray-100 font-16 text-gray-500 mt-8">
                                            @if(!empty($faqData['description']))
                                                {!! nl2br($faqData['description']) !!}
                                            @endif
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
