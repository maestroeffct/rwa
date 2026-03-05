<div class="d-flex-center size-120 rounded-circle bg-primary-20">
    @if($type == "quiz_certificate")
        <x-iconsax-bul-award class="icons text-primary" width="64px" height="64px"/>
    @elseif($type == "course_certificate")
        <x-iconsax-bul-teacher class="icons text-primary" width="64px" height="64px"/>
    @endif
</div>

<h3 class="mt-12 font-16 text-dark">
    @if($type == "quiz_certificate")
        {{ trans('update.quiz_certificate') }}
    @elseif($type == "course_certificate")
        {{ trans('update.course_completion_certificate') }}
    @endif
</h3>

@if(!empty($participatedUsers) and count($participatedUsers))
    <div class="d-flex align-items-center overlay-avatars overlay-avatars-16 mt-44">
        @foreach($participatedUsers as $participatedUser)
            <div class="overlay-avatars__item size-40 rounded-circle bg-gray-100">
                <img src="{{ $participatedUser->getAvatar(40) }}" alt="{{ $participatedUser->full_name }}" class="img-cover rounded-circle">
            </div>
        @endforeach

        @if((count($participatedUsersIds) - 4) > 0)
            <div class="overlay-avatars__count size-40 rounded-circle d-flex-center font-12 text-gray-500 border-4 border-gray-100 bg-gray-200">
                +{{ (count($participatedUsersIds) - 4) }}
            </div>
        @endif
    </div>
@endif

@if(count($participatedUsersIds))
    <div class="mt-8 font-12 font-weight-bold">{{ count($participatedUsersIds) }}</div>
    <div class="mt-4 font-12 text-gray-500">{{ trans('update.students_got_this_certificate') }}</div>
@endif

<a href="" target="_blank" class="btn btn-primary btn-lg mt-24">{{ trans('update.view_student_certificates') }}</a>
