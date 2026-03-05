<li data-id="{{ !empty($chapterItem) ? $chapterItem->id :'' }}" class="accordion bg-white border-gray-200 p-12 rounded-16 mt-16">
    <div class="accordion__title d-flex align-items-center justify-content-between " role="tab" id="file_{{ !empty($file) ? $file->id :'record' }}">
        <div class="d-flex align-items-center cursor-pointer" href="#collapseFile{{ !empty($file) ? $file->id :'record' }}" aria-controls="collapseFile{{ !empty($file) ? $file->id :'record' }}" data-parent="#chapterContentAccordion{{ !empty($chapter) ? $chapter->id :'' }}" role="button" data-toggle="collapse" aria-expanded="true">
            <div class="d-flex mr-8">
                @php
                    $fileIcon = !empty($file) ? $file->getIconXByType() : 'document';
                @endphp

                @svg("iconsax-lin-{$fileIcon}", ['height' => 20, 'width' => 20, 'class' => 'text-gray-500'])
            </div>

            <div class="font-14 font-weight-bold d-block">{{ !empty($file) ? $file->title : trans('public.add_new_files') }}</div>
        </div>

        <div class="d-flex align-items-center">

            @if(!empty($file))

                @if($file->status != \App\Models\ProductFile::$Active)
                    <span class="px-8 py-4 bg-danger-20 text-danger font-12 mr-12 rounded-8">{{ trans('public.disabled') }}</span>
                @endif

                <div class="move-icon mr-12 cursor-pointer d-flex" data-tippy-content="{{ trans('update.sort') }}">
                    <x-iconsax-lin-arrow-3 class="icons text-gray-500" width="20px" height="20px"/>
                </div>


                <a href="/panel/store/products/files/{{ $file->id }}/delete" class="delete-action d-flex text-gray-500 mr-12">
                    <x-iconsax-lin-trash class="icons text-gray-500" width="20px" height="20px"/>
                </a>
            @endif

            <div class="collapse-arrow-icon d-flex cursor-pointer" href="#collapseFile{{ !empty($file) ? $file->id :'record' }}" aria-controls="collapseFile{{ !empty($file) ? $file->id :'record' }}" data-parent="#chapterContentAccordion{{ !empty($chapter) ? $chapter->id :'' }}" role="button" data-toggle="collapse" aria-expanded="true">
                <x-iconsax-lin-arrow-up-1 class="icons text-gray-500" width="20px" height="20px"/>
            </div>

        </div>
    </div>

    <div id="collapseFile{{ !empty($file) ? $file->id :'record' }}" class=" collapse @if(empty($file)) show @endif" role="tabpanel">
        <div class="js-content-form file-form" data-action="/panel/store/products/files/{{ !empty($file) ? $file->id . '/update' : 'store' }}">
            <input type="hidden" name="ajax[{{ !empty($file) ? $file->id : 'new' }}][product_id]" value="{{ !empty($product) ? $product->id :'' }}">

            <div class="mt-20">
                @include('design_1.panel.includes.locale.locale_select',[
                    'itemRow' => !empty($file) ? $file : null,
                    'withoutReloadLocale' => true,
                    'extraClass' => 'js-webinar-content-locale',
                    'extraData' => "data-product-id='".(!empty($product) ? $product->id : '')."'  data-id='".(!empty($file) ? $file->id : '')."'  data-relation='files' data-fields='title,description'"
                ])
            </div>

            <div class="form-group">
                <label class="form-group-label">{{ trans('public.title') }}</label>
                <input type="text" name="ajax[{{ !empty($file) ? $file->id : 'new' }}][title]" class="js-ajax-title form-control" value="{{ !empty($file) ? $file->title : '' }}" placeholder="{{ trans('forms.maximum_255_characters') }}"/>
                <div class="invalid-feedback"></div>
            </div>

            <div class="form-group">
                <label class="form-group-label">{{ trans('update.choose_file') }}</label>

                <div class="custom-file bg-white js-ajax-file_upload">
                    <input type="file" name="ajax[{{ !empty($file) ? $file->id : 'new' }}][file_upload]" class="js-ajax-upload-file-input js-ajax-file_upload custom-file-input" data-upload-name="ajax[{{ !empty($file) ? $file->id : 'new' }}][file_upload]" id="file_upload_input_{{ !empty($file) ? $file->id : 'record' }}">
                    <span class="custom-file-text">{{ (!empty($file) and !empty($file->path)) ? getFileNameByPath($file->path) : '' }}</span>
                    <label class="custom-file-label" for="file_upload_input_{{ !empty($file) ? $file->id : 'record' }}">{{ trans('update.browse') }}</label>
                </div>

                <div class="invalid-feedback d-block"></div>

                {{--@if(!empty($file) and !empty($file->file))
                    <a href="{{ $file->file }}" target="_blank" class="font-12 text-primary mt-8">{{ trans('update.preview') }}</a>
                @endif--}}
            </div>

            <div class="row">
                <div class="col-6">
                    <div class="form-group ">
                        <label class="form-group-label">{{ trans('webinars.file_type') }}</label>

                        <select name="ajax[{{ !empty($file) ? $file->id : 'new' }}][file_type]" class="js-ajax-file_type form-control select2">
                            <option value="">{{ trans('webinars.select_file_type') }}</option>

                            @foreach(\App\Models\File::$fileTypes as $fileType)
                                <option value="{{ $fileType }}" @if(!empty($file) and $file->file_type == $fileType) selected @endif>{{ trans('update.file_type_'.$fileType) }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback"></div>
                    </div>
                </div>

                <div class="col-6">
                    <div class="form-group">
                        <label class="form-group-label">{{ trans('webinars.file_volume') }}</label>
                        <span class="has-translation bg-gray-100 font-14 text-gray-500 w-auto px-4">{{ trans('update.mb') }}</span>
                        <input type="number" name="ajax[{{ !empty($file) ? $file->id : 'new' }}][volume]" value="{{ (!empty($file)) ? $file->volume : '' }}" class="js-ajax-volume form-control" placeholder="{{ trans('webinars.online_file_volume') }}"/>
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="form-group-label">{{ trans('public.description') }}</label>
                <textarea name="ajax[{{ !empty($file) ? $file->id : 'new' }}][description]" class="js-ajax-description form-control" rows="6">{{ !empty($file) ? $file->description : '' }}</textarea>
                <div class="invalid-feedback"></div>
            </div>


            <div class="form-group d-flex align-items-center">
                <div class="custom-switch mr-8">
                    <input id="fileStatusSwitch{{ !empty($file) ? $file->id : '_record' }}" type="checkbox" name="ajax[{{ !empty($file) ? $file->id : 'new' }}][status]" class="custom-control-input" {{ (empty($file) or $file->status == \App\Models\File::$Active) ? 'checked' : ''  }}>
                    <label class="custom-control-label cursor-pointer" for="fileStatusSwitch{{ !empty($file) ? $file->id : '_record' }}"></label>
                </div>

                <div class="">
                    <label class="cursor-pointer" for="fileStatusSwitch{{ !empty($file) ? $file->id : '_record' }}">{{ trans('public.active') }}</label>
                </div>
            </div>

            <div class="progress d-none">
                <div class="progress-bar progress-bar-striped progress-bar-animated bg-primary" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0"></div>
            </div>

            <div class="mt-20 d-flex align-items-center justify-content-end">
                <button type="button" class="js-save-course-content btn btn-lg btn-primary">{{ trans('public.save') }}</button>

                @if(empty($file))
                    <button type="button" class="btn btn-lg btn-danger ml-12 cancel-accordion">{{ trans('public.close') }}</button>
                @endif
            </div>
        </div>
    </div>
</li>


@push('scripts_bottom')
    <script>
        var filePathPlaceHolderBySource = {
            upload: '{{ trans('update.file_source_upload_placeholder') }}',
            youtube: '{{ trans('update.file_source_youtube_placeholder') }}',
            vimeo: '{{ trans('update.file_source_vimeo_placeholder') }}',
            external_link: '{{ trans('update.file_source_external_link_placeholder') }}',
            google_drive: '{{ trans('update.file_source_google_drive_placeholder') }}',
            dropbox: '{{ trans('update.file_source_dropbox_placeholder') }}',
            iframe: '{{ trans('update.file_source_iframe_placeholder') }}',
            s3: '{{ trans('update.file_source_s3_placeholder') }}',
        }
    </script>
@endpush
