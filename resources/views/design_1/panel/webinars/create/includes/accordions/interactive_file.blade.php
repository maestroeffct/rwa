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

                @if($file->accessibility == 'free')
                    <span class="px-8 py-4 bg-primary-20 text-primary font-12 mr-12 rounded-8">{{ trans('public.free') }}</span>
                @endif

                @if($file->status != \App\Models\WebinarChapter::$chapterActive)
                    <span class="px-8 py-4 bg-danger-20 text-danger font-12 mr-12 rounded-8">{{ trans('public.disabled') }}</span>
                @endif

                <div class="js-change-content-chapter cursor-pointer mr-12" data-item-id="{{ $file->id }}" data-item-type="{{ \App\Models\WebinarChapterItem::$chapterFile }}" data-chapter-id="{{ !empty($chapter) ? $chapter->id : '' }}">
                    <x-iconsax-lin-category-2 class="icons text-gray-500" width="20px" height="20px"/>
                </div>

                <div class="move-icon mr-12 cursor-pointer d-flex" data-tippy-content="{{ trans('update.sort') }}">
                    <x-iconsax-lin-arrow-3 class="icons text-gray-500" width="20px" height="20px"/>
                </div>


                <a href="/panel/files/{{ $file->id }}/delete" class="delete-action d-flex text-gray-500 mr-12">
                    <x-iconsax-lin-trash class="icons text-gray-500" width="20px" height="20px"/>
                </a>
            @endif

            <div class="collapse-arrow-icon d-flex cursor-pointer" href="#collapseFile{{ !empty($file) ? $file->id :'record' }}" aria-controls="collapseFile{{ !empty($file) ? $file->id :'record' }}" data-parent="#chapterContentAccordion{{ !empty($chapter) ? $chapter->id :'' }}" role="button" data-toggle="collapse" aria-expanded="true">
                <x-iconsax-lin-arrow-up-1 class="icons text-gray-500" width="20px" height="20px"/>
            </div>

        </div>
    </div>

    <div id="collapseFile{{ !empty($file) ? $file->id :'record' }}" class=" collapse @if(empty($file)) show @endif" role="tabpanel">
        <div class="js-content-form file-form" data-action="/panel/files/{{ !empty($file) ? $file->id . '/update' : 'store' }}">
            <input type="hidden" name="ajax[{{ !empty($file) ? $file->id : 'new' }}][webinar_id]" value="{{ !empty($webinar) ? $webinar->id :'' }}">
            <input type="hidden" name="ajax[{{ !empty($file) ? $file->id : 'new' }}][storage]" value="upload_archive" class="">
            <input type="hidden" name="ajax[{{ !empty($file) ? $file->id : 'new' }}][file_type]" value="archive" class="">
            <input type="hidden" name="ajax[{{ !empty($file) ? $file->id : 'new' }}][file_url]" value="{{ !empty($file) ? $file->file : '' }}" class="">

            <div class="mt-20">
                @include('design_1.panel.includes.locale.locale_select',[
                    'itemRow' => !empty($file) ? $file : null,
                    'withoutReloadLocale' => true,
                    'extraClass' => 'js-webinar-content-locale',
                    'extraData' => "data-webinar-id='".(!empty($webinar) ? $webinar->id : '')."'  data-id='".(!empty($file) ? $file->id : '')."'  data-relation='files' data-fields='title,description'"
                ])
            </div>


            <div class="form-group">
                <label class="form-group-label">{{ trans('public.title') }}</label>
                <input type="text" name="ajax[{{ !empty($file) ? $file->id : 'new' }}][title]" class="js-ajax-title form-control" value="{{ !empty($file) ? $file->title : '' }}" placeholder="{{ trans('forms.maximum_255_characters') }}"/>
                <div class="invalid-feedback"></div>
            </div>

            @if(!empty($file))
                <div class="form-group">
                    <label class="form-group-label">{{ trans('public.chapter') }}</label>
                    <select name="ajax[{{ !empty($file) ? $file->id : 'new' }}][chapter_id]" class="js-ajax-chapter_id form-control">
                        @foreach($webinar->chapters as $ch)
                            <option value="{{ $ch->id }}" {{ ($file->chapter_id == $ch->id) ? 'selected' : '' }}>{{ $ch->title }}</option>
                        @endforeach
                    </select>
                    <div class="invalid-feedback"></div>
                </div>
            @else
                <input type="hidden" name="ajax[new][chapter_id]" value="" class="chapter-input">
            @endif

            <div class="form-group">
                <label class="form-group-label">{{ trans('update.interactive_type') }}</label>
                <select name="ajax[{{ !empty($file) ? $file->id : 'new' }}][interactive_type]" class="js-interactive-type form-control">
                    <option value="adobe_captivate" {{ (!empty($file) and $file->interactive_type == 'adobe_captivate') ? 'selected' : '' }}>{{ trans('update.adobe_captivate') }}</option>
                    <option value="i_spring" {{ (!empty($file) and $file->interactive_type == 'i_spring') ? 'selected' : '' }}>{{ trans('update.i_spring') }}</option>
                    <option value="custom" {{ (!empty($file) and $file->interactive_type == 'custom') ? 'selected' : '' }}>{{ trans('update.custom') }}</option>
                </select>
                <div class="invalid-feedback"></div>
            </div>

            <div class="js-interactive-file-name-input form-group {{ (!empty($file) and $file->interactive_type == 'custom') ? '' : 'd-none' }}">
                <label class="form-group-label">{{ trans('update.interactive_file_name') }}</label>
                <input type="text" name="ajax[{{ !empty($file) ? $file->id : 'new' }}][interactive_file_name]" class="js-ajax-interactive_file_name form-control" value="{{ !empty($file) ? $file->interactive_file_name : '' }}" placeholder="{{ trans('update.interactive_file_name_placeholder') }}"/>
                <div class="invalid-feedback"></div>
            </div>

            <div class="form-group">
                <label class="font-14 text-gray-500 bg-white">{{ trans('public.accessibility') }}</label>

                <div class="d-flex align-items-center js-ajax-accessibility mt-12">

                    <div class="custom-control custom-radio mr-12">
                        <input type="radio" name="ajax[{{ !empty($file) ? $file->id : 'new' }}][accessibility]" id="accessibilityRadio1_{{ !empty($file) ? $file->id : 'record' }}" value="free" class="custom-control-input" @if(empty($file) or (!empty($file) and $file->accessibility == 'free')) checked="checked" @endif>
                        <label class="custom-control__label cursor-pointer pl-0" for="accessibilityRadio1_{{ !empty($file) ? $file->id : 'record' }}">{{ trans('public.free') }}</label>
                    </div>

                    <div class="custom-control custom-radio mr-12">
                        <input type="radio" name="ajax[{{ !empty($file) ? $file->id : 'new' }}][accessibility]" id="accessibilityRadio2_{{ !empty($file) ? $file->id : 'record' }}" value="paid" class="custom-control-input" @if(empty($file) or (!empty($file) and $file->accessibility == 'paid')) checked="checked" @endif>
                        <label class="custom-control__label cursor-pointer pl-0" for="accessibilityRadio2_{{ !empty($file) ? $file->id : 'record' }}">{{ trans('public.paid') }}</label>
                    </div>
                </div>

                <div class="invalid-feedback"></div>
            </div>

            <div class="form-group">
                <label class="form-group-label">{{ trans('update.choose_file') }}</label>

                <div class="custom-file bg-white">
                    <input type="file" name="ajax[{{ !empty($file) ? $file->id : 'new' }}][file_upload]" class="js-ajax-upload-file-input js-ajax-file_upload custom-file-input" data-upload-name="ajax[{{ !empty($file) ? $file->id : 'new' }}][file_upload]" id="file_upload_input_{{ !empty($file) ? $file->id : 'record' }}" >
                    <span class="custom-file-text">{{ (!empty($file) and !empty($file->file)) ? getFileNameByPath($file->file) : '' }}</span>
                    <label class="custom-file-label" for="file_upload_input_{{ !empty($file) ? $file->id : 'record' }}">{{ trans('update.browse') }}</label>
                </div>

                <div class="invalid-feedback d-block"></div>

                {{--@if(!empty($file) and !empty($file->file))
                    <a href="{{ $file->file }}" target="_blank" class="font-12 text-primary mt-8">{{ trans('update.preview') }}</a>
                @endif--}}
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

            @if(getFeaturesSettings('sequence_content_status'))
                <div class="form-group d-flex align-items-center">
                    <div class="custom-switch mr-8">
                        <input id="interactiveFileSequenceContentSwitch{{ !empty($file) ? $file->id : '_record' }}" type="checkbox" name="ajax[{{ !empty($file) ? $file->id : 'new' }}][sequence_content]" class="js-sequence-content-switch custom-control-input" {{ (!empty($file) and ($file->check_previous_parts or !empty($file->access_after_day))) ? 'checked' : ''  }}>
                        <label class="custom-control-label cursor-pointer" for="interactiveFileSequenceContentSwitch{{ !empty($file) ? $file->id : '_record' }}"></label>
                    </div>

                    <div class="">
                        <label class="cursor-pointer" for="interactiveFileSequenceContentSwitch{{ !empty($file) ? $file->id : '_record' }}">{{ trans('update.sequence_content') }}</label>
                    </div>
                </div>

                <div class="js-sequence-content-inputs pl-4 {{ (!empty($file) and ($file->check_previous_parts or !empty($file->access_after_day))) ? '' : 'd-none' }}">
                    <div class="form-group d-flex align-items-center">
                        <div class="custom-switch mr-8">
                            <input id="checkPreviousPartsSwitch{{ !empty($file) ? $file->id : '_record' }}" type="checkbox" name="ajax[{{ !empty($file) ? $file->id : 'new' }}][check_previous_parts]" class="custom-control-input" {{ (empty($file) or $file->check_previous_parts) ? 'checked' : ''  }}>
                            <label class="custom-control-label cursor-pointer" for="checkPreviousPartsSwitch{{ !empty($file) ? $file->id : '_record' }}"></label>
                        </div>

                        <div class="">
                            <label class="cursor-pointer" for="checkPreviousPartsSwitch{{ !empty($file) ? $file->id : '_record' }}">{{ trans('update.check_previous_parts') }}</label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-group-label">{{ trans('update.access_after_day') }}</label>
                        <input type="number" name="ajax[{{ !empty($file) ? $file->id : 'new' }}][access_after_day]" value="{{ (!empty($file)) ? $file->access_after_day : '' }}" class="js-ajax-access_after_day form-control" placeholder="{{ trans('update.access_after_day_placeholder') }}"/>
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
            @endif

            <div class="mt-20 d-flex align-items-center justify-content-end">
                <button type="button" class="js-save-course-content btn btn-lg btn-primary">{{ trans('public.save') }}</button>

                @if(empty($file))
                    <button type="button" class="btn btn-lg btn-danger ml-12 cancel-accordion">{{ trans('public.close') }}</button>
                @endif
            </div>
        </div>
    </div>
</li>
