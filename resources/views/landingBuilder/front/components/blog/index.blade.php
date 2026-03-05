@if(!empty($landingComponent) and $landingComponent->enable)
    @php
        $contents = [];
        if (!empty($landingComponent->content)) {
            $contents = json_decode($landingComponent->content, true);
        }

        $frontComponentsDataMixins = (new \App\Mixins\LandingBuilder\FrontComponentsDataMixins());
        $posts = $frontComponentsDataMixins->getBlogData();
    @endphp

    @if($posts->isNotEmpty())
        @push('styles_top')
            <link rel="stylesheet" href="{{ getLandingComponentStylePath("blog") }}">
        @endpush

        <div class="blog-section position-relative" @if(!empty($contents['background'])) style="background-image: url({{ $contents['background'] }})" @endif>

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
                    $firstPost = $posts->first();
                    $posts = $posts->slice(1); // other last 4 item
                @endphp

                <div class="row mt-4">
                    <div class="col-12 col-lg-6 mt-24">
                        @include('landingBuilder.front.components.blog.post_card', ['post' => $firstPost, 'className' => 'one-large-col', 'showPostStats' => true])
                    </div>

                    <div class="col-12 col-lg-6">
                        <div class="row">
                            @foreach($posts as $postRow)
                                <div class="col-6 mt-24">
                                    @include('landingBuilder.front.components.blog.post_card', ['post' => $postRow, 'className' => 'four-small-col'])
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- Primary Button --}}
                @if(!empty($contents['main_content']['button']) and !empty($contents['main_content']['button']['label']))
                    <div class="d-flex-center flex-column text-center mt-24">

                        <a href="{{ !empty($contents['main_content']['button']['url']) ? $contents['main_content']['button']['url'] : '' }}" class="btn-flip-effect btn btn-primary btn-xlg text-white gap-8" data-text="{{ $contents['main_content']['button']['label'] }}">
                            @if(!empty($contents['main_content']['button']['icon']))
                                @svg("iconsax-{$contents['main_content']['button']['icon']}", ['width' => '24px', 'height' => '24px', 'class' => "icons"])
                            @endif

                            <span class="btn-flip-effect__text text-white">{{ $contents['main_content']['button']['label'] }}</span>
                        </a>

                    </div>
                @endif

            </div>
        </div>
    @endif
@endif
