@php
    $progressSteps = [
        1 => [
            'name' => 'basic_information',
            'icon' => 'note-2'
        ],

        2 => [
            'name' => 'extra_information',
            'icon' => 'note-add'
        ],

        3 => [
            'name' => 'pricing',
            'icon' => 'empty-wallet'
        ],

        4 => [
            'name' => 'content',
            'icon' => 'document-cloud'
        ],

        5 => [
            'name' => 'prerequisites',
            'icon' => 'archive-tick'
        ],

        6 => [
            'name' => 'faq',
            'icon' => 'bill'
        ],

        7 => [
            'name' => 'quiz_certificate',
            'icon' => 'clipboard-tick'
        ],

    ];

    if (empty(getGeneralOptionsSettings('direct_publication_of_courses'))) {
        $progressSteps[8] = [
            'name' => 'message_to_reviewer',
            'icon' => 'shield-search'
        ];
    }

@endphp

<div class="position-relative d-flex align-items-center p-20 rounded-16 bg-white">
    <div class="webinar-progress-mask"></div>

    @foreach($progressSteps as $key => $progressStep)
        @php
            $isActiveStep = ($currentStep == $key);
        @endphp

        <div class="js-get-next-step {{ $isActiveStep ? 'd-flex' : 'd-none d-lg-flex' }} align-items-center cursor-pointer {{ !($loop->last) ? 'mr-40' : '' }}" data-step="{{ $key }}" @if(!$isActiveStep) data-tippy-content="{{ trans('public.' . $progressStep['name']) }}" @endif>
            <div class="d-flex-center size-48 rounded-circle {{ $isActiveStep ? 'bg-primary' : 'bg-gray-100' }}">
                @svg("iconsax-lin-{$progressStep['icon']}", ['height' => 24, 'width' => 24, 'class' => $isActiveStep ? 'text-white' : 'text-gray-400'])
            </div>

            @if($isActiveStep)
                <div class="ml-8">
                    <p class="font-12 text-gray-500">{{ trans('webinars.progress_step', ['step' => $key,'count' => $stepCount]) }}</p>
                    <h6 class="font-14 font-weight-bold mt-2">{{ trans('public.' . $progressStep['name']) }}</h6>
                </div>
            @endif
        </div>
    @endforeach

</div>
