<form id="eventPrerequisitesForm" action="{{ getAdminPanelUrl("/events/{$event->id}/prerequisites/") }}{{ !empty($prerequisite) ? ($prerequisite->id.'/update') : 'store' }}" method="post">

    <div class="form-group">
        <label class="">{{ trans('admin/main.course') }}</label>
        <select name="prerequisite" class="js-ajax-prerequisite form-control bg-white js-make-search-select2"
                data-allow-clear="false"
                data-placeholder="{{ trans('update.search_courses') }}"
                data-dropdown-parent=".js-custom-modal"
                data-api-path="{{ getAdminPanelUrl("/webinars/search") }}"
                data-item-column-name="title"
                data-option=""
                data-webinar-id=""
        >
            @if(!empty($prerequisite))
                <option value="{{ $prerequisite->prerequisite_id }}" selected>{{ $prerequisite->course->title }}</option>
            @endif
        </select>
        <div class="invalid-feedback d-block"></div>
    </div>

    <div class="form-group mt-30 d-flex align-items-center ">
        <label class="cursor-pointer mr-16" for="requiredPrerequisiteSwitch">{{ trans('public.required') }}</label>
        <div class="custom-control custom-switch">
            <input type="checkbox" name="required" class="custom-control-input" id="requiredPrerequisiteSwitch" {{ (!empty($prerequisite) and $prerequisite->required) ? 'checked' : ''  }}>
            <label class="custom-control-label" for="requiredPrerequisiteSwitch"></label>
        </div>
    </div>

</form>

