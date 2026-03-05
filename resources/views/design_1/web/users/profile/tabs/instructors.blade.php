@if(!empty($instructors) and $instructors->isNotEmpty())
    <div id="profileInstructorsRow" class="row">
        @foreach($instructors as $instructor)
            @include('design_1.web.users.profile.tabs.components.instructor',['instructor' => $instructor])
        @endforeach
    </div>

    @if(!empty($hasMoreInstructors))
        <div class="d-flex-center mt-16">
            <div class="js-profile-tab-load-more-btn btn border-dashed border-gray-300 rounded-12 bg-white bg-hover-gray-100 cursor-pointer" data-path="/users/{{ $user->getUsername() }}/get-instructors" data-el="profileInstructorsRow">
                <x-iconsax-lin-rotate-left class="icons text-gray-500" width="16px" height="16px"/>
                <span class="ml-4 text-gray-500">{{ trans('update.load_more') }}</span>
            </div>
        </div>
    @endif
@else
    @include('design_1.panel.includes.no-result',[
        'file_name' => 'profile_instructors.svg',
        'title' => trans('update.user_profile_not_have_instructors'),
        'hint' => trans('update.user_profile_not_have_instructors_hint'),
        'extraClass' => 'mt-0',
    ])
@endif
