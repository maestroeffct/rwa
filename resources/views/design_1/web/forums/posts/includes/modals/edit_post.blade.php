<form action="{{ $post->getEditUrl($forum->slug, $topic->slug) }}" method="post">

    <div class="form-group bg-white-editor">
        <label class="form-group-label">{{ trans('public.description') }}</label>
        <textarea name="description" data-height="500" class="js-ajax-description js-edit-post-summernote form-control">{!! $post->description !!}</textarea>
        <div class="invalid-feedback"></div>
    </div>


    <div class="form-group custom-input-file mb-0 flex-1">
        <label class="form-group-label">{{ trans('update.attachment') }} ({{ trans('public.optional') }})</label>

        <div class="custom-file bg-white">
            <input type="file" name="attachment" class="custom-file-input js-ajax-upload-file-input" id="attachmentsInputEdit" data-upload-name="attachment">
            <span class="custom-file-text text-gray-500">{{ $post->getAttachmentName()  }}</span>
            <label class="custom-file-label bg-transparent" for="attachmentsInputEdit">
                <x-iconsax-lin-export class="icons text-gray-400" width="24px" height="24px"/>
            </label>
        </div>
    </div>
</form>
