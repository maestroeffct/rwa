<x-landingBuilder-input
    label="{{ trans('public.title') }}"
    name="contents[course_tabs_content][{{ !empty($itemKey) ? $itemKey : 'record' }}][title]"
    value="{{ (!empty($tabContentData) and !empty($tabContentData['title'])) ? $tabContentData['title'] : '' }}"
    placeholder=""
    hint=""
    className=""
/>


<x-landingBuilder-select
    label="{{ trans('update.source') }}"
    name="contents[course_tabs_content][{{ !empty($itemKey) ? $itemKey : 'record' }}][source]"
    value="{{ (!empty($tabContentData) and !empty($tabContentData['source'])) ? $tabContentData['source'] : '' }}"
    :items="['category', 'instructor', 'custom']"
    hint=""
    className=""
    selectClassName="js-select-change-for-action"
    changeActionEls="js-course-tab-content-source-fields"
/>


@php
    $tabSourceIsCategory = (empty($tabContentData) or empty($tabContentData['source']) or $tabContentData['source'] == "category");
    $tabSourceIsInstructor = (!empty($tabContentData) and !empty($tabContentData['source']) and $tabContentData['source'] == "instructor");
    $tabSourceIsCustom = (!empty($tabContentData) and !empty($tabContentData['source']) and $tabContentData['source'] == "custom");
@endphp



{{-- Categories --}}
<div class="form-group select2-bg-white js-course-tab-content-source-fields js-select-change-for-action-field-category {{ $tabSourceIsCategory ? '' : 'd-none' }}">
    <label class="form-group-label bg-white">{{ trans('update.select_a_category') }}</label>

    <select
        name="contents[course_tabs_content][{{ !empty($itemKey) ? $itemKey : 'record' }}][category]"
        class="{{ !empty($itemKey) ? 'js-make-select2' : 'js-make-select2-item' }} form-control bg-white"
    >
        <option value="">{{ trans('public.choose_category') }}</option>

        @if(!empty($categories) and count($categories))
            @foreach($categories as $category)
                @if(!empty($category->subCategories) and count($category->subCategories))
                    <optgroup label="{{  $category->title }}">
                        @foreach($category->subCategories as $subCategory)
                            <option value="{{ $subCategory->id }}" {{ (!empty($tabContentData) and !empty($tabContentData['category']) and $tabContentData['category'] == $subCategory->id) ? 'selected' : '' }}>{{ $subCategory->title }}</option>
                        @endforeach
                    </optgroup>
                @else
                    <option value="{{ $category->id }}" {{ (!empty($tabContentData) and !empty($tabContentData['category']) and $tabContentData['category'] == $category->id) ? 'selected' : '' }}>{{ $category->title }}</option>
                @endif
            @endforeach
        @endif
    </select>
</div>

{{-- instructor --}}
<x-landingBuilder-searchable-user
    label="{{ trans('update.select_an_instructor') }}"
    name="contents[course_tabs_content][{{ !empty($itemKey) ? $itemKey : 'record' }}][instructor]"
    value="{{ (!empty($tabContentData) and !empty($tabContentData['instructor'])) ? $tabContentData['instructor'] : '' }}"
    placeholder="{{ trans('update.search_a_instructor') }}"
    className="js-course-tab-content-source-fields js-select-change-for-action-field-instructor {{ $tabSourceIsInstructor ? '' : 'd-none' }}"
    hint=""
    selectClassName=""
    changeActionEls=""
    changeActionParent=""
    searchOption="just_teachers"
/>

{{-- Sort --}}
<x-landingBuilder-select
    label="{{ trans('update.sort') }}"
    name="contents[course_tabs_content][{{ !empty($itemKey) ? $itemKey : 'record' }}][sort]"
    value="{{ (!empty($tabContentData) and !empty($tabContentData['sort'])) ? $tabContentData['sort'] : '' }}"
    :items="['best_rated', 'lowest_price', 'highest_price', 'publish_date']"
    hint=""
    className=""
    selectClassName=""
    changeActionEls=""
/>

{{-- Custom Courses --}}
<div class="js-course-tab-content-source-fields js-select-change-for-action-field-custom {{ $tabSourceIsCustom ? '' : 'd-none' }}">

    @php
        $customCourses = (!empty($tabContentData) and !empty($tabContentData['custom_courses']) and is_array($tabContentData['custom_courses'])) ? $tabContentData['custom_courses'] : [];
    @endphp

    <x-landingBuilder-addable-search-course
        title="{{ trans('update.courses') }}"
        inputLabel="{{ trans('update.course') }}"
        addText=""
        inputName="contents[course_tabs_content][{{ !empty($itemKey) ? $itemKey : 'nabat' }}][custom_courses][record]"
        :items="$customCourses"
        className=""
    />

</div>
