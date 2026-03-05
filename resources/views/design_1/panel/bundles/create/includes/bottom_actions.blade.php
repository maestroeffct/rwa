<div class="position-fixed position-bottom-0 position-left-0 position-right-0 z-index-3 bg-white soft-shadow-2">
    <div class="container d-flex flex-column flex-lg-row align-items-lg-center justify-content-lg-between py-16">

        <div class="d-flex align-items-center">
            {{-- Previous --}}
            <a href="{{ (!empty($bundle) and $currentStep > 1) ? ("/panel/bundles/{$bundle->id}/step/" . ($currentStep - 1)) : '#!' }}" class="d-flex-center size-48 rounded-circle bg-gray-100">
                @svg("iconsax-lin-arrow-left", ['height' => 16, 'width' => 16, 'class' => (!empty($bundle) and $currentStep > 1) ? 'text-primary' : 'text-gray-500'])
            </a>

            {{-- Next --}}
            <div id="getNextStep" class="d-flex-center size-48 rounded-circle bg-gray-100 ml-16 cursor-pointer">
                @svg("iconsax-lin-arrow-right", ['height' => 16, 'width' => 16, 'class' => ($currentStep < $stepCount) ? 'text-primary' : 'text-gray-500'])
            </div>

        </div>

        <div class="d-flex align-items-center mt-20 mt-lg-0 gap-8">
            {{-- Save as Draft --}}
            <button type="button" id="saveAsDraft" class=" btn btn-transparent text-gray-500">{{ trans('public.save_as_draft') }}</button>

            @if(!empty($bundle) and $bundle->creator_id == $authUser->id)
                @include('design_1.panel.includes.content_delete_btn', [
                    'deleteContentUrl' => "/panel/bundles/{$bundle->id}/delete?redirect_to=/panel/bundles",
                    'deleteContentClassName' => 'webinar-actions text-danger ml-16',
                    'deleteContentItem' => $bundle,
                    'deleteContentItemType' => "bundle",
                ])
            @endif

            {{-- Send for Review --}}
            <button type="button" id="sendForReview" class="btn btn-lg btn-primary ml-16">{{ trans('public.send_for_review') }}</button>


        </div>
    </div>

    @php
        $stepProgressPercent = (($currentStep * 100) / $stepCount);
    @endphp

    <div class="create-course-bottom-progress">
        <div class="create-course-bottom-progress__process" style="width: {{ $stepProgressPercent }}%"></div>
    </div>
</div>
