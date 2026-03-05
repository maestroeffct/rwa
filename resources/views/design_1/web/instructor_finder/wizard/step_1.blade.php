<div class="">

    <div class="d-inline-flex-center py-4 px-8 bg-gray-100 rounded-32 text-gray-500">{{ trans('update.step') }} 1/4</div>
    <h3 class="mt-8 font-24 font-weight-bold">{{ trans('update.tutoring_subject') }} 🎓</h3>

    <div class="d-flex align-items-center w-100 mt-4">
        <div class="instructor-finder-wizard__progress rounded-4 bg-gray-100 flex-1 mr-8">
            <div class="progressbar rounded-4 bg-success" style="width: 25%"></div>
        </div>

        <div class="d-flex-center size-32 bg-gray-100 rounded-circle">
            <x-iconsax-lin-teacher class="icons text-gray-500" width="16px" height="16px"/>
        </div>
    </div>

    <div class="mt-32 text-gray-500">{{ trans('update.which_meeting_type_do_you_prefer?') }}</div>

    <div class="form-group  mt-28">
        <label class="form-group-label">{{ trans('update.subject') }}</label>

        <select name="category_id" class="form-control select2 @error('category_id') is-invalid @enderror">
            <option value="">{{ trans('webinars.select_category') }}</option>

            @if(!empty($categories))
                @foreach($categories as $category)
                    @if(!empty($category->subCategories) and count($category->subCategories))
                        <optgroup label="{{  $category->title }}">
                            @foreach($category->subCategories as $subCategory)
                                <option value="{{ $subCategory->id }}" @if(request()->get('category_id') == $subCategory->id) selected="selected" @endif>{{ $subCategory->title }}</option>
                            @endforeach
                        </optgroup>
                    @else
                        <option value="{{ $category->id }}" @if(request()->get('category_id') == $category->id) selected="selected" @endif>{{ $category->title }}</option>
                    @endif
                @endforeach
            @endif
        </select>

        @error('category_id')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>

</div>
