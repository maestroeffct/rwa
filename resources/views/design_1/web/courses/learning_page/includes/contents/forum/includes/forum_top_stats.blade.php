<div class="d-grid grid-columns-auto grid-lg-columns-4 gap-16">
    {{-- Total Questions --}}
    <div class="learning-page-top-stat-box-120 d-flex align-items-start justify-content-between p-16 rounded-16 bg-white border-gray-200">
        <div class="d-flex justify-content-between flex-column mt-8 h-100">
            <span class="text-gray-500">{{ trans('update.total_questions') }}</span>

            <div class="d-flex align-items-end font-24 font-weight-bold">{{ $questionsCount }}</div>
        </div>

        <div class="d-flex-center size-48 rounded-12 bg-primary-40">
            <x-iconsax-lin-message-question class="icons text-primary" width="24px" height="24px"/>
        </div>
    </div>

    {{-- Resolved Questions --}}
    <div class="learning-page-top-stat-box-120 d-flex align-items-start justify-content-between p-16 rounded-16 bg-white border-gray-200">
        <div class="d-flex justify-content-between flex-column mt-8 h-100">
            <span class="text-gray-500">{{ trans('update.resolved_questions') }}</span>

            <div class="d-flex align-items-end font-24 font-weight-bold">{{ $resolvedCount }}</div>
        </div>

        <div class="d-flex-center size-48 rounded-12 bg-success-40">
            <x-iconsax-lin-message-tick class="icons text-success" width="24px" height="24px"/>
        </div>
    </div>

    {{-- Open Questions --}}
    <div class="learning-page-top-stat-box-120 d-flex align-items-start justify-content-between p-16 rounded-16 bg-white border-gray-200">
        <div class="d-flex justify-content-between flex-column mt-8 h-100">
            <span class="text-gray-500">{{ trans('update.open_questions') }}</span>

            <div class="d-flex align-items-end font-24 font-weight-bold">{{ $openQuestionsCount }}</div>
        </div>

        <div class="d-flex-center size-48 rounded-12 bg-warning-40">
            <x-iconsax-lin-message-time class="icons text-warning" width="24px" height="24px"/>
        </div>
    </div>

    {{-- Active Users --}}
    <div class="learning-page-top-stat-box-120 d-flex align-items-start justify-content-between p-16 rounded-16 bg-white border-gray-200">
        <div class="d-flex justify-content-between flex-column mt-8 h-100">
            <span class="text-gray-500">{{ trans('update.active_users') }}</span>

            <div class="d-flex align-items-end font-24 font-weight-bold">{{ $activeUsersCount }}</div>
        </div>

        <div class="d-flex-center size-48 rounded-12 bg-danger-40">
            <x-iconsax-lin-profile-2user class="icons text-danger" width="24px" height="24px"/>
        </div>
    </div>

</div>
