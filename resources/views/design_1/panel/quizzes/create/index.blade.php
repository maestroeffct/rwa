@extends('design_1.panel.layouts.panel')

@push('styles_top')
    <link rel="stylesheet" href="/assets/default/vendors/sortable/jquery-ui.min.css"/>
@endpush

@section('content')
    <div class="bg-white p-16 rounded-16 mb-56">

        <div class="js-quiz-main-page-form row">
            {{-- Form --}}
            <div class="col-12 col-lg-6">
                <h3 class="font-16 font-weight-bold">{{ trans('public.basic_information') }}</h3>

                @include('design_1.panel.quizzes.create.quiz_form', ['isQuizPage' => true])
            </div>

            <div class="col-12 col-lg-6">
                @include('design_1.panel.quizzes.create.questions_list')
            </div>

            <div class="d-flex flex-column flex-lg-row align-items-lg-center justify-content-lg-between border-top-gray-100 col-12 pt-16 mt-16">
                <div class="d-flex align-items-center">
                    <div class="d-flex-center size-48 rounded-12 bg-gray-200">
                        <x-iconsax-bol-info-circle class="icons text-gray-400" width="24px" height="24px"/>
                    </div>
                    <div class="ml-8">
                        <h5 class="font-14">{{ trans('update.notice') }}</h5>
                        <p class="mt-2 font-12 text-gray-500">{{ trans('update.the_Support_message_sending_hint') }}</p>
                    </div>
                </div>

                <button type="button" class="js-submit-quiz-form-main-page btn btn-primary mt-20 mt-lg-0">{{ trans('public.save') }}</button>
            </div>

        </div>

    </div>
@endsection

@push('scripts_bottom')
    <script>
        var saveSuccessLang = '{{ trans('webinars.success_store') }}';
        var quizzesSectionLang = '{{ trans('quiz.quizzes_section') }}';
        var newQuestionLang = '{{ trans('update.new_question') }}';
        var editQuestionLang = '{{ trans('update.edit_question') }}';
        var saveLang = '{{ trans('public.save') }}';
        var closeLang = '{{ trans('public.close') }}';
    </script>

    <script src="/assets/default/vendors/sortable/jquery-ui.min.js"></script>

    <script src="/assets/design_1/js/panel/quiz_create.min.js"></script>
@endpush
