@php
    $aiSettings = getAiContentsSettingsName();
@endphp

<div class="ai-content-generator-drawer bg-white">

    <div class="d-flex align-items-center p-16 border-bottom-gray-200">
        <div class="js-right-drawer-close d-flex cursor-pointer">
            <x-iconsax-lin-arrow-right class="icons text-gray-400" width="20px" height="20px"/>
        </div>

        <h5 class="font-14 font-weight-bold ml-8">{{ trans('update.ai_content_generator') }}</h5>
    </div>


    <div class="drawer-content p-16" data-simplebar @if((!empty($isRtl) and $isRtl)) data-simplebar-direction="rtl" @endif>

        <div class="generate-content-easily-card position-relative bg-primary p-4 rounded-16 mb-40">
            <div class="d-flex align-items-center bg-white p-16 rounded-12">
                <div class="d-flex-center size-56 rounded-circle bg-primary-20">
                    <div class="d-flex-center size-40 rounded-circle bg-primary">
                        <x-iconsax-bul-cpu-charge class="icons text-white" width="24px" height="24px"/>
                    </div>
                </div>

                <div class="ml-8">
                    <h4 class="font-14 font-weight-bold">{{ trans('update.generate_content_easily') }}</h4>
                    <p class="mt-4 font-12 text-gray-500">{{ trans('update.select_the_content_type_you_want_and_describe_your_requirements_and_get_the_content') }}</p>
                </div>
            </div>

            <a href="/panel/ai-contents" target="_blank" class="d-flex align-items-center mt-16 mx-12 mb-12 text-white">
                <x-iconsax-lin-arrow-right class="icons text-white" width="20px" height="20px"/>
                <span class="font-12 ml-4">{{ trans('update.view_my_generated_content') }}</span>
            </a>
        </div>


        <form action="/panel/ai-contents/generate" method="post">
            {{ csrf_field() }}

            @if(!empty($aiSettings['activate_text_service_type']) and !empty($aiSettings['activate_image_service_type']))
                <div class="form-group">
                    <label class="form-group-label">{{ trans('update.service_type') }}</label>
                    <select name="service_type" class="form-control">
                        <option value="">{{ trans('update.select_service_type') }}</option>
                        <option value="text">{{ trans('update.text') }}</option>
                        <option value="image">{{ trans('update.image') }}</option>
                    </select>
                </div>
            @elseif(!empty($aiSettings['activate_text_service_type']))
                <input type="hidden" name="service_type" value="text">
            @elseif(!empty($aiSettings['activate_image_service_type']))
                <input type="hidden" name="service_type" value="image">
            @endif

            <div class="">
                <span class="js-ajax-service_type"></span>
                <div class="invalid-feedback d-block"></div>
            </div>

            {{-- Text Fields --}}
            <div class="js-text-templates-field mt-20 {{ (!empty($aiSettings['activate_text_service_type']) and empty($aiSettings['activate_image_service_type'])) ? '' : 'd-none' }}">
                <div class="form-group">
                    <label class="form-group-label">{{ trans('update.service') }}</label>
                    <select name="text_service_id" class="js-ajax-text_service_id js-text-service-templates form-control">
                        <option value="">{{ trans('update.select_service') }}</option>

                        @if(!empty($aiContentTemplates))
                            @foreach($aiContentTemplates->where('type', 'text') as $aiContentTemplate)
                                <option value="{{ $aiContentTemplate->id }}" data-enable-length="{{ $aiContentTemplate->enable_length ? 'yes' : 'no' }}" data-length="{{ $aiContentTemplate->length }}">{{ $aiContentTemplate->title }}</option>
                            @endforeach
                        @endif

                        <option value="custom_text">{{ trans('update.custom_text') }}</option>
                    </select>
                    <div class="invalid-feedback"></div>
                </div>

                @if(!empty(getUserLanguagesLists()))
                    <div class="js-for-service-fields form-group d-none">
                        <label class="form-group-label">{{ trans('auth.language') }}</label>
                        <select name="language" class="js-ajax-language form-control">
                            @foreach(getUserLanguagesLists() as $lang => $language)
                                <option value="{{ $lang }}">{{ $language }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback"></div>
                    </div>
                @endif

                <div class="js-for-service-fields form-group d-none">
                    <label class="form-group-label">{{ trans('update.keyword') }}</label>
                    <input type="text" name="keyword" class="js-ajax-keyword form-control"/>
                    <div class="invalid-feedback"></div>

                    <p class="mt-4 font-12 text-gray-500">{{ trans('update.describe_in_some_words_about_what_you_want') }}</p>
                </div>

                <div class="form-group js-question-field d-none">
                    <label class="form-group-label">{{ trans('update.question') }}</label>
                    <input type="text" name="question" class="js-ajax-question form-control"/>
                    <div class="invalid-feedback"></div>
                    <p class="mt-4 font-12 text-gray-500">{{ trans('update.ask_ai_what_you_want') }}</p>
                </div>


                <div class="js-service-length-field form-group d-none">
                    <label class="form-group-label">{{ trans('update.length') }}</label>
                    <input type="number" name="length" class="js-ajax-length form-control" min="1" max="" data-max-error="{{ trans('update.the_maximum_allowed_is') }}"/>
                    <div class="invalid-feedback"></div>
                </div>
            </div>

            {{-- Image Fields --}}
            <div class="js-image-templates-field {{ (!empty($aiSettings['activate_image_service_type']) and empty($aiSettings['activate_text_service_type'])) ? '' : 'd-none' }}">

                <div class="form-group mt-20">
                    <label class="form-group-label">{{ trans('update.service') }}</label>
                    <select name="image_service_id" class="js-ajax-image_service_id js-image-service-templates form-control">
                        <option value="">{{ trans('update.select_service') }}</option>

                        @if(!empty($aiContentTemplates))
                            @foreach($aiContentTemplates->where('type', 'image') as $aiContentTemplate)
                                <option value="{{ $aiContentTemplate->id }}">{{ $aiContentTemplate->title }}</option>
                            @endforeach
                        @endif

                        <option value="custom_image">{{ trans('update.custom_image') }}</option>
                    </select>
                    <div class="invalid-feedback"></div>
                </div>

                <div class="form-group js-image-question-field d-none">
                    <label class="form-group-label">{{ trans('update.question') }}</label>
                    <input type="text" name="image_question" class="js-ajax-image_question form-control"/>
                    <div class="invalid-feedback"></div>
                    <p class="mt-4 font-12 text-gray-500">{{ trans('update.ask_ai_what_you_want') }}</p>
                </div>

                <div class="js-image-keyword-field form-group d-none">
                    <label class="form-group-label">{{ trans('update.keyword') }}</label>
                    <input type="text" name="image_keyword" class="js-ajax-image_keyword form-control"/>
                    <div class="invalid-feedback"></div>
                    <p class="mt-4 font-12 text-gray-500">{{ trans('update.describe_in_some_words_about_what_you_want') }}</p>
                </div>

                <div class="js-image-size-field form-group d-none">
                    <label class="form-group-label">{{ trans('update.image_size') }}</label>
                    <select name="image_size" class="js-ajax-image_size form-control">
                        <option value="">{{ trans('update.select_image_size') }}</option>
                        <option value="256">{{ trans('update.256x256') }}</option>
                        <option value="512">{{ trans('update.512x512') }}</option>
                        <option value="1024">{{ trans('update.1024x1024') }}</option>
                    </select>
                    <div class="invalid-feedback"></div>
                </div>

            </div>

            <button type="button" class="js-submit-ai-content-form btn btn-primary btn-block mt-20">{{ trans('update.generate') }}</button>
        </form>

        {{-- Text Generated --}}
        <div id="generatedTextContents" class="d-none"></div>


        {{-- Text Generated --}}
        <div class="js-image-generated d-none">

        </div>

    </div>

</div>

<div class="ai-content-generator-drawer-mask"></div>

@push('scripts_bottom')
    <script>
        var generatedContentLang = '{{ trans('update.generated_content') }}';
        var generatedImageLang = '{{ trans('update.generated_image') }}';
        var copyLang = '{{ trans('public.copy') }}';
        var doneLang = '{{ trans('public.done') }}';
        var copyIcon = `<x-iconsax-lin-document-copy class="icons text-gray-500" width="16px" height="16px"/>`;
        var downloadIcon = `<x-iconsax-lin-import class="icons text-gray-500" width="20px" height="20px"/>`;
    </script>

    <script src="/assets/design_1/js/panel/ai_content_generator.min.js"></script>
@endpush
