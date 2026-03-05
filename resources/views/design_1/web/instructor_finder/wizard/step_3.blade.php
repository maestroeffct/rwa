<div class="">

    <div class="d-inline-flex-center py-4 px-8 bg-gray-100 rounded-32 text-gray-500">{{ trans('update.step') }} 3/4</div>
    <h3 class="mt-8 font-24 font-weight-bold">{{ trans('update.tutoring_level') }} 📈</h3>

    <div class="d-flex align-items-center w-100 mt-4">
        <div class="instructor-finder-wizard__progress rounded-4 bg-gray-100 flex-1 mr-8">
            <div class="progressbar rounded-4 bg-success" style="width: 75%"></div>
        </div>

        <div class="d-flex-center size-32 bg-gray-100 rounded-circle">
            <x-iconsax-lin-teacher class="icons text-gray-500" width="16px" height="16px"/>
        </div>
    </div>

    <div class="mt-32 text-gray-500">{{ trans('update.which_skill_level_do_you_want_to_learn') }}</div>

    <div class="form-group mt-28">
        <label class="form-group-label">{{ trans('update.skill_level') }}</label>

        <select name="level_of_training" class="form-control select2" data-minimum-results-for-search="Infinity">
            <option value="beginner" {{ (request()->get('level_of_training') == 'beginner') ? 'selected' : '' }}>{{ trans('update.beginner') }}</option>
            <option value="middle" {{ (empty(request()->get('level_of_training')) or request()->get('level_of_training') == 'middle') ? 'selected' : '' }}>{{ trans('update.middle') }}</option>
            <option value="expert" {{ (request()->get('level_of_training') == 'expert') ? 'selected' : '' }}>{{ trans('update.expert') }}</option>
        </select>
    </div>

</div>
