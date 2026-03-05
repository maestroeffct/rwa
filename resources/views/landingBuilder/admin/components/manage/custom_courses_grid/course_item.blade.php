<x-landingBuilder-searchable-course
    label="{{ trans('update.select_a_course') }}"
    name="contents[featured_courses][{{ !empty($itemKey) ? $itemKey : 'record' }}][course]"
    value="{{ (!empty($featuredCourse) and !empty($featuredCourse['course'])) ? $featuredCourse['course'] : '' }}"
    placeholder="{{ trans('update.search_a_course') }}"
    className=""
    hint=""
    selectClassName=""
    changeActionEls=""
    changeActionParent=""
/>

<div class="d-flex align-items-center justify-content-end mt-24">
    <button type="button" class="js-addable-accordion-remove-btn btn btn-danger">{{ trans('public.delete') }}</button>
</div>
