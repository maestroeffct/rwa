@extends('design_1.panel.layouts.panel')

@section('content')
    <div class="row pb-56">
        <div class="col-12 col-lg-6">
            <div class="bg-white p-16 rounded-24">
                <h3 class="font-14 font-weight-bold">{{ trans('update.general_information') }}</h3>

                <form method="post" action="/panel/support/store" class="mt-24" enctype="multipart/form-data">
                    {{ csrf_field() }}

                    <div class="form-group ">
                        <label class="form-group-label">{{ trans('public.type') }}</label>

                        <select name="type" id="supportType" class="form-control select2  @error('type')  is-invalid @enderror" data-allow-clear="false" data-search="false" data-minimum-results-for-search="Infinity">
                            <option selected disabled></option>
                            <option value="course_support" @if($errors->has('webinar_id')) selected @endif>{{ trans('panel.course_support') }}</option>
                            <option value="platform_support" @if($errors->has('department_id')) selected @endif>{{ trans('panel.platform_support') }}</option>
                        </select>

                        @error('type')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>

                    <div id="departmentInput" class="form-group  @if(!$errors->has('department_id')) d-none @endif">
                        <label class="form-group-label">{{ trans('panel.department') }}</label>

                        <select name="department_id" id="departments" class="form-control select2 @error('department_id')  is-invalid @enderror" data-allow-clear="false" data-search="false">
                            <option selected disabled></option>
                            @foreach($departments as $department)
                                <option value="{{ $department->id }}">{{ $department->title }}</option>
                            @endforeach
                        </select>

                        @error('department_id')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>

                    <div id="courseInput" class="form-group  @if(!$errors->has('webinar_id')) d-none @endif">
                        <label class="form-group-label">{{ trans('product.course') }}</label>
                        <select name="webinar_id" class="form-control select2 @error('webinar_id')  is-invalid @enderror">
                            <option value="" selected disabled>{{ trans('panel.select_course') }}</option>

                            @foreach($webinars as $webinar)
                                <option value="{{ $webinar->id }}">{{ $webinar->title }} - {{ $webinar->creator->full_name }}</option>
                            @endforeach
                        </select>
                        @error('webinar_id')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-group-label">{{ trans('site.subject') }}</label>
                        <input type="text" name="title" value="{{ old('title') }}" class="form-control @error('title')  is-invalid @enderror"/>
                        @error('title')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-group-label">{{ trans('site.message') }}</label>
                        <textarea name="message" class="form-control" rows="10">{{ old('message') }}</textarea>
                    </div>

                    <div class="form-group mb-0">
                        <label class="form-group-label">{{ trans('panel.attach_file') }}</label>

                        <div class="custom-file bg-white">
                            <input type="file" name="attach" class="js-ajax-upload-file-input js-ajax-attach custom-file-input" data-upload-name="attach" id="attachFile">
                            <span class="custom-file-text">{{ trans('update.select_a_file') }}</span>
                            <label class="custom-file-label" for="attachFile">{{ trans('update.browse') }}</label>
                        </div>

                        <div class="invalid-feedback d-block"></div>
                    </div>

                    <div class="d-flex flex-column flex-lg-row align-items-lg-center justify-content-lg-between border-top-gray-100 pt-16 mt-16">
                        <div class="d-flex align-items-center">
                            <div class="d-flex-center size-48 rounded-12 bg-gray-200">
                                <x-iconsax-bol-info-circle class="icons text-gray-400" width="24px" height="24px"/>
                            </div>
                            <div class="ml-8">
                                <h5 class="font-14">{{ trans('update.notice') }}</h5>
                                <p class="mt-2 font-12 text-gray-500">{{ trans('update.the_support_message_sending_hint') }}</p>
                            </div>
                        </div>

                        <button type="submit" class="js-submit-special-offer-btn btn btn-lg btn-primary mt-20 mt-lg-0">{{ trans('site.send_message') }}</button>
                    </div>



                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts_bottom')
    <script src="/assets/design_1/js/panel/conversations.min.js"></script>
@endpush
