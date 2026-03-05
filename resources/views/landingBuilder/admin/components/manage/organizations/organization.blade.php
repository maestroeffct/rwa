
<div class="form-group select2-bg-white">
    <label class="form-group-label bg-white">{{ trans('update.organization') }}</label>

    <select name="contents[specific_organizations][{{ !empty($itemKey) ? $itemKey : 'record' }}][organization_id]" class="{{ !empty($itemKey) ? 'js-make-select2' : 'js-make-select2-item' }} form-control bg-white" >
        <option value="">{{ trans('update.choose_a_organization') }}</option>

        @if(!empty($organizations) and count($organizations))
            @foreach($organizations as $organization)
                <option value="{{ $organization->id }}" {{ (!empty($selectedOrganizationItem) and $selectedOrganizationItem->id == $organization->id) ? 'selected' : '' }}>{{ $organization->full_name }}</option>
            @endforeach
        @endif
    </select>
</div>

<div class="d-flex align-items-center justify-content-end mt-24">
    <button type="button" class="js-addable-accordion-remove-btn btn btn-danger">{{ trans('public.delete') }}</button>
</div>

