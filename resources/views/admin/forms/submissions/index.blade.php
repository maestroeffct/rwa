@extends('admin.layouts.app')

@push('libraries_top')

@endpush

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{ $pageTitle }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ getAdminPanelUrl() }}">{{trans('admin/main.dashboard')}}</a>
                </div>
                <div class="breadcrumb-item">{{ trans('update.forms') }}</div>
            </div>
        </div>

        <div class="section-body">

            <section class="card">
                <div class="card-body">
                    <form action="{{ getAdminPanelUrl("/forms/submissions") }}" method="get" class="row mb-0">

                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="input-label">{{ trans('update.form') }}</label>
                                <select name="form" class="form-control">
                                    <option value="">{{ trans('admin/main.all') }}</option>
                                    @foreach($forms as $form)
                                        <option value="{{ $form->id }}" {{ (request()->get('form') == $form->id) ? 'selected' : '' }}>{{ $form->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="input-label">{{ trans('admin/main.user') }}</label>
                                <select name="user_ids[]" multiple="multiple" data-search-option="" class="form-control search-user-select2"
                                        data-placeholder="{{ trans('public.search_user') }}">

                                    @if(!empty($users) and $users->count() > 0)
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}" selected>{{ $user->full_name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="input-label">{{trans('admin/main.start_date')}}</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="dateInputGroupPrepend">
                                            <i class="fa fa-calendar-alt"></i>
                                        </span>
                                    </div>
                                    <input type="text" name="from" autocomplete="off" class="form-control @if(!empty(request()->get('from'))) datepicker @else datefilter @endif"
                                           aria-describedby="dateInputGroupPrepend"
                                           value="{{ request()->get('from',null) }}"/>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="input-label">{{trans('admin/main.end_date')}}</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="dateInputGroupPrepend">
                                            <i class="fa fa-calendar-alt"></i>
                                        </span>
                                    </div>
                                    <input type="text" name="to" autocomplete="off" class="form-control @if(!empty(request()->get('to'))) datepicker @else datefilter @endif"
                                           aria-describedby="dateInputGroupPrepend"
                                           value="{{ request()->get('to',null) }}"/>
                                </div>
                            </div>
                        </div>


                        <div class="col-12 col-md-4 d-flex align-items-center justify-content-end">
                            <button type="submit" class="btn btn-primary w-100">{{ trans('public.show_results') }}</button>
                        </div>
                    </form>
                </div>
            </section>


            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table custom-table font-14">
                                    <tr>
                                        <th>{{ trans('admin/main.title') }}</th>
                                        <th>{{ trans('admin/main.user') }}</th>
                                        <th>{{ trans('update.submission_date') }}</th>
                                        <th>{{ trans('admin/main.actions') }}</th>
                                    </tr>
                                    @foreach($submissions as $submission)
                                        <tr>
                                            <td>{{ $submission->form->title }}</td>

                                            <td>{{ !empty($submission->user) ? $submission->user->full_name : trans('update.guest') }}</td>

                                            <td>{{ dateTimeFormat($submission->created_at, 'j M Y H:i') }}</td>

                                            <td width="80px">
                                                <div class="btn-group dropdown table-actions position-relative">
                                                    <button type="button" class="btn-transparent dropdown-toggle" data-toggle="dropdown">
                                                        <x-iconsax-lin-more class="icons text-gray-500" width="20px" height="20px"/>
                                                    </button>

                                                    <div class="dropdown-menu dropdown-menu-right">
                                                        <a href="{{ getAdminPanelUrl() }}/forms/submissions/{{ $submission->id }}/show"
                                                           class="dropdown-item d-flex align-items-center mb-3 py-3 px-0 gap-4">
                                                            <x-iconsax-lin-eye class="icons text-gray-500 mr-2" width="18px" height="18px"/>
                                                            <span class="text-gray-500 font-14">{{ trans('update.show_details') }}</span>
                                                        </a>

                                                        @include('admin.includes.delete_button',[
                                                            'url' => getAdminPanelUrl().'/forms/submissions/'.$submission->id.'/delete',
                                                            'btnClass' => 'dropdown-item text-danger mb-0 py-3 px-0 font-14',
                                                            'btnText' => trans('admin/main.delete'),
                                                            'btnIcon' => 'trash',
                                                            'iconType' => 'lin',
                                                            'iconClass' => 'text-danger mr-2'
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
                            {{ $submissions->appends(request()->input())->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts_bottom')

@endpush
