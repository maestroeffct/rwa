@extends('admin.layouts.app')

@push('libraries_top')

@endpush

@php
    $values = !empty($setting) ? $setting->value : null;

    if (!empty($values)) {
        $values = json_decode($values, true);
    }

    $featuredInstructors = collect();
    $topMentors = collect();

    if (!empty($values) and !empty($values['featured_instructors_ids']) and is_array($values['featured_instructors_ids'])) {
        $featuredInstructors = \App\User::query()->whereIn('id', $values['featured_instructors_ids'])->get();
    }

    if (!empty($values) and !empty($values['top_mentors_ids']) and is_array($values['top_mentors_ids'])) {
        $topMentors = \App\User::query()->whereIn('id', $values['top_mentors_ids'])->get();
    }
@endphp


@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{ trans('update.settings') }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ getAdminPanelUrl() }}">{{trans('admin/main.dashboard')}}</a></div>
                <div class="breadcrumb-item">{{ trans('update.settings') }}</div>
            </div>
        </div>

        <div class="section-body">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <form action="{{ getAdminPanelUrl('/settings/main') }}" method="post">
                                {{ csrf_field() }}
                                <input type="hidden" name="page" value="general">
                                <input type="hidden" name="name" value="{{ \App\Models\Setting::$instructorFinderSettingsName }}">
                                <input type="hidden" name="locale" value="{{ \App\Models\Setting::$defaultSettingsLocale }}">


                                <div class="section-title after-line mb-3">{{ trans('update.featured_instructors') }}</div>

                                <div class="form-group">
                                    <label class="input-label">{{trans('update.specific_instructors')}}</label>

                                    <select name="value[featured_instructors_ids][]" multiple="multiple" data-search-option="just_teacher_role" class="form-control search-user-select2"
                                            data-placeholder="{{trans('public.search_instructors')}}">

                                        @foreach($featuredInstructors as $instructor)
                                            <option value="{{ $instructor->id }}" selected>{{ $instructor->full_name }}</option>
                                        @endforeach
                                    </select>
                                </div>


                                <div class="form-group">
                                    <label class="input-label">{{trans('update.top_mentors')}}</label>

                                    <select name="value[top_mentors_ids][]" multiple="multiple" data-search-option="just_teacher_role" class="form-control search-user-select2"
                                            data-placeholder="{{trans('public.search_instructors')}}">

                                        @foreach($topMentors as $topMentor)
                                            <option value="{{ $topMentor->id }}" selected>{{ $topMentor->full_name }}</option>
                                        @endforeach
                                    </select>
                                </div>


                                <div class="section-title after-line mb-3">{{ trans('public.images') }}</div>

                                @foreach([1,2,3,4] as $step)
                                    <div class="mb-4">
                                        <div class="font-16 font-weight-600 text-dark mb-3">{{ trans('update.images_for_step_n',['step' => $step]) }}</div>

                                        @foreach(['main_image', 'overlay_image'] as $type)
                                            @php
                                                $key = "{$type}_step_{$step}";
                                            @endphp

                                            <div class="form-group mb-3">
                                                <label class="input-label">{{ trans("update.{$type}") }}</label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <button type="button" class="input-group-text admin-file-manager" data-input="{{ $key }}" data-preview="holder">
                                                            <i class="fa fa-chevron-up"></i>
                                                        </button>
                                                    </div>
                                                    <input type="text" name="value[{{ $key }}]" id="{{ $key }}" value="{{ (!empty($values) and !empty($values[$key])) ? $values[$key] : '' }}" class="form-control"/>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endforeach

                                <div class="text-right mt-3">
                                <button type="submit" class="btn btn-primary mt-1">{{ trans('admin/main.submit') }}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection


@push('scripts_bottom')
    <script src="/assets/admin/js/parts/ai_content_settings.min.js"></script>
@endpush
