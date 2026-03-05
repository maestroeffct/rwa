@if(!empty($landingComponent) and $landingComponent->enable)
    @php
        $contents = [];
        if (!empty($landingComponent->content)) {
            $contents = json_decode($landingComponent->content, true);
        }

        $frontComponentsDataMixins = (new \App\Mixins\LandingBuilder\FrontComponentsDataMixins());
    @endphp


    @push('styles_top')
        <link rel="stylesheet" href="{{ getLandingComponentStylePath("instructors") }}">
    @endpush

    <div class="instructors-section position-relative" @if(!empty($contents['background'])) style="background-image: url({{ $contents['background'] }})" @endif>
        <div class="container">
            <div class="d-flex-center flex-column text-center">
                @if(!empty($contents['main_content']) and !empty($contents['main_content']['pre_title']))
                    <div class="d-inline-flex-center py-8 px-16 rounded-8 border-primary bg-primary-20 font-12 text-primary">{{ $contents['main_content']['pre_title'] }}</div>
                @endif

                @if(!empty($contents['main_content']) and !empty($contents['main_content']['title']))
                    <h2 class="mt-12 font-32 text-dark">{{ $contents['main_content']['title'] }}</h2>
                @endif

                @if(!empty($contents['main_content']) and !empty($contents['main_content']['description']))
                    <p class="mt-16 font-16 text-gray-500">{!! nl2br($contents['main_content']['description']) !!}</p>
                @endif
            </div>

            {{-- List --}}
            @php
                $ids = [];
                if (!empty($contents['specific_instructors']) and is_array($contents['specific_instructors'])) {
                    foreach ($contents['specific_instructors'] as $instructorData) {
                        if (!empty($instructorData['instructor_id'])) {
                            $ids[] = $instructorData['instructor_id'];
                        }
                    }
                }

                $instructors = $frontComponentsDataMixins->getUsersByIds($ids, \App\Models\Role::$teacher);
            @endphp

            @if($instructors->isNotEmpty())
                <div class="d-grid grid-columns-auto grid-lg-columns-3 gap-32 mt-40">
                    @include('design_1.web.instructors.components.cards.grids.index', ['instructors' => $instructors, 'gridCardClassName' => ""])
                </div>
            @endif

            {{-- CTA --}}
            <div class="d-flex-center flex-column text-lg-center mt-48">
                @if(!empty($contents['cta_section']))
                    <div class="d-flex align-items-lg-center gap-4">
                        @if(!empty($contents['cta_section']['icon']))
                            <div class="d-flex-center size-24">
                                @svg("iconsax-{$contents['cta_section']['icon']}", ['width' => '24px', 'height' => '24px', 'class' => "icons text-dark"])
                            </div>
                        @endif

                        <div class="d-flex flex-column font-16 flex-lg-row align-items-lg-center gap-4">
                            @if(!empty($contents['cta_section']['title_bold_text']))
                                <h5 class="font-14">{{ $contents['cta_section']['title_bold_text'] }}</h5>
                            @endif

                            @if(!empty($contents['cta_section']['title_regular_text']))
                                <div class="font-16">{{ $contents['cta_section']['title_regular_text'] }}</div>
                            @endif
                        </div>
                    </div>

                    @if(!empty($contents['cta_section']['description']))
                        <div class="font-16 text-gray-500 mt-16">{{ $contents['cta_section']['description'] }}</div>
                    @endif
                @endif

                {{-- Primary Button --}}
                @if(!empty($contents['main_content']['button']) and !empty($contents['main_content']['button']['label']))
                    <a href="{{ !empty($contents['main_content']['button']['url']) ? $contents['main_content']['button']['url'] : '' }}" class="btn-flip-effect btn btn-primary btn-xlg gap-8 mt-24 text-white" data-text="{{ $contents['main_content']['button']['label'] }}">
                        @if(!empty($contents['main_content']['button']['icon']))
                            @svg("iconsax-{$contents['main_content']['button']['icon']}", ['width' => '24px', 'height' => '24px', 'class' => "icons"])
                        @endif

                        <span class="btn-flip-effect__text text-white">{{ $contents['main_content']['button']['label'] }}</span>
                    </a>
                @endif
            </div>
        </div>
    </div>
@endif
