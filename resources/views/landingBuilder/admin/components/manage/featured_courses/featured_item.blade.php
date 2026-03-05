<x-landingBuilder-searchable-course
    label="{{ trans('update.select_a_featured_course') }}"
    name="contents[featured_courses][{{ !empty($itemKey) ? $itemKey : 'record' }}][course]"
    value="{{ (!empty($featuredCourse) and !empty($featuredCourse['course'])) ? $featuredCourse['course'] : '' }}"
    placeholder="{{ trans('update.search_a_course') }}"
    className=""
    hint=""
    selectClassName=""
    changeActionEls=""
    changeActionParent=""
/>

<x-landingBuilder-file
    label="{{ trans('public.cover_image') }}"
    name="contents[featured_courses][{{ !empty($itemKey) ? $itemKey : 'record' }}][cover_image]"
    value="{{ (!empty($featuredCourse) and !empty($featuredCourse['cover_image'])) ? $featuredCourse['cover_image'] : '' }}"
    placeholder="{{ (!empty($featuredCourse) and !empty($featuredCourse['cover_image'])) ? getFileNameByPath($featuredCourse['cover_image']) : '' }}"
    hint="{{ trans('update.preferred_size') }} 120x480px"
    icon="export"
    accept="image/*"
    className=""
/>

<x-landingBuilder-file
    label="{{ trans('update.overlay_image') }}"
    name="contents[featured_courses][{{ !empty($itemKey) ? $itemKey : 'record' }}][overlay_image]"
    value="{{ (!empty($featuredCourse) and !empty($featuredCourse['overlay_image'])) ? $featuredCourse['overlay_image'] : '' }}"
    placeholder="{{ (!empty($featuredCourse) and !empty($featuredCourse['overlay_image'])) ? getFileNameByPath($featuredCourse['overlay_image']) : '' }}"
    hint="{{ trans('update.preferred_size') }} 120x480px"
    icon="export"
    accept="image/*"
    className=""
/>

<x-landingBuilder-addable-text-input
    title="{{ trans('update.checked_items') }}"
    inputLabel="{{ trans('public.title') }}"
    inputName="contents[featured_courses][{{ !empty($itemKey) ? $itemKey : 'record' }}][checked_items][nabat]"
    :items="(!empty($featuredCourse) and !empty($featuredCourse['checked_items'])) ? $featuredCourse['checked_items'] : []"
    className=""
    titleClassName="text-dark"
/>

<div class="d-flex align-items-center justify-content-end mt-24">
    <button type="button" class="js-addable-accordion-remove-btn btn btn-danger">{{ trans('public.delete') }}</button>
</div>
