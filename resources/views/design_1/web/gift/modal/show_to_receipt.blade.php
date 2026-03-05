@php
    if(!empty($gift->webinar_id)) {
        $itemTitle = $gift->webinar->title;
        $itemRate = $gift->webinar->getRate();
        $itemImage = $gift->webinar->getImage();
        $itemUrl = $gift->webinar->getUrl();
    } else if(!empty($gift->bundle_id)) {
        $itemTitle = $gift->bundle->title;
        $itemRate = $gift->bundle->getRate();
        $itemImage = $gift->bundle->getImage();
        $itemUrl = $gift->bundle->getUrl();
    } else if(!empty($gift->product_id)) {
        $itemTitle = $gift->product->title;
        $itemRate = $gift->product->getRate();
        $itemImage = $gift->product->thumbnail;
        $itemUrl = $gift->product->getUrl();
    }
@endphp

<div class="js-custom-modal rounded-top-20 soft-shadow-5 pt-24">
    <div class="d-flex align-items-center justify-content-between px-16">
        <div class="">
            <h3 class="font-16 text-dark">{{ trans('update.gift_received') }}</h3>
        </div>

        <button class="modal-close-btn close-swl">
            <x-iconsax-lin-add class="close-icon" width="25px" height="25px"/>
        </button>
    </div>

    <div class="py-8 custom-swl-modal-body has-footer px-48">
        <div class="d-flex-center flex-column text-center mt-36">
            <div class="position-relative d-flex-center size-136">
                <img src="/assets/design_1/img/panel/gift/gift_received.svg" alt="{{ trans('gift_received') }}" class="img-fluid" width="136px" height="136px">

                <div class="gift-received-modal__sender-avatar d-flex-center size-48 bg-primary rounded-circle">
                    <div class="size-40 bg-gray-100 rounded-circle">
                        <img src="{{ $gift->user->getAvatar(40) }}" alt="{{ $gift->user->full_name }}" class="img-cover rounded-circle">
                    </div>
                </div>
            </div>

            <h4 class="font-14 font-weight-bold mt-20">{{ trans('update.you_received_a_gift_from_a_friend') }}</h4>

            <div class="font-12 mt-4 text-gray-500">{!! trans('update.user_send_item_to_you_as_a_gift',['user' => $gift->user->full_name, 'item_title' => $gift->getItemTitle()]) !!}</div>

            <div class="d-flex align-items-center mt-24 p-8 rounded-16 border-gray-200 bg-white">
                <div class="gift-modal-item-img rounded-8 bg-gray-100">
                    <img src="{{ $itemImage }}" alt="" class="img-cover rounded-8">
                </div>
                <div class="ml-8">
                    <h5 class="font-12">{{ $itemTitle }}</h5>

                    @include('design_1.web.components.rate', ['rate' => $itemRate, 'rateClassName' => 'mt-8', 'showRateStars' => true])
                </div>
            </div>
        </div>
    </div>

    <div class="custom-modal-footer d-flex justify-content-end bg-gray-100 p-16 w-100 rounded-bottom-20">
        <a href="{{ $itemUrl }}" class="btn btn-primary btn-lg">{{ trans('update.view_gift') }}</a>
    </div>
</div>
