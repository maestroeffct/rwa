<li data-id="{{ !empty($extraDescription) ? $extraDescription->id :'' }}" class="accordion bg-white rounded-15 p-16 border-gray-200 mt-16">
    <div class="d-flex align-items-center justify-content-between " role="tab" id="{{ $extraDescriptionType }}_{{ !empty($extraDescription) ? $extraDescription->id :'record' }}">
        <div class="font-weight-bold text-dark-blue" href="#collapseExtraDescription{{ !empty($extraDescription) ? $extraDescription->id :'record' }}" aria-controls="collapseExtraDescription{{ !empty($extraDescription) ? $extraDescription->id :'record' }}" data-parent="#{{ $extraDescriptionParentAccordion }}" role="button" data-toggle="collapse" aria-expanded="true">
            @if(!empty($extraDescription) and !empty($extraDescription->value))
                <span>{{ truncate($extraDescription->value, 45) }}</span>
            @else
                <span>{{ trans('update.new_item') }}</span>
            @endif
        </div>

        @if(!empty($extraDescription))
            <div class="d-flex align-items-center">
                <span class="move-icon mr-8 cursor-pointer d-flex text-gray-500"><x-iconsax-lin-arrow-3 class="icons" width="18"/></span>

                <div class="actions-dropdown position-relative mr-12">
                    <button type="button" class="btn-transparent d-flex align-items-center justify-content-center">
                        <x-iconsax-lin-more class="icons text-gray-500" width="18"/>
                    </button>

                    <div class="actions-dropdown__dropdown-menu">
                        <ul class="my-8">
                            <li class="actions-dropdown__dropdown-menu-item">
                                <a href="/panel/webinar-extra-description/{{ $extraDescription->id }}/delete" class="delete-action text-danger">{{ trans('public.delete') }}</a>
                            </li>
                        </ul>
                    </div>
                </div>

                <span class="collapse-arrow-icon d-flex cursor-pointer" href="#collapseExtraDescription{{ !empty($extraDescription) ? $extraDescription->id :'record' }}" data-parent="#{{ $extraDescriptionParentAccordion }}" role="button" data-toggle="collapse">
                    <x-iconsax-lin-arrow-up-1 class="icons text-gray-500" width="18"/>
                </span>
            </div>
        @endif

    </div>

    <div id="collapseExtraDescription{{ !empty($extraDescription) ? $extraDescription->id :'record' }}" aria-labelledby="{{ $extraDescriptionType }}_{{ !empty($extraDescription) ? $extraDescription->id :'record' }}" class=" accordion__collapse @if(empty($extraDescription)) show @endif" role="tabpanel">
        <div class="js-content-form extra_description-form" data-action="/panel/webinar-extra-description/{{ !empty($extraDescription) ? $extraDescription->id . '/update' : 'store' }}">
            <input type="hidden" name="ajax[{{ !empty($extraDescription) ? $extraDescription->id : 'new' }}][upcoming_course_id]" value="{{ !empty($upcomingCourse) ? $upcomingCourse->id :'' }}">
            <input type="hidden" name="ajax[{{ !empty($extraDescription) ? $extraDescription->id : 'new' }}][type]" value="{{ $extraDescriptionType }}">

            <div class="row">
                <div class="col-12">

                    @include('design_1.panel.includes.locale.locale_select',[
                        'itemRow' => !empty($extraDescription) ? $extraDescription : null,
                        'withoutReloadLocale' => true,
                        'extraClass' => 'js-upcoming-course-content-locale',
                        'extraData' => "data-upcoming-course-id='".(!empty($upcomingCourse) ? $upcomingCourse->id : '')."'  data-id='".(!empty($extraDescription) ? $extraDescription->id : '')."'  data-relation='extraDescriptions' data-fields='value'"
                    ])

                    <div class="form-group">
                        <label class="form-group-label">{{ trans('public.title') }}</label>
                        <input type="text" name="ajax[{{ !empty($extraDescription) ? $extraDescription->id : 'new' }}][value]" class="js-ajax-value form-control" value="{{ !empty($extraDescription) ? $extraDescription->value : '' }}"/>
                        <div class="invalid-feedback"></div>
                    </div>

                </div>
            </div>

            <div class=" d-flex align-items-center">
                <button type="button" class="js-save-course-content btn btn-primary">{{ trans('public.save') }}</button>
            </div>
        </div>
    </div>
</li>
