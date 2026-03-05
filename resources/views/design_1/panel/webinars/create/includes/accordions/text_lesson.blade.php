<li data-id="{{ !empty($chapterItem) ? $chapterItem->id :'' }}" class="accordion bg-white border-gray-200 p-12 rounded-16 mt-16">
    <div class="accordion__title d-flex align-items-center justify-content-between " role="tab" id="text_lesson_{{ !empty($textLesson) ? $textLesson->id :'record' }}">
        <div class="d-flex align-items-center cursor-pointer" href="#collapseTextLesson{{ !empty($textLesson) ? $textLesson->id :'record' }}" aria-controls="collapseTextLesson{{ !empty($textLesson) ? $textLesson->id :'record' }}" data-parent="#chapterContentAccordion{{ !empty($chapter) ? $chapter->id :'' }}" role="button" data-toggle="collapse" aria-expanded="true">
            <div class="d-flex mr-8">
                <x-iconsax-lin-document-text class="icons text-gray-500" width="20px" height="20px"/>
            </div>

            <div class="font-14 font-weight-bold d-block">{{ !empty($textLesson) ? $textLesson->title . ($textLesson->accessibility == 'free' ? " (". trans('public.free') .")" : '') : trans('public.add_new_test_lesson') }}</div>
        </div>

        <div class="d-flex align-items-center">

            @if(!empty($textLesson))
                @if($textLesson->status != \App\Models\WebinarChapter::$chapterActive)
                    <span class="px-8 py-4 bg-danger-20 text-danger font-12 mr-12 rounded-8">{{ trans('public.disabled') }}</span>
                @endif


                <div class="js-change-content-chapter cursor-pointer mr-12" data-item-id="{{ $textLesson->id }}" data-item-type="{{ \App\Models\WebinarChapterItem::$chapterTextLesson }}" data-chapter-id="{{ !empty($chapter) ? $chapter->id : '' }}" data-tippy-content="{{ trans('public.edit_chapter') }}">
                    <x-iconsax-lin-category-2 class="icons text-gray-500" width="20px" height="20px"/>
                </div>


                <div class="move-icon mr-12 cursor-pointer d-flex" data-tippy-content="{{ trans('update.sort') }}">
                    <x-iconsax-lin-arrow-3 class="icons text-gray-500" width="20px" height="20px"/>
                </div>


                <a href="/panel/text-lesson/{{ $textLesson->id }}/delete" class="delete-action d-flex text-gray-500 mr-12">
                    <x-iconsax-lin-trash class="icons text-gray-500" width="20px" height="20px"/>
                </a>
            @endif

            <div class="collapse-arrow-icon d-flex cursor-pointer" href="#collapseTextLesson{{ !empty($textLesson) ? $textLesson->id :'record' }}" aria-controls="collapseTextLesson{{ !empty($textLesson) ? $textLesson->id :'record' }}" data-parent="#chapterContentAccordion{{ !empty($chapter) ? $chapter->id :'' }}" role="button" data-toggle="collapse" aria-expanded="true">
                <x-iconsax-lin-arrow-up-1 class="icons text-gray-500" width="20px" height="20px"/>
            </div>
        </div>
    </div>

    <div id="collapseTextLesson{{ !empty($textLesson) ? $textLesson->id :'record' }}" class=" collapse @if(empty($textLesson)) show @endif" role="tabpanel">
        <div class="js-content-form text_lesson-form" data-action="/panel/text-lesson/{{ !empty($textLesson) ? $textLesson->id . '/update' : 'store' }}">
            <input type="hidden" name="ajax[{{ !empty($textLesson) ? $textLesson->id : 'new' }}][webinar_id]" value="{{ !empty($webinar) ? $webinar->id :'' }}">


            <div class="mt-20">
                @include('design_1.panel.includes.locale.locale_select',[
                    'itemRow' => !empty($textLesson) ? $textLesson : null,
                    'withoutReloadLocale' => true,
                    'extraClass' => 'js-webinar-content-locale',
                    'extraData' => "data-webinar-id='".(!empty($webinar) ? $webinar->id : '')."'  data-id='".(!empty($textLesson) ? $textLesson->id : '')."'  data-relation='textLessons' data-fields='title,summary,content'"
                ])
            </div>

            @if(!empty($textLesson))
                <div class="form-group ">
                    <label class="form-group-label">{{ trans('public.chapter') }}</label>
                    <select name="ajax[{{ !empty($textLesson) ? $textLesson->id : 'new' }}][chapter_id]" class="js-ajax-chapter_id form-control select2">
                        @foreach($webinar->chapters as $ch)
                            <option value="{{ $ch->id }}" {{ ($textLesson->chapter_id == $ch->id) ? 'selected' : '' }}>{{ $ch->title }}</option>
                        @endforeach
                    </select>
                    <div class="invalid-feedback"></div>
                </div>
            @else
                <input type="hidden" name="ajax[new][chapter_id]" value="" class="chapter-input">
            @endif

            <div class="form-group">
                <label class="form-group-label">{{ trans('public.title') }}</label>
                <input type="text" name="ajax[{{ !empty($textLesson) ? $textLesson->id : 'new' }}][title]" class="js-ajax-title form-control" value="{{ !empty($textLesson) ? $textLesson->title : '' }}" placeholder=""/>
                <div class="invalid-feedback"></div>
            </div>

            <div class="form-group">
                <label class="form-group-label">{{ trans('public.study_time') }}</label>
                <span class="has-translation bg-gray-100 font-14 text-gray-500 w-auto px-4">{{ trans('public.minutes') }}</span>
                <input type="number" name="ajax[{{ !empty($textLesson) ? $textLesson->id : 'new' }}][study_time]" class="js-ajax-study_time form-control" value="{{ !empty($textLesson) ? $textLesson->study_time : '' }}" placeholder="{{ trans('forms.maximum_255_characters') }}"/>
                <div class="invalid-feedback"></div>
            </div>

            <div class="form-group ">
                <label class="form-group-label">{{ trans('public.image') }}</label>

                <div class="custom-file bg-white">
                    <input type="file" name="ajax[{{ !empty($textLesson) ? $textLesson->id : 'new' }}][image]" class="js-ajax-upload-file-input js-ajax-file_upload custom-file-input" data-upload-name="ajax[{{ !empty($textLesson) ? $textLesson->id : 'new' }}][image]" id="text_lesson_image_{{ !empty($textLesson) ? $textLesson->id :'record' }}">
                    <span class="custom-file-text">{{ (!empty($textLesson) and !empty($textLesson->image)) ? getFileNameByPath($textLesson->image) : '' }}</span>
                    <label class="custom-file-label" for="text_lesson_image_{{ !empty($textLesson) ? $textLesson->id :'record' }}">{{ trans('update.browse') }}</label>
                </div>

                <div class="invalid-feedback d-block"></div>

                @if(!empty($textLesson) and !empty($textLesson->image))
                    <a href="{{ $textLesson->image }}" target="_blank" class="font-12 text-primary mt-8">{{ trans('update.preview') }}</a>
                @endif
            </div>

            <div class="form-group">
                <label class="font-14 text-gray-500 bg-white">{{ trans('public.accessibility') }}</label>

                <div class="d-flex align-items-center js-ajax-accessibility mt-12">

                    <div class="custom-control custom-radio mr-12">
                        <input type="radio" name="ajax[{{ !empty($textLesson) ? $textLesson->id : 'new' }}][accessibility]" id="accessibilityRadio1_{{ !empty($textLesson) ? $textLesson->id : 'record' }}" value="free" class="custom-control-input" @if(empty($textLesson) or (!empty($textLesson) and $textLesson->accessibility == 'free')) checked="checked" @endif>
                        <label class="custom-control__label cursor-pointer pl-0" for="accessibilityRadio1_{{ !empty($textLesson) ? $textLesson->id : 'record' }}">{{ trans('public.free') }}</label>
                    </div>

                    <div class="custom-control custom-radio mr-12">
                        <input type="radio" name="ajax[{{ !empty($textLesson) ? $textLesson->id : 'new' }}][accessibility]" id="accessibilityRadio2_{{ !empty($textLesson) ? $textLesson->id : 'record' }}" value="paid" class="custom-control-input" @if(empty($textLesson) or (!empty($textLesson) and $textLesson->accessibility == 'paid')) checked="checked" @endif>
                        <label class="custom-control__label cursor-pointer pl-0" for="accessibilityRadio2_{{ !empty($textLesson) ? $textLesson->id : 'record' }}">{{ trans('public.paid') }}</label>
                    </div>
                </div>

                <div class="invalid-feedback"></div>
            </div>

            <div class="form-group ">
                <label class="form-group-label d-block">{{ trans('public.attachments') }}</label>

                @php
                    $textLessonAttachmentsFileIds = [];

                    if (!empty($textLesson)) {
                        $textLessonAttachmentsFileIds = $textLesson->attachments->pluck('file_id')->toArray();
                    }
                @endphp

                <select class="js-ajax-attachments form-control {{ !empty($textLesson) ? 'select2' : 'attachments-select2' }}" multiple="multiple" name="ajax[{{ !empty($textLesson) ? $textLesson->id : 'new' }}][attachments][]" data-placeholder="{{ trans('public.choose_attachments') }}">
                    <option></option>

                    @if(!empty($webinar->files) and count($webinar->files))
                        @foreach($webinar->files as $filesInfo)
                            <option value="{{ $filesInfo->id }}" @if(!empty($textLesson) and in_array($filesInfo->id, $textLessonAttachmentsFileIds)) selected @endif>{{ $filesInfo->title }}</option>
                        @endforeach
                    @endif
                </select>
                <div class="invalid-feedback"></div>
            </div>

            <div class="form-group">
                <label class="form-group-label">{{ trans('public.summary') }}</label>
                <textarea name="ajax[{{ !empty($textLesson) ? $textLesson->id : 'new' }}][summary]" class="js-ajax-summary form-control" rows="6">{{ !empty($textLesson) ? $textLesson->summary : '' }}</textarea>
                <div class="invalid-feedback"></div>
            </div>


            <div class="form-group">
                <label class="form-group-label">{{ trans('public.content') }}</label>
                <div class="content-summernote js-ajax-file_path">
                    <textarea class="js-content-summernote-input form-control {{ !empty($textLesson) ? 'js-content-summernote' : '' }}">{{ !empty($textLesson) ? $textLesson->content : '' }}</textarea>
                    <textarea name="ajax[{{ !empty($textLesson) ? $textLesson->id : 'new' }}][content]" class="js-hidden-content-summernote {{ !empty($textLesson) ? 'js-hidden-content-'.$textLesson->id : '' }} d-none">{{ !empty($textLesson) ? $textLesson->content : '' }}</textarea>
                </div>
                <div class="invalid-feedback"></div>
            </div>

            <div class="form-group d-flex align-items-center">
                <div class="custom-switch mr-8">
                    <input id="textLessonStatusSwitch{{ !empty($textLesson) ? $textLesson->id : '_record' }}" type="checkbox" name="ajax[{{ !empty($textLesson) ? $textLesson->id : 'new' }}][status]" class="custom-control-input" {{ (empty($textLesson) or $textLesson->status == \App\Models\File::$Active) ? 'checked' : ''  }}>
                    <label class="custom-control-label cursor-pointer" for="textLessonStatusSwitch{{ !empty($textLesson) ? $textLesson->id : '_record' }}"></label>
                </div>

                <div class="">
                    <label class="cursor-pointer" for="textLessonStatusSwitch{{ !empty($textLesson) ? $textLesson->id : '_record' }}">{{ trans('public.active') }}</label>
                </div>
            </div>


            @if(getFeaturesSettings('sequence_content_status'))
                <div class="form-group d-flex align-items-center">
                    <div class="custom-switch mr-8">
                        <input id="textLessonSequenceContentSwitch{{ !empty($textLesson) ? $textLesson->id : '_record' }}" type="checkbox" name="ajax[{{ !empty($textLesson) ? $textLesson->id : 'new' }}][sequence_content]" class="js-sequence-content-switch custom-control-input" {{ (!empty($textLesson) and ($textLesson->check_previous_parts or !empty($textLesson->access_after_day))) ? 'checked' : ''  }}>
                        <label class="custom-control-label cursor-pointer" for="textLessonSequenceContentSwitch{{ !empty($textLesson) ? $textLesson->id : '_record' }}"></label>
                    </div>

                    <div class="">
                        <label class="cursor-pointer" for="textLessonSequenceContentSwitch{{ !empty($textLesson) ? $textLesson->id : '_record' }}">{{ trans('update.sequence_content') }}</label>
                    </div>
                </div>

                <div class="js-sequence-content-inputs pl-4 {{ (!empty($textLesson) and ($textLesson->check_previous_parts or !empty($textLesson->access_after_day))) ? '' : 'd-none' }}">
                    <div class="form-group d-flex align-items-center">
                        <div class="custom-switch mr-8">
                            <input id="checkPreviousPartsSwitch{{ !empty($textLesson) ? $textLesson->id : '_record' }}" type="checkbox" name="ajax[{{ !empty($textLesson) ? $textLesson->id : 'new' }}][check_previous_parts]" class="custom-control-input" {{ (empty($textLesson) or $textLesson->check_previous_parts) ? 'checked' : ''  }}>
                            <label class="custom-control-label cursor-pointer" for="checkPreviousPartsSwitch{{ !empty($textLesson) ? $textLesson->id : '_record' }}"></label>
                        </div>

                        <div class="">
                            <label class="cursor-pointer" for="checkPreviousPartsSwitch{{ !empty($textLesson) ? $textLesson->id : '_record' }}">{{ trans('update.check_previous_parts') }}</label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-group-label">{{ trans('update.access_after_day') }}</label>
                        <input type="number" name="ajax[{{ !empty($textLesson) ? $textLesson->id : 'new' }}][access_after_day]" value="{{ (!empty($textLesson)) ? $textLesson->access_after_day : '' }}" class="js-ajax-access_after_day form-control" placeholder="{{ trans('update.access_after_day_placeholder') }}"/>
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
            @endif


            <div class="mt-20 d-flex align-items-center justify-content-end">
                <button type="button" class="js-save-course-content btn btn-lg btn-primary">{{ trans('public.save') }}</button>

                @if(empty($textLesson))
                    <button type="button" class="btn btn-lg btn-danger ml-12 cancel-accordion">{{ trans('public.close') }}</button>
                @endif
            </div>
        </div>
    </div>
</li>
