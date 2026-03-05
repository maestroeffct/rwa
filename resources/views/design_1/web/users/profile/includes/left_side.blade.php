<div class="profile-card-has-mask bg-white py-16 rounded-24 w-100">
    <div class="d-flex-center flex-column text-center px-16">

        <div class="profile-avatar-card size-80 rounded-circle mt-32">
            <img src="{{ $user->getAvatar(80) }}" alt="{{ $user->full_name }}" class="img-cover rounded-circle">

            @if($user->verified)
                <div class="profile-avatar-card__verified-badge d-flex-center rounded-circle size-16 p-2 bg-primary" data-tippy-content="{{ trans('public.verified') }}">
                    <x-tick-icon class="icons text-white"/>
                </div>
            @endif
        </div>

        <h4 class="mt-16 font-16 font-weight-bold">{{ $user->full_name }}</h4>

        @include('design_1.web.components.rate', ['rate' => $userRates['rate'], 'rateCount' => $userRates['count'], 'rateClassName' => 'mt-8'])

        <div class="profile-followers-card position-relative d-flex align-items-center justify-content-around mt-16 w-100">
            <div class="flex-1 text-center">
                <span class="js-user-profile-followers-count d-block font-14 font-weight-bold">{{ shortNumbers($userFollowers->count()) }}</span>
                <span class="d-block mt-4 font-12 text-gray-500">{{ trans('panel.followers') }}</span>
            </div>

            <div class="flex-1 text-center">
                <span class="d-block font-14 font-weight-bold">{{ shortNumbers($userFollowing->count()) }}</span>
                <span class="d-block mt-4 font-12 text-gray-500">{{ trans('panel.following') }}</span>
            </div>
        </div>

        <div class="d-flex align-items-center gap-12 mt-16 w-100">
            <button type="button" id="followToggle" data-user-id="{{ $user->username }}" class="btn btn-{{ (!empty($authUserIsFollower)) ? 'danger' : 'primary' }} btn-lg flex-1">
                @if(!empty($authUserIsFollower))
                    {{ trans('panel.unfollow') }}
                @else
                    {{ trans('panel.follow') }}
                @endif
            </button>

            @if($user->public_message)
                <button type="button" class="js-send-message d-flex-center size-48 rounded-12 border-2 border-gray-400 bg-white bg-hover-gray-100" data-path="/users/{{ $user->getUsername() }}/get-send-message-form" data-tippy-content="{{ trans('site.send_message') }}">
                    <x-iconsax-lin-sms class="icons text-gray-500" width="24px" height="24px"/>
                </button>
            @endif
        </div>

        @php
            $socials = getSocials();
            if (!empty($socials) and count($socials)) {
                $socials = collect($socials)->sortBy('order')->toArray();
            }

            $userSocials = !empty($user->socials) ? json_decode($user->socials, true) : [];
        @endphp

        {{-- Socials --}}
        @if(count($socials) and !empty($userSocials))
            <div class="d-flex-center gap-20 flex-wrap mt-16 w-100">

                @foreach($socials as $socialKey => $socialValue)
                    @if(!empty($socialValue['title']) and !empty($userSocials[$socialKey]))
                        <a href="{{ $userSocials[$socialKey] }}" class="" target="_blank" rel="nofollow">
                            @if(!empty($socialValue['image']))
                                <img src="{{ $socialValue['image'] }}" alt="{{ $socialValue['title'] }}" class="img-fluid" width="24px" height="24px">
                            @else
                                <x-iconsax-lin-mobile class="icon text-gray-500" width="24px" height="24px"/>
                            @endif
                        </a>
                    @endif
                @endforeach
            </div>
        @endif

    </div>


    <div class="pt-24 mt-24 px-16 border-top-gray-200">

        <div class="text-center text-gray-500 mb-8">{{ trans('update.member_since') }} {{ dateTimeFormat($user->created_at, 'M Y') }}</div>

        @if($user->offline)
            <div class="mt-24 p-12 rounded-12 border-warning bg-warning-10">
                <h5 class="font-14 font-weight-bold text-warning">{{ trans('update.the_user_is_temporarily_unavailable') }}</h5>

                @if(!empty($user->offline_message))
                    <p class="mt-8 font-12 text-warning opacity-75">{{ $user->offline_message }}</p>
                @endif
            </div>
        @endif

    </div>

</div>
