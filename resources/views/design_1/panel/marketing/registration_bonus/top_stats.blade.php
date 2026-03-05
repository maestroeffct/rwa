<div class="row">
    <div class="col-6 col-lg-3">
        <div class="bg-white p-16 rounded-24">
            <div class="d-flex align-items-start justify-content-between">
                <span class="text-gray-500 mt-8">{{ trans('update.registration_bonus') }}</span>
                <div class="size-48 d-flex-center bg-primary-30 rounded-12">
                    <x-iconsax-bul-gift class="icons text-primary" width="24px" height="24px"/>
                </div>
            </div>

            <h5 class="font-24 mt-12 line-height-1">{{ handlePrice($registrationBonusSettings['registration_bonus_amount'] ?? 0) }}</h5>
        </div>
    </div>

    <div class="col-6 col-lg-3">
        <div class="bg-white p-16 rounded-24">
            <div class="d-flex align-items-start justify-content-between">
                <span class="text-gray-500 mt-8">{{ trans('update.earned_bonus') }}</span>
                <div class="size-48 d-flex-center bg-success-30 rounded-12">
                    <x-iconsax-bul-money-recive class="icons text-success" width="24px" height="24px"/>
                </div>
            </div>

            <h5 class="font-24 mt-12 line-height-1">{{ handlePrice($earnedRegistrationBonus) }}</h5>
        </div>
    </div>

    <div class="col-6 col-lg-3 mt-16 mt-md-0">
        <div class="bg-white p-16 rounded-24">
            <div class="d-flex align-items-start justify-content-between">
                <span class="text-gray-500 mt-8">{{ trans('update.bonus_status') }}</span>
                <div class="size-48 d-flex-center bg-danger-30 rounded-12">
                    <x-iconsax-bul-shield-security class="icons text-danger" width="24px" height="24px"/>
                </div>
            </div>

            <h5 class="font-24 mt-12 line-height-1 {{ !empty($accounting) ? 'text-success' : 'text-danger' }}">{{ !empty($accounting) ? trans('update.unlocked') : trans('update.locked') }}</h5>
        </div>
    </div>

    <div class="col-6 col-lg-3 mt-16 mt-md-0">
        <div class="bg-white p-16 rounded-24">
            <div class="d-flex align-items-start justify-content-between">
                <span class="text-gray-500 mt-8">{{ trans('update.bonus_date') }}</span>
                <div class="size-48 d-flex-center bg-warning-30 rounded-12">
                    <x-iconsax-bul-calendar-tick class="icons text-warning" width="24px" height="24px"/>
                </div>
            </div>

            <h5 class="font-24 mt-12 line-height-1">{{ !empty($accounting) ? dateTimeFormat($accounting->created_at, 'j M Y') : '-' }}</h5>
        </div>
    </div>
</div>
