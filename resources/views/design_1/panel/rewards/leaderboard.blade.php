<div class="bg-white p-16 rounded-24">
    <h3 class="font-16 font-weight-bold">{{ trans('update.leaderboard') }}</h3>

    <div class="swiper-container js-make-swiper leaderboard-swiper pt-24"
         data-item="leaderboard-swiper"
         data-autoplay="false"
         data-breakpoints="1440:4.4,769:3.4,320:1.4"
    >
        <div class="swiper-wrapper py-8">
            @foreach($mostPointsUsers as $mostPoint)
                <div class="swiper-slide">
                    <div class="reward-leaderboard-user-card position-relative d-flex align-items-center flex-column text-center p-16 rounded-12 bg-gray-100">
                        <div class="user-avatar size-48 rounded-circle bg-gray-200 {{ $loop->first ? 'most-points-user' : '' }}">
                            <img src="{{ $mostPoint->user->getAvatar(48) }}" alt="" class="img-cover rounded-circle">
                        </div>

                        <h6 class="reward-leaderboard-user-name my-8 font-14 font-weight-bold">{{ $mostPoint->user->full_name }}</h6>

                        <div class="d-flex-center mt-auto py-4 px-8 rounded-16 border-gray-200 bg-white">
                            <x-iconsax-bol-star-1 class="icons text-warning" width="16px" height="16px"/>
                            <span class="ml-4 font-12 text-gray-500">{{ $mostPoint->total_points }}</span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="d-flex align-items-center justify-content-between mt-16 px-24 py-16 border-dashed border-primary rounded-12">
        <div class="d-flex align-items-center">
            <x-iconsax-bol-star class="icons text-primary" width="24px" height="24px"/>

            <div class="ml-12">
                <h3 class="font-14 font-weight-bold">{{ trans('update.want_more_points') }}</h3>
                <p class="mt-4 font-12 text-gray-500">{{ trans('update.want_more_points_hint') }}</p>
            </div>
        </div>

        <a href="{{ (!empty($rewardsSettings) and !empty($rewardsSettings['want_more_points_link'])) ? $rewardsSettings['want_more_points_link'] : '' }}" class="d-flex align-items-center font-12 text-primary font-weight-bold">
            <span class="mr-2">{{ trans('update.view_more') }}</span>
            <x-iconsax-lin-arrow-right class="icons text-primary" width="16px" height="16px"/>
        </a>
    </div>

</div>
