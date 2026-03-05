<div class="js-statistic-item-card ">
    <x-landingBuilder-input
        label="{{ trans('public.title') }}"
        name="contents[statistics][{{ !empty($itemKey) ? $itemKey : 'record' }}][title]"
        value="{{ (!empty($statistic) and !empty($statistic['title'])) ? $statistic['title'] : '' }}"
        placeholder=""
        hint=""
        className=""
    />

    <x-landingBuilder-icons-select
        label="{{ trans('update.icon') }}"
        name="contents[statistics][{{ !empty($itemKey) ? $itemKey : 'record' }}][icon]"
        value="{{ (!empty($statistic) and !empty($statistic['icon'])) ? $statistic['icon'] : '' }}"
        placeholder="{{ trans('update.search_icons') }}"
        hint=""
        selectClassName="{{ !empty($itemKey) ? 'js-icons-select2' : 'js-make-icons-select2' }}"
        className=""
    />

    <x-landingBuilder-select
        label="{{ trans('update.data_type') }}"
        name="contents[statistics][{{ !empty($itemKey) ? $itemKey : 'record' }}][data_type]"
        value="{{ (!empty($statistic) and !empty($statistic['data_type'])) ? $statistic['data_type'] : '' }}"
        :items="['real', 'semi_real', 'manual']"
        hint=""
        className=""
        selectClassName="js-select-change-for-action"
        changeActionEls="js-data-types-fields"
        changeActionParent="js-statistic-item-card"
    />

    <x-landingBuilder-select
        label="{{ trans('update.data_source') }}"
        name="contents[statistics][{{ !empty($itemKey) ? $itemKey : 'record' }}][data_source]"
        value="{{ (!empty($statistic) and !empty($statistic['data_source'])) ? $statistic['data_source'] : '' }}"
        :items="['number_of_instructors', 'number_of_students', 'number_of_organizations', 'number_of_all_users', 'number_of_video_courses', 'number_of_live_courses', 'number_of_text_courses', 'number_of_courses', 'number_of_bundles', 'number_of_store_products', 'number_of_upcoming_courses', 'number_of_sales', 'sales_amount']"
        hint=""
        className="js-data-types-fields js-select-change-for-action-field-real js-select-change-for-action-field-semi_real"
    />

    <x-landingBuilder-input
        label="{{ trans('update.start_from') }}"
        name="contents[statistics][{{ !empty($itemKey) ? $itemKey : 'record' }}][start_from]"
        value="{{ (!empty($statistic) and !empty($statistic['start_from'])) ? $statistic['start_from'] : '' }}"
        type="number"
        placeholder=""
        hint=""
        className="js-data-types-fields js-select-change-for-action-field-semi_real {{ (!empty($statistic) and !empty($statistic['data_type']) and $statistic['data_type'] == 'semi_real') ? '' : 'd-none' }}"
    />

    <x-landingBuilder-input
        label="{{ trans('update.manual_data') }}"
        name="contents[statistics][{{ !empty($itemKey) ? $itemKey : 'record' }}][manual_data]"
        value="{{ (!empty($statistic) and !empty($statistic['manual_data'])) ? $statistic['manual_data'] : '' }}"
        placeholder=""
        hint=""
        className="js-data-types-fields js-select-change-for-action-field-manual {{ (!empty($statistic) and !empty($statistic['data_type']) and $statistic['data_type'] == 'manual') ? '' : 'd-none' }}"
    />
</div>

<div class="d-flex align-items-center justify-content-end mt-24">
    <button type="button" class="js-addable-accordion-remove-btn btn btn-danger">{{ trans('public.delete') }}</button>
</div>
