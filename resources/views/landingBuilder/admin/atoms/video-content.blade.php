<div class="js-video-sources-parent {{ $className ?? '' }}">

    <div class="form-group">
        <label class="form-group-label bg-white">{{ trans('update.video_source') }}</label>

        <select name="{{ $inputNamePrefix }}[video_source]" class="js-video-source-select form-control bg-white">
            @foreach(['youtube', 'vimeo', 'upload', 'external', 'iframe'] as $source)
                <option value="{{ $source }}" {{ (!empty($videoSource) and $videoSource == $source) ? 'selected' : '' }}>{{ trans("update.{$source}") }}</option>
            @endforeach
        </select>
    </div>

    @php
        $showVideoPath = (empty($videoSource) or (in_array($videoSource, ['youtube', 'vimeo', 'external', 'iframe'])));
        $showVideoFile = (!empty($videoSource) and in_array($videoSource, ['upload']));
    @endphp

    <x-landingBuilder-input
        label="{{ trans('update.video_path') }}"
        name="{{ $inputNamePrefix }}[video_path]"
        value="{{ !empty($videoPath) ? $videoPath : '' }}"
        placeholder=""
        hint=""
        icon="link-1"
        className="js-video-sources-online-field {{ $showVideoPath ? '' : 'd-none' }}"
    />

    <x-landingBuilder-file
        label="{{ trans('update.video_file') }}"
        name="{{ $inputNamePrefix }}[video_file]"
        value="{{ !empty($videoFile) ? $videoFile : '' }}"
        placeholder="{{ !empty($videoFile) ? getFileNameByPath($videoFile) : '' }}"
        hint=""
        icon="export"
        accept="video/*"
        className="js-video-sources-upload-field {{ $showVideoFile ? '' : 'd-none' }}"
    />

</div>
