<div class="col-12 col-lg-6 pb-24">
    <div class="upcoming-course-follower-card d-flex align-items-center bg-white p-12 rounded-12 border-gray-200">
        <div class="size-48 rounded-circle">
            <img src="{{ $follower->user->getAvatar(48) }}" alt="{{ $follower->user->full_name }}" class="img-cover rounded-circle">
        </div>

        <div class="ml-8">
            <h4 class="font-14 font-weight-bold">{{ $follower->user->full_name }}</h4>
            <p class="font-12 text-gray-500 mt-4">{{ dateTimeFormat($follower->created_at, 'j M Y H:i') }}</p>
        </div>
    </div>
</div>
