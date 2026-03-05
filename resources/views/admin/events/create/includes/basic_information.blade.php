<h2 class="section-title after-line">{{ trans('public.basic_information') }}</h2>

<div class="row">
    <div class="col-12 col-md-5">
        @if(!empty(getGeneralSettings('content_translate')))
            <div class="form-group">
                <label class="input-label">{{ trans('auth.language') }}</label>
                <select name="locale" class="form-control {{ !empty($event) ? 'js-edit-content-locale' : '' }}">
                    @foreach($userLanguages as $lang => $language)
                        <option value="{{ $lang }}" @if(mb_strtolower(request()->get('locale', app()->getLocale())) == mb_strtolower($lang)) selected @endif>{{ $language }}</option>
                    @endforeach
                </select>
                @error('locale')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>
        @else
            <input type="hidden" name="locale" value="{{ getDefaultLocale() }}">
        @endif

        <div class="form-group mt-15 ">
            <label class="input-label d-block">{{ trans('update.event_type') }}</label>

            <select name="type" class="custom-select @error('type')  is-invalid @enderror">
                <option value="">{{ trans('update.select_a_type') }}</option>

                @foreach(\App\Enums\EventEnums::allTypes as $type)
                    <option value="{{ $type }}" {{ (!empty($event) and $event->type == $type) ? 'selected' : '' }}>{{ trans("update.{$type}") }}</option>
                @endforeach
            </select>

            @error('type')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
        </div>

        <div class="form-group mt-15">
            <label class="input-label">{{ trans('public.title') }}</label>
            <input type="text" name="title" value="{{ (!empty($event) and !empty($event->translate($locale))) ? $event->translate($locale)->title : old('title') }}" class="form-control @error('title')  is-invalid @enderror" placeholder=""/>
            @error('title')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
        </div>

        <div class="form-group mt-15">
            <label class="input-label">{{ trans('admin/main.url') }}</label>
            <input type="text" name="slug" value="{{ !empty($event) ? $event->slug : old('slug') }}" class="form-control @error('slug')  is-invalid @enderror" placeholder=""/>
            <div class="text-gray-500 text-small mt-1">{{ trans('update.event_url_hint') }}</div>
            @error('slug')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
        </div>

        <div class="form-group mt-15 ">
            <label class="input-label d-block">{{ trans('update.select_a_creator') }}</label>

            <select name="creator_id" data-search-option="except_user" class="form-control search-user-select22" data-placeholder="{{ trans('update.select_a_creator') }}">
                @if(!empty($event))
                    <option value="{{ $event->creator->id }}" selected>{{ $event->creator->full_name }}</option>
                @endif
            </select>

            @error('creator_id')
            <div class="invalid-feedback d-block">{{ $message }}</div>@enderror
        </div>

        <div class="form-group mt-15">
            <label class="input-label">{{ trans('update.subtitle') }}</label>
            <input type="text" name="subtitle" value="{{ (!empty($event) and !empty($event->translate($locale))) ? $event->translate($locale)->subtitle : old('subtitle') }}" class="form-control @error('subtitle')  is-invalid @enderror" placeholder=""/>
            @error('subtitle')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
        </div>

        <div class="form-group mt-15">
            <label class="input-label">{{ trans('public.thumbnail_image') }}</label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <button type="button" class="input-group-text admin-file-manager" data-input="thumbnail" data-preview="holder">
                        <i class="fa fa-upload"></i>
                    </button>
                </div>
                <input type="text" name="thumbnail" id="thumbnail" value="{{ !empty($event) ? $event->thumbnail : old('thumbnail') }}" class="form-control @error('thumbnail')  is-invalid @enderror"/>
                <div class="input-group-append">
                    <button type="button" class="input-group-text admin-file-view" data-input="thumbnail">
                        <i class="fa fa-eye"></i>
                    </button>
                </div>
                @error('thumbnail')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>
        </div>


        <div class="form-group mt-15">
            <label class="input-label">{{ trans('public.cover_image') }}</label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <button type="button" class="input-group-text admin-file-manager" data-input="cover_image" data-preview="holder">
                        <i class="fa fa-upload"></i>
                    </button>
                </div>
                <input type="text" name="cover_image" id="cover_image" value="{{ !empty($event) ? $event->cover_image : old('cover_image') }}" class="form-control @error('cover_image')  is-invalid @enderror"/>
                <div class="input-group-append">
                    <button type="button" class="input-group-text admin-file-view" data-input="cover_image">
                        <i class="fa fa-eye"></i>
                    </button>
                </div>
                @error('cover_image')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>
        </div>

        <div class="form-group mt-15">
            <label class="input-label">{{ trans('update.icon') }} ({{ trans('public.optional') }})</label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <button type="button" class="input-group-text admin-file-manager" data-input="icon" data-preview="holder">
                        <i class="fa fa-upload"></i>
                    </button>
                </div>
                <input type="text" name="icon" id="icon" value="{{ !empty($event) ? $event->icon : old('icon') }}" class="form-control @error('icon')  is-invalid @enderror"/>
                <div class="input-group-append">
                    <button type="button" class="input-group-text admin-file-view" data-input="icon">
                        <i class="fa fa-eye"></i>
                    </button>
                </div>
                @error('icon')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>
        </div>

        <div class="form-group mt-25">
            <label class="input-label">{{ trans('public.demo_video') }} ({{ trans('public.optional') }})</label>


            <div class="">
                <label class="input-label font-12">{{ trans('public.source') }}</label>
                <select name="video_demo_source" class="js-video-demo-source form-control">
                    @foreach(getAvailableUploadFileSources() as $source)
                        <option value="{{ $source }}" @if(!empty($event) and $event->video_demo_source == $source) selected @endif>{{ trans('update.file_source_'.$source) }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="js-video-demo-other-inputs form-group mt-0 {{ (empty($event) or !in_array($event->video_demo_source, ['secure_host', 's3'])) ? '' : 'd-none' }}">
            <label class="input-label font-12">{{ trans('update.path') }}</label>
            <div class="input-group js-video-demo-path-input">
                <div class="input-group-prepend">
                    <button type="button" class="js-video-demo-path-upload input-group-text admin-file-manager {{ (empty($event) or empty($event->video_demo_source) or $event->video_demo_source == 'upload') ? '' : 'd-none' }}" data-input="demo_video" data-preview="holder">
                        <i class="fa fa-upload"></i>
                    </button>

                    <button type="button" class="js-video-demo-path-links rounded-left input-group-text input-group-text-rounded-left  {{ (empty($event) or empty($event->video_demo_source) or $event->video_demo_source == 'upload') ? 'd-none' : '' }}">
                        <i class="fa fa-link"></i>
                    </button>
                </div>
                <input type="text" name="video_demo" id="demo_video" value="{{ !empty($event) ? $event->video_demo : old('video_demo') }}" class="form-control @error('video_demo')  is-invalid @enderror"/>
                @error('video_demo')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>
        </div>

        <div class="form-group js-video-demo-file-input {{ (!empty($event) and in_array($event->video_demo_source, ['secure_host', 's3'])) ? '' : 'd-none' }}">
            <div class="input-group">
                <div class="input-group-prepend">
                    <button type="button" class="input-group-text">
                        <i class="fa fa-upload"></i>
                    </button>
                </div>
                <div class="custom-file js-ajax-s3_file">
                    <input type="file" name="video_demo_file" class="custom-file-input cursor-pointer" id="video_demo_file" accept="video/*">
                    <label class="custom-file-label cursor-pointer" for="video_demo_file">{{ trans('update.choose_file') }}</label>
                </div>

                <div class="invalid-feedback"></div>
            </div>
        </div>

        <div class="form-group mt-15">
            <label class="input-label">{{ trans('public.seo_description') }}</label>
            <input type="text" name="seo_description" value="{{ (!empty($event) and !empty($event->translate($locale))) ? $event->translate($locale)->seo_description : old('seo_description') }}" class="form-control @error('seo_description')  is-invalid @enderror"/>
            <div class="text-gray-500 text-small mt-1">{{ trans('admin/main.seo_description_hint') }}</div>
            @error('seo_description')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
        </div>

        <div class="form-group mt-15">
            <label class="input-label">{{ trans('public.summary') }}</label>
            <textarea name="summary" rows="5" class="form-control @error('summary')  is-invalid @enderror" placeholder="{{ trans('update.event_summary_placeholder') }}">{!! (!empty($event) and !empty($event->translate($locale))) ? $event->translate($locale)->summary : old('summary')  !!}</textarea>
            @error('summary')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
        </div>

    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="form-group mt-15">
            <label class="input-label">{{ trans('public.description') }}</label>
            <textarea id="summernote" name="description" class="form-control @error('description')  is-invalid @enderror" placeholder="{{ trans('forms.webinar_description_placeholder') }}">{!! (!empty($event) and !empty($event->translate($locale))) ? $event->translate($locale)->description : old('description')  !!}</textarea>
            @error('description')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
        </div>
    </div>
</div>
