@push('styles_top')
    <link rel="stylesheet" href="/assets/vendors/summernote/summernote-bs4.min.css">
@endpush

<div class="bg-white rounded-16 p-16 mt-32">

    {{-- event_type --}}
    <div class="form-group mb-0">
        <h3 class="font-14 font-weight-bold position-relative d-inline-flex is-required">{{ trans('panel.event_type') }}</h3>
    </div>

    <div class="d-grid grid-columns-auto grid-lg-columns-2 gap-24 mt-16">
        @php
            $eventTypes = [
                'in_person' => 'location',
                'online' => 'monitor',
            ];
        @endphp
        @foreach($eventTypes as $eventType => $eventTypeIcon)
            <div class="create-webinar-course-types custom-input-button position-relative">
                <input type="radio" class="" name="type" id="event_type_{{ $eventType }}" value="{{ $eventType }}" {{ (!empty($event) and $event->type == $eventType) ? 'checked' : '' }}>
                <label for="event_type_{{ $eventType }}" class="position-relative d-flex-center flex-column p-16 p-lg-32 rounded-16 border-gray-200 text-center bg-white">
                    <div class="create-webinar-course-types__icon-box d-flex-center size-64 rounded-16">
                        @svg("iconsax-bul-{$eventTypeIcon}", ['height' => 32, 'width' => 32, 'class' => ''])
                    </div>

                    <div class="mt-12 font-14 font-weight-bold">{{ trans("update.{$eventType}") }}</div>
                    <p class="mt-4 font-12 text-gray-500">{{ trans("update.create_{$eventType}_event_hint") }}</p>
                </label>
            </div>
        @endforeach

        @error('type')
        <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>

    <h3 class="font-14 font-weight-bold mt-24 mb-16">{{ trans('public.basic_information') }}</h3>


    @include('design_1.panel.includes.locale.locale_select',[
        'itemRow' => !empty($event) ? $event : null,
        'withoutReloadLocale' => false,
        'extraClass' => ''
    ])

    <div class="form-group">
        <label class="form-group-label is-required bg-white">{{ trans('public.title') }}</label>
        <span class="has-translation bg-gray-300 rounded-8 p-8"><x-iconsax-lin-translate class="icons text-gray-500"/></span>
        <input type="text" name="title" class="form-control @error('title')  is-invalid @enderror" value="{{ (!empty($event) and !empty($event->translate($locale))) ? $event->translate($locale)->title : old('title') }}" placeholder=""/>
        @error('title')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>

    <div class="form-group">
        <label class="form-group-label is-required bg-white">{{ trans('update.subtitle') }}</label>
        <span class="has-translation bg-gray-300 rounded-8 p-8"><x-iconsax-lin-translate class="icons text-gray-500"/></span>
        <input type="text" name="subtitle" class="form-control @error('subtitle')  is-invalid @enderror" value="{{ (!empty($event) and !empty($event->translate($locale))) ? $event->translate($locale)->subtitle : old('subtitle') }}" placeholder=""/>
        @error('subtitle')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>

    <div class="form-group mt-15">
        <label class="form-group-label">{{ trans('public.seo_description') }}</label>
        <span class="has-translation bg-gray-300 rounded-8 p-8"><x-iconsax-lin-translate class="icons text-gray-500"/></span>
        <input type="text" name="seo_description" class="form-control @error('seo_description')  is-invalid @enderror " value="{{ (!empty($event) and !empty($event->translate($locale))) ? $event->translate($locale)->seo_description : old('seo_description') }}" placeholder="{{ trans('forms.50_160_characters_preferred') }}"/>
        @error('seo_description')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>

    <div class="form-group mb-0 mt-24">
        <h3 class="font-14 font-weight-bold position-relative d-inline-flex is-required">{{ trans('update.thumbnail_&_cover') }}</h3>
    </div>

    <div class="row">

        @include('design_1.panel.webinars.create.includes.media',[
            'media' => !empty($event) ? $event->thumbnail : null,
            'mediaName' => 'thumbnail',
            'mediaTitle' => trans('update.thumbnail'),
        ])

        @include('design_1.panel.webinars.create.includes.media',[
            'media' => !empty($event) ? $event->cover_image : null,
            'mediaName' => 'cover_image',
            'mediaTitle' => trans('public.cover_image'),
        ])


        <div class="col-12 mt-8">
            @error('thumbnail')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror

            @error('cover_image')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
        </div>
    </div>

    {{-- Event Icon --}}
    <h3 class="font-14 font-weight-bold mt-24 mb-16">{{ trans('update.event_icon') }} ({{ trans('public.optional') }})</h3>

    <div class="row">
        <div class="col-12 col-lg-6">
            <div class="form-group">
                <label class="form-group-label">{{ trans('update.icon') }}</label>

                <div class="custom-file bg-white">
                    <input type="file" name="icon" class="js-ajax-upload-file-input js-ajax-icon custom-file-input" data-upload-name="icon" id="iconInput" accept="image/*">
                    <span class="custom-file-text">{{ (!empty($event) and !empty($event->icon)) ? getFileNameByPath($event->icon) : '' }}</span>
                    <label class="custom-file-label" for="iconInput">{{ trans('update.browse') }}</label>
                </div>

                @error('icon')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror

                @if(!empty($event) and !empty($event->icon))
                    <a href="{{ url($event->icon) }}" target="_blank" class="text-danger mt-4 font-12">{{ trans('update.preview') }}</a>
                @endif
            </div>
        </div>
    </div>


    {{-- Video --}}
    <h3 class="font-14 font-weight-bold mt-24 mb-16">{{ trans('public.demo_video') }} ({{ trans('public.optional') }})</h3>

    <div class="js-inputs-with-source row">

        @php
            $selectedVideoSource = (!empty($event) and !empty($event->video_demo_source)) ? $event->video_demo_source : null;
        @endphp

        <div class="col-12 col-lg-6">
            <div class="form-group">
                <label class="form-group-label">{{ trans('update.video_source') }}</label>
                <select name="video_demo_source" class="js-upload-source-input form-control @error('video_demo_source') is-invalid @enderror select2" data-minimum-results-for-search="Infinity">
                    @foreach(getAvailableUploadFileSources() as $source)
                        @php
                            if($loop->first and empty($selectedVideoSource)) {
                                $selectedVideoSource = $source;
                            }
                        @endphp

                        <option value="{{ $source }}" {{ (!empty($event) and $event->video_demo_source == $source) ? 'selected' : '' }}>{{ trans('update.file_source_'.$source) }}</option>
                    @endforeach
                </select>

                @error('video_demo_source')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="col-12 col-lg-6">
            <div class="form-group js-online-upload {{ (!in_array($selectedVideoSource, \App\Enums\UploadSource::uploadItems)) ? '' : 'd-none' }}">
                <span class="has-translation bg-transparent">
                    <x-iconsax-lin-link-21 class="icons text-gray-400" width="24px" height="24px"/>
                </span>
                <label class="form-group-label">{{ trans('update.path') }}</label>
                <input type="text" name="demo_video_path" class="form-control" value="{{ !empty($event) ? $event->video_demo : old('demo_video_path') }}" placeholder="{{ trans('update.insert_demo_video_link') }}">
            </div>

            <div class="form-group js-local-upload {{ (in_array($selectedVideoSource, \App\Enums\UploadSource::uploadItems)) ? '' : 'd-none' }}">
                <span class="has-translation bg-transparent">
                    <x-iconsax-lin-export class="icons text-gray-400" width="24px" height="24px"/>
                </span>

                <label class="form-group-label">{{ trans('update.upload_video') }}</label>
                <div class="custom-file bg-white">
                    <input type="file" name="demo_video_local" class="custom-file-input" id="demo_video_local" accept="video/*">
                    <span class="custom-file-text text-dark">{{ trans('update.select_a_video') }}</span>
                    <label class="custom-file-label bg-gray-100" for="demo_video_local">{{ trans('update.browse') }}</label>
                </div>
            </div>
        </div>

    </div>

    {{-- Event Summary --}}
    <h3 class="font-14 font-weight-bold mt-24 mb-16">{{ trans('update.event_summary') }}</h3>

    <div class="form-group bg-white-editor">
        <label class="form-group-label is-required">{{ trans('public.summary') }}</label>
        <textarea name="summary" rows="5" class="form-control @error('summary')  is-invalid @enderror" placeholder="{{ trans('update.event_summary_placeholder') }}">{!! (!empty($event) and !empty($event->translate($locale))) ? $event->translate($locale)->summary : old('summary')  !!}</textarea>
        @error('summary')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>

    {{-- Event Description --}}
    <h3 class="font-14 font-weight-bold mt-24 mb-16">{{ trans('update.event_description') }}</h3>

    <div class="form-group bg-white-editor">
        <label class="form-group-label is-required">{{ trans('public.description') }}</label>
        <textarea name="description" class="main-summernote form-control @error('description')  is-invalid @enderror" data-height="400" placeholder="{{ trans('forms.webinar_description_placeholder') }}">{!! (!empty($event) and !empty($event->translate($locale))) ? $event->translate($locale)->description : old('description')  !!}</textarea>
        @error('description')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>

    @if($isOrganization)
        <div class="row mt-20">
            <div class="col-12 col-lg-6">
                <div class="form-group d-flex align-items-center">
                    <div class="custom-switch mr-8">
                        <input id="privateSwitch" type="checkbox" name="private" class="custom-control-input" {{ (!empty($event) and $event->private) ? 'checked' :  '' }}>
                        <label class="custom-control-label cursor-pointer" for="privateSwitch"></label>
                    </div>

                    <div class="">
                        <label class="cursor-pointer" for="privateSwitch">{{ trans('webinars.private') }}</label>
                    </div>
                </div>
                <p class="text-gray-500 font-12">{{ trans('webinars.create_private_event_hint') }}</p>
            </div>
        </div>
    @endif

</div>


@push('scripts_bottom')
    <script src="/assets/vendors/summernote/summernote-bs4.min.js"></script>
@endpush
