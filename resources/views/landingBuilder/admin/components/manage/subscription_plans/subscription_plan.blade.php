<div class="form-group select2-bg-white">
    <label class="form-group-label bg-white">{{ trans('update.select_a_plan') }}</label>

    <select name="contents[subscriptions_plans][{{ !empty($itemKey) ? $itemKey : 'record' }}][plan_id]" class="{{ !empty($itemKey) ? 'js-make-select2' : 'js-make-select2-item' }} form-control bg-white" >
        <option value="">{{ trans('update.choose_a_plan') }}</option>

        @if(!empty($subscriptionPlans) and count($subscriptionPlans))
            @foreach($subscriptionPlans as $subscriptionPlan)
                <option value="{{ $subscriptionPlan->id }}" {{ (!empty($selectedSubscriptionItem) and $selectedSubscriptionItem->id == $subscriptionPlan->id) ? 'selected' : '' }}>{{ $subscriptionPlan->title }}</option>
            @endforeach
        @endif
    </select>
</div>

<div class="d-flex align-items-center justify-content-end mt-24">
    <button type="button" class="js-addable-accordion-remove-btn btn btn-danger">{{ trans('public.delete') }}</button>
</div>
