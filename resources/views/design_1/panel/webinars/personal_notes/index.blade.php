@extends('design_1.panel.layouts.panel')


@section('content')

    @if(!empty($personalNotes) and !$personalNotes->isEmpty())
        <div class="bg-white pt-16 rounded-24">
           <div class="d-flex align-items-center justify-content-between pb-16 px-16">
                <div class="">
                    <h3 class="font-16">{{ trans('update.course_notes') }}</h3>
                    <p class="font-14 text-gray-500 mt-4">{{ trans('update.view_course_notes_and_related_statistics') }}</p>
                </div>
            </div>

            {{-- List Table --}}
            <div id="tableListContainer" class="table-responsive-lg" data-view-data-path="/panel/courses/personal-notes">
                <table class="table panel-table">
                    <thead>
                    <tr>
                        <th class="text-left">{{ trans('product.course') }}</th>
                        <th class="text-left">{{ trans('public.file') }}</th>
                        <th class="text-center">{{ trans('update.note') }}</th>

                        @if(!empty(getFeaturesSettings('course_notes_attachment')))
                            <th class="text-center">{{ trans('update.attachment') }}</th>
                        @endif

                        <th class="text-center">{{ trans('public.date') }}</th>
                        <th class="text-right">{{ trans('update.controls') }}</th>
                    </tr>
                    </thead>
                    <tbody class="js-table-body-lists">
                    @foreach($personalNotes as $personalNoteRow)
                        @include('design_1.panel.webinars.personal_notes.table_items', ['personalNote' => $personalNoteRow])
                    @endforeach
                    </tbody>
                </table>

                {{-- Pagination --}}
                <div id="pagination" class="js-ajax-pagination" data-container-id="tableListContainer" data-container-items=".js-table-body-lists">
                    {!! $pagination !!}
                </div>
            </div>
        </div>
    @else
        @include('design_1.panel.includes.no-result',[
            'file_name' => 'personal_note.svg',
            'title' => trans('update.no_notes'),
            'hint' =>  nl2br(trans("update.you_haven't_submitted_notes_for_your_courses")),
            'extraClass' => 'mt-0',
        ])
    @endif

@endsection

@push('scripts_bottom')
    <script>
        var noteLang = '{{ trans('update.note') }}';
    </script>
    <script src="{{ getDesign1ScriptPath("get_view_data") }}"></script>

    <script src="/assets/design_1/js/panel/personal_note.min.js"></script>
@endpush
