<div class="course-right-side-section position-relative">
    <div class="course-right-side-section__mask"></div>

    <div class="position-relative bg-white rounded-24 pb-24 z-index-2">

        {{-- Thumbnail --}}
        <div class="course-right-side__thumbnail position-relative bg-gray-200">
            <img src="{{ $bundle->getImage() }}" class="img-cover" alt="{{ $bundle->title }}">

            @if($bundle->video_demo)
                <div id="webinarDemoVideoBtn" class="has-video-icon d-flex-center size-64 rounded-circle"
                     data-video-path="{{ $bundle->video_demo_source == 'upload' ?  url($bundle->video_demo) : $bundle->video_demo }}"
                     data-video-source="{{ $bundle->video_demo_source }}"
                >
                    <x-iconsax-bol-play class="icons text-white" width="24px" height="24px"/>
                </div>
            @endif
        </div>

        <form action="/cart/store" method="post">
            {{ csrf_field() }}
            <input type="hidden" name="item_id" value="{{ $bundle->id }}">
            <input type="hidden" name="item_name" value="bundle_id">

            {{-- Price --}}
            @include("design_1.web.bundles.show.includes.rightSide.price")

            {{-- Enroll Form --}}
            @include("design_1.web.bundles.show.includes.rightSide.enroll_form")
        </form>

        @if(!empty(getOthersPersonalizationSettings('show_guarantee_text')) and !empty(getGuarantyTextSettings("bundle_guaranty_text")))
            <div class="mt-14 d-flex align-items-center justify-content-center text-gray-500">
                <x-iconsax-lin-shield-tick class="icons text-gray-500" width="20px" height="20px"/>
                <span class="ml-4 font-12">{{ getGuarantyTextSettings("bundle_guaranty_text") }}</span>
            </div>
        @endif

        <div class="mt-16 px-16">
            <div class="d-flex align-items-center justify-content-around mt-16 p-12 rounded-12 border-dashed border-gray-200">

                <a @if(auth()->guest()) href="/login" @else href="/bundles/{{ $bundle->slug }}/favorite" id="favoriteToggle" @endif class="d-flex-center flex-column font-12 {{ !empty($isFavorite) ? 'text-danger' : 'text-gray-500' }}">
                    <x-iconsax-lin-heart class="icons {{ !empty($isFavorite) ? 'text-danger' : 'text-gray-500' }}" width="20px" height="20px"/>
                    <span class="mt-2">{{ trans('panel.favorite') }}</span>
                </a>

                <div class="js-share-course d-flex-center flex-column text-gray-500 font-12 cursor-pointer" data-path="/bundles/{{ $bundle->slug }}/share-modal">
                    <x-iconsax-lin-share class="icons text-gray-500" width="20px" height="20px"/>
                    <span class="mt-2">{{ trans('public.share') }}</span>
                </div>
            </div>

            {{--<div class="mt-24 text-center">
                <button type="button" class="js-report-course font-12 text-gray-500 btn-transparent" data-path="/course/{{ $bundle->slug }}/report-modal">{{ trans('update.report_abuse') }}</button>
            </div>--}}
        </div>

    </div>
</div>

{{-- Specifications --}}
@include("design_1.web.bundles.show.includes.rightSide.specifications")

{{-- teacher --}}
@include("design_1.web.courses.show.includes.rightSide.teacher", ['userRow' => $bundle->teacher])

{{-- organization --}}
@if($bundle->creator_id != $bundle->teacher_id)
    @include("design_1.web.courses.show.includes.rightSide.teacher", ['userRow' => $bundle->creator])
@endif

{{-- Cashback --}}
@include('design_1.web.cashback.alert_card', [
    'cashbackRules' => $cashbackRules,
    'itemPrice' => $bundle->price,
    'cashbackRulesCardClassName' => "mt-28"
])

{{-- Send as Gift --}}
@include('design_1.web.bundles.show.includes.rightSide.send_gift')


{{-- tags --}}
@if($bundle->tags->count() > 0)
    <div class="course-right-side-section position-relative mt-28">
        <div class="course-right-side-section__mask"></div>

        <div class="position-relative card-before-line bg-white rounded-24 p-16 z-index-2">
            <h4 class="font-14 font-weight-bold">{{ trans('public.tags') }}</h4>

            <div class="d-flex gap-12 flex-wrap mt-16">
                @foreach($bundle->tags as $tag)
                    <a href="/tags/bundles/{{ urlencode($tag->title) }}" target="_blank" class="d-flex-center p-10 rounded-8 bg-gray-100 font-12 text-gray-500 text-center">{{ $tag->title }}</a>
                @endforeach
            </div>
        </div>
    </div>
@endif
