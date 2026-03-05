
<div class="form-group select2-bg-white">
    <label class="form-group-label bg-white">{{ trans('update.instructor') }}</label>

    <select name="contents[specific_instructors][{{ !empty($itemKey) ? $itemKey : 'record' }}][instructor_id]" class="{{ !empty($itemKey) ? 'js-make-select2' : 'js-make-select2-item' }} form-control bg-white" >
        <option value="">{{ trans('update.choose_a_instructor') }}</option>

        @if(!empty($instructors) and count($instructors))
            @foreach($instructors as $instructor)
                <option value="{{ $instructor->id }}" {{ (!empty($selectedInstructorItem) and $selectedInstructorItem->id == $instructor->id) ? 'selected' : '' }}>{{ $instructor->full_name }}</option>
            @endforeach
        @endif
    </select>
</div>

<div class="d-flex align-items-center justify-content-end mt-24">
    <button type="button" class="js-addable-accordion-remove-btn btn btn-danger">{{ trans('public.delete') }}</button>
</div>

