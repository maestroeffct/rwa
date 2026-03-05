@extends("design_1.web.layouts.app")

@push("styles_top")
    <link rel="stylesheet" href="/assets/vendors/wrunner-html-range-slider-with-2-handles/css/wrunner-default-theme.css">
    <link rel="stylesheet" href="/assets/default/vendors/swiper/swiper-bundle.min.css">
    <link rel="stylesheet" href="{{ getDesign1StylePath("course_text_lesson_page") }}">
@endpush

@section("content")
    <main class="pb-120">
        <section class="text-lesson-hero position-relative">
            <div class="text-lesson-hero__mask"></div>
            <img src="{{ $course->image_cover }}" class="img-cover" alt="{{ $course->title }}"/>
        </section>


        <div class="container">
            {{-- Header --}}
            <div class="text-lesson-header position-relative">
                <div class="text-lesson-header__mask"></div>
                <div class="position-relative d-flex flex-column align-items-start bg-white rounded-32 z-index-2 p-16 p-lg-32">
                    <div class="d-flex-center size-64 rounded-12 bg-primary-30">
                        <x-iconsax-bul-note-2 class="icons text-primary" width="32px" height="32px"/>
                    </div>

                    <div class="d-flex align-items-center mt-16 text-gray-500">
                        <a href="{{ $course->getUrl() }}" class="text-gray-500">{{ $course->title }}</a>
                        <x-iconsax-lin-arrow-right-1 class="mx-4" width="16px" height="16px"/>
                        <span class="">{{ trans('update.text_lesson') }}</span>
                    </div>

                    <div class="d-flex flex-column flex-lg-row align-items-lg-end justify-content-lg-between gap-12 gap-lg-40 mt-12 w-100">
                        <div class="flex-1">
                            <h1 class="font-24 font-weight-bold">{{ $textLesson->title }}</h1>
                            <div class="font-12 text-gray-500 mt-8">{{ $textLesson->summary }}</div>
                        </div>

                        @if(!empty($textLesson->study_time))
                            <div class="d-flex align-items-center gap-8 ">
                                <div class="d-flex-center size-40 bg-gray-100 rounded-circle">
                                    <x-iconsax-lin-clock-1 class="icons text-gray-500" width="20px" height="20px"/>
                                </div>
                                <div class="">
                                    <div class="font-12 text-gray-400">{{ trans('public.study_time') }}</div>
                                    <div class="font-14 font-weight-bold text-gray-500">{{ $textLesson->study_time }} {{ trans('public.min') }}</div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Content --}}
            <div class="row">
                <div class="col-12 col-lg-9 mt-28">
                    <div class="text-lesson-contents bg-white p-16 rounded-32">
                        @if(!empty($textLesson->image))
                            <div class="">
                                <img src="{{ url($textLesson->image) }}" alt="{{ $textLesson->title }}"/>
                            </div>
                        @endif

                        {!! nl2br($textLesson->content) !!}
                    </div>
                </div>

                {{-- Right Side --}}
                <div class="col-12 col-lg-3 mt-28">
                    {{-- Attachments --}}
                    @if(!empty($textLesson->attachments) and count($textLesson->attachments))
                        <div class="card-with-mask position-relative mb-28">
                            <div class="mask-8-white"></div>
                            <div class="position-relative bg-white py-16 rounded-16 ">
                                <div class="card-before-line px-16 ">
                                    <h4 class="font-14 text-dark">{{ trans('update.attachments') }}</h4>
                                </div>

                                <div class="px-16 pb-8">
                                    @foreach($textLesson->attachments as $attachment)
                                        @php
                                            $file = $attachment->file;
                                            $url = $url = "{$course->getLearningPageUrl()}?type=file&item={$file->id}";

                                            if($file->accessibility == 'paid') {
                                                if (!empty($authUser) and $userHasBought) {
                                                    if($file->downloadable) {
                                                        $url = "{$course->getUrl()}/file/{$file->id}/download";
                                                    }
                                                }
                                            } else {
                                                if($file->downloadable) {
                                                    $url = "{$course->getUrl()}/file/{$file->id}/download";
                                                }
                                            }
                                        @endphp

                                        <a href="{{ $url }}" class="d-block card-with-mask position-relative {{ $loop->first ? 'mt-16' : 'mt-20' }}" target="_blank">
                                            <div class="mask-8-white border-dashed border-gray-200"></div>
                                            <div class="position-relative d-flex align-items-center bg-white rounded-12 border-gray-200 p-16">
                                                <div class="d-flex-center size-48 bg-gray-100 rounded-circle">
                                                    <div class="d-flex-center size-32 bg-gray-300 rounded-circle">
                                                        <x-iconsax-bul-document-download class="icons text-primary" width="16px" height="16px"/>
                                                    </div>
                                                </div>
                                                <div class="ml-8">
                                                    <div class="font-weight-bold text-dark">{{ $attachment->file->title }}</div>
                                                    <div class="font-12 text-gray-500 mt-2">{{ $attachment->file->file_type }}</div>
                                                </div>
                                            </div>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- Text Lessons --}}
                    @if(!empty($course->textLessons) and count($course->textLessons))
                        <div class="card-with-mask position-relative mb-28">
                            <div class="mask-8-white"></div>
                            <div class="position-relative bg-white py-16 rounded-16 ">
                                <div class="card-before-line px-16 ">
                                    <h4 class="font-14 text-dark">{{ trans('update.text_lessons') }}</h4>
                                </div>

                                <div class="px-16 pb-8">
                                    @foreach($course->textLessons as $lesson)
                                        <a href="{{ $course->getUrl() }}/lessons/{{ $lesson->id }}/read" class="d-block text-lesson-item card-with-mask position-relative {{ ($lesson->id == $textLesson->id) ? 'is-current' : '' }} {{ $loop->first ? 'mt-16' : 'mt-20' }}" target="_blank">
                                            <div class="mask-8-white"></div>
                                            <div class="position-relative text-lesson-item__card rounded-12 p-16">
                                                <div class="text-lesson-item__card-number">#{{ $loop->iteration }}</div>

                                                <h3 class="font-14 mt-4">{{ $lesson->title }}</h3>
                                            </div>
                                        </a>
                                    @endforeach


                                    <a href="{{ $course->getLearningPageUrl() }}" class="d-block text-lesson-item card-with-mask position-relative mt-20" target="_blank">
                                        <div class="position-relative text-lesson-item__card view-full-lessons d-flex align-items-center justify-content-between rounded-12 p-16 pr-24">
                                            <div class="">
                                                <span class="d-block font-weight-bold">{{ trans('update.view_full_lessons') }}</span>
                                                <span class="d-block mt-4 font-12">{{ trans('update.check_course_learning_page') }}</span>
                                            </div>

                                            <x-iconsax-lin-arrow-right class="icons " width="16px" height="16px"/>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endif

                </div>
            </div> {{-- End Row --}}
        </div>{{-- End Container --}}
    </main>


    <div class="text-lesson-bottom-fixed-card bg-white">
        <div class="container d-flex flex-column flex-lg-row align-items-lg-center justify-content-lg-between h-100">
            <div class="d-flex align-items-center mb-16 mb-lg-0">
                <div class="bg-gray-100 border-gray-200 rounded-8 py-8 px-12 text-center">
                    <span class="d-block font-12 text-gray-400">{{ trans('update.text_lesson') }}</span>
                    <span class="d-block font-12 text-gray-500 font-weight-bold">{{ $textLesson->order }}/{{ count($course->textLessons) }}</span>
                </div>
                <div class="ml-8">
                    <div class="font-12 text-gray-500">{{ trans('update.you_are_viewing') }}</div>
                    <div class="mt-4 font-14 font-weight-bold">{{ $textLesson->title }}</div>
                </div>
            </div>

            @if(!empty($course->textLessons) and count($course->textLessons))
                <div class="d-flex align-items-center gap-16">


                    <a href="{{ !empty($previousLesson) ? ($course->getUrl() .'/lessons/'. $previousLesson->id .'/read') : '#!' }}" class="btn btn-lg {{ (!empty($previousLesson)) ? 'btn-primary text-white' : 'bg-gray-300 text-gray-500 disabled' }}">
                        <x-iconsax-lin-arrow-left class="icons" width="16px" height="16px"/>
                        <span class="ml-4">{{ trans('public.previous_lesson') }}</span>
                    </a>


                    <a href="{{ !empty($nextLesson) ? ($course->getUrl() .'/lessons/'. $nextLesson->id .'/read') : '#' }}" class="btn btn-lg {{ (!empty($nextLesson)) ? 'btn-primary text-white' : 'bg-gray-300 text-gray-500 disabled' }}">
                        <x-iconsax-lin-arrow-right class="icons" width="16px" height="16px"/>
                        <span class="ml-4">{{ trans('public.next_lesson') }}</span>
                    </a>
                </div>
            @endif
        </div>
    </div>

    <div class="text-lesson-bottom-fixed-card__progress">
        <div class="progress-line"></div>
    </div>

@endsection

@push('scripts_bottom')

    <script src="{{ getDesign1ScriptPath("course_text_lesson_page") }}"></script>
@endpush
