<div class="row">
    <div class="col-12 col-md-6">

        <div class="form-group mb-3">
            <label class="input-label">{{ trans("update.custom_css") }}</label>
            <textarea name="contents[custom_css]" class="form-control" rows="10">{{ (!empty($themeContents) and !empty($themeContents['custom_css'])) ? $themeContents['custom_css'] : '' }}</textarea>
        </div>

        <div class="form-group mb-3">
            <label class="input-label">{{ trans("update.custom_js") }}</label>
            <textarea name="contents[custom_js]" class="form-control" rows="10">{{ (!empty($themeContents) and !empty($themeContents['custom_js'])) ? $themeContents['custom_js'] : '' }}</textarea>
        </div>


    </div>
</div>
