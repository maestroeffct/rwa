
<div class="form-group select2-bg-white">
    <label class="form-group-label bg-white">{{ trans('update.assign_a_instructor') }}</label>

    <select name="contents[meeting_instructors][{{ !empty($itemKey) ? $itemKey : 'record' }}][instructor]" class="{{ !empty($itemKey) ? 'js-make-select2' : 'js-make-select2-item' }} form-control bg-white" >
        <option value="">{{ trans('public.choose_instructor') }}</option>

        @if(!empty($meetingInstructors) and count($meetingInstructors))
            @foreach($meetingInstructors as $meetingInstructor)
                <option value="{{ $meetingInstructor->id }}" {{ (!empty($selectedInstructorItem) and $selectedInstructorItem->id == $meetingInstructor->id) ? 'selected' : '' }}>{{ $meetingInstructor->full_name }}</option>
            @endforeach
        @endif
    </select>
</div>

<div class="d-flex align-items-center justify-content-end mt-24">
    <button type="button" class="js-addable-accordion-remove-btn btn btn-danger">{{ trans('public.delete') }}</button>
</div>

