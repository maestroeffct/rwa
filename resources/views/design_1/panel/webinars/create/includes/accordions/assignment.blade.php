<li data-id="{{ !empty($chapterItem) ? $chapterItem->id :'' }}" class="accordion bg-white border-gray-200 p-12 rounded-16 mt-16">
    <div class="accordion__title d-flex align-items-center justify-content-between " role="tab" id="file_{{ !empty($assignment) ? $assignment->id :'record' }}">
        <div class="d-flex align-items-center cursor-pointer" href="#collapseAssignment{{ !empty($assignment) ? $assignment->id :'record' }}" aria-controls="collapseAssignment{{ !empty($assignment) ? $assignment->id :'record' }}" data-parent="#chapterContentAccordion{{ !empty($chapter) ? $chapter->id :'' }}" role="button" data-toggle="collapse" aria-expanded="true">
            <div class="d-flex mr-8">
                <x-iconsax-lin-chart class="icons text-gray-500" width="20px" height="20px"/>
            </div>

            <div class="font-14 font-weight-bold d-block">{{ !empty($assignment) ? $assignment->title : trans('update.add_new_assignments') }}</div>
        </div>

        <div class="d-flex align-items-center">
            @if(!empty($assignment))

                @if($assignment->accessibility == 'free')
                    <span class="px-8 py-4 bg-primary-20 text-primary font-12 mr-12 rounded-8">{{ trans('public.free') }}</span>
                @endif

                @if($assignment->status != \App\Models\WebinarChapter::$chapterActive)
                    <span class="px-8 py-4 bg-danger-20 text-danger font-12 mr-12 rounded-8">{{ trans('public.disabled') }}</span>
                @endif

                <div class="js-change-content-chapter cursor-pointer mr-12" data-item-id="{{ $assignment->id }}" data-item-type="{{ \App\Models\WebinarChapterItem::$chapterAssignment }}" data-chapter-id="{{ !empty($chapter) ? $chapter->id : '' }}" data-tippy-content="{{ trans('public.edit_chapter') }}">
                    <x-iconsax-lin-category-2 class="icons text-gray-500" width="20px" height="20px"/>
                </div>

                <div class="move-icon mr-12 cursor-pointer d-flex" data-tippy-content="{{ trans('update.sort') }}">
                    <x-iconsax-lin-arrow-3 class="icons text-gray-500" width="20px" height="20px"/>
                </div>

                <a href="/panel/assignments/{{ $assignment->id }}/delete" class="delete-action d-flex text-gray-500 mr-12">
                    <x-iconsax-lin-trash class="icons text-gray-500" width="20px" height="20px"/>
                </a>
            @endif


            <div class="collapse-arrow-icon d-flex cursor-pointer" href="#collapseAssignment{{ !empty($assignment) ? $assignment->id :'record' }}" aria-controls="collapseAssignment{{ !empty($assignment) ? $assignment->id :'record' }}" data-parent="#chapterContentAccordion{{ !empty($chapter) ? $chapter->id :'' }}" role="button" data-toggle="collapse" aria-expanded="true">
                <x-iconsax-lin-arrow-up-1 class="icons text-gray-500" width="20px" height="20px"/>
            </div>
        </div>
    </div>

    <div id="collapseAssignment{{ !empty($assignment) ? $assignment->id :'record' }}" class=" collapse @if(empty($assignment)) show @endif" role="tabpanel">
        <div class="js-content-form assignment-form" data-action="/panel/assignments/{{ !empty($assignment) ? $assignment->id . '/update' : 'store' }}">
            <input type="hidden" name="ajax[{{ !empty($assignment) ? $assignment->id : 'new' }}][webinar_id]" value="{{ !empty($webinar) ? $webinar->id :'' }}">

            <div class="mt-20">
                @include('design_1.panel.includes.locale.locale_select',[
                    'itemRow' => !empty($assignment) ? $assignment : null,
                    'withoutReloadLocale' => true,
                    'extraClass' => 'js-webinar-content-locale',
                    'extraData' => "data-webinar-id='".(!empty($webinar) ? $webinar->id : '')."'  data-id='".(!empty($assignment) ? $assignment->id : '')."'  data-relation='assignments' data-fields='title,description'"
                ])
            </div>

            @if(!empty($assignment))
                <div class="form-group ">
                    <label class="form-group-label  bg-white">{{ trans('public.chapter') }}</label>
                    <select name="ajax[{{ !empty($assignment) ? $assignment->id : 'new' }}][chapter_id]" class="js-ajax-chapter_id form-control select2">
                        @foreach($webinar->chapters as $ch)
                            <option value="{{ $ch->id }}" {{ ($assignment->chapter_id == $ch->id) ? 'selected' : '' }}>{{ $ch->title }}</option>
                        @endforeach
                    </select>
                    <div class="invalid-feedback"></div>
                </div>
            @else
                <input type="hidden" name="ajax[new][chapter_id]" value="" class="chapter-input">
            @endif

            <div class="form-group">
                <label class="form-group-label">{{ trans('public.title') }}</label>
                <input type="text" name="ajax[{{ !empty($assignment) ? $assignment->id : 'new' }}][title]" class="js-ajax-title form-control" value="{{ !empty($assignment) ? $assignment->title : '' }}" placeholder=""/>
                <div class="invalid-feedback"></div>
            </div>

            <div class="form-group">
                <label class="form-group-label">{{ trans('public.description') }}</label>
                <textarea name="ajax[{{ !empty($assignment) ? $assignment->id : 'new' }}][description]" class="js-ajax-description form-control" rows="6">{{ !empty($assignment) ? $assignment->description : '' }}</textarea>
                <div class="invalid-feedback"></div>
            </div>

            <div class="form-group">
                <label class="form-group-label">{{ trans('quiz.grade') }}</label>
                <input type="text" name="ajax[{{ !empty($assignment) ? $assignment->id : 'new' }}][grade]" class="js-ajax-grade form-control" value="{{ !empty($assignment) ? $assignment->grade : '' }}"/>
                <div class="invalid-feedback"></div>
            </div>

            <div class="form-group">
                <label class="form-group-label">{{ trans('update.pass_grade') }}</label>
                <input type="text" name="ajax[{{ !empty($assignment) ? $assignment->id : 'new' }}][pass_grade]" class="js-ajax-pass_grade form-control" value="{{ !empty($assignment) ? $assignment->pass_grade : '' }}"/>
                <div class="invalid-feedback"></div>
            </div>

            <div class="form-group">
                <label class="form-group-label">{{ trans('update.deadline') }}</label>
                <input type="text" name="ajax[{{ !empty($assignment) ? $assignment->id : 'new' }}][deadline]" class="js-ajax-deadline form-control" value="{{ !empty($assignment) ? $assignment->deadline : '' }}"/>
                <div class="invalid-feedback"></div>
            </div>

            <div class="form-group">
                <label class="form-group-label">{{ trans('update.attempts') }}</label>
                <input type="text" name="ajax[{{ !empty($assignment) ? $assignment->id : 'new' }}][attempts]" class="js-ajax-attempts form-control" value="{{ !empty($assignment) ? $assignment->attempts : '' }}"/>
                <div class="invalid-feedback"></div>
            </div>

            <div class="js-assignment-attachments form-group">
                <div class="d-flex align-items-center justify-content-between">
                    <label class="font-14 font-weight-bold text-dark bg-white">{{ trans('public.attachments') }}</label>

                    <div class="js-assignment-attachments-add-btn d-flex align-items-center cursor-pointer" data-input-key="{{ !empty($assignment) ? $assignment->id : 'new' }}">
                        <x-iconsax-lin-add class="icons text-primary" width="16px" height="16px"/>
                        <span class="font-12 text-primary ml-4">{{ trans('update.new_attachment') }}</span>
                    </div>
                </div>

                <div class="js-assignment-attachments-items">

                    @if(!empty($assignment) and !empty($assignment->attachments) and count($assignment->attachments))
                        @foreach($assignment->attachments as $attachment)
                            <div class="js-ajax-attachments position-relative mt-12">
                                <div class="p-16 border-gray-200 rounded-8">
                                    <div class="row">
                                        <div class="col-12 col-lg-6">
                                            <div class="form-group mb-0">
                                                <label class="form-group-label">{{ trans('public.title') }}</label>
                                                <input type="text" name="ajax[{{ $assignment->id }}][attachments][{{ $attachment->id }}][title]" value="{{ $attachment->title }}" class="form-control" placeholder="{{ trans('forms.maximum_255_characters') }}"/>
                                            </div>
                                        </div>

                                        <div class="col-12 col-lg-6 mt-20 mt-lg-0">
                                            <div class="form-group mb-0">
                                                <label class="form-group-label">{{ trans('update.choose_file') }}</label>

                                                <div class="custom-file bg-white">
                                                    <input type="file" name="ajax[{{ $assignment->id }}][attachments][{{ $attachment->id }}][attach]" class="js-ajax-upload-file-input js-ajax-file_upload custom-file-input" data-upload-name="ajax[{{ $assignment->id }}][attachments][{{ $attachment->id }}][attach]" id="attachments_assignmentTemp">
                                                    <input type="hidden" name="ajax[{{ $assignment->id }}][attachments][{{ $attachment->id }}][attach_path]" value="{{ $attachment->attach }}">
                                                    <span class="custom-file-text">{{ getFileNameByPath($attachment->attach) }}</span>
                                                    <label class="custom-file-label" for="attachments_assignmentTemp">{{ trans('update.browse') }}</label>
                                                </div>

                                                <div class="invalid-feedback d-block"></div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-end mt-16">
                                        <div class="js-assignment-attachments-remove-btn btn btn-danger btn-lg">{{ trans('public.delete') }}</div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>


            <div class="form-group d-flex align-items-center">
                <div class="custom-switch mr-8">
                    <input id="assignmentStatusSwitch{{ !empty($assignment) ? $assignment->id : '_record' }}" type="checkbox" name="ajax[{{ !empty($assignment) ? $assignment->id : 'new' }}][status]" class="custom-control-input" {{ (empty($assignment) or $assignment->status == \App\Models\File::$Active) ? 'checked' : ''  }}>
                    <label class="custom-control-label cursor-pointer" for="assignmentStatusSwitch{{ !empty($assignment) ? $assignment->id : '_record' }}"></label>
                </div>

                <div class="">
                    <label class="cursor-pointer" for="assignmentStatusSwitch{{ !empty($assignment) ? $assignment->id : '_record' }}">{{ trans('public.active') }}</label>
                </div>
            </div>

            @if(getFeaturesSettings('sequence_content_status'))
                <div class="form-group d-flex align-items-center">
                    <div class="custom-switch mr-8">
                        <input id="assignmentSequenceContentSwitch{{ !empty($assignment) ? $assignment->id : '_record' }}" type="checkbox" name="ajax[{{ !empty($assignment) ? $assignment->id : 'new' }}][sequence_content]" class="js-sequence-content-switch custom-control-input" {{ (!empty($assignment) and ($assignment->check_previous_parts or !empty($assignment->access_after_day))) ? 'checked' : ''  }}>
                        <label class="custom-control-label cursor-pointer" for="assignmentSequenceContentSwitch{{ !empty($assignment) ? $assignment->id : '_record' }}"></label>
                    </div>

                    <div class="">
                        <label class="cursor-pointer" for="assignmentSequenceContentSwitch{{ !empty($assignment) ? $assignment->id : '_record' }}">{{ trans('update.sequence_content') }}</label>
                    </div>
                </div>

                <div class="js-sequence-content-inputs pl-4 {{ (!empty($assignment) and ($assignment->check_previous_parts or !empty($assignment->access_after_day))) ? '' : 'd-none' }}">
                    <div class="form-group d-flex align-items-center">
                        <div class="custom-switch mr-8">
                            <input id="checkPreviousPartsSwitch{{ !empty($assignment) ? $assignment->id : '_record' }}" type="checkbox" name="ajax[{{ !empty($assignment) ? $assignment->id : 'new' }}][check_previous_parts]" class="custom-control-input" {{ (empty($assignment) or $assignment->check_previous_parts) ? 'checked' : ''  }}>
                            <label class="custom-control-label cursor-pointer" for="checkPreviousPartsSwitch{{ !empty($assignment) ? $assignment->id : '_record' }}"></label>
                        </div>

                        <div class="">
                            <label class="cursor-pointer" for="checkPreviousPartsSwitch{{ !empty($assignment) ? $assignment->id : '_record' }}">{{ trans('update.check_previous_parts') }}</label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-group-label">{{ trans('update.access_after_day') }}</label>
                        <input type="number" name="ajax[{{ !empty($assignment) ? $assignment->id : 'new' }}][access_after_day]" value="{{ (!empty($assignment)) ? $assignment->access_after_day : '' }}" class="js-ajax-access_after_day form-control" placeholder="{{ trans('update.access_after_day_placeholder') }}"/>
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
            @endif


            <div class="mt-20 d-flex align-items-center justify-content-end">
                <button type="button" class="js-save-course-content btn btn-lg btn-primary">{{ trans('public.save') }}</button>

                @if(empty($assignment))
                    <button type="button" class="btn btn-lg btn-danger ml-12 cancel-accordion">{{ trans('public.close') }}</button>
                @endif
            </div>
        </div>
    </div>
</li>

@push('scripts_bottom')
    <script>
        var titleLang = '{{ trans('public.title') }}';
        var chooseFileLang = '{{ trans('update.choose_file') }}';
        var browseLang = '{{ trans('update.browse') }}';
        var deleteLang = '{{ trans('public.delete') }}';
    </script>
@endpush
