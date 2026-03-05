<div class="js-attachment-form" data-action="/panel/setting/attachments/{{ !empty($attachment) ? $attachment->id.'/update' : 'store' }}">

    @if(!empty($user_id))
        <input type="hidden" name="user_id" value="{{ $user_id }}">
    @endif

    <input type="hidden" name="locale" value="{{ getDefaultLocale() }}">

    <div class="form-group mt-16">
        <label class="form-group-label">{{ trans('public.title') }}</label>
        <input type="text" name="title" class="js-ajax-title form-control" value="{{ !empty($attachment) ? $attachment->title : '' }}">
        <div class="invalid-feedback"></div>
    </div>

    <div class="form-group">
        <label class="form-group-label">{{ trans('public.description') }}</label>
        <textarea name="description" class="js-ajax-description form-control" rows="3">{{ !empty($attachment) ? $attachment->description : '' }}</textarea>
        <div class="invalid-feedback"></div>
    </div>

    <div class="form-group ">
        <label class="form-group-label">{{ trans('webinars.file_type') }}</label>
        <select name="file_type" class="js-ajax-file_type form-control select2" >
            <option value="">{{ trans('webinars.select_file_type') }}</option>

            @foreach(\App\Models\File::$fileTypes as $fileType)
                <option value="{{ $fileType }}" {{ (!empty($attachment) and $attachment->file_type == $fileType) ? 'selected' : '' }}>{{ trans('update.file_type_'.$fileType) }}</option>
            @endforeach
        </select>
        <div class="invalid-feedback"></div>
    </div>

    <div class="form-group mb-0">
        <div class="custom-file bg-white">
            <input type="file" name="attachment" class="js-ajax-upload-file-input js-ajax-attachment custom-file-input" data-upload-name="attachment" id="attachFile">
            <span class="custom-file-text">{{ trans('update.select_a_file') }}</span>
            <label class="custom-file-label" for="attachFile">{{ trans('update.browse') }}</label>
        </div>

        <div class="invalid-feedback d-block"></div>
    </div>

</div>
