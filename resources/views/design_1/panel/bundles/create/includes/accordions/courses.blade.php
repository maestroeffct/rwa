<li data-id="{{ !empty($bundleWebinar) ? $bundleWebinar->id :'' }}" class="accordion bg-white rounded-15 p-16 border-gray-200 mt-16">
    <div class="accordion__title d-flex align-items-center justify-content-between" role="tab" id="bundleWebinar_{{ !empty($bundleWebinar) ? $bundleWebinar->id :'record' }}">
        <div class="font-weight-bold font-14 cursor-pointer" href="#collapseBundleWebinar{{ !empty($bundleWebinar) ? $bundleWebinar->id :'record' }}" data-parent="#bundleWebinarsAccordion" role="button" data-toggle="collapse">
            <span>{{ (!empty($bundleWebinar) and !empty($bundleWebinar->webinar)) ? $bundleWebinar->webinar->title : trans('update.add_new_course') }}</span>
        </div>

        @if(!empty($bundleWebinar))
            <div class="d-flex align-items-center">
                <span class="move-icon mr-8 cursor-pointer d-flex text-gray-500"><x-iconsax-lin-arrow-3 class="icons" width="18"/></span>

                <div class="actions-dropdown position-relative mr-12">
                    <button type="button" class="btn-transparent d-flex align-items-center justify-content-center">
                        <x-iconsax-lin-more class="icons text-gray-500" width="18"/>
                    </button>

                    <div class="actions-dropdown__dropdown-menu">
                        <ul class="my-8">
                            <li class="actions-dropdown__dropdown-menu-item">
                                <a href="/panel/bundle-webinars/{{ $bundleWebinar->id }}/delete" class="delete-action text-danger">{{ trans('public.delete') }}</a>
                            </li>
                        </ul>
                    </div>
                </div>

                <span class="collapse-arrow-icon d-flex cursor-pointer" href="#collapseBundleWebinar{{ !empty($bundleWebinar) ? $bundleWebinar->id :'record' }}" data-parent="#bundleWebinarsAccordion" role="button" data-toggle="collapse">
                    <x-iconsax-lin-arrow-up-1 class="icons text-gray-500" width="18"/>
                </span>
            </div>
        @endif

    </div>

    <div id="collapseBundleWebinar{{ !empty($bundleWebinar) ? $bundleWebinar->id :'record' }}" class="accordion__collapse {{ empty($bundleWebinar) ? 'show' : '' }}" role="tabpanel">
        <div class="js-content-form js-bundle-webinars-form" data-action="/panel/bundle-webinars/{{ !empty($bundleWebinar) ? $bundleWebinar->id . '/update' : 'store' }}">
            <input type="hidden" name="ajax[{{ !empty($bundleWebinar) ? $bundleWebinar->id : 'new' }}][bundle_id]" value="{{ !empty($bundle) ? $bundle->id :'' }}">

            <div class="form-group mt-20">
                <label class="form-group-label">{{ trans('panel.select_course') }}</label>

                <select name="ajax[{{ !empty($bundleWebinar) ? $bundleWebinar->id : 'new' }}][webinar_id]" class="js-ajax-webinar_id form-control select2" data-allow-clear="false" data-placeholder="{{ trans('update.search_courses') }}">
                    <option value="">{{ trans('panel.select_course') }}</option>

                    @if(!empty($webinars))
                        @foreach($webinars as $webinar)
                            <option value="{{ $webinar->id }}" {{ (!empty($bundleWebinar) and $bundleWebinar->webinar_id == $webinar->id) ? 'selected' : '' }}>{{ $webinar->title }}</option>
                        @endforeach
                    @endif
                </select>
                <div class="invalid-feedback"></div>

                <div class="mt-8">
                    <p class="font-12 text-gray-500">- {{ trans('update.bundle_webinars_required_hint') }}</p>
                </div>
            </div>


            <div class="mt-30 d-flex align-items-center">
                <button type="button" class="js-save-course-content btn btn-primary">{{ trans('public.save') }}</button>

                @if(!empty($bundleWebinar))
                    <a href="/panel/bundle-webinars/{{ $bundleWebinar->id }}/delete" class="delete-action btn btn-outline-danger ml-8 cancel-accordion">{{ trans('delete') }}</a>
                @endif
            </div>
        </div>
    </div>
</li>
