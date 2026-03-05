<div class="row">
    <div class="col-12">

        <div class="form-group mt-15 ">
            <label class="input-label d-block">{{ trans('update.target_types') }}</label>

            <select name="target_type" class="js-target-types-input custom-select @error('target_types')  is-invalid @enderror">
                @foreach(\App\Models\Subscribe::$targetTypes as $type)
                    <option value="{{ $type }}" @if(!empty($subscribe) and $subscribe->target_type == $type) selected @endif>{{ trans('update.target_types_'.$type) }}</option>
                @endforeach
            </select>

            @error('target_types')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
        </div>

        <div class="form-group mt-15 js-select-target-field {{ (empty($subscribe) or $subscribe->target_type == 'all') ? 'd-none' : '' }}">
            <label class="input-label d-block">{{ trans('update.select_target') }}</label>

            @php
                $targets = [
                    'courses' => \App\Models\Subscribe::$courseTargets,
                    'bundles' => \App\Models\Subscribe::$bundleTargets,
                ];
            @endphp

            <select name="target" class="js-target-input custom-select  @error('target')  is-invalid @enderror">
                <option value="">{{ trans('update.select_target') }}</option>

                @foreach($targets as $targetKey => $targetItems)
                    @foreach($targetItems as $target)
                        <option value="{{ $target }}" class="js-target-option js-target-option-{{ $targetKey }} {{ (!empty($subscribe) and $subscribe->target_type == $targetKey) ? '' : 'd-none' }}" @if(!empty($subscribe) and $subscribe->target == $target) selected @endif>{{ trans('update.'.$target) }}</option>
                    @endforeach
                @endforeach
            </select>

            @error('target')
            <div class="invalid-feedback d-none">
                {{ $message }}
            </div>
            @enderror
        </div>

        @php
            $selectedCategoryIds = !empty($subscribe) ? $subscribe->categories->pluck('id')->toArray() : [];
        @endphp

        <div class="form-group js-specific-categories-field {{ (!empty($subscribe) and $subscribe->target == "specific_categories") ? '' : 'd-none' }}">
            <label class="input-label">{{ trans('update.specific_categories') }}</label>

            <select name="category_ids[]" id="categories" class="custom-select select2" multiple data-placeholder="{{ trans('public.choose_category') }}">

                @foreach($categories as $category)
                    @if(!empty($category->subCategories) and count($category->subCategories))
                        <optgroup label="{{  $category->title }}">
                            @foreach($category->subCategories as $subCategory)
                                <option value="{{ $subCategory->id }}" {{ in_array($subCategory->id, $selectedCategoryIds) ? 'selected' : '' }}>{{ $subCategory->title }}</option>
                            @endforeach
                        </optgroup>
                    @else
                        <option value="{{ $category->id }}" {{ in_array($category->id, $selectedCategoryIds) ? 'selected' : '' }}>{{ $category->title }}</option>
                    @endif
                @endforeach
            </select>

            @error('category_ids')
            <div class="invalid-feedback d-block">
                {{ $message }}
            </div>
            @enderror
        </div>


        <div class="form-group js-specific-instructors-field {{ (!empty($subscribe) and $subscribe->target == "specific_instructors") ? '' : 'd-none' }}">
            <label class="input-label">{{trans('update.specific_instructors')}}</label>

            <select name="instructor_ids[]" multiple="multiple" data-search-option="just_teacher_role" class="form-control search-user-select2"
                    data-placeholder="{{trans('public.search_instructors')}}">

                @if(!empty($subscribe) and count($subscribe->instructors))
                    @foreach($subscribe->instructors as $instructor)
                        <option value="{{ $instructor->id }}" selected>{{ $instructor->full_name }}</option>
                    @endforeach
                @endif
            </select>

            @error('instructor_ids')
            <div class="invalid-feedback d-block">
                {{ $message }}
            </div>
            @enderror
        </div>


        <div class="form-group js-specific-courses-field {{ (!empty($subscribe) and $subscribe->target == "specific_courses") ? '' : 'd-none' }}">
            <label class="input-label">{{ trans('update.specific_courses') }}</label>
            <select name="courses_ids[]" multiple="multiple" class="form-control search-webinar-select2"
                    data-placeholder="Search classes">

                @if(!empty($subscribe) and count($subscribe->courses))
                    @foreach($subscribe->courses as $specificCourse)
                        <option value="{{ $specificCourse->id }}" selected>{{ $specificCourse->title }}</option>
                    @endforeach
                @endif
            </select>

            @error('courses_ids')
            <div class="invalid-feedback d-block">
                {{ $message }}
            </div>
            @enderror
        </div>


        <div class="form-group js-specific-bundles-field {{ (!empty($subscribe) and $subscribe->target == "specific_bundles") ? '' : 'd-none' }}">
            <label class="input-label">{{ trans('update.specific_bundles') }}</label>
            <select name="bundle_ids[]" multiple="multiple" class="form-control search-bundle-select2 " data-placeholder="Search bundles">

                @if(!empty($subscribe) and count($subscribe->bundles))
                    @foreach($subscribe->bundles as $bundle)
                        <option value="{{ $bundle->id }}" selected>{{ $bundle->title }}</option>
                    @endforeach
                @endif
            </select>

            @error('bundle_ids')
            <div class="invalid-feedback d-block">
                {{ $message }}
            </div>
            @enderror
        </div>

    </div>
</div>
