@extends('admin.layouts.app')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{ $pageTitle }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ getAdminPanelUrl() }}">{{ trans('admin/main.dashboard') }}</a>
                </div>
                <div class="breadcrumb-item active">
                    <a href="{{ getAdminPanelUrl('/appointments') }}">{{ trans('panel.meetings') }}</a>
                </div>
                <div class="breadcrumb-item">{{!empty($meetingPackage) ?trans('/admin/main.edit'): trans('admin/main.new') }}</div>
            </div>
        </div>

        <div class="section-body">

            <div class="row">
                <div class="col-12 col-md-6 col-lg-6">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ getAdminPanelUrl("/meeting-packages/") }}{{ !empty($meetingPackage) ? $meetingPackage->id.'/update' : 'store' }}" method="Post" enctype="multipart/form-data">
                                {{ csrf_field() }}

                                @include('admin.includes.locale_select',[
                                    'itemRow' => !empty($meetingPackage) ? $meetingPackage : null,
                                    'withoutReloadLocale' => false,
                                    'extraClass' => '',
                                    'extraData' => null
                                ])

                                <div class="form-group mt-15 ">
                                    <label class="input-label d-block">{{ trans('public.creator') }}</label>

                                    <select name="creator_id" class="form-control search-user-select2"
                                            data-placeholder="{{ trans('update.select_a_user') }}"
                                            data-search-option="except_user"
                                    >
                                        @if(!empty($meetingPackage))
                                            <option value="{{ $meetingPackage->creator_id }}" selected>{{ $meetingPackage->creator->full_name }}</option>
                                        @else
                                            <option selected disabled>{{ trans('update.select_a_user') }}</option>
                                        @endif
                                    </select>

                                    @error('creator_id')
                                    <div class="invalid-feedback d-block">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>


                                <div class="form-group">
                                    <label class="form-group-label">{{ trans('admin/main.title') }}</label>
                                    <input type="text" name="title"
                                           class="form-control @error('title') is-invalid @enderror"
                                           value="{{ (!empty($meetingPackage) and !empty($meetingPackage->translate($locale))) ? $meetingPackage->translate($locale)->title : old('title') }}"
                                           placeholder="{{ trans('admin/main.choose_title') }}"/>
                                    @error('title')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>


                                <div class="form-group">
                                    <label class="input-label">{{ trans('update.icon') }}</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <button type="button" class="input-group-text admin-file-manager" data-input="icon" data-preview="holder">
                                                <i class="fa fa-chevron-up"></i>
                                            </button>
                                        </div>
                                        <input type="text" name="icon" id="icon" value="{{ (!empty($meetingPackage)) ? $meetingPackage->icon : old('icon') }}" class="form-control @error('icon') is-invalid @enderror" />
                                        <div class="input-group-append">
                                            <button type="button" class="input-group-text admin-file-view" data-input="icon">
                                                <i class="fa fa-eye"></i>
                                            </button>
                                        </div>
                                        @error('icon')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="form-group-label">{{ trans('update.package_validity_duration') }}</label>
                                    <input type="number" name="duration" class="form-control @error('duration') is-invalid @enderror" value="{{ !empty($meetingPackage) ? $meetingPackage->duration : old('duration') }}"/>

                                    <div class="invalid-feedback">@error('duration') {{ $message }} @enderror</div>
                                </div>


                                <div class="form-group">
                                    <label class="form-group-label">{{ trans('update.duration_type') }}</label>
                                    <select name="duration_type" class="form-control select2 @error('duration_type') is-invalid @enderror" data-minimum-results-for-search="Infinity">
                                        @foreach(['day', 'week', 'month', 'year'] as $durationType)
                                            <option value="{{ $durationType }}" {{ (!empty($meetingPackage) and $meetingPackage->duration_type == $durationType) ? 'selected' : '' }}>{{ trans("update.{$durationType}") }}</option>
                                        @endforeach
                                    </select>

                                    <div class="invalid-feedback">@error('duration_type') {{ $message }} @enderror</div>
                                </div>

                                <div class="form-group">
                                    <label class="form-group-label">{{ trans('update.number_of_sessions') }}</label>
                                    <input type="number" name="sessions" class="form-control @error('sessions') is-invalid @enderror" value="{{ !empty($meetingPackage) ? $meetingPackage->sessions : old('sessions') }}"/>
                                    <div class="invalid-feedback">@error('sessions') {{ $message }} @enderror</div>
                                </div>

                                <div class="form-group">
                                    <label class="form-group-label">{{ trans('update.session_duration') }} ({{ trans('public.minutes') }})</label>
                                    <input type="number" name="session_duration" class="form-control @error('session_duration') is-invalid @enderror" value="{{ !empty($meetingPackage) ? $meetingPackage->session_duration : old('session_duration') }}"/>
                                    <div class="invalid-feedback">@error('session_duration') {{ $message }} @enderror</div>
                                </div>

                                <div class="form-group">
                                    <label class="form-group-label">{{ trans('public.price') }}</label>
                                    <span class="has-translation bg-gray-100 text-gray-500">{{ $currency }}</span>
                                    <input type="text" name="price" class="form-control @error('price')  is-invalid @enderror" value="{{ (!empty($meetingPackage) and !empty($meetingPackage->price)) ? convertPriceToUserCurrency($meetingPackage->price) : old('price') }}" placeholder="{{ trans('update.empty_or_0_means_free') }}" oninput="validatePrice(this)"/>
                                    <div class="invalid-feedback d-block">@error('price') {{ $message }} @enderror</div>
                                </div>

                                <div class="form-group">
                                    <label class="form-group-label">{{ trans('public.discount') }}</label>
                                    <span class="has-translation bg-gray-100 text-gray-500">%</span>
                                    <input type="number" name="discount" class="form-control @error('discount')  is-invalid @enderror" value="{{ (!empty($meetingPackage) and !empty($meetingPackage->discount)) ? $meetingPackage->discount : old('discount') }}"/>
                                    <div class="invalid-feedback d-block">@error('discount') {{ $message }} @enderror</div>
                                </div>

                                <div class="form-group custom-switches-stacked">
                                    <label class="custom-switch pl-0 d-flex align-items-center">
                                        <input type="hidden" name="enable" value="no">
                                        <input type="checkbox" name="enable" id="forumStatusSwitch" value="on" class="custom-switch-input" {{ (!empty($meetingPackage) and $meetingPackage->enable) ? 'checked' : '' }}/>
                                        <span class="custom-switch-indicator"></span>
                                        <label class="custom-switch-description mb-0 cursor-pointer" for="forumStatusSwitch">{{ trans('admin/main.active') }}</label>
                                    </label>
                                </div>


                                <div class="text-right mt-4">
                                    <button class="btn btn-primary">{{ trans('admin/main.submit') }}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
