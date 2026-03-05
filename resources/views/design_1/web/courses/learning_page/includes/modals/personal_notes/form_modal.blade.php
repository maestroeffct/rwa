<div class="js-personal-note-form my-16" data-action="{{ $course->getLearningPageUrl() }}/personal-note/store">
    <input type="hidden" name="item_id" value="{{ $itemId }}">
    <input type="hidden" name="item_type" value="{{ $itemType }}">

    <div class="form-group">
        <label class="form-group-label">{{ trans('update.note_details') }}</label>
        <textarea name="details" class="js-ajax-details form-control" rows="6">{!! !empty($personalNote) ? $personalNote->note : '' !!}</textarea>
        <div class="invalid-feedback"></div>
    </div>


    <div class="form-group custom-input-file mb-0 flex-1">
        <label class="form-group-label">{{ trans('update.attachment') }} ({{ trans('public.optional') }})</label>

        <div class="custom-file bg-white">
            <input type="file" name="attachment" class="custom-file-input js-ajax-upload-file-input" id="attachmentsInput" data-upload-name="attachment">
            <span class="custom-file-text text-gray-500">{{ !empty($personalNote) ? getFileNameByPath($personalNote->attachment) : '' }}</span>
            <label class="custom-file-label bg-transparent" for="attachmentsInput">
                <x-iconsax-lin-export class="icons text-gray-400" width="24px" height="24px"/>
            </label>
        </div>
    </div>

</div>
