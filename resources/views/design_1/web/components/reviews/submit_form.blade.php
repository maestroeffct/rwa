@if(!empty($authUser))
    @if(!$hasBought)
        <div class="d-flex-center flex-column text-center mt-24 rounded-12 border-gray-200 border-dashed bg-white p-32 pb-40">
            <div class="d-flex-center size-56 rounded-12 bg-primary-20">
                <x-iconsax-bul-star class="icons text-primary" width="32px" height="32px"/>
            </div>

            @if($itemName == "webinar_id")
                <h5 class="font-14 font-weight-bold mt-12">{{ trans('update.review_this_course') }}</h5>
                <p class="font-12 text-gray-500 mt-4">{{ trans('update.you_need_to_enroll_of_the_course_to_review_this_course') }}</p>
            @elseif($itemName == "bundle_id")
                <h5 class="font-14 font-weight-bold mt-12">{{ trans('update.review_this_bundle') }}</h5>
                <p class="font-12 text-gray-500 mt-4">{{ trans('update.you_need_to_enroll_of_the_bundle_to_review_this_bundle') }}</p>
            @elseif($itemName == "product_id")
                <h5 class="font-14 font-weight-bold mt-12">{{ trans('update.review_this_product') }}</h5>
                <p class="font-12 text-gray-500 mt-4">{{ trans('update.you_need_to_enroll_of_the_product_to_review_this_product') }}</p>
            @elseif($itemName == "event_id")
                <h5 class="font-14 font-weight-bold mt-12">{{ trans('update.review_this_event') }}</h5>
                <p class="font-12 text-gray-500 mt-4">{{ trans('update.you_need_to_enroll_of_the_tickets_to_review_this_event') }}</p>
            @endif
        </div>
    @elseif($itemRow->reviews()->where('creator_id', $authUser->id)->count() < 1)
        <div class="bg-white mt-24 rounded-12 border-gray-200 border-dashed p-16">
            <h5 class="font-14">{{ trans('update.write_your_review') }}</h5>

            <form action="{{ $reviewFormPath }}" class="mt-16" method="post">
                {{ csrf_field() }}
                <input type="hidden" name="{{ $itemName }}" value="{{ $itemRow->id }}"/>

                <div class="form-group">
                    <textarea name="description" class="js-ajax-description form-control" rows="6"></textarea>
                    <div class="invalid-feedback"></div>
                </div>

                <h6 class="font-12">{{ trans('update.feedback_parameters') }}</h6>

                <div class="d-grid grid-columns-auto grid-lg-columns-4 gap-16 mt-12">

                    @foreach($reviewOptions as $reviewOption)
                        <div class="form-group mb-0">
                            <div class="barrating-stars bg-gray-100 p-12 rounded-8 js-ajax-{{ $reviewOption }} ">
                                <div class="text-gray-500 mb-8">{{ trans("product.{$reviewOption}") }}</div>
                                <select name="{{ $reviewOption }}" data-rate="1">
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                </select>
                            </div>

                            <div class="invalid-feedback"></div>
                        </div>
                    @endforeach

                </div>

                <div class="d-flex flex-column flex-lg-row align-items-lg-center justify-content-lg-between mt-16 pt-16 border-top-gray-100">
                    <div class="d-flex align-items-center">
                        <div class="d-flex-center size-48 bg-gray-300 rounded-12">
                            <x-iconsax-bol-info-circle class="icons text-gray-500" width="24px" height="24px"/>
                        </div>
                        <div class="ml-8">
                            <h6 class="font-14">{{ trans('update.submit_review') }}</h6>
                            <p class="mt-4 font-12 text-gray-500">{{ trans('update.course_page_submit_review_hint') }}</p>
                        </div>
                    </div>

                    <button type="button" class="js-submit-review-btn btn btn-lg btn-primary mt-16 mt-lg-0">{{ trans('product.post_review') }}</button>
                </div>
            </form>
        </div>
    @endif
@endif
