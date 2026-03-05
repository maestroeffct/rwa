<div class="bg-white p-16 rounded-24 w-100 h-100">
    <div class="d-flex-center flex-column text-center py-64 px-16 py-lg-160 px-lg-56 border-gray-200 rounded-12">
        <div class="d-flex-center bg-primary size-120 rounded-circle">
            <x-iconsax-lin-clipboard-tick class="icons text-white" width="64px" height="64px"/>
        </div>

        <h3 class="font-16 text-dark mt-12">{{ $assignment->title }}</h3>
        <div class="mt-8 font-12 text-gray-500">{!! $assignment->description !!}</div>

        <div class="d-flex align-items-center flex-wrap gap-40 mt-16 text-left">

            {{-- Participated Students --}}
            <div class="d-flex align-items-center">
                <div class="d-flex-center size-40 rounded-circle bg-gray-100">
                    <x-iconsax-lin-profile-2user class="icons text-gray-500" width="20px" height="20px"/>
                </div>
                <div class="ml-8">
                    <span class="d-block font-12 text-gray-400">{{ trans('update.participated_students') }}</span>
                    <span class="d-block mt-2 font-14 text-gray-500 font-weight-bold">{{ $assignment->submissions }}</span>
                </div>
            </div>

            {{-- Passed Students --}}
            <div class="d-flex align-items-center">
                <div class="d-flex-center size-40 rounded-circle bg-gray-100">
                    <x-iconsax-lin-tick-circle class="icons text-gray-500" width="20px" height="20px"/>
                </div>
                <div class="ml-8">
                    <span class="d-block font-12 text-gray-400">{{ trans('update.passed_students') }}</span>
                    <span class="d-block mt-2 font-14 text-gray-500 font-weight-bold">{{ $assignment->passedCount }}</span>
                </div>
            </div>

            {{-- Failed Students --}}
            <div class="d-flex align-items-center">
                <div class="d-flex-center size-40 rounded-circle bg-gray-100">
                    <x-iconsax-lin-close-circle class="icons text-gray-500" width="20px" height="20px"/>
                </div>
                <div class="ml-8">
                    <span class="d-block font-12 text-gray-400">{{ trans('update.failed_students') }}</span>
                    <span class="d-block mt-2 font-14 text-gray-500 font-weight-bold">{{ $assignment->failedCount }}</span>
                </div>
            </div>

            {{-- Waiting Students --}}
            <div class="d-flex align-items-center">
                <div class="d-flex-center size-40 rounded-circle bg-gray-100">
                    <x-iconsax-lin-more-circle class="icons text-gray-500" width="20px" height="20px"/>
                </div>
                <div class="ml-8">
                    <span class="d-block font-12 text-gray-400">{{ trans('update.waiting_students') }}</span>
                    <span class="d-block mt-2 font-14 text-gray-500 font-weight-bold">{{ $assignment->pendingCount }}</span>
                </div>
            </div>

            {{-- Average Grade --}}
            <div class="d-flex align-items-center">
                <div class="d-flex-center size-40 rounded-circle bg-gray-100">
                    <x-iconsax-lin-sound class="icons text-gray-500" width="20px" height="20px"/>
                </div>
                <div class="ml-8">
                    <span class="d-block font-12 text-gray-400">{{ trans('update.average_grade') }}</span>
                    <span class="d-block mt-2 font-14 text-gray-500 font-weight-bold">{{ $assignment->average_grade }}</span>
                </div>
            </div>
        </div>

        <a href="/panel/assignments" target="_blank" class="btn btn-primary btn-lg mt-24">{{ trans('update.manage_students_assignment') }}</a>


    </div>
</div>
