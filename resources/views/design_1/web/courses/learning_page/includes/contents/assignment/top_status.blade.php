@if($assignmentHistory->status == "pending")
    <div class="d-flex align-items-center p-12 rounded-16 border-dashed border-gray-300">
        <div class="d-flex-center size-48 rounded-12 bg-warning-20">
            <x-iconsax-bul-clipboard-text class="icons text-warning" width="24px" height="24px"/>
        </div>
        <div class="ml-8">
            <h5 class="font-14 text-dark">{{ trans('update.pending_assignment') }}</h5>
            <div class="font-12 text-gray-500 mt-4">{{ trans('update.learning_page_pending_assignment_hint') }}</div>
        </div>
    </div>
@elseif($assignmentHistory->status == "passed")
    <div class="d-flex align-items-center p-12 rounded-16 border-dashed border-gray-300">
        <div class="d-flex-center size-48 rounded-12 bg-success-20">
            <x-iconsax-bul-clipboard-tick class="icons text-success" width="24px" height="24px"/>
        </div>
        <div class="ml-8">
            <h5 class="font-14 text-dark">{{ trans('update.passed_assignment') }}</h5>
            <div class="font-12 text-gray-500 mt-4">{{ trans('update.learning_page_passed_assignment_hint') }}</div>
        </div>
    </div>
@elseif($assignmentHistory->status == "not_passed")
    <div class="d-flex align-items-center p-12 rounded-16 border-dashed border-gray-300">
        <div class="d-flex-center size-48 rounded-12 bg-danger-20">
            <x-iconsax-bul-clipboard-close class="icons text-danger" width="24px" height="24px"/>
        </div>
        <div class="ml-8">
            <h5 class="font-14 text-dark">{{ trans('update.assignment_failed') }}</h5>
            <div class="font-12 text-gray-500 mt-4">{{ trans('update.learning_page_assignment_failed_hint') }}</div>
        </div>
    </div>
@elseif($assignmentHistory->status == "not_submitted")
    <div class="d-flex align-items-center p-12 rounded-16 border-dashed border-gray-300">
        <div class="d-flex-center size-48 rounded-12 bg-danger-20">
            <x-iconsax-bul-clipboard-close class="icons text-danger" width="24px" height="24px"/>
        </div>
        <div class="ml-8">
            <h5 class="font-14 text-dark">{{ trans('update.not_submitted_assignment') }}</h5>
            <div class="font-12 text-gray-500 mt-4">{{ trans('update.learning_page_assignment_not_submitted_hint') }}</div>
        </div>
    </div>
@endif
