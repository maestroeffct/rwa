<div class="col-12 col-md-6 col-lg-4 mt-16">
    <a href="{{ $instructor->getProfileUrl() }}" class="text-dark w-100">
        <div class="profile-instructor-card position-relative w-100 mb-8">
            <div class="profile-instructor-card__mask"></div>

            <div class="position-relative d-flex align-items-center bg-white p-16 rounded-16 border-gray-200 z-index-2 w-100 h-100">
                <div class="size-64 rounded-circle bg-gray-100">
                    <img src="{{ $instructor->getAvatar(64) }}" class="img-cover rounded-circle" alt="{{ $instructor->full_name }}">
                </div>

                <div class="ml-12">
                    <h3 class="font-14 font-weight-bold text-dark">{{ $instructor->full_name }}</h3>

                    <div class="mt-4 font-12 text-gray-500">{{ trans('update.count_courses',['count' => $instructor->webinars_count]) }}</div>
                </div>
            </div>

        </div>
    </a>
</div>
