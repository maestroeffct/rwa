@extends('admin.layouts.app')

@push('styles_top')
    <link rel="stylesheet" href="/assets/admin/vendor/bootstrap-colorpicker/bootstrap-colorpicker.min.css">
@endpush

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{ trans('admin/main.support_departments') }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ getAdminPanelUrl() }}">{{trans('admin/main.dashboard')}}</a>
                </div>
                <div class="breadcrumb-item">{{ trans('admin/main.departments') }}</div>
            </div>
        </div>

        <div class="section-body">
            <div class="card">
                <div class="card-body col-12">
                    <div class="tabs">
                        <ul class="nav nav-pills">
                            <li class="nav-item"><a class="nav-link active" href="#list" data-toggle="tab"> {{ trans('admin/main.departments') }} </a></li>
                            <li class="nav-item"><a class="nav-link" href="#newitem" data-toggle="tab">{{ trans('admin/main.new_department') }}</a></li>
                        </ul>
                        <div class="tab-content">
                            <div id="list" class="tab-pane active">
                                <div class="table-responsive">
                                    <table class="table custom-table font-14">

                                        <tr>
                                            <th>{{ trans('admin/main.department') }}</th>
                                            <th class="text-center" width="200">{{ trans('admin/main.conversations') }}</th>
                                            <th class="text-center" width="100">{{ trans('admin/main.actions') }}</th>
                                        </tr>

                                        @foreach($departments as $department)
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        @if(!empty($department->icon))
                                                            <div class="avatar mr-2" style="background-color: {{ $department->color ?? '#fff' }}">
                                                                <img src="{{ $department->icon }}" alt="" class="img-fluid p-8">
                                                            </div>
                                                        @endif

                                                        <span>{{ $department->title }}</span>
                                                    </div>
                                                </td>

                                                <td>{{ $department->supports_count }}</td>

                                                <td class="text-center">
    <div class="btn-group dropdown table-actions position-relative">
        <button type="button" class="btn-transparent dropdown-toggle" data-toggle="dropdown">
            <x-iconsax-lin-more class="icons text-gray-500" width="20px" height="20px"/>
        </button>

        <div class="dropdown-menu dropdown-menu-right">
            @can('admin_support_departments_edit')
                <a href="{{ getAdminPanelUrl() }}/supports/departments/{{ $department->id }}/edit"
                   class="dropdown-item d-flex align-items-center mb-3 py-3 px-0 gap-4">
                    <x-iconsax-lin-edit-2 class="icons text-gray-500 mr-2" width="18px" height="18px"/>
                    <span class="text-gray-500 font-14">{{ trans('admin/main.edit') }}</span>
                </a>
            @endcan

            @can('admin_support_departments_delete')
                @include('admin.includes.delete_button',[
                    'url' => getAdminPanelUrl().'/supports/departments/'.$department->id.'/delete',
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

                                <div class="text-center mt-2">
                                    {{ $departments->appends(request()->input())->links() }}
                                </div>
                            </div>

                            <div id="newitem" class="tab-pane ">
                                <div class="row">
                                    <div class="col-12 col-md-6">
                                        <form action="{{ getAdminPanelUrl() }}/supports/departments/store"
                                              method="Post">
                                            {{ csrf_field() }}

                                            @if(!empty(getGeneralSettings('content_translate')))
                                                <div class="form-group">
                                                    <label class="input-label">{{ trans('auth.language') }}</label>
                                                    <select name="locale" class="form-control {{ !empty($department) ? 'js-edit-content-locale' : '' }}">
                                                        @foreach($userLanguages as $lang => $language)
                                                            <option value="{{ $lang }}" @if(mb_strtolower(request()->get('locale', app()->getLocale())) == mb_strtolower($lang)) selected @endif>{{ $language }}</option>
                                                        @endforeach
                                                    </select>
                                                    @error('locale')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                    @enderror
                                                </div>
                                            @else
                                                <input type="hidden" name="locale" value="{{ getDefaultLocale() }}">
                                            @endif


                                            <div class="form-group">
                                                <label>{{ trans('admin/main.title') }}</label>
                                                <input type="text" name="title"
                                                       class="form-control  @error('title') is-invalid @enderror"
                                                       value="{{ old('title') }}"/>
                                                @error('title')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                                @enderror
                                            </div>

                                            <div class="form-group">
                                                <label class="input-label">{{ trans('admin/main.icon') }}</label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <button type="button" class="input-group-text admin-file-manager" data-input="statisticIcon" data-preview="holder">
                                                            <i class="fa fa-chevron-up"></i>
                                                        </button>
                                                    </div>
                                                    <input type="text" name="icon" id="statisticIcon" value="{{ old("icon") }}" class="js-ajax-icon form-control"/>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label>{{ trans('admin/main.trend_color') }}</label>

                                                <div class="input-group colorpickerinput">
                                                    <input type="text" name="color"
                                                           autocomplete="off"
                                                           class="form-control  @error('color') is-invalid @enderror"
                                                           value="{{ old('color') }}"
                                                           placeholder=""
                                                    />

                                                    <div class="input-group-append">
                                                        <div class="input-group-text">
                                                            <i class="fas fa-fill-drip"></i>
                                                        </div>
                                                    </div>

                                                    @error('color')
                                                    <div class="invalid-feedback d-block">
                                                        {{ $message }}
                                                    </div>
                                                    @enderror
                                                </div>
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
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts_bottom')
    <script src="/assets/admin/vendor/bootstrap-colorpicker/bootstrap-colorpicker.min.js"></script>
@endpush
