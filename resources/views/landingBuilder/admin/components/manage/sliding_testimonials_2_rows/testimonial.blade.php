
<div class="form-group select2-bg-white">
    <label class="form-group-label bg-white">{{ trans('update.assign_a_testimonial') }}</label>

    <select name="contents[testimonials][{{ !empty($itemKey) ? $itemKey : 'record' }}][testimonial_id]" class="{{ !empty($itemKey) ? 'js-make-select2' : 'js-make-select2-item' }} form-control bg-white" >
        <option value="">{{ trans('update.search_for_a_testimonial') }}</option>

        @if(!empty($testimonials) and count($testimonials))
            @foreach($testimonials as $testimonial)
                <option value="{{ $testimonial->id }}" {{ (!empty($selectedTestimonialItem) and $selectedTestimonialItem->id == $testimonial->id) ? 'selected' : '' }}>{{ $testimonial->user_name }}</option>
            @endforeach
        @endif
    </select>
</div>

<div class="d-flex align-items-center justify-content-end mt-24">
    <button type="button" class="js-addable-accordion-remove-btn btn btn-danger">{{ trans('public.delete') }}</button>
</div>

