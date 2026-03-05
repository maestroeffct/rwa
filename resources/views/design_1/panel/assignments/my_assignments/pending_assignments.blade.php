@if(!empty($pendingAssignments) and count($pendingAssignments))
    <div class="mt-28">

        <div class="d-flex align-items-center justify-content-between pb-16 px-16 border-bottom-gray-100">
            <div class="">
                <h3 class="font-16">{{ trans('update.pending_assignments') }}</h3>
                <p class="font-14 text-gray-500 mt-4">{{ trans('update.you_have_several_assignments_that_you_haven’t_checked_them') }}</p>
            </div>
        </div>

        <div class="position-relative mt-16">
            <div class="swiper-container js-make-swiper pending-assignments-swiper pb-24"
                 data-item="pending-assignments-swiper"
                 data-autoplay="false"
                 data-breakpoints="1440:4.2,769:3.4,320:1.4"
            >
                <div class="swiper-wrapper py-8">
                    @foreach($pendingAssignments->sortByDesc('deadlineTime') as $pendingAssignment)
                        <div class="swiper-slide">
                            <div class=" bg-white p-20 rounded-12">
                                <div class="d-flex align-items-center justify-content-between">
                                   
                                        @if(!empty($pendingAssignment->icon))
                                        <div class="d-flex-center size-64 rounded-12">
                                            <img src="{{ $pendingAssignment->icon }}" class="img-cover rounded-12">
                                        @else
                                        <div class="d-flex-center size-64 bg-primary-30 rounded-12">
                                            <x-iconsax-bul-bookmark class="icons text-white" width="32px" height="32px"/>
                                        @endif
                                    </div>

                                    <a href="{{ "{$pendingAssignment->webinar->getLearningPageUrl()}?type=assignment&item={$pendingAssignment->id}" }}" target="_blank" class="d-flex align-items-center ml-8 cursor-pointer">
                                        <span class="font-12 text-primary mr-4">{{ trans('update.submit_now') }}</span>
                                        <x-iconsax-lin-arrow-right class="icons text-primary" width="16px" height="16px"/>
                                    </a>
                                </div>

                                <h5 class="mt-12 font-14 font-weight-bold">{{ $pendingAssignment->title }}</h5>
                                <p class="mt-4 font-12 text-gray-500 text-ellipsis">{{ $pendingAssignment->webinar->title }}</p>

                                <div class="d-flex align-items-center mt-12 flex-wrap">

                                    <div class="d-flex align-items-center mr-16">
                                        <x-iconsax-lin-medal class="icons text-gray-400" width="16px" height="16px"/>
                                        <span class="ml-4 font-12 text-gray-400">{{ $pendingAssignment->pass_grade }}</span>
                                    </div>

                                    @php
                                        $itemDeadline = $pendingAssignment->getDeadlineTimestamp($authUser);
                                    @endphp

                                    <div class="d-flex align-items-center mr-16">
                                        <x-iconsax-lin-calendar-2 class="icons text-gray-400" width="16px" height="16px"/>

                                        @if(is_bool($itemDeadline))
                                            <span class="ml-4 font-12 text-gray-400">{{ trans('update.unlimited') }}</span>
                                        @elseif(!empty($itemDeadline))
                                            <span class="ml-4 font-12 text-gray-400">{{ trans('update.expired_on_date', ['date' => dateTimeFormat($itemDeadline, 'j M Y H:i')]) }}</span>
                                        @else
                                            <span class="ml-4 font-12 text-gray-400">{{ trans('update.unlimited') }}</span>
                                        @endif
                                    </div>

                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

        </div>
    </div>
@endif
