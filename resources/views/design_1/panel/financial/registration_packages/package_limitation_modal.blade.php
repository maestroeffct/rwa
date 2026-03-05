<div class="js-custom-modal rounded-top-20 soft-shadow-5 pt-24">
    <div class="d-flex align-items-center justify-content-between pb-16 px-16 border-bottom-gray-100">
        <div class="">
            <h3 class="font-16 text-dark">{{ trans('update.upgrade_your_plan') }}</h3>
        </div>

        <button class="modal-close-btn close-swl">
            <x-iconsax-lin-add class="close-icon" width="25px" height="25px"/>
        </button>
    </div>

    <div class="py-8 custom-swl-modal-body has-footer px-16">
        <div class="d-flex-center flex-column text-center mt-16">
            <div class="d-flex-center">
                <img src="/assets/design_1/img/panel/registration_packages/upgrade_your_plan.svg" alt="{{ trans('upgrade_your_plan') }}" class="img-fluid" width="216px" height="161px">
            </div>

            <h4 class="font-14 font-weight-bold mt-12">{{ trans('update.your_account_limited') }}</h4>

            <div class="font-12 mt-8 text-gray-500">{{ trans('update.your_account_limited_new_hint') }}</div>

            @if(!empty($currentCount))
                <div class="font-12 mt-8 text-gray-500">{{ trans('update.your_current_plan_'.$type,['count' => $currentCount]) }}</div>
            @endif

        </div>
    </div>

    <div class="custom-modal-footer d-flex justify-content-end bg-gray-100 p-16 w-100 rounded-bottom-20">
        <button type="button" class="close-swl btn-transparent text-gray-500 mr-12">{{ trans('public.cancel') }}</button>
        <a href="/panel/financial/registration-packages" class="btn btn-primary btn-lg">{{ trans('update.upgrade') }}</a>
    </div>
</div>
