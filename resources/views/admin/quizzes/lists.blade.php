@extends('admin.layouts.app')

@push('libraries_top')

@endpush

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{ trans('admin/main.quizzes') }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ getAdminPanelUrl() }}">{{trans('admin/main.dashboard')}}</a>
                </div>
                <div class="breadcrumb-item">{{ trans('admin/main.quizzes') }}</div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card-statistic">
                    <div class="card-statistic__mask"></div>
                    <div class="card-statistic__wrap">
                        <div class="d-flex align-items-start justify-content-between">
                            <span class="text-gray-500 mt-8">{{ trans('admin/main.total_quizzes') }}</span>
                            <div class="d-flex-center size-48 bg-primary-30 rounded-12">
                                <x-iconsax-bul-clipboard-text class="icons text-primary" width="24px" height="24px"/>
                            </div>
                        </div>
                        <h5 class="font-24 mt-12 line-height-1 text-black">{{ $totalQuizzes }}</h5>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card-statistic">
                    <div class="card-statistic__mask"></div>
                    <div class="card-statistic__wrap">
                        <div class="d-flex align-items-start justify-content-between">
                            <span class="text-gray-500 mt-8">{{ trans('admin/main.active_quizzes') }}</span>
                            <div class="d-flex-center size-48 bg-success-30 rounded-12">
                                <x-iconsax-bul-clipboard-tick class="icons text-success" width="24px" height="24px"/>
                            </div>
                        </div>
                        <h5 class="font-24 mt-12 line-height-1 text-black">{{ $totalActiveQuizzes }}</h5>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card-statistic">
                    <div class="card-statistic__mask"></div> 
                    <div class="card-statistic__wrap">
                        <div class="d-flex align-items-start justify-content-between">
                            <span class="text-gray-500 mt-8">{{ trans('admin/main.total_students') }}</span>
                            <div class="d-flex-center size-48 bg-accent-30 rounded-12">
                                <x-iconsax-bul-profile-2user class="icons text-accent" width="24px" height="24px"/>
                            </div>
                        </div>
                        <h5 class="font-24 mt-12 line-height-1 text-black">{{ $totalStudents }}</h5>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card-statistic">
                    <div class="card-statistic__mask"></div>
                    <div class="card-statistic__wrap">
                        <div class="d-flex align-items-start justify-content-between">
                            <span class="text-gray-500 mt-8">{{ trans('admin/main.total_passed_students') }}</span>
                            <div class="d-flex-center size-48 bg-success-30 rounded-12">
                                <x-iconsax-bul-user-tick class="icons text-whatsapp" width="24px" height="24px"/>
                            </div>
                        </div>
                        <h5 class="font-24 mt-12 line-height-1 text-black">{{ $totalPassedStudents }}</h5>
                    </div>
                </div>
            </div>
        </div>

        <div class="section-body">

            <section class="card mt-32">
                <div class="card-body pb-4">
                    <form action="{{ getAdminPanelUrl() }}/quizzes" method="get" class="row mb-0">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="input-label">{{ trans('admin/main.search') }}</label>
                                <input type="text" class="form-control" name="title" value="{{ request()->get('title') }}">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="input-label">{{ trans('admin/main.start_date') }}</label>
                                <div class="input-group">
                                    <input type="date" id="fsdate" class="text-center form-control" name="from" value="{{ request()->get('from') }}" placeholder="Start Date">
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="input-label">{{ trans('admin/main.end_date') }}</label>
                                <div class="input-group">
                                    <input type="date" id="lsdate" class="text-center form-control" name="to" value="{{ request()->get('to') }}" placeholder="End Date">
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="input-label">{{ trans('admin/main.filters') }}</label>
                                <select name="sort" data-plugin-selectTwo class="form-control populate">
                                    <option value="">{{ trans('admin/main.filter_type') }}</option>
                                    <option value="have_certificate" @if(request()->get('sort') == 'have_certificate') selected @endif>{{ trans('admin/main.quizzes_have_certificate') }}</option>
                                    <option value="students_count_asc" @if(request()->get('sort') == 'students_count_asc') selected @endif>{{ trans('admin/main.students_ascending') }}</option>
                                    <option value="students_count_desc" @if(request()->get('sort') == 'students_count_desc') selected @endif>{{ trans('admin/main.students_descending') }}</option>
                                    <option value="passed_count_asc" @if(request()->get('sort') == 'passed_count_asc') selected @endif>{{ trans('admin/main.passed_students_ascending') }}</option>
                                    <option value="passed_count_desc" @if(request()->get('sort') == 'passed_count_desc') selected @endif>{{ trans('admin/main.passes_students_descending') }}</option>
                                    <option value="grade_avg_asc" @if(request()->get('sort') == 'grade_avg_asc') selected @endif>{{ trans('admin/main.grades_average_ascending') }}</option>
                                    <option value="grade_avg_desc" @if(request()->get('sort') == 'grade_avg_desc') selected @endif>{{ trans('admin/main.grades_average_descending') }}</option>
                                    <option value="created_at_asc" @if(request()->get('sort') == 'created_at_asc') selected @endif>{{ trans('admin/main.create_date_ascending') }}</option>
                                    <option value="created_at_desc" @if(request()->get('sort') == 'created_at_desc') selected @endif>{{ trans('admin/main.create_date_descending') }}</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="input-label">{{ trans('admin/main.instructor') }}</label>
                                <select name="teacher_ids[]" multiple="multiple" data-search-option="just_teacher_role" class="form-control search-user-select2"
                                        data-placeholder="Search teachers">

                                    @if(!empty($teachers) and $teachers->count() > 0)
                                        @foreach($teachers as $teacher)
                                            <option value="{{ $teacher->id }}" selected>{{ $teacher->full_name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="input-label">{{ trans('admin/main.class') }}</label>
                                <select name="webinar_ids[]" multiple="multiple" class="form-control search-webinar-select2"
                                        data-placeholder="Search classes">

                                    @if(!empty($webinars) and $webinars->count() > 0)
                                        @foreach($webinars as $webinar)
                                            <option value="{{ $webinar->id }}" selected>{{ $webinar->title }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="input-label">{{ trans('admin/main.status') }}</label>
                                <select name="statue" data-plugin-selectTwo class="form-control populate">
                                    <option value="">{{ trans('admin/main.all_status') }}</option>
                                    <option value="active" @if(request()->get('status') == 'active') selected @endif>{{ trans('admin/main.active') }}</option>
                                    <option value="inactive" @if(request()->get('status') == 'inactive') selected @endif>{{ trans('admin/main.inactive') }}</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-12 col-md-3 d-flex align-items-center justify-content-end">
                            <button type="submit" class="btn btn-primary w-100">{{ trans('admin/main.show_results') }}</button>
                        </div>
                    </form>
                </div>
            </section>

            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="card">
                        
                        <div class="card-header justify-content-between">
                            
                            <div>
                               <h5 class="font-14 mb-0">{{ $pageTitle }}</h5>
                               <p class="font-12 mt-4 mb-0 text-gray-500">{{ trans('update.manage_all_quizzes_in_a_single_place') }}</p>
                           </div>
                           
                            <div class="d-flex align-items-center gap-12">

                            @can('admin_quizzes_lists_excel')
                                   <a href="{{ getAdminPanelUrl() }}/quizzes/excel?{{ http_build_query(request()->all()) }}" class="btn bg-white bg-hover-gray-100 border-gray-400 text-gray-500">
                                       <x-iconsax-lin-import-2 class="icons text-gray-500" width="18px" height="18px"/>
                                       <span class="ml-4 font-12">{{ trans('admin/main.export_xls') }}</span>
                                   </a>
                               @endcan

                               @can('admin_quizzes_create')
                                   <a href="{{ getAdminPanelUrl() }}/quizzes/create" target="_blank" class="btn btn-primary">
                                       <x-iconsax-lin-add class="icons text-white" width="18px" height="18px"/>
                                       <span class="ml-4 font-12">{{ trans('quiz.new_quiz') }}</span>
                                   </a>
                               @endcan

                            </div>
                           
                       </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table custom-table font-14">
                                    <tr>
                                        <th class="text-left">{{ trans('admin/main.title') }}</th>
                                        <th class="text-left">{{ trans('admin/main.instructor') }}</th>
                                        <th class="text-center">{{ trans('admin/main.question_count') }}</th>
                                        <th class="text-center">{{ trans('admin/main.students_count') }}</th>
                                        <th class="text-center">{{ trans('admin/main.average_grade') }}</th>
                                        <th class="text-center">{{ trans('admin/main.certificate') }}</th>
                                        <th class="text-center">{{ trans('admin/main.status') }}</th>
                                        <th>{{ trans('admin/main.actions') }}</th>
                                    </tr>

                                    @foreach($quizzes as $quiz)
                                        <tr>
                                            <td>
                                                <span>{{ $quiz->title }}</span>
                                                @if(!empty($quiz->webinar))
                                                    <small class="d-block text-left text-gray-500">{{ $quiz->webinar->title }}</small>
                                                @endif
                                            </td>

                                            <td class="text-left">{{ $quiz->teacher->full_name }}</td>

                                            <td class="text-center">
                                            <span>{{ $quiz->quizQuestions->count() }}</span>
                                                @if(($quiz->display_limited_questions and !empty($quiz->display_number_of_questions)))
                                                    <span class="d-block text-gray-500 font-12">({{ trans('public.active') }}: {{ $quiz->display_number_of_questions }})</span>
                                                @endif
                                            </td>

                                            <td class="text-center">
                                                <span>{{ $quiz->quizResults->pluck('user_id')->count() }}</span>
                                                <span class="d-block text-gray-500 font-12">({{ trans('admin/main.passed') }}: {{ $quiz->quizResults->where('status','passed')->count() }})</span>
                                            </td>

                                            <td class="text-center">{{ round($quiz->quizResults->avg('user_grade'),2) }} </td>

                                            <td class="text-center">
                                                @if($quiz->certificate)
                                                    <a class="text-success fas fa-check"></a>
                                                @else
                                                    <a class="text-danger fas fa-times"></a>
                                                @endif
                                            </td>

                                            <td class="text-center">
                                                @if($quiz->status === \App\Models\Quiz::ACTIVE)
                                                <span class="badge-status text-success bg-success-30">{{ trans('admin/main.active') }}</span>
                                                @else
                                                <span class="badge-status text-danger bg-danger-30">{{ trans('admin/main.inactive') }}</span>
                                                @endif
                                            </td>

                                            <td>
                                            <div class="btn-group dropdown table-actions position-relative">
                                                    <button type="button" class="btn-transparent dropdown-toggle" data-toggle="dropdown">
                                                        <x-iconsax-lin-more class="icons text-gray-500" width="20px" height="20px"/>
                                                    </button>

                                                    <div class="dropdown-menu dropdown-menu-right">
                                                        @can('admin_quizzes_results')
                                                            <a href="{{ getAdminPanelUrl() }}/quizzes/{{ $quiz->id }}/results" class="dropdown-item d-flex align-items-center mb-3 py-3 px-0 gap-4">
                                                            <x-iconsax-lin-chart class="icons text-gray-500 mr-2" width="18px" height="18px"/>
                                                                <span class="text-gray-500 font-14">{{ trans('admin/main.quiz_results') }}</span>
                                                            </a>
                                                        @endcan

                                                        @can('admin_quizzes_edit')
                                                            <a href="{{ getAdminPanelUrl() }}/quizzes/{{ $quiz->id }}/edit" class="dropdown-item d-flex align-items-center mb-3 py-3 px-0 gap-4">
                                                            <x-iconsax-lin-edit-2 class="icons text-gray-500 mr-2" width="18px" height="18px"/>
                                                                <span class="text-gray-500 font-14">{{ trans('admin/main.edit') }}</span>
                                                            </a>
                                                        @endcan

                                                        @can('admin_quizzes_delete')

                                                            @include('admin.includes.delete_button',[
                                                           'url' => getAdminPanelUrl().'/quizzes/'.$quiz->id.'/delete',
                                                           'btnClass' => 'dropdown-item text-danger mb-0 py-3 px-0 font-14',
                                                           'btnText' => trans("admin/main.delete"),
                                                           'btnIcon' => 'trash',
                                                           'iconType' => 'lin',
                                                           'iconClass' => 'text-danger mr-2',
                                                        ])
                                                        @endcan
                                                    </div>
                                                </div>
                                            </td>

                                            
                                        </tr>
                                    @endforeach

                                </table>
                            </div>
                        </div>

                        <div class="card-footer text-center">
                            {{ $quizzes->appends(request()->input())->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts_bottom')

@endpush
