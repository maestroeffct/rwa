@if(!empty($form->end_date))
    <div class="position-relative pl-8 mt-28">
        <div class="d-flex align-items-center p-12 rounded-12 bg-warning-20">
            <div class="alert-left-20 d-flex-center size-48 bg-warning rounded-12">
                <x-iconsax-bol-calendar-2 class="icons text-white" width="24px" height="24px"/>
            </div>

            <div class="ml-8">
                <h6 class="font-14 text-warning">{{ trans('update.form_expiry') }}</h6>
                <p class="font-12 text-warning opacity-75">{{ trans('update.this_form_will_be_expired_on_date',['date' => dateTimeFormat($form->end_date, 'j M Y')]) }}</p>
            </div>
        </div>
    </div>
@endif

@if(!$form->enable_resubmission)
    <div class="position-relative pl-8 mt-20">
        <div class="d-flex align-items-center p-12 rounded-12 bg-primary-20">
            <div class="alert-left-20 d-flex-center size-48 bg-primary rounded-12">
                <x-iconsax-bol-note-remove class="icons text-white" width="24px" height="24px"/>
            </div>

            <div class="ml-8">
                <h6 class="font-14 text-primary">{{ trans('update.resubmission_disabled') }}</h6>
                <p class="font-12 text-primary opacity-75">{{ trans('update.resubmission_disabled_alert_hint') }}</p>
            </div>
        </div>
    </div>
@endif
