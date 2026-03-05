@extends('admin.layouts.app')

@push('libraries_top')

@endpush

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{ trans('admin/main.quiz_results') }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ getAdminPanelUrl() }}">{{trans('admin/main.dashboard')}}</a>
                </div>
                <div class="breadcrumb-item">{{ trans('admin/main.quiz_results') }}</div>
            </div>
        </div>

        <div class="section-body">

            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="card">
                      
                        <div class="card-header justify-content-between">
                            
                            <div>
                               <h5 class="font-14 mb-0">{{ $pageTitle }}</h5>
                               <p class="font-12 mt-4 mb-0 text-gray-500">{{ trans('update.manage_all_items_in_a_single_place') }}</p>
                           </div>
                           
                            <div class="d-flex align-items-center gap-12">

                            @can('admin_quiz_result_export_excel')
                                <div class="d-flex align-items-center gap-12">
                                    <a href="{{ getAdminPanelUrl() }}/quizzes/{{ $quiz_id }}/results/excel" class="btn bg-white bg-hover-gray-100 border-gray-400 text-gray-500">
                                        <x-iconsax-lin-import-2 class="icons text-gray-500" width="18px" height="18px"/>
                                        <span class="ml-4 font-12">{{ trans('admin/main.export_xls') }}</span>
                                    </a>
                                </div>
                            @endcan

                            </div>
                           
                       </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table custom-table font-14">
                                    <tr>
                                        <th class="text-left">{{ trans('admin/main.title') }}</th>
                                        <th class="text-left">{{ trans('quiz.student') }}</th>
                                        <th class="text-left">{{ trans('admin/main.instructor') }}</th>
                                        <th class="text-center">{{ trans('admin/main.grade') }}</th>
                                        <th class="text-center">{{ trans('admin/main.quiz_date') }}</th>
                                        <th class="text-center">{{ trans('admin/main.status') }}</th>
                                        <th>{{ trans('admin/main.actions') }}</th>
                                    </tr>

                                    @foreach($quizzesResults as $result)
                                        <tr>
                                            <td>
                                                <span>{{ $result->quiz->title }}</span>
                                                <small class="d-block text-left text-primary">({{ $result->quiz->webinar->title }})</small>
                                            </td>
                                            <td class="text-left">{{ $result->user->full_name }}</td>
                                            <td class="text-left">
                                                {{ $result->quiz->teacher->full_name }}
                                            </td>
                                            <td class="text-center">
                                                <span>{{ $result->user_grade }}</span>
                                            </td>
                                            <td class="text-center">{{ dateTimeformat($result->created_at, 'j F Y') }}</td>
                                            <td class="text-center">
                                                @switch($result->status)
                                                    @case(\App\Models\QuizzesResult::$passed)
                                                        <span class="badge-status text-success bg-success-30">{{ trans('quiz.passed') }}</span>
                                                        @break

                                                    @case(\App\Models\QuizzesResult::$failed)
                                                        <span class="badge-status text-danger bg-danger-30">{{ trans('quiz.failed') }}</span>
                                                        @break

                                                    @case(\App\Models\QuizzesResult::$waiting)
                                                        <span class="badge-status text-warning bg-warning-30">{{ trans('quiz.waiting') }}</span>
                                                        @break

                                                @endswitch
                                            </td>

                                            <td>
    <div class="btn-group dropdown table-actions position-relative">
        <button type="button" class="btn-transparent dropdown-toggle" data-toggle="dropdown">
            <x-iconsax-lin-more class="icons text-gray-500" width="20px" height="20px"/>
        </button>

        <div class="dropdown-menu dropdown-menu-right">
            @if($result->status == 'waiting')
                @can('admin_quiz_result_review')
                    <a href="{{ getAdminPanelUrl() }}/quizzes/{{ $result->quiz_id }}/results/{{ $result->id }}/review"
                       target="_blank"
                       class="dropdown-item d-flex align-items-center mb-3 py-3 px-0 gap-4">
                        <x-iconsax-lin-eye class="icons text-gray-500 mr-2" width="18px" height="18px"/>
                        <span class="text-gray-500 font-14">{{ trans('public.review') }}</span>
                    </a>
                @endcan
            @endif

            @can('admin_quizzes_results_delete')
                @include('admin.includes.delete_button',[
                    'url' => getAdminPanelUrl().'/quizzes/'.$result->quiz_id.'/results/'.$result->id.'/delete',
                    'btnClass' => 'dropdown-item text-danger mb-0 py-3 px-0 font-14',
                    'btnText' => trans('admin/main.delete'),
                    'btnIcon' => 'trash',
                    'iconType' => 'lin',
                    'iconClass' => 'text-danger mr-2'
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
                            {{ $quizzesResults->appends(request()->input())->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts_bottom')

@endpush
