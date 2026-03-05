@if(!empty($courseStudents['students']) and count($courseStudents['students']))
    <div class="bg-white p-16 rounded-16 mt-24">
        <div class="">
            <h3 class="font-16 text-dark">{{ trans('update.course_students') }}</h3>
            <p class="mt-4 text-gray-500">{{ trans('update.view_and_manage_course_students') }}</p>
        </div>

        <div class="mt-16 pt-16 border-top-gray-100">
            {{-- Filters --}}
            @include('design_1.panel.webinars.course_statistics.students.filters')
        </div>

        {{-- List Table --}}
        <div id="tableListContainer" class="table-responsive-lg mt-24" data-view-data-path="/panel/courses/{{ $course->id }}/statistics">
            <table class="table panel-table">
                <thead>
                <tr>
                    <th class="text-left">{{ trans('quiz.student') }}</th>
                    <th class="text-left">{{ trans('update.progress') }}</th>
                    <th>{{ trans('update.learning_activity') }}</th>
                    <th>{{ trans('update.passed_quizzes') }}</th>
                    <th>{{ trans('update.passed_assignments') }}</th>
                    <th>{{ trans('panel.certificates') }}</th>
                    <th>{{ trans('update.enrollment_date') }}</th>
                </tr>
                </thead>
                <tbody class="js-body-lists">
                @foreach($courseStudents['students'] as $studentRow)
                    @include('design_1.panel.webinars.course_statistics.students.item_table', ['student' => $studentRow])
                @endforeach
                </tbody>
            </table>

            {{-- Pagination --}}
            <div id="pagination" class="js-ajax-pagination" data-container-id="tableListContainer" data-container-items=".js-body-lists" data-noscroll="true">
                {!! $courseStudents['pagination'] !!}
            </div>
        </div>
    </div>
@else
    @include('design_1.panel.includes.no-result',[
        'file_name' => 'course_statistics.svg',
        'title' => trans('update.course_statistic_students_no_result'),
        'hint' =>  nl2br(trans('update.course_statistic_students_no_result_hint')),
    ])
@endif
