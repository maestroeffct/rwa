@extends('admin.layouts.app')

@push('styles_top')

@endpush

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{ $pageTitle }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ getAdminPanelUrl() }}">{{trans('admin/main.dashboard')}}</a>
                </div>
                <div class="breadcrumb-item">{{trans('admin/main.classes')}}</div>

                <div class="breadcrumb-item">{{ $pageTitle }}</div>
            </div>
        </div>



        <div class="section-body">

            <section class="card">
                <div class="card-body">
                    <form action="{{ getAdminPanelUrl() }}/webinars/personal-notes" method="get" class="row mb-0">
                        <div class="col-12 col-lg-3">
                            <div class="form-group">
                                <label class="input-label">{{ trans('admin/main.search') }}</label>
                                <input type="text" name="search" class="form-control" value="{{ request()->get('search',null) }}"/>
                            </div>
                        </div>

                        <div class="col-12 col-lg-3">
                            <div class="form-group">
                                <label class="input-label">{{ trans('admin/main.content_type') }}</label>

                                <select name="content_type" class="form-control">
                                    <option value="">{{ trans('admin/main.all') }}</option>
                                    <option value="webinar" {{ (request()->get('content_type') == "webinar") ? 'selected' : '' }}>{{ trans('admin/main.webinar') }}</option>
                                    <option value="course" {{ (request()->get('content_type') == "course") ? 'selected' : '' }}>{{ trans('admin/main.course') }}</option>
                                    <option value="text_lesson" {{ (request()->get('content_type') == "text_lesson") ? 'selected' : '' }}>{{ trans('admin/main.text_lesson') }}</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="input-label">{{trans('admin/main.start_date')}}</label>
                                <div class="input-group">
                                    <input type="date" id="fsdate" class="text-center form-control" name="from" value="{{ request()->get('from') }}" placeholder="Start Date">
                                </div>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="input-label">{{trans('admin/main.end_date')}}</label>
                                <div class="input-group">
                                    <input type="date" id="lsdate" class="text-center form-control" name="to" value="{{ request()->get('to') }}" placeholder="End Date">
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-lg-2 d-flex align-items-center justify-content-end">
                            <button type="submit" class="btn btn-primary w-100">{{ trans('admin/main.show_results') }}</button>
                        </div>
                    </form>
                </div>
            </section>


            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="card">

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table custom-table font-14 ">
                                    <tr>
                                        <th class="text-left">{{trans('admin/main.course')}}</th>
                                        <th class="text-left">{{trans('admin/main.user')}}</th>
                                        <th class="text-center">{{trans('update.note')}}</th>
                                        @if(!empty(getFeaturesSettings('course_notes_attachment')))
                                            <th class="text-center">{{ trans('update.attachment') }}</th>
                                        @endif

                                        <th class="text-center">{{ trans('public.date') }}</th>
                                        <th width="120">{{trans('admin/main.actions')}}</th>
                                    </tr>

                                    @foreach($personalNotes as $personalNote)
                                        <tr>
                                            <td class="text-left">
                                                <span class="d-block">{{ $personalNote->course->title }}</span>
                                                <span class="d-block font-12 text-gray-500 mt-1">{{ trans('public.by') }} {{ $personalNote->course->teacher->full_name }}</span>
                                            </td>

                                            <td class="text-left">
                                                <span class="d-block">{{ $personalNote->user->full_name }}</span>

                                                @if(!empty($personalNote->user->email))
                                                    <span class="d-block font-12 text-gray-500 mt-1">{{ $personalNote->user->email }}</span>
                                                @endif

                                                @if(!empty($personalNote->user->mobile))
                                                    <span class="d-block font-12 text-gray-500 mt-1">{{ $personalNote->user->mobile }}</span>
                                                @endif
                                            </td>

                                            <td class="text-center">
                                                <input type="hidden" class="js-note-message" value="{{ $personalNote->note }}">
                                                <input type="hidden" class="js-note-attachment" value="{{ $personalNote->attachment }}">

                                                <button type="button" class="js-show-note btn btn-sm btn-gray200">{{ trans('public.view') }}</button>
                                            </td>

                                            @if(!empty(getFeaturesSettings('course_notes_attachment')))
                                                <td class="align-middle">
                                                    @if(!empty($personalNote->attachment))
                                                        <a href="{{ getAdminPanelUrl("/webinars/personal-notes/{$personalNote->id}/download-attachment") }}" class="btn btn-sm btn-gray200">{{ trans('home.download') }}</a>
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                            @endif

                                            <td class="align-middle">{{ dateTimeFormat($personalNote->created_at,'j M Y | H:i') }}</td>

                                            <td>
                                            <div class="btn-group dropdown table-actions position-relative">
                                                    <button type="button" class="btn-transparent dropdown-toggle" data-toggle="dropdown">
                                                        <x-iconsax-lin-more class="icons text-gray-500" width="20px" height="20px"/>
                                                    </button>

                                                    <div class="dropdown-menu dropdown-menu-right">
                                                            <a type="button" class="dropdown-item d-flex align-items-center mb-3 py-3 px-0" href="{{ "{$personalNote->course->getLearningPageUrl()}?type={$personalNote->getItemType()}&item={$personalNote->targetable_id}" }}">
                                                            <x-iconsax-lin-eye class="icons text-gray-500 mr-2" width="18px" height="18px"/>
                                                                <span class="text-gray-500 font-14">{{ trans('public.view') }}</span>
                                                            </a>



                                                            <button type="button" class="dropdown-item js-edit-note btn-transparent  d-flex align-items-center mb-3 py-3 px-0 font-14" data-action="{{ getAdminPanelUrl("/webinars/personal-notes/{$personalNote->id}/update") }}" >
                                                            <x-iconsax-lin-edit-2 class="icons text-gray-500 mr-2" width="18px" height="18px"/>
                                                            <span class="text-gray-500 font-14">{{ trans('admin/main.edit') }}</span>
                                                            </button>

                                                            @include('admin.includes.delete_button',[
                                                           'url' => getAdminPanelUrl().'/webinars/personal-notes/'.$personalNote->id.'/delete',
                                                           'btnClass' => 'dropdown-item text-danger mb-0 py-3 px-0 font-14',
                                                           'btnText' => trans("admin/main.delete"),
                                                           'btnIcon' => 'trash',
                                                           'iconType' => 'lin',
                                                           'iconClass' => 'text-danger mr-2',
                                                        ])

                                                    </div>
                                                </div>
                                            </td>


                                        </tr>
                                    @endforeach

                                </table>
                            </div>
                        </div>

                        <div class="card-footer text-center">
                            {{ $personalNotes->appends(request()->input())->links() }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>


@endsection

@push('scripts_bottom')
    <script>
        var noteLang = '{{ trans('update.note') }}';
        var personalNoteLang = '{{ trans('update.personal_note') }}';
        var attachmentLang = '{{ trans('update.attachment') }}';
        var saveNoteLang = '{{ trans('public.save') }}';
        var closeLang = '{{ trans('public.close') }}';
    </script>


    <script src="/assets/admin/js/parts/personal_note.min.js"></script>
@endpush
