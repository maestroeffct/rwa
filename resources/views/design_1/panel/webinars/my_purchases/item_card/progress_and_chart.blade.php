@if(!empty($nextSession) and $nextSession->date > time() and checkTimestampInToday($nextSession->date))
    <div class="js-next-session-info d-flex align-items-center cursor-pointer" data-webinar-id="{{ $saleItem->id }}">
        <div class="d-flex-center">
            <x-iconsax-bol-video class="icons text-primary " width="24px" height="24px"/>
        </div>

        <div class="ml-8">
            <h5 class="font-12 font-weight-bold text-dark">{{ trans('update.live_session_in_progress') }}</h5>
            <p class="font-12 text-gray-500">{{ trans('update.join_it_now') }}</p>
        </div>
    </div>
@elseif($sale->payment_method == \App\Models\Sale::$subscribe and $sale->checkExpiredPurchaseWithSubscribe($sale->buyer_id, $saleItem->id, !empty($sale->webinar) ? 'webinar_id' : 'bundle_id'))
    <div class="d-flex align-items-center">
        <div class="d-flex-center">
            <x-iconsax-bol-cup class="icons text-danger " width="24px" height="24px"/>
        </div>

        <div class="ml-8">
            <h5 class="font-12 font-weight-bold text-dark">{{ trans('update.expired_subscription') }}</h5>
            <p class="font-12 text-gray-500">{{ trans('update.your_subscription_plan_expired') }}</p>
        </div>
    </div>
@elseif(!empty($saleItem->access_days))
    @if(!$saleItem->checkHasExpiredAccessDays($sale->created_at, $sale->gift_id))
        <div class="d-flex align-items-center">
            <div class="d-flex-center">
                <x-iconsax-bol-gift class="icons text-success " width="24px" height="24px"/>
            </div>

            <div class="ml-8">
                <h5 class="font-12 font-weight-bold text-dark">{{ trans('update.access_days_expired') }}</h5>
                <p class="font-12 text-gray-500">{{ trans('update.your_purchase_expired') }}</p>
            </div>
        </div>
    @else
        <div class="d-flex align-items-center">
            <div class="d-flex-center">
                <x-iconsax-bol-calendar-tick class="icons text-primary " width="24px" height="24px"/>
            </div>

            <div class="ml-8">
                <h5 class="font-12 font-weight-bold text-dark">{{ trans('update.course_expiry') }}</h5>
                <p class="font-12 text-gray-500">{{ trans('update.the_course_access_expires_on_date', ['date' => dateTimeFormat($saleItem->getExpiredAccessDays($sale->created_at, $sale->gift_id), 'j M Y')]) }}</p>
            </div>
        </div>
    @endif
@elseif(!empty($sale->webinar))
    @php
        $percent = $saleItem->getProgress(true);
    @endphp

    @if($percent > 99)
        <div class="d-flex align-items-center">
            <div class="d-flex-center size-36 bg-success rounded-circle">
                <x-iconsax-lin-tick-circle class="icons text-white " width="24px" height="24px"/>
            </div>

            <div class="ml-8">
                <h5 class="font-12 font-weight-bold text-dark">{{ trans('update.completed') }}!</h5>
                <p class="font-12 text-gray-500">{{ trans('update.you_did_it_perfectly...') }}</p>
            </div>
        </div>
    @else
        <div class="w-100">
            <div class="d-flex align-items-center gap-4 font-12">
                <span class="font-weight-bold text-dark">{{ round($percent,1) }}%</span>
                <span class="text-gray-500">{{ trans('update.av._learning_progress') }}</span>
            </div>

            <div class="progress-bar d-flex mt-8 rounded-4 bg-gray-100 w-100">
                <span class="bg-success rounded-4" style="width: {{ $percent }}%"></span>
            </div>
        </div>
    @endif
@elseif(count($saleItem->reviews->where('creator_id', $authUser->id)) < 1)
    <a href="{{ $saleItem->getUrl() }}?tab=reviews" target="_blank" class="d-flex align-items-center">
        <div class="d-flex-center">
            <x-iconsax-bol-star-1 class="icons text-warning " width="24px" height="24px"/>
        </div>

        <div class="ml-8">
            <h5 class="font-12 font-weight-bold text-dark">{{ (!empty($sale->bundle)) ? trans('update.rate_this_bundle') : trans('update.rate_this_course') }}</h5>
            <p class="font-12 text-gray-500">{{ (!empty($sale->bundle)) ? trans('update.submit_a_review_for_the_course') : trans('update.submit_a_review_for_the_course') }}</p>
        </div>
    </a>
@endif
