<div class="form-group">
    <label class="form-group-label bg-white">{{ trans('update.user_role') }}</label>

    <select name="contents[specific_buttons][{{ !empty($itemKey) ? $itemKey : 'record' }}][user_role]" class="form-control bg-white">
        <option value="">{{ trans('update.select_user_role') }}</option>
        <option value="for_guest" {{ (!empty($selectedUserRoleId) and $selectedUserRoleId == 'for_guest') ? 'selected' : '' }}>{{ trans('update.guest') }}</option>

        @if(!empty($userRoles) and count($userRoles))
            @foreach($userRoles as $userRole)
                <option value="{{ $userRole->id }}" {{ (!empty($selectedUserRoleId) and $selectedUserRoleId == $userRole->id) ? 'selected' : '' }}>{{ $userRole->caption }}</option>
            @endforeach
        @endif
    </select>

    {{--<div class="mt-8 font-12 text-gray-500">{{ trans('empty_means_all_roles') }}</div>--}}
</div>


<x-landingBuilder-input
    label="{{ trans('update.button_title') }}"
    name="contents[specific_buttons][{{ !empty($itemKey) ? $itemKey : 'record' }}][title]"
    value="{{ (!empty($navbarButtonData) and !empty($navbarButtonData['title'])) ? $navbarButtonData['title'] : '' }}"
    placeholder=""
    hint=""
    className=""
/>

<x-landingBuilder-input
    label="{{ trans('panel.url') }}"
    name="contents[specific_buttons][{{ !empty($itemKey) ? $itemKey : 'record' }}][url]"
    value="{{ (!empty($navbarButtonData) and !empty($navbarButtonData['url'])) ? $navbarButtonData['url'] : '' }}"
    placeholder=""
    hint=""
    icon="link-1"
    className=""
/>

<x-landingBuilder-icons-select
    label="{{ trans('update.icon') }}"
    name="contents[specific_buttons][{{ !empty($itemKey) ? $itemKey : 'record' }}][icon]"
    value="{{ (!empty($navbarButtonData) and !empty($navbarButtonData['icon'])) ? $navbarButtonData['icon'] : '' }}"
    placeholder="{{ trans('update.search_icons') }}"
    hint=""
    selectClassName="{{ !empty($itemKey) ? 'js-icons-select2' : 'js-make-icons-select2' }}"
    className=""
/>


<div class="d-flex align-items-center justify-content-end mt-24">
    <button type="button" class="js-addable-accordion-remove-btn btn btn-danger btn-lg">{{ trans('public.delete') }}</button>
</div>

