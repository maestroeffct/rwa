<form action="{{ getAdminPanelUrl('/forums/settings') }}" method="post">
    {{ csrf_field() }}
    <input type="hidden" name="name" value="{{ \App\Models\Setting::$forumsImagesSettingsName }}">

    <div class="row">
        <div class="col-12 col-md-6">

            @php
                $images = [
                    'create_topic_cover_image',
                    'forum_search_topics_cover_image',
                    'featured_topics_left_float_image',
                    'featured_topics_right_float_image',
                ];
            @endphp

            @foreach($images as $image)
                <div class="form-group">
                    <label class="input-label">{{ trans("update.{$image}") }}</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <button type="button" class="input-group-text admin-file-manager" data-input="image_{{ $image }}" data-preview="holder">
                                <i class="fa fa-chevron-up"></i>
                            </button>
                        </div>
                        <input type="text" name="value[{{ $image }}]" id="image_{{ $image }}" value="{{ (!empty($settingValues) and !empty($settingValues[$image])) ? $settingValues[$image] : "" }}" class="form-control"/>
                    </div>
                </div>
            @endforeach


        </div>
    </div>

    <button type="submit" class="btn btn-primary mt-1">{{ trans('admin/main.submit') }}</button>
</form>
