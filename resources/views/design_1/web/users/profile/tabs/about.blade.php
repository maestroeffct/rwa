<div class="d-flex align-items-center mt-16 border-gray-200 rounded-16">

    <div class="flex-1 px-12 py-16 p-lg-20">
        <x-iconsax-bul-teacher class="icons text-accent" width="32px" height="32px"/>
        <div class="font-14 text-gray-500 mt-8">{{ trans('quiz.student') }}</div>
        <div class="font-16 font-weight-bold mt-4">{{ $user->getTeacherStudentsCount() ?? '-' }}</div>
    </div>

    <div class="flex-1 px-12 py-16 p-lg-20 border-left-gray-200">
        <x-iconsax-bul-video-play class="icons text-primary" width="32px" height="32px"/>
        <div class="font-14 text-gray-500 mt-8">{{ trans('update.courses') }}</div>
        <div class="font-16 font-weight-bold mt-4">{{ $coursesCount ?? '-' }}</div>
    </div>

    <div class="flex-1 px-12 py-16 p-lg-20 border-left-gray-200">
        <x-iconsax-bul-note-2 class="icons text-success" width="32px" height="32px"/>
        <div class="font-14 text-gray-500 mt-8">{{ trans('update.articles') }}</div>
        <div class="font-16 font-weight-bold mt-4">{{ $user->reviewsCount() ?? '-' }}</div>
    </div>

    <div class="flex-1 px-12 py-16 p-lg-20 border-left-gray-200">
        <x-iconsax-bul-calendar-2 class="icons text-warning" width="32px" height="32px"/>
        <div class="font-14 text-gray-500 mt-8">{{ trans('panel.meetings') }}</div>
        <div class="font-16 font-weight-bold mt-4">{{ $appointments ?? '-' }}</div>
    </div>

</div>


<div class="row">
    @if(!empty($user->about))
        <div class="col-12 col-md-6 mt-24">
            <h4 class="font-16 font-weight-bold">{{ trans('update.about_me') }}</h4>
            <div class="mt-12 text-gray-500">{!! nl2br($user->about) !!}</div>
        </div>
    @endif

    @if(!empty($user->profile_video))
        <div class="col-12 col-md-6 mt-24">
            @push('styles_top')
                <link rel="stylesheet" href="/assets/vendors/plyr.io/plyr.min.css">
            @endpush

            @push('scripts_bottom')
                <script src="/assets/vendors/plyr.io/plyr.min.js"></script>
            @endpush

            <div class="profile-video-card">
                <video class="js-init-plyr-io plyr-io-video" controls preload="auto" width="100%">
                    <source src="{{ $user->profile_video }}" type="video/mp4"/>
                </video>
            </div>
        </div>
    @endif

    @if(!empty($educations) and !$educations->isEmpty())
        <div class="col-12 col-md-6 mt-24">
            <h4 class="font-16 font-weight-bold">{{ trans('site.education') }}</h4>

            @foreach($educations as $education)
                <div class="profile-education-card d-flex align-items-center {{ $loop->first ? 'mt-12' : 'mt-16' }}">
                    <div class="d-flex-center size-40 rounded-12 bg-gray-100">
                        <x-iconsax-bul-teacher class="icons text-gray-500" width="24px" height="24px"/>
                    </div>
                    <div class="ml-8 text-gray-500">{{ $education->value }}</div>
                </div>
            @endforeach
        </div>
    @endif

    @if(!empty($experiences) and !$experiences->isEmpty())
        <div class="col-12 col-md-6 mt-24">
            <h4 class="font-16 font-weight-bold">{{ trans('site.experiences') }}</h4>

            @foreach($experiences as $experience)
                <div class="profile-education-card d-flex align-items-center {{ $loop->first ? 'mt-12' : 'mt-16' }}">
                    <div class="d-flex-center size-40 rounded-12 bg-gray-100">
                        <x-iconsax-bul-briefcase class="icons text-gray-500" width="24px" height="24px"/>
                    </div>
                    <div class="ml-8 text-gray-500">{{ $experience->value }}</div>
                </div>
            @endforeach
        </div>
    @endif

    @if(!empty($occupations) and !$occupations->isEmpty())
        <div class="col-12 mt-24">
            <h4 class="font-16 font-weight-bold">{{ trans('update.skills_&_interests') }}</h4>

            <div class="d-flex align-items-center mt-8 gap-12 flex-wrap">
                @foreach($occupations as $occupation)
                    <div class="bg-gray-100 p-10 rounded-8 font-12 text-gray-500">{{ $occupation->category->title }}</div>
                @endforeach
            </div>
        </div>
    @endif
</div>
