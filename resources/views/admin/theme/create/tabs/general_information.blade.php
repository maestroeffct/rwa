<div class="row">
    <div class="col-12 col-md-6">

        <div class="form-group mb-3">
            <label>{{ trans('admin/main.title') }}</label>
            <input type="text" name="title" value="{{ !empty($themeItem) ? $themeItem->title : old('title') }}" class="form-control @error('title') is-invalid @enderror"/>
            @error('title')
            <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group mb-3">
            <label class="input-label">{{ trans('update.preview_image') }}</label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <button type="button" class="input-group-text admin-file-manager" data-input="preview_image" data-preview="holder">
                        <i class="fa fa-chevron-up"></i>
                    </button>
                </div>

                <input type="text" name="preview_image" id="preview_image" value="{{ !empty($themeItem->preview_image) ? $themeItem->preview_image : old('preview_image') }}" class="form-control @error('preview_image') is-invalid @enderror"/>
                <div class="input-group-append">
                    <button type="button" class="input-group-text admin-file-view" data-input="preview_image">
                        <i class="fa fa-eye"></i>
                    </button>
                </div>

                @error('preview_image')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>
        </div>

        <div class="form-group mb-3">
            <label class="input-label">{{ trans('update.default_color_mode') }}</label>
            <select name="default_color_mode" class="form-control @error('default_color_mode') is-invalid @enderror">
                <option value="light" {{ (!empty($themeItem) and $themeItem->default_color_mode == 'light') ? 'selected' : '' }}>{{ trans('update.light_mode') }}</option>
                <option value="dark" {{ (!empty($themeItem) and $themeItem->default_color_mode == 'dark') ? 'selected' : '' }}>{{ trans('update.dark_mode') }}</option>
            </select>

            @error('default_color_mode')
            <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group mb-3">
            <label class="input-label">{{ trans('update.color_combination') }}</label>
            <select name="color_id" class="form-control @error('color_id') is-invalid @enderror">
                <option value="">{{ trans('update.select_a_color') }}</option>

                @foreach($themeColors as $themeColor)
                    <option value="{{ $themeColor->id }}" {{ (!empty($themeItem) and $themeItem->color_id == $themeColor->id) ? 'selected' : '' }}>{{ $themeColor->title }}</option>
                @endforeach
            </select>

            @error('color_id')
            <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group mb-3">
            <label class="input-label">{{ trans('update.font_combination') }}</label>
            <select name="font_id" class="form-control @error('font_id') is-invalid @enderror">
                <option value="">{{ trans('update.select_a_font') }}</option>

                @foreach($themeFonts as $themeFont)
                    <option value="{{ $themeFont->id }}" {{ (!empty($themeItem) and $themeItem->font_id == $themeFont->id) ? 'selected' : '' }}>{{ $themeFont->title }}</option>
                @endforeach
            </select>

            @error('font_id')
            <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group mb-3">
            <label class="input-label">{{ trans('update.header_style') }}</label>
            <select name="header_id" class="form-control @error('header_id') is-invalid @enderror">
                <option value="">{{ trans('update.select_a_header') }}</option>

                @foreach($themeHeaders as $themeHeader)
                    <option value="{{ $themeHeader->id }}" {{ (!empty($themeItem) and $themeItem->header_id == $themeHeader->id) ? 'selected' : '' }}>{{ $themeHeader->title }}</option>
                @endforeach
            </select>

            @error('header_id')
            <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group mb-3">
            <label class="input-label">{{ trans('update.footer_style') }}</label>
            <select name="footer_id" class="form-control @error('footer_id') is-invalid @enderror">
                <option value="">{{ trans('update.select_a_footer') }}</option>

                @foreach($themeFooters as $themeFooter)
                    <option value="{{ $themeFooter->id }}" {{ (!empty($themeItem) and $themeItem->footer_id == $themeFooter->id) ? 'selected' : '' }}>{{ $themeFooter->title }}</option>
                @endforeach
            </select>

            @error('footer_id')
            <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group mb-3 d-flex align-items-center">
            <label class="cursor-pointer" for="statusSwitch">{{ trans('admin/main.active') }}</label>
            <div class="custom-control custom-switch ml-3">
                <input type="checkbox" name="enable" class="custom-control-input" id="statusSwitch" {{ (!empty($themeItem) and $themeItem->enable) ? 'checked' : '' }}>
                <label class="custom-control-label" for="statusSwitch"></label>
            </div>
        </div>

    </div>
</div>
