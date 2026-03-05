<li data-id="{{ !empty($speaker) ? $speaker->id :'' }}" class="accordion bg-white rounded-15 p-16 border-gray-200 mt-16">
    <div class="accordion__title d-flex align-items-center justify-content-between" role="tab" id="speaker_{{ !empty($speaker) ? $speaker->id :'record' }}">
        <div class="font-weight-bold font-14 cursor-pointer" href="#collapseSpeaker{{ !empty($speaker) ? $speaker->id :'record' }}" data-parent="#speakersAccordion" role="button" data-toggle="collapse">
            <span>{{ !empty($speaker) ? $speaker->name : trans('update.new_speaker') }}</span>
        </div>

        @if(!empty($speaker))
            <div class="d-flex align-items-center">
                <span class="move-icon mr-8 cursor-pointer d-flex text-gray-500"><x-iconsax-lin-arrow-3 class="icons" width="18"/></span>

                <div class="actions-dropdown position-relative mr-12">
                    <button type="button" class="btn-transparent d-flex align-items-center justify-content-center">
                        <x-iconsax-lin-more class="icons text-gray-500" width="18"/>
                    </button>

                    <div class="actions-dropdown__dropdown-menu">
                        <ul class="my-8">
                            <li class="actions-dropdown__dropdown-menu-item">
                                <a href="/panel/events/{{ $event->id }}/speakers/{{ $speaker->id }}/delete" class="delete-action text-danger">{{ trans('public.delete') }}</a>
                            </li>
                        </ul>
                    </div>
                </div>

                <span class="collapse-arrow-icon d-flex cursor-pointer" href="#collapseSpeaker{{ !empty($speaker) ? $speaker->id :'record' }}" data-parent="#speakersAccordion" role="button" data-toggle="collapse">
                    <x-iconsax-lin-arrow-up-1 class="icons text-gray-500" width="18"/>
                </span>
            </div>
        @endif

    </div>

    <div id="collapseSpeaker{{ !empty($speaker) ? $speaker->id :'record' }}" class="accordion__collapse {{ empty($speaker) ? 'show' : '' }}" role="tabpanel">
        <div class="js-content-form js-speaker-form" data-action="/panel/events/{{ $event->id }}/speakers/{{ !empty($speaker) ? $speaker->id . '/update' : 'store' }}">

            @include('design_1.panel.includes.locale.locale_select',[
                'itemRow' => !empty($speaker) ? $speaker : null,
                'withoutReloadLocale' => true,
                'className' => 'mt-24',
                'extraClass' => 'js-event-content-locale',
                'extraData' => "data-event-id='".(!empty($event) ? $event->id : '')."'  data-id='".(!empty($speaker) ? $speaker->id : '')."'  data-relation='speakers' data-fields='name,job,description'"
            ])

            <div class="form-group">
                <label class="form-group-label">{{ trans('public.name') }}</label>
                <span class="has-translation bg-gray-100 rounded-8 p-8"><x-iconsax-lin-translate class="icons text-gray-500"/></span>
                <input type="text" name="ajax[{{ !empty($speaker) ? $speaker->id : 'new' }}][name]" class="js-ajax-name form-control" value="{{ !empty($speaker) ? $speaker->name : '' }}"/>
                <div class="invalid-feedback"></div>
            </div>

            <div class="form-group">
                <label class="form-group-label">{{ trans('panel.job_title') }}</label>
                <span class="has-translation bg-gray-100 rounded-8 p-8"><x-iconsax-lin-translate class="icons text-gray-500"/></span>
                <input type="text" name="ajax[{{ !empty($speaker) ? $speaker->id : 'new' }}][job]" class="js-ajax-job form-control" value="{{ !empty($speaker) ? $speaker->job : '' }}"/>
                <div class="invalid-feedback"></div>
            </div>

            <div class="form-group">
                <label class="form-group-label">{{ trans('public.description') }}</label>
                <span class="has-translation bg-gray-100 rounded-8 p-8"><x-iconsax-lin-translate class="icons text-gray-500"/></span>
                <textarea type="text" name="ajax[{{ !empty($speaker) ? $speaker->id : 'new' }}][description]" class="js-ajax-description form-control" >{{ !empty($speaker) ? $speaker->description : '' }}</textarea>
                <div class="invalid-feedback"></div>
            </div>

            <div class="form-group ">
                <label class="form-group-label">{{ trans('public.image') }}</label>

                <div class="custom-file bg-white">
                    <input type="file" name="ajax[{{ !empty($speaker) ? $speaker->id : 'new' }}][image]" class="js-ajax-upload-file-input js-ajax-file_upload custom-file-input" data-upload-name="ajax[{{ !empty($speaker) ? $speaker->id : 'new' }}][image]" id="speaker_image_{{ !empty($speaker) ? $speaker->id :'record' }}">
                    <span class="custom-file-text">{{ (!empty($speaker) and !empty($speaker->image)) ? getFileNameByPath($speaker->image) : '' }}</span>
                    <label class="custom-file-label" for="speaker_image_{{ !empty($speaker) ? $speaker->id :'record' }}">{{ trans('update.browse') }}</label>
                </div>

                <div class="invalid-feedback d-block"></div>

                @if(!empty($speaker) and !empty($speaker->image))
                    <a href="{{ $speaker->image }}" target="_blank" class="font-12 text-primary mt-8">{{ trans('update.preview') }}</a>
                @endif
            </div>

            <div class="form-group">
                <label class="form-group-label">{{ trans('public.link') }}</label>
                <span class="has-translation"><x-iconsax-lin-link class="icons text-gray-500" width="20px" height="20px"/></span>
                <input type="text" name="ajax[{{ !empty($speaker) ? $speaker->id : 'new' }}][link]" class="js-ajax-link form-control" value="{{ !empty($speaker) ? $speaker->link : '' }}"/>
                <div class="invalid-feedback"></div>
            </div>

            <div class="form-group d-flex align-items-center">
                <div class="custom-switch mr-8">
                    <input id="enableSpeakerSwitch_{{ !empty($speaker) ? $speaker->id : 'new' }}" type="checkbox" name="ajax[{{ !empty($speaker) ? $speaker->id : 'new' }}][enable]" class="custom-control-input" {{ (!empty($speaker) and $speaker->enable) ? 'checked' : '' }}>
                    <label class="custom-control-label cursor-pointer" for="enableSpeakerSwitch_{{ !empty($speaker) ? $speaker->id : 'new' }}"></label>
                </div>

                <div class="">
                    <label class="cursor-pointer" for="enableSpeakerSwitch_{{ !empty($speaker) ? $speaker->id : 'new' }}">{{ trans('update.enable') }}</label>
                </div>
            </div>

            <div class="d-flex align-items-center justify-content-end">
                <button type="button" class="js-save-course-content btn btn-primary">{{ trans('public.save') }}</button>

                @if(!empty($speaker))
                    <a href="/panel/events/{{ $event->id }}/speakers/{{ $speaker->id }}/delete" class="delete-action btn btn-outline-danger ml-8 cancel-accordion">{{ trans('delete') }}</a>
                @endif
            </div>
        </div>
    </div>
</li>
