<div class="js-content-form change-chapter-form mt-16" data-action="/panel/chapters/change">

    <div class="d-flex-center flex-column mt-12 mb-24">
        <div class="d-flex-center size-64 rounded-16 bg-primary">
            <x-iconsax-bul-category class="icons text-white" width="32px" height="32px"/>
        </div>

        <h4 class="font-14 font-weight-bold mt-12">{{ trans('update.change_chapter') }}</h4>
        <p class="font-12 text-gray-500 mt-4">{{ trans('update.create_a_new_section_and_include_different_materials') }}</p>
    </div>

    <input type="hidden" name="ajax[webinar_id]" class="" value="{{ $webinar->id }}">
    <input type="hidden" name="ajax[item_id]" class="js-item-id" value="">
    <input type="hidden" name="ajax[item_type]" class="js-item-type" value="">

    <div class="form-group">
        <label class="form-group-label">{{ trans('public.chapter') }}</label>

        <select name="ajax[chapter_id]" class="js-ajax-chapter_id form-control">
            <option value="">{{ trans('update.select_chapter') }}</option>

            @if(!empty($webinar->chapters) and count($webinar->chapters))
                @foreach($webinar->chapters as $chapter)
                    <option value="{{ $chapter->id }}">{{ $chapter->title }}</option>
                @endforeach
            @endif
        </select>
    </div>

</div>
