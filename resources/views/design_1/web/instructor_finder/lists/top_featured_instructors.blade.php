@if(!empty($featuredInstructors) and count($featuredInstructors))
    <div class="position-relative mt-32">
        <div class="swiper-container js-make-swiper top-featured-instructors pb-0"
             data-item="top-featured-instructors"
             data-autoplay="true"
             data-loop="true"
             data-breakpoints="1440:4.8,769:3.4,320:1.4"
        >
            <div class="swiper-wrapper py-0  mx-16 mx-md-32">
                @foreach($featuredInstructors as $featuredInstructor)
                    <div class="swiper-slide">
                        <a href="{{ $featuredInstructor->getProfileUrl() }}" target="_blank" class="">
                            <div class="d-flex align-items-center bg-gray-100 p-12 rounded-pill overflow-hidden">
                                <div class="size-48 rounded-circle">
                                    <img src="{{ $featuredInstructor->getAvatar(48) }}" alt="{{ $featuredInstructor->full_name }}" class="img-cover rounded-circle">
                                </div>

                                <div class="ml-8">
                                    <h6 class="font-14 font-weight-bold text-dark text-ellipsis">{{ $featuredInstructor->full_name }}</h6>

                                    @if(!empty($featuredInstructor->meeting) and !empty($featuredInstructor->meeting->meetingTimes) and count($featuredInstructor->meeting->meetingTimes))
                                        @php
                                            $featuredInstructorPrice = (!empty($featuredInstructor->meeting)) ? $featuredInstructor->meeting->amount : 0;
                                            $featuredInstructorDiscount = (!empty($featuredInstructorPrice) and !empty($featuredInstructor->meeting) and !empty($featuredInstructor->meeting->discount) and $featuredInstructor->meeting->discount > 0) ? $featuredInstructor->meeting->discount : 0;
                                        @endphp

                                        <div class="d-flex align-items-start font-12 text-gray-500 mt-4">
                                            @if(!empty($featuredInstructorPrice) and $featuredInstructorPrice > 0)
                                                <div class="d-flex flex-column">
                                                    <span class="">{{ handlePrice(!empty($featuredInstructorDiscount) ? ($featuredInstructorPrice - ($featuredInstructorPrice * $featuredInstructorDiscount / 100)) : $featuredInstructorPrice) }}</span>

                                                    @if(!empty($featuredInstructorDiscount))
                                                        <span class=" text-decoration-line-through">{{ handlePrice($featuredInstructorPrice) }}</span>
                                                    @endif
                                                </div>

                                                <span class="">/{{ trans('update.hr.') }}</span>
                                            @else
                                                <span class="">{{ trans('public.free') }}</span>
                                            @endif
                                        </div>
                                    @else
                                        <div class="mt-4 font-12 text-gray-500">{{ trans('update.not_available_for_meeting') }}</div>
                                    @endif

                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endif
