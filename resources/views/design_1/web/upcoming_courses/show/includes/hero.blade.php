<div class="course-hero d-flex flex-column justify-content-end rounded-32 px-20 bg-gray-200">
    <div class="course-hero__mask rounded-32"></div>

    <img src="{{ $upcomingCourse->getImageCover() }}" class="course-hero__cover-img img-cover rounded-32" alt="{{ $upcomingCourse->title }}"/>

    <div class="course-hero__content position-relative z-index-3">
        @if(!empty($upcomingCourse->category))
            <div class="d-flex align-items-center text-white opacity-50">
                <a href="/classes" class="text-white">{{ trans('update.courses') }}</a>
                <x-iconsax-lin-arrow-right-1 class="icons text-white mx-2" width="16px" height="16px"/>
                <a href="{{ $upcomingCourse->category->getUrl() }}" class="text-white">{{ $upcomingCourse->category->title }}</a>
            </div>
        @endif

        <div class="d-flex align-items-center flex-wrap gap-12 mt-4">
            <h1 class="course-hero__title font-32 font-weight-bold text-white text-ellipsis">{{ clean($upcomingCourse->title, 't') }}</h1>

            {{-- Badges --}}
            <div class="d-flex align-items-center gap-12">
                {{-- Featured --}}

                <div class="d-flex-center p-4 pr-8 rounded-32 bg-success">
                    <x-iconsax-bul-verify class="icons text-white" width="20px" height="20px"/>
                    <span class="ml-4 font-12 text-white">Featured</span>
                </div>
                {{-- Top Seller --}}
                <div class="d-flex-center p-4 pr-8 rounded-32 bg-accent">
                    <x-iconsax-bul-moneys class="icons text-white" width="20px" height="20px"/>
                    <span class="ml-4 font-12 text-white">Top Seller</span>
                </div>
            </div>
        </div>

        @if(!empty($upcomingCourse->summary))
            <div class="mt-8 text-white opacity-50">{!! nl2br($upcomingCourse->summary) !!}</div>
        @endif

        <div class="d-flex align-items-center flex-wrap gap-24 mt-12">

            {{-- Created By --}}
            <div class="d-flex align-items-center font-12 text-white">
                <x-iconsax-lin-profile class="icons text-white" width="16px" height="16px"/>
                <span class="mx-4 opacity-50">{{ trans('public.created_by') }}</span>
                <a href="{{ $upcomingCourse->teacher->getProfileUrl() }}" target="_blank" class="text-white font-14 font-weight-bold">{{ $upcomingCourse->teacher->full_name }}</a>
            </div>

            {{-- Lectures --}}
            @if(!empty($upcomingCourse->parts))
                <div class="d-flex align-items-center font-12 text-white">
                    <x-iconsax-lin-note-2 class="icons text-white" width="16px" height="16px"/>
                    <span class="mx-4 font-weight-bold">{{ $upcomingCourse->parts }}</span>
                    <span class="opacity-50">{{ trans('update.lectures') }}</span>
                </div>
            @endif
        </div>

        @if(!empty($followingUsers) and count($followingUsers))
            <div class="d-flex align-items-center mt-20">
                <div class="d-flex align-items-center overlay-avatars">
                    @foreach($followingUsers as $followingUser)
                        <div class="overlay-avatars__item size-40 rounded-circle border-white border-2">
                            <img src="{{ $followingUser->user->getAvatar(40) }}" alt="{{ $followingUser->user->full_name }}" class="img-cover rounded-circle">
                        </div>
                    @endforeach

                    @if(($followingUsersCount - count($followingUsers)) > 0)
                        <div class="overlay-avatars__count size-40 rounded-circle d-flex align-items-center justify-content-center font-12 text-gray-500 bg-gray-100 border-white border-2">
                            +{{ $followingUsersCount - $followingUsers->count() }}
                        </div>
                    @endif
                </div>

                <div class="ml-8">
                    <span class="d-block font-14 font-weight-bold text-white">{{ $followingUsersCount }} {{ trans('panel.users') }}</span>
                    <span class="d-block font-12 text-white opacity-50">{{ trans('update.are_following_this_upcoming_course') }}</span>
                </div>
            </div>
        @endif

    </div>
</div>
