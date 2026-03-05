@extends('admin.layouts.app')

@push('styles_top')
    <link rel="stylesheet" href="/assets/admin/vendor/bootstrap-colorpicker/bootstrap-colorpicker.min.css">
@endpush

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{ trans('admin/main.theme_colors') }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ getAdminPanelUrl() }}">{{ trans('admin/main.dashboard') }}</a></div>
                <div class="breadcrumb-item active"><a href="{{ getAdminPanelUrl("/themes/colors") }}">{{ trans('admin/main.theme_colors') }}</a></div>
                <div class="breadcrumb-item">{{ trans('update.color') }}</div>
            </div>
        </div>

        <div class="section-body">

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">

                            <form action="{{ getAdminPanelUrl('/themes/colors/').(!empty($colorItem) ? $colorItem->id.'/update' : 'store') }}" method="post" enctype="multipart/form-data">
                                {{ csrf_field() }}

                                <div class="row">

                                    <div class="col-12 col-md-6">

                                        <div class="form-group">
                                            <label>{{ trans('admin/main.title') }}</label>
                                            <input type="text" name="title" value="{{ !empty($colorItem) ? $colorItem->title : old('title') }}" class="form-control "/>
                                            @error('title')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        @php
                                            $allColors = [
                                                'primary',
                                                'primary_saturated',
                                                'secondary',
                                                'accent',
                                                'success',
                                                'info',
                                                'warning',
                                                'danger',
                                                'dark',
                                                'black',
                                                'white',
                                                'gray_100',
                                                'gray_200',
                                                'gray_300',
                                                'gray_400',
                                                'gray_500',
                                                'gray',
                                                'section_bg',
                                            ];
                                        @endphp

                                        <div class="row mt-3">
                                            <div class="col-12 col-md-6 mb-3">
                                                <strong class="">{{ trans('update.light_mode_colors') }}</strong>
                                            </div>

                                            <div class="col-12 col-md-6 mb-3">
                                                <strong class="">{{ trans('update.dark_mode_colors') }}</strong>
                                            </div>

                                            @foreach($allColors as $item)
                                                <div class="col-12 col-md-6">
                                                    <div class="form-group">
                                                        <label>{{ $item }}</label>

                                                        <div class="input-group colorpickerinput">
                                                            <input type="text" name="contents[light][{{ $item }}]"
                                                                   autocomplete="off"
                                                                   class="form-control "
                                                                   value="{{ (!empty($contents) and !empty($contents['light']) and !empty($contents['light'][$item])) ? $contents['light'][$item] : '' }}"
                                                            />
                                                            <div class="input-group-append">
                                                                <div class="input-group-text">
                                                                    <i class="fas fa-fill-drip"></i>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-12 col-md-6">
                                                    <div class="form-group">
                                                        <label>{{ $item }}</label>

                                                        <div class="input-group colorpickerinput">
                                                            <input type="text" name="contents[dark][{{ $item }}]"
                                                                   autocomplete="off"
                                                                   class="form-control "
                                                                   value="{{ (!empty($contents) and !empty($contents['dark']) and !empty($contents['dark'][$item])) ? $contents['dark'][$item] : '' }}"
                                                            />
                                                            <div class="input-group-append">
                                                                <div class="input-group-text">
                                                                    <i class="fas fa-fill-drip"></i>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>

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
    <script src="/assets/admin/vendor/bootstrap-colorpicker/bootstrap-colorpicker.min.js"></script>
@endpush
