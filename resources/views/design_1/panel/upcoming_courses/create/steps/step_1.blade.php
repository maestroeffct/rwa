@push('styles_top')
    <link rel="stylesheet" href="/assets/vendors/summernote/summernote-bs4.min.css">
@endpush

<div class="bg-white rounded-16 p-16 mt-32">

    {{-- course_type --}}
    <div class="form-group mb-0">
        <h3 class="font-14 font-weight-bold position-relative d-inline-flex is-required">{{ trans('panel.course_type') }}</h3>
    </div>

    <div class="d-grid grid-columns-auto grid-lg-columns-3 gap-24 mt-16">
        @php
            $coursetypes = [
                'webinar' => 'video',
                'course' => 'video-circle',
                'text_lesson' => 'book',
            ];
        @endphp

        @foreach($coursetypes as $coursetype => $coursetypeIcon)
            <div class="create-webinar-course-types custom-input-button position-relative">
                <input type="radio" class="" name="type" id="course_type_{{ $coursetype }}" value="{{ $coursetype }}" {{ (!empty($upcomingCourse) and $upcomingCourse->type == $coursetype) ? 'checked' : '' }}>
                <label for="course_type_{{ $coursetype }}" class="position-relative d-flex-center flex-column p-16 p-lg-32 rounded-16 border-gray-200 text-center bg-white">
                    <div class="d-flex-center size-64 bg-gray-100 rounded-16">
                        @svg("iconsax-bul-{$coursetypeIcon}", ['height' => 32, 'width' => 32, 'class' => 'text-gray-500'])
                    </div>

                    <div class="mt-12 font-14 font-weight-bold">{{ trans("webinars.{$coursetype}") }}</div>
                    <p class="mt-4 font-12 text-gray-500">{{ trans("update.create_{$coursetype}_hint") }}</p>
                </label>
            </div>
        @endforeach

        @error('type')
        <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>


    <h3 class="font-14 font-weight-bold my-24">{{ trans('public.basic_information') }}</h3>

    @include('design_1.panel.includes.locale.locale_select',[
        'itemRow' => !empty($upcomingCourse) ? $upcomingCourse : null,
        'withoutReloadLocale' => false,
        'extraClass' => ''
    ])

    @if($isOrganization)
        <div class="form-group ">
            <label class="form-group-label">{{ trans('public.select_a_teacher') }}</label>

            <select name="teacher_id" class="select2 @error('teacher_id')  is-invalid @enderror">
                <option value="" {{ (!empty($upcomingCourse) and !empty($upcomingCourse->teacher_id)) ? '' : 'selected' }}>{{ trans('public.choose_instructor') }}</option>

                @foreach($teachers as $teacher)
                    <option value="{{ $teacher->id }}" {{ (!empty($upcomingCourse) && $upcomingCourse->teacher_id == $teacher->id) ? 'selected' : '' }}>{{ $teacher->full_name }}</option>
                @endforeach
            </select>

            @error('teacher_id')
            <div class="invalid-feedback d-block">
                {{ $message }}
            </div>
            @enderror
        </div>
    @endif

    <div class="form-group">
        <label class="form-group-label is-required">{{ trans('public.title') }}</label>
        <span class="has-translation bg-gray-300 rounded-8 p-8"><x-iconsax-lin-translate class="icons text-gray-500"/></span>
        <input type="text" name="title" class="form-control @error('title')  is-invalid @enderror" value="{{ (!empty($upcomingCourse) and !empty($upcomingCourse->translate($locale))) ? $upcomingCourse->translate($locale)->title : old('title') }}" placeholder=""/>
        @error('title')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>

    <div class="form-group mt-15">
        <label class="form-group-label">{{ trans('public.seo_description') }}</label>
        <span class="has-translation bg-gray-300 rounded-8 p-8"><x-iconsax-lin-translate class="icons text-gray-500"/></span>
        <input type="text" name="seo_description" class="form-control @error('seo_description')  is-invalid @enderror " value="{{ (!empty($upcomingCourse) and !empty($upcomingCourse->translate($locale))) ? $upcomingCourse->translate($locale)->seo_description : old('seo_description') }}" placeholder="{{ trans('forms.50_160_characters_preferred') }}"/>
        @error('seo_description')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>


    <div class="form-group mb-0">
        <h3 class="font-14 font-weight-bold position-relative d-inline-flex is-required">{{ trans('update.thumbnail_&_cover') }}</h3>
    </div>

    <div class="row">

        @include('design_1.panel.upcoming_courses.create.includes.media',[
            'media' => !empty($upcomingCourse) ? $upcomingCourse->thumbnail : null,
            'mediaName' => 'thumbnail',
            'mediaTitle' => trans('update.thumbnail'),
        ])

        @include('design_1.panel.upcoming_courses.create.includes.media',[
            'media' => !empty($upcomingCourse) ? $upcomingCourse->image_cover : null,
            'mediaName' => 'image_cover',
            'mediaTitle' => trans('public.cover_image'),
        ])


        <div class="col-12 mt-8">
            @error('thumbnail')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror

            @error('image_cover')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
        </div>
    </div>

    {{-- Video --}}
    <h3 class="font-14 font-weight-bold my-24">{{ trans('public.demo_video') }} ({{ trans('public.optional') }})</h3>

    <div class="js-inputs-with-source row">
        @php
            $selectedVideoSource = (!empty($upcomingCourse) and !empty($upcomingCourse->video_demo_source)) ? $upcomingCourse->video_demo_source : null;
        @endphp

        <div class="col-12 col-md-6">
            <div class="form-group">
                <label class="form-group-label">{{ trans('update.video_source') }}</label>
                <select name="video_demo_source" class="js-upload-source-input form-control @error('video_demo_source') is-invalid @enderror">
                    @foreach(getAvailableUploadFileSources() as $source)
                        @php
                            if($loop->first and empty($selectedVideoSource)) {
                                $selectedVideoSource = $source;
                            }
                        @endphp

                        <option value="{{ $source }}" {{ (!empty($upcomingCourse) and $upcomingCourse->video_demo_source == $source) ? 'selected' : '' }}>{{ trans('update.file_source_'.$source) }}</option>
                    @endforeach
                </select>

                @error('video_demo_source')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="col-12 col-md-6">
            <div class="form-group js-online-upload {{ (!in_array($selectedVideoSource, \App\Enums\UploadSource::uploadItems)) ? '' : 'd-none' }}">
                <span class="has-translation bg-transparent">
                    <x-iconsax-lin-link-21 class="icons text-gray-400" width="24px" height="24px"/>
                </span>
                <label class="form-group-label">{{ trans('update.path') }}</label>
                <input type="text" name="demo_video_path" class="form-control" value="{{ !empty($upcomingCourse) ? $upcomingCourse->video_demo : old('demo_video_path') }}" placeholder="{{ trans('update.insert_demo_video_link') }}">
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

    {{-- Course Summary --}}
    <h3 class="font-14 font-weight-bold my-24">{{ trans('update.course_summary') }}</h3>

    <div class="form-group bg-white-editor">
        <label class="form-group-label">{{ trans('public.summary') }}</label>
        <textarea name="summary" rows="5" class="form-control @error('summary')  is-invalid @enderror" placeholder="{{ trans('update.course_summary_placeholder') }}">{!! (!empty($upcomingCourse) and !empty($upcomingCourse->translate($locale))) ? $upcomingCourse->translate($locale)->summary : old('summary')  !!}</textarea>
        @error('summary')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>

    {{-- Course Description --}}
    <h3 class="font-14 font-weight-bold my-24">{{ trans('update.course_description') }}</h3>

    <div class="form-group bg-white-editor">
        <label class="form-group-label is-required">{{ trans('public.description') }}</label>
        <textarea name="description" class="main-summernote form-control @error('description')  is-invalid @enderror" data-height="400" placeholder="{{ trans('forms.webinar_description_placeholder') }}">{!! (!empty($upcomingCourse) and !empty($upcomingCourse->translate($locale))) ? $upcomingCourse->translate($locale)->description : old('description')  !!}</textarea>
        @error('description')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>


</div>

@push('scripts_bottom')
    <script src="/assets/vendors/summernote/summernote-bs4.min.js"></script>
@endpush
