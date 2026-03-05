@extends('admin.layouts.app')

@push('styles_top')

@endpush

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{ trans('admin/main.theme_fonts') }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ getAdminPanelUrl() }}">{{ trans('admin/main.dashboard') }}</a></div>
                <div class="breadcrumb-item active"><a href="{{ getAdminPanelUrl("/themes/fonts") }}">{{ trans('admin/main.theme_fonts') }}</a></div>
                <div class="breadcrumb-item">{{ trans('update.font') }}</div>
            </div>
        </div>

        <div class="section-body">

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">

                            <form action="{{ getAdminPanelUrl('/themes/fonts/').(!empty($fontItem) ? $fontItem->id.'/update' : 'store') }}" method="post" enctype="multipart/form-data">
                                {{ csrf_field() }}

                                <div class="row">

                                    <div class="col-12 col-md-6">

                                        <div class="form-group">
                                            <label>{{ trans('admin/main.title') }}</label>
                                            <input type="text" name="title" value="{{ !empty($fontItem) ? $fontItem->title : old('title') }}" class="form-control "/>
                                            @error('title')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        @foreach(['main','rtl'] as $fontType)

                                            <div class="mt-2">
                                                <strong class="font-16 mb-2 text-dark d-block">{{ trans('update.'.$fontType.'_font') }}</strong>

                                                <div class="pl-3">
                                                    <div class="form-group">
                                                        <label class="input-label">{{ trans('update.regular') }}</label>
                                                        <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <button type="button" class="input-group-text admin-file-manager" data-input="{{ $fontType }}FontRegular" data-preview="holder">
                                                                    <i class="fa fa-chevron-up"></i>
                                                                </button>
                                                            </div>
                                                            <input type="text" name="contents[{{ $fontType }}][regular]" id="{{ $fontType }}FontRegular" value="{{ (!empty($contents) and !empty($contents[$fontType]) and !empty($contents[$fontType]['regular'])) ? $contents[$fontType]['regular'] : '' }}" class="form-control"/>
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="input-label">{{ trans('update.bold') }}</label>
                                                        <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <button type="button" class="input-group-text admin-file-manager" data-input="{{ $fontType }}FontBold" data-preview="holder">
                                                                    <i class="fa fa-chevron-up"></i>
                                                                </button>
                                                            </div>
                                                            <input type="text" name="contents[{{ $fontType }}][bold]" id="{{ $fontType }}FontBold" value="{{ (!empty($contents) and !empty($contents[$fontType]) and !empty($contents[$fontType]['bold'])) ? $contents[$fontType]['bold'] : '' }}" class="form-control"/>
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="input-label">{{ trans('update.medium') }}</label>
                                                        <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <button type="button" class="input-group-text admin-file-manager" data-input="{{ $fontType }}FontMedium" data-preview="holder">
                                                                    <i class="fa fa-chevron-up"></i>
                                                                </button>
                                                            </div>
                                                            <input type="text" name="contents[{{ $fontType }}][medium]" id="{{ $fontType }}FontMedium" value="{{ (!empty($contents) and !empty($contents[$fontType]) and !empty($contents[$fontType]['medium'])) ? $contents[$fontType]['medium'] : '' }}" class="form-control"/>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach

                                    </div>
                                </div>


                                <div class="">
                                    <button type="submit" class="btn btn-primary">{{ trans('admin/main.save_change') }}</button>
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

@endpush
