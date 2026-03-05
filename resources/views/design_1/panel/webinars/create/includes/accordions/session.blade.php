@php
    if (!empty($session->agora_settings)) {
        $session->agora_settings = json_decode($session->agora_settings);
    }
@endphp

<li data-id="{{ !empty($chapterItem) ? $chapterItem->id :'' }}" class="accordion bg-white border-gray-200 p-12 rounded-16 mt-16">
    <div class="accordion__title d-flex align-items-center justify-content-between " role="tab" id="session_{{ !empty($session) ? $session->id :'record' }}">
        <div class="d-flex align-items-center cursor-pointer" href="#collapseSession{{ !empty($session) ? $session->id :'record' }}" aria-controls="collapseSession{{ !empty($session) ? $session->id :'record' }}" data-parent="#chapterContentAccordion{{ !empty($chapter) ? $chapter->id :'' }}" role="button" data-toggle="collapse" aria-expanded="true">
            <div class="d-flex mr-8">
                <x-iconsax-lin-video class="icons text-gray-500" width="20px" height="20px"/>
            </div>

            <div class="font-14 font-weight-bold d-block">{{ !empty($session) ? $session->title : trans('public.add_new_sessions') }}</div>
        </div>

        <div class="d-flex align-items-center">

            @if(!empty($session))
                @if($session->status != \App\Models\WebinarChapter::$chapterActive)
                    <span class="px-8 py-4 bg-danger-20 text-danger font-12 mr-12 rounded-8">{{ trans('public.disabled') }}</span>
                @endif

                <div class="js-change-content-chapter cursor-pointer mr-12" data-item-id="{{ $session->id }}" data-item-type="{{ \App\Models\WebinarChapterItem::$chapterSession }}" data-chapter-id="{{ !empty($chapter) ? $chapter->id : '' }}" data-tippy-content="{{ trans('public.edit_chapter') }}">
                    <x-iconsax-lin-category-2 class="icons text-gray-500" width="20px" height="20px"/>
                </div>

                <div class="move-icon mr-12 cursor-pointer d-flex" data-tippy-content="{{ trans('update.sort') }}">
                    <x-iconsax-lin-arrow-3 class="icons text-gray-500" width="20px" height="20px"/>
                </div>

                <a href="/panel/sessions/{{ $session->id }}/delete" class="delete-action d-flex text-gray-500 mr-12">
                    <x-iconsax-lin-trash class="icons text-gray-500" width="20px" height="20px"/>
                </a>
            @endif

            <div class="collapse-arrow-icon d-flex cursor-pointer" href="#collapseSession{{ !empty($session) ? $session->id :'record' }}" data-parent="#chapterContentAccordion{{ !empty($chapter) ? $chapter->id :'' }}" role="button" data-toggle="collapse">
                <x-iconsax-lin-arrow-up-1 class="icons text-gray-500" width="20px" height="20px"/>
            </div>
        </div>
    </div>

    <div id="collapseSession{{ !empty($session) ? $session->id :'record' }}" class=" collapse {{ empty($session) ? 'show' : '' }}" role="tabpanel">

        <div class="js-content-form session-form" data-action="/panel/sessions/{{ !empty($session) ? $session->id . '/update' : 'store' }}">
            <input type="hidden" name="ajax[{{ !empty($session) ? $session->id : 'new' }}][webinar_id]" value="{{ !empty($webinar) ? $webinar->id :'' }}">

            <div class="form-group mt-20">
                <label class="font-14 text-gray-500 bg-white">{{ trans('webinars.select_session_api') }}</label>

                <div class="js-session-api d-flex align-items-center mt-12">
                    @foreach(getFeaturesSettings("available_session_apis") as $sessionApi)
                        <div class="custom-control custom-radio mr-12">
                            <input type="radio" name="ajax[{{ !empty($session) ? $session->id : 'new' }}][session_api]" id="{{ $sessionApi }}_api_{{ !empty($session) ? $session->id : '' }}" value="{{ $sessionApi }}" @if((!empty($session) and $session->session_api == $sessionApi) or (empty($session) and $sessionApi == 'local')) checked @endif class="js-api-input custom-control-input" {{ (!empty($session) and $session->session_api != 'local') ? 'disabled' :'' }}>
                            <label class="custom-control__label cursor-pointer pl-0" for="{{ $sessionApi }}_api_{{ !empty($session) ? $session->id : '' }}">{{ trans('update.session_api_'.$sessionApi) }}</label>
                        </div>
                    @endforeach
                </div>

                <div class="invalid-feedback"></div>

                <div class="js-zoom-not-complete-alert mt-12 text-danger d-none">
                    {{ trans('webinars.your_zoom_settings_are_not_complete') }}
                </div>
            </div>


            @include('design_1.panel.includes.locale.locale_select',[
                'itemRow' => !empty($session) ? $session : null,
                'withoutReloadLocale' => true,
                'extraClass' => 'js-webinar-content-locale',
                'extraData' => "data-webinar-id='".(!empty($webinar) ? $webinar->id : '')."'  data-id='".(!empty($session) ? $session->id : '')."'  data-relation='sessions' data-fields='title,description'"
            ])

            <div class="form-group js-api-secret {{ (!empty($session) and in_array($session->session_api, ['zoom', 'agora', 'jitsi'])) ? 'd-none' :'' }}">
                <label class="form-group-label">{{ trans('auth.password') }}</label>
                <input type="text" name="ajax[{{ !empty($session) ? $session->id : 'new' }}][api_secret]" class="js-ajax-api_secret form-control" value="{{ !empty($session) ? $session->api_secret : '' }}" {{ (!empty($session) and $session->session_api != 'local') ? 'disabled' :'' }}/>
                <div class="invalid-feedback"></div>
            </div>

            <div class="form-group js-moderator-secret {{ (empty($session) or $session->session_api != 'big_blue_button') ? 'd-none' :'' }}">
                <label class="form-group-label">{{ trans('public.moderator_password') }}</label>
                <input type="text" name="ajax[{{ !empty($session) ? $session->id : 'new' }}][moderator_secret]" class="js-ajax-moderator_secret form-control" value="{{ !empty($session) ? $session->moderator_secret : '' }}" {{ (!empty($session) and $session->session_api == 'big_blue_button') ? 'disabled' :'' }}/>
                <div class="invalid-feedback"></div>
            </div>

            @if(!empty($session))
                <div class="form-group">
                    <label class="form-group-label">{{ trans('public.chapter') }}</label>

                    <select name="ajax[{{ !empty($session) ? $session->id : 'new' }}][chapter_id]" class="js-ajax-chapter_id form-control">
                        @foreach($webinar->chapters as $ch)
                            <option value="{{ $ch->id }}" {{ ($session->chapter_id == $ch->id) ? 'selected' : '' }}>{{ $ch->title }}</option>
                        @endforeach
                    </select>
                    <div class="invalid-feedback"></div>
                </div>
            @else
                <input type="hidden" name="ajax[new][chapter_id]" value="" class="chapter-input">
            @endif

            <div class="form-group">
                <label class="form-group-label">{{ trans('public.title') }}</label>
                <span class="has-translation bg-gray-300 rounded-8 p-8"><x-iconsax-lin-translate class="icons text-gray-500"/></span>
                <input type="text" name="ajax[{{ !empty($session) ? $session->id : 'new' }}][title]" class="js-ajax-title form-control" value="{{ !empty($session) ? $session->title : '' }}" placeholder="{{ trans('forms.maximum_255_characters') }}"/>
                <div class="invalid-feedback"></div>
            </div>

            <div class="form-group">
                <label class="form-group-label">{{ trans('public.date') }}</label>
                <span class="has-translation bg-transparent"><x-iconsax-lin-calendar-2 class="text-gray-500" width="24px" height="24px"/></span>
                <input type="text" name="ajax[{{ !empty($session) ? $session->id : 'new' }}][date]" class="js-ajax-date form-control datetimepicker" value="{{ !empty($session) ? dateTimeFormat($session->date, 'Y-m-d H:i', false, true, ($session->webinar ? $session->webinar->timezone : null)) : '' }}" aria-describedby="dateRangeLabel" {{ (!empty($session) and $session->session_api != 'local') ? 'disabled' :'' }} autocomplete="off"/>
                <div class="invalid-feedback"></div>
            </div>

            <div class="form-group">
                <label class="form-group-label">{{ trans('public.duration') }}</label>
                <span class="has-translation bg-gray-100 font-14 text-gray-500 w-auto px-4">{{ trans('public.minutes') }}</span>
                <input type="text" name="ajax[{{ !empty($session) ? $session->id : 'new' }}][duration]" class="js-ajax-duration form-control" value="{{ !empty($session) ? $session->duration : '' }}" {{ (!empty($session) and $session->session_api != 'local') ? 'disabled' :'' }}/>
                <div class="invalid-feedback"></div>
            </div>

            <div class="form-group js-local-link {{ (!empty($session) and in_array($session->session_api, ['agora', 'jitsi'])) ? 'd-none' : '' }}">
                <label class="form-group-label">{{ trans('public.link') }}</label>
                <span class="has-translation bg-transparent"><x-iconsax-lin-link class="text-gray-500" width="24px" height="24px"/></span>
                <input type="text" name="ajax[{{ !empty($session) ? $session->id : 'new' }}][link]" class="js-ajax-link form-control" value="{{ !empty($session) ? $session->getJoinLink() : '' }}" {{ (!empty($session) and $session->session_api != 'local') ? 'disabled' :'' }}/>
                <div class="invalid-feedback"></div>
            </div>

            <div class="form-group">
                <label class="form-group-label">{{ trans('public.description') }}</label>
                <span class="has-translation bg-gray-300 rounded-8 p-8"><x-iconsax-lin-translate class="icons text-gray-500"/></span>
                <textarea name="ajax[{{ !empty($session) ? $session->id : 'new' }}][description]" class="js-ajax-description form-control" rows="6">{{ !empty($session) ? $session->description : '' }}</textarea>
                <div class="invalid-feedback"></div>
            </div>

            @if(!empty(getFeaturesSettings('extra_time_to_join_status')) and getFeaturesSettings('extra_time_to_join_status'))
                <div class="form-group">
                    <label class="form-group-label">{{ trans('update.extra_time_to_join') }}</label>
                    <span class="has-translation bg-gray-100 font-14 text-gray-500 w-auto px-4">{{ trans('public.minutes') }}</span>
                    <input type="text" name="ajax[{{ !empty($session) ? $session->id : 'new' }}][extra_time_to_join]" value="{{ (!empty($session) and $session->extra_time_to_join) ? $session->extra_time_to_join : getFeaturesSettings('extra_time_to_join_default_value') }}" class="js-ajax-extra_time_to_join form-control" placeholder=""/>
                    <div class="invalid-feedback"></div>
                </div>
            @elseif(!empty(getFeaturesSettings('extra_time_to_join_default_value')))
                <input type="hidden" name="ajax[{{ !empty($session) ? $session->id : 'new' }}][extra_time_to_join]" value="{{ (!empty($session) and $session->extra_time_to_join) ? $session->extra_time_to_join : getFeaturesSettings('extra_time_to_join_default_value') }}" class="js-ajax-extra_time_to_join form-control" placeholder=""/>
            @endif

            <div class="form-group d-flex align-items-center">
                <div class="custom-switch mr-8">
                    <input id="sessionStatusSwitch{{ !empty($session) ? $session->id : '_record' }}" type="checkbox" name="ajax[{{ !empty($session) ? $session->id : 'new' }}][status]" class="custom-control-input" {{ (empty($session) or $session->status == \App\Models\Session::$Active) ? 'checked' : ''  }}>
                    <label class="custom-control-label cursor-pointer" for="sessionStatusSwitch{{ !empty($session) ? $session->id : '_record' }}"></label>
                </div>

                <div class="">
                    <label class="cursor-pointer" for="sessionStatusSwitch{{ !empty($session) ? $session->id : '_record' }}">{{ trans('public.active') }}</label>
                </div>
            </div>

            @if(!empty(getAttendanceSettings("status")))
                <div class="form-group d-flex align-items-center">
                    <div class="custom-switch mr-8">
                        <input id="sessionAttendanceStatusSwitch{{ !empty($session) ? $session->id : '_record' }}" type="checkbox" name="ajax[{{ !empty($session) ? $session->id : 'new' }}][enable_attendance]" class="custom-control-input" {{ (!empty($session) and $session->enable_attendance) ? 'checked' : ''  }}>
                        <label class="custom-control-label cursor-pointer" for="sessionAttendanceStatusSwitch{{ !empty($session) ? $session->id : '_record' }}"></label>
                    </div>

                    <div class="">
                        <label class="cursor-pointer" for="sessionAttendanceStatusSwitch{{ !empty($session) ? $session->id : '_record' }}">{{ trans('update.enable_attendance') }}</label>
                    </div>
                </div>
            @endif

            <div class="js-agora-chat-and-rec  {{ (empty($session) or $session->session_api !== 'agora') ? 'd-none' : '' }}">
                @if(getFeaturesSettings('agora_chat'))
                    <div class="form-group d-flex align-items-center">
                        <div class="custom-switch mr-8">
                            <input id="sessionAgoraChatSwitch{{ !empty($session) ? $session->id : '_record' }}" type="checkbox" name="ajax[{{ !empty($session) ? $session->id : 'new' }}][agora_chat]" class="custom-control-input" {{ (!empty($session) and !empty($session->agora_settings) and $session->agora_settings->chat) ? 'checked' : ''  }}>
                            <label class="custom-control-label cursor-pointer" for="sessionAgoraChatSwitch{{ !empty($session) ? $session->id : '_record' }}"></label>
                        </div>

                        <div class="">
                            <label class="cursor-pointer" for="sessionAgoraChatSwitch{{ !empty($session) ? $session->id : '_record' }}">{{ trans('update.chat') }}</label>
                        </div>
                    </div>
                @endif
            </div>

            @if(getFeaturesSettings('sequence_content_status'))
                <div class="form-group d-flex align-items-center">
                    <div class="custom-switch mr-8">
                        <input id="sessionSequenceContentSwitch{{ !empty($session) ? $session->id : '_record' }}" type="checkbox" name="ajax[{{ !empty($session) ? $session->id : 'new' }}][sequence_content]" class="js-sequence-content-switch custom-control-input" {{ (!empty($session) and ($session->check_previous_parts or !empty($session->access_after_day))) ? 'checked' : ''  }}>
                        <label class="custom-control-label cursor-pointer" for="sessionSequenceContentSwitch{{ !empty($session) ? $session->id : '_record' }}"></label>
                    </div>

                    <div class="">
                        <label class="cursor-pointer" for="sessionSequenceContentSwitch{{ !empty($session) ? $session->id : '_record' }}">{{ trans('update.sequence_content') }}</label>
                    </div>
                </div>

                <div class="js-sequence-content-inputs pl-4 {{ (!empty($session) and ($session->check_previous_parts or !empty($session->access_after_day))) ? '' : 'd-none' }}">
                    <div class="form-group d-flex align-items-center">
                        <div class="custom-switch mr-8">
                            <input id="checkPreviousPartsSwitch{{ !empty($session) ? $session->id : '_record' }}" type="checkbox" name="ajax[{{ !empty($session) ? $session->id : 'new' }}][check_previous_parts]" class="custom-control-input" {{ (empty($session) or $session->check_previous_parts) ? 'checked' : ''  }}>
                            <label class="custom-control-label cursor-pointer" for="checkPreviousPartsSwitch{{ !empty($session) ? $session->id : '_record' }}"></label>
                        </div>

                        <div class="">
                            <label class="cursor-pointer" for="checkPreviousPartsSwitch{{ !empty($session) ? $session->id : '_record' }}">{{ trans('update.check_previous_parts') }}</label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-group-label">{{ trans('update.access_after_day') }}</label>
                        <input type="number" name="ajax[{{ !empty($session) ? $session->id : 'new' }}][access_after_day]" value="{{ (!empty($session)) ? $session->access_after_day : '' }}" class="js-ajax-access_after_day form-control" placeholder="{{ trans('update.access_after_day_placeholder') }}"/>
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
            @endif

            <div class="mt-20 d-flex align-items-center justify-content-end">
                <button type="button" class="js-save-course-content btn btn-lg btn-primary">{{ trans('public.save') }}</button>

                @if(!empty($session))
                    @if(!$session->isFinished())
                        <a href="{{ $session->getJoinLink(true) }}" target="_blank" class="ml-12 btn btn-lg btn-accent">{{ trans('footer.join') }}</a>
                    @else
                        <button type="button" class="js-session-has-ended ml-12 btn btn-lg btn-accent disabled">{{ trans('footer.join') }}</button>
                    @endif
                @endif

                @if(empty($session))
                    <button type="button" class="btn btn-lg btn-danger ml-12 cancel-accordion">{{ trans('public.close') }}</button>
                @endif
            </div>
        </div>

    </div>
</li>
