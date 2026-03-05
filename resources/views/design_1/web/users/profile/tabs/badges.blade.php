@if(!empty($userBadges) and count($userBadges))
    <div id="profileBadgesRow" class="d-grid grid-columns-2 grid-lg-columns-5 gap-16 mt-16">
        @foreach($userBadges as $userBadge)
            <div class="profile-badge-card position-relative mb-8">
                <div class="profile-badge-card__mask"></div>

                <div class="position-relative d-flex-center flex-column text-center bg-white rounded-16 border-gray-200 p-16 pt-32 z-index-2 h-100 w-100">
                    <div class="d-flex-center size-64 rounded-16 bg-gray-100">
                        <img src="{{ !empty($userBadge->badge_id) ? $userBadge->badge->image : $userBadge->image }}" class="img-fluid" alt="{{ !empty($userBadge->badge_id) ? $userBadge->badge->title : $userBadge->title }}">
                    </div>

                    <div class="mt-12 font-14 font-weight-bold ">{{ !empty($userBadge->badge_id) ? $userBadge->badge->title : $userBadge->title }}</div>

                    <div class="mt-8 font-12 text-gray-500">{!! (!empty($userBadge->badge_id) ? nl2br($userBadge->badge->description) : nl2br($userBadge->description)) !!}</div>
                </div>
            </div>
        @endforeach
    </div>
@else
    @include('design_1.panel.includes.no-result',[
        'file_name' => 'profile_badges.svg',
        'title' => trans('update.user_profile_not_have_badges'),
        'hint' => trans('update.user_profile_not_have_badges_hint'),
        'extraClass' => 'mt-0',
    ])
@endif

