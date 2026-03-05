<div class="actions-dropdown position-relative d-flex justify-content-end align-items-center">
    <div class="d-flex-center size-40 bg-white border-gray-200 rounded-8 cursor-pointer">
        <x-iconsax-lin-more class="icons text-gray-500" width="24px" height="24px"/>
    </div>

    <div class="actions-dropdown__dropdown-menu dropdown-menu-width-220">
        <ul class="my-8">

            @if(!empty($sale->gift_id) and $sale->buyer_id == $authUser->id)
                <li class="actions-dropdown__dropdown-menu-item">
                    <a href="/panel/courses/{{ $saleItem->id }}/sale/{{ $sale->id }}/invoice" target="_blank" class="">{{ trans('public.invoice') }}</a>
                </li>
            @else
                @if(!empty($saleItem->access_days) and !$saleItem->checkHasExpiredAccessDays($sale->created_at, $sale->gift_id))
                    <li class="actions-dropdown__dropdown-menu-item">
                        <a href="{{ $saleItem->getUrl() }}" target="_blank" class="">{{ trans('update.enroll_on_course') }}</a>
                    </li>
                @elseif(!empty($sale->webinar))
                    <li class="actions-dropdown__dropdown-menu-item">
                        <a href="{{ $saleItem->getLearningPageUrl() }}" target="_blank" class="webinar-actions d-block">{{ trans('update.learning_page') }}</a>
                    </li>

                    @if(!empty($saleItem->start_date) and ($saleItem->start_date > time() or ($saleItem->isProgressing() and !empty($nextSession))))
                        <li class="actions-dropdown__dropdown-menu-item">
                            <button type="button" data-webinar-id="{{ $saleItem->id }}" class="js-next-session-info">{{ trans('footer.join') }}</button>
                        </li>
                    @endif

                    @if(!empty($saleItem->downloadable) or (!empty($saleItem->files) and count($saleItem->files)))
                        <li class="actions-dropdown__dropdown-menu-item">
                            <a href="{{ $saleItem->getUrl() }}?tab=content" target="_blank" class="">{{ trans('home.download') }}</a>
                        </li>
                    @endif

                    @if($saleItem->price > 0)
                        <li class="actions-dropdown__dropdown-menu-item">
                            <a href="/panel/courses/{{ $saleItem->id }}/sale/{{ $sale->id }}/invoice" target="_blank" class="">{{ trans('public.invoice') }}</a>
                        </li>
                    @endif
                @endif

                <li class="actions-dropdown__dropdown-menu-item">
                    <a href="{{ $saleItem->getUrl() }}?tab=reviews" target="_blank" class="">{{ trans('public.feedback') }}</a>
                </li>
            @endif

        </ul>
    </div>
</div>
