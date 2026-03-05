<div class="d-flex-center flex-column text-center mt-20">
    <div class="">
        <img src="/assets/design_1/img/panel/store/enter_tracking_code.svg" alt="enter_tracking_code" class="img-fluid" width="215px" height="160px">
    </div>

    <h4 class="font-14 mt-12">{{ trans('update.submit_tracking_code') }}</h4>
    <p class="font-12 text-gray-500 mt-8">{!! nl2br(trans('update.submit_tracking_code_modal_hint')) !!}</p>
</div>

<div class="p-16 mt-16 border-gray-200">
    <span class="d-block font-weight-bold text-gray-500">{{ trans('update.address') }}</span>
    <span class="d-block mt-8">{{ $order->address }}</span>
</div>

<form class="js-set-tracking-code-form" action="/panel/store/sales/{{ $saleId }}/productOrder/{{ $order->id }}/setTrackingCode" method="post">
    {{ csrf_field() }}

    <div class="form-group mt-24">
        <label class="form-group-label">{{ trans('update.tracking_code') }}</label>
        <input type="text" name="tracking_code" class="js-ajax-tracking_code form-control" placeholder=""/>
        <div class="invalid-feedback"></div>
    </div>
</form>
