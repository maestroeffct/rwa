<div class="row">
    <div class="col-12 col-lg-6 mt-20">
        {{-- General Information --}}
        <div class="p-16 rounded-16 border-gray-200">
            <h3 class="font-14 mb-24">{{ trans('update.general_information') }}</h3>

            @include('design_1.panel.includes.locale.locale_select',[
                'itemRow' => !empty($landingComponent) ? $landingComponent : null,
                'withoutReloadLocale' => false,
                'extraClass' => ''
            ])


            <x-landingBuilder-switch
                label="{{ trans('update.enable_component') }}"
                id="enable"
                name="enable"
                checked="{{ !!($landingComponent->enable) }}"
                hint=""
                className="mb-0"
            />

        </div>

        <div class="p-16 rounded-16 border-gray-200 mt-20">
            <h3 class="font-14 mb-24">{{ trans('update.main_content') }}</h3>

            <x-landingBuilder-input
                label="{{ trans('public.title') }}"
                name="contents[main_content][title]"
                value="{{ (!empty($contents['main_content']) and !empty($contents['main_content']['title'])) ? $contents['main_content']['title'] : '' }}"
                placeholder=""
                hint=""
                className=""
            />

            <x-landingBuilder-textarea
                label="{{ trans('public.description') }}"
                name="contents[main_content][description]"
                value="{{ (!empty($contents['main_content']) and !empty($contents['main_content']['description'])) ? $contents['main_content']['description'] : '' }}"
                placeholder=""
                rows="3"
                hint="{{ trans('update.suggested_about_120_characters') }}"
                className=""
            />

            <x-landingBuilder-file
                label="{{ trans('update.floating_image') }}"
                name="contents[main_content][floating_image]"
                value="{{ (!empty($contents['main_content']) and !empty($contents['main_content']['floating_image'])) ? $contents['main_content']['floating_image'] : '' }}"
                placeholder="{{ (!empty($contents['main_content']) and !empty($contents['main_content']['floating_image'])) ? getFileNameByPath($contents['main_content']['floating_image']) : '' }}"
                hint="{{ trans('update.preferred_size') }} 160x160px"
                icon="export"
                accept="image/*"
                className=""
            />


            <x-landingBuilder-addable-text-input
                title="{{ trans('update.subscription_benefits') }}"
                inputLabel="{{ trans('public.title') }}"
                inputName="contents[checked_items][record]"
                :items="(!empty($contents['checked_items'])) ? $contents['checked_items'] : []"
                className="mt-28"
                titleClassName="text-gray-500"
            />

            <x-landingBuilder-make-button
                title="{{ trans('update.button') }}"
                inputNamePrefix="contents[main_content][button]"
                :buttonData="(!empty($contents['main_content']) and !empty($contents['main_content']['button'])) ? $contents['main_content']['button'] : []"
                className="mt-24"
            />

        </div>

    </div>{{-- End Col --}}

    <div class="col-12 col-lg-6 mt-20">

        {{-- Features  --}}
        <div class="p-16 rounded-16 border-gray-200 ">
            <x-landingBuilder-addable-accordions
                title="{{ trans('update.subscription_plans') }}"
                addText="{{ trans('update.add_a_plan') }}"
                className="mb-0"
                mainRow="js-subscription-item-main-card"
            >
                @if(!empty($contents) and !empty($contents['subscriptions_plans']) and count($contents['subscriptions_plans']))
                    @foreach($contents['subscriptions_plans'] as $sKey => $itemData)
                        @if($sKey != 'record')
                            @php
                                $selectedSubscriptionPlanId = (!empty($itemData) and !empty($itemData['plan_id'])) ? $itemData['plan_id'] : null;
                                $selectedSubscriptionPlan = (!empty($selectedSubscriptionPlanId) and !empty($subscriptionPlans) and count($subscriptionPlans)) ? $subscriptionPlans->where('id', $selectedSubscriptionPlanId)->first() : null;
                            @endphp

                            <x-landingBuilder-accordion
                                title="{{ (!empty($selectedSubscriptionPlan)) ? $selectedSubscriptionPlan->title : trans('update.new_plan') }}"
                                id="subscription_{{ $sKey }}"
                                className=""
                                show=""
                            >
                                @include('landingBuilder.admin.components.manage.subscription_plans.subscription_plan',['itemKey' => $sKey, 'selectedSubscriptionItem' => $selectedSubscriptionPlan])
                            </x-landingBuilder-accordion>
                        @endif
                    @endforeach
                @endif
            </x-landingBuilder-addable-accordions>
        </div>

    </div>{{-- End Col --}}

</div>{{-- End Row --}}


<div class="js-subscription-item-main-card d-none">
    <x-landingBuilder-accordion
        title="{{ trans('update.new_plan') }}"
        id="record"
        className=""
        show="true"
    >
        @include('landingBuilder.admin.components.manage.subscription_plans.subscription_plan')
    </x-landingBuilder-accordion>
</div>
