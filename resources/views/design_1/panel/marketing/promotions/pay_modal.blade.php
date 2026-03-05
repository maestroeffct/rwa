<div class="d-flex-center flex-column text-center mt-20">
    <img src="/assets/design_1/img/panel/promotions/modal.svg" alt="modal" class="img-fluid" width="215px" height="160px">

    <h4 class="font-14 mt-12">{{ trans('update.promote_your_course') }}</h4>
    <p class="font-12 text-gray-500 mt-8">{!! nl2br(trans('update.promote_your_course_hint_in_pay_promotion_plan_modal')) !!}</p>
</div>

<div class="mt-16 p-16 rounded-12 border-gray-200">
    <div class="d-flex align-items-center justify-content-between">
        <span class="text-gray-500">{{ trans('update.selected_plan') }}</span>
        <span class="">{{ $promotion->title }}</span>
    </div>

    <div class="d-flex align-items-center justify-content-between mt-12">
        <span class="text-gray-500">{{ trans('public.price') }}</span>
        <span class="">{{ (!empty($promotion->price) and $promotion->price > 0) ? handlePrice($promotion->price, true, true, false, null, true) : trans('public.free') }}</span>
    </div>

    <div class="d-flex align-items-center justify-content-between mt-12">
        <span class="text-gray-500">{{ trans('update.plan_duration') }}</span>
        <span class="">{{ trans('update.n_day',['day' => $promotion->days]) }}</span>
    </div>
</div>

<form class="js-promotion-plan-pay-form" action="/panel/marketing/promotions/{{ $promotion->id }}/pay" method="post">
    {{ csrf_field() }}

    <div class="form-group  mt-24">
        <label class="form-group-label">{{ trans('update.select_a_course') }}</label>

        <select name="course_id" class="js-ajax-course_id form-control custom-select2" data-dropdown-parent=".js-custom-modal">
            <option value="">{{ trans('panel.select_course') }}</option>

            @foreach($webinars as $webinar)
                <option value="{{ $webinar->id }}">{{ $webinar->title }}</option>
            @endforeach
        </select>

        <div class="invalid-feedback">{{ trans('panel.select_course') }}</div>
    </div>

</form>
