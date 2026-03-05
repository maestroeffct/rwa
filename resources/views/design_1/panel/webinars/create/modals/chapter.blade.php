<div class="js-content-form chapter-form mt-16" data-action="/panel/chapters/{{ !empty($chapter) ? $chapter->id.'/update' : 'store' }}">

    @if(empty($chapter))
        <div class="d-flex-center flex-column mt-12 mb-24">
            <div class="d-flex-center size-64 rounded-16 bg-primary">
                <x-iconsax-bul-category class="icons text-white" width="32px" height="32px"/>
            </div>

            <h4 class="font-14 font-weight-bold mt-12">{{ trans('update.new_course_chapter') }}</h4>
            <p class="font-12 text-gray-500 mt-4">{{ trans('update.create_a_new_section_and_include_different_materials') }}</p>
        </div>
    @endif

    <input type="hidden" name="ajax[chapter][webinar_id]" class="js-chapter-webinar-id" value="{{ !empty($chapter) ? $chapter->webinar_id : '' }}">
    {{--<input type="hidden" name="ajax[chapter][type]" class="js-chapter-type" value="">--}}

    @include('design_1.panel.includes.locale.locale_select',[
        'itemRow' => !empty($chapter) ? $chapter : null,
        'withoutReloadLocale' => true,
        'extraClass' => 'js-webinar-content-locale',
        'extraData' => "data-webinar-id='".(!empty($chapter) ? $chapter->webinar_id : '')."'  data-id='".(!empty($chapter) ? $chapter->id : '')."'  data-relation='chapters' data-fields='title'"
    ])

    <div class="form-group">
        <label class="form-group-label">{{ trans('public.chapter_title') }}</label>
        <span class="has-translation bg-gray-300 rounded-8 p-8"><x-iconsax-lin-translate class="icons text-gray-500"/></span>
        <input type="text" name="ajax[chapter][title]" class="js-ajax-title form-control" value="{{ !empty($chapter) ? $chapter->title : '' }}"/>
        <span class="invalid-feedback"></span>
    </div>

    <div class="form-group d-flex align-items-center">
        <div class="custom-switch mr-8">
            <input id="statusSwitch" type="checkbox" name="ajax[chapter][status]" class="custom-control-input" {{ (!empty($chapter) and $chapter->status == \App\Models\WebinarChapter::$chapterActive) ? 'checked' :  '' }}>
            <label class="custom-control-label cursor-pointer" for="statusSwitch"></label>
        </div>

        <div class="">
            <label class="cursor-pointer" for="statusSwitch">{{ trans('public.active') }}</label>
        </div>
    </div>

    @if(getFeaturesSettings('sequence_content_status'))
        <div class="form-group d-flex align-items-center">
            <div class="custom-switch mr-8">
                <input id="checkAllContentsPassSwitch_record" type="checkbox" name="ajax[chapter][check_all_contents_pass]" class="custom-control-input" {{ (!empty($chapter) and $chapter->check_all_contents_pass) ? 'checked' :  '' }}>
                <label class="custom-control-label cursor-pointer" for="checkAllContentsPassSwitch_record"></label>
            </div>

            <div class="">
                <label class="cursor-pointer" for="checkAllContentsPassSwitch_record">{{ trans('update.check_all_contents_pass') }}</label>
            </div>
        </div>
    @endif
</div>
