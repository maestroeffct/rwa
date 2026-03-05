<li data-id="{{ !empty($prerequisite) ? $prerequisite->id :'' }}" class="accordion bg-white rounded-15 p-16 border-gray-200 mt-16">
    <div class="accordion__title d-flex align-items-center justify-content-between" role="tab" id="prerequisite_{{ !empty($prerequisite) ? $prerequisite->id :'record' }}">
        <div class="font-weight-bold font-14 cursor-pointer" href="#collapsePrerequisite{{ !empty($prerequisite) ? $prerequisite->id :'record' }}" data-parent="#prerequisitesAccordion" role="button" data-toggle="collapse">
            <span>{{ (!empty($prerequisite) and !empty($prerequisite->course)) ? $prerequisite->course->title .' - '. $prerequisite->course->teacher->full_name : trans('public.add_new_prerequisites') }}</span>
        </div>

        @if(!empty($prerequisite))
            <div class="d-flex align-items-center">
                {{--<span class="move-icon mr-8 cursor-pointer d-flex text-gray-500"><x-iconsax-lin-arrow-3 class="icons" width="18"/></span>--}}

                <div class="actions-dropdown position-relative mr-12">
                    <button type="button" class="btn-transparent d-flex align-items-center justify-content-center">
                        <x-iconsax-lin-more class="icons text-gray-500" width="18"/>
                    </button>

                    <div class="actions-dropdown__dropdown-menu">
                        <ul class="my-8">
                            <li class="actions-dropdown__dropdown-menu-item">
                                <a href="/panel/prerequisites/{{ $prerequisite->id }}/delete" class="delete-action text-danger">{{ trans('public.delete') }}</a>
                            </li>
                        </ul>
                    </div>
                </div>

                <span class="collapse-arrow-icon d-flex cursor-pointer" href="#collapsePrerequisite{{ !empty($prerequisite) ? $prerequisite->id :'record' }}" data-parent="#prerequisitesAccordion" role="button" data-toggle="collapse">
                    <x-iconsax-lin-arrow-up-1 class="icons text-gray-500" width="18"/>
                </span>
            </div>
        @endif

    </div>

    <div id="collapsePrerequisite{{ !empty($prerequisite) ? $prerequisite->id :'record' }}" class="accordion__collapse {{ empty($prerequisite) ? 'show' : '' }}" role="tabpanel">
        <div class="js-content-form js-prerequisite-form" data-action="/panel/prerequisites/{{ !empty($prerequisite) ? $prerequisite->id . '/update' : 'store' }}">
            <input type="hidden" name="ajax[{{ !empty($prerequisite) ? $prerequisite->id : 'new' }}][event_id]" value="{{ !empty($event) ? $event->id :'' }}">

            <div class="form-group mt-20">
                <label class="form-group-label">{{ trans('public.select_prerequisites') }}</label>

                <select name="ajax[{{ !empty($prerequisite) ? $prerequisite->id : 'new' }}][prerequisite_id]" class="js-ajax-prerequisite_id form-control searchable-select bg-white" data-allow-clear="false" data-placeholder="{{ trans('public.search_prerequisites') }}"
                        data-api-path="/panel/courses/search"
                        data-item-column-name="title"
                        data-option=""
                        data-webinar-id=""
                >
                    @if(!empty($prerequisite) and !empty($prerequisite->course))
                        <option selected value="{{ $prerequisite->course->id }}">{{ $prerequisite->course->title .' - '. $prerequisite->course->teacher->full_name }}</option>
                    @endif
                </select>
                <div class="invalid-feedback"></div>
            </div>

            <div class="form-group d-flex align-items-center">
                <div class="custom-switch mr-8">
                    <input id="requiredPrerequisitesSwitch{{ !empty($prerequisite) ? $prerequisite->id : 'record' }}" type="checkbox" name="ajax[{{ !empty($prerequisite) ? $prerequisite->id : 'new' }}][required]" class="custom-control-input" {{ (!empty($prerequisite) and $prerequisite->required) ? 'checked' : ''  }}>
                    <label class="custom-control-label cursor-pointer" for="requiredPrerequisitesSwitch{{ !empty($prerequisite) ? $prerequisite->id : 'record' }}"></label>
                </div>

                <div class="">
                    <label class="cursor-pointer" for="requiredPrerequisitesSwitch{{ !empty($prerequisite) ? $prerequisite->id : 'record' }}">{{ trans('public.required') }}</label>
                </div>
            </div>

            <div class="mt-30 d-flex align-items-center">
                <button type="button" class="js-save-course-content btn btn-primary">{{ trans('public.save') }}</button>

                @if(!empty($prerequisite))
                    <a href="/panel/prerequisites/{{ $prerequisite->id }}/delete" class="delete-action btn btn-outline-danger ml-8 cancel-accordion">{{ trans('delete') }}</a>
                @endif
            </div>
        </div>
    </div>
</li>
