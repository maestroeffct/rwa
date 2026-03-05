@if(!empty($landingComponent) and $landingComponent->enable)
    @php
        $meetingBookingListBackgroundColor = "primary";
        $contents = [];
        if (!empty($landingComponent->content)) {
            $contents = json_decode($landingComponent->content, true);
        }

        if (!empty($contents['background_color'])) {
            $meetingBookingListBackgroundColor = $contents['background_color'];
        }


        $frontComponentsDataMixins = (new \App\Mixins\LandingBuilder\FrontComponentsDataMixins());
    @endphp


    @push('styles_top')
        <link rel="stylesheet" href="{{ getLandingComponentStylePath("meeting_booking_list") }}">
    @endpush


    <div class="meeting-booking-list-section position-relative" style="background-color: var({{ "--".$meetingBookingListBackgroundColor }}); {{ (!empty($contents['background']) ? "background-image: url({$contents['background']}); " : '') }}">
        <div class="container">
            <div class="d-flex-center flex-column text-center">
                @if(!empty($contents['main_content']) and !empty($contents['main_content']['pre_title']))
                    <div class="d-inline-flex-center py-8 px-16 rounded-8 border-white bg-white-20 font-12 text-white">{{ $contents['main_content']['pre_title'] }}</div>
                @endif

                @if(!empty($contents['main_content']) and !empty($contents['main_content']['title']))
                    <h2 class="mt-16 font-32 text-white">{{ $contents['main_content']['title'] }}</h2>
                @endif

                @if(!empty($contents['main_content']) and !empty($contents['main_content']['description']))
                    <p class="mt-20 font-16 text-white opacity-70">{!! nl2br($contents['main_content']['description']) !!}</p>
                @endif
            </div>

            {{-- Instructors --}}
            @php
                $ids = [];

                if (!empty($contents['meeting_instructors']) and is_array($contents['meeting_instructors'])) {
                    foreach ($contents['meeting_instructors'] as $meetingInstructor) {
                        if (!empty($meetingInstructor['instructor'])) {
                            $ids[] = $meetingInstructor['instructor'];
                        }
                    }
                }

                $instructors = $frontComponentsDataMixins->getMeetingBookingListInstructorsByIds($ids);
            @endphp

            @if($instructors->isNotEmpty())
                <div class="row justify-content-center">
                    <div class="col-12 col-lg-10">
                        <div class="meeting-booking-list-section__table-container w-100">
                            <table class="meeting-booking-list-section__meetings-table">
                                <thead class="">
                                <tr>
                                    <td width="22%"></td>
                                    <td class="text-center">{{ trans('update.weekly_hours') }}</td>
                                    <td class="text-center">{{ trans('panel.total_meetings') }}</td>
                                    <td class="text-center">{{ trans('update.tutoring_hours') }}</td>
                                    <td class="text-center">{{ trans('update.earliest_available_time') }}</td>
                                    <td class="text-center">{{ trans('update.hourly_rate') }}</td>
                                    <td></td>
                                </tr>
                                </thead>

                                <tbody>
                                @foreach($instructors as $instructor)
                                    @php
                                        $instructorRates = $instructor->rates(true);
                                        $price = (!empty($instructor->meeting)) ? $instructor->meeting->amount : 0;
                                        $discount = (!empty($price) and !empty($instructor->meeting) and !empty($instructor->meeting->discount) and $instructor->meeting->discount > 0) ? $instructor->meeting->discount : 0;

                                        $fromPrice = 0;

                                        if(!empty($instructor->meeting) and !empty($instructor->meeting->meetingTimes) and count($instructor->meeting->meetingTimes)) {
                                            if(!empty($price) and $price > 0) {
                                                $fromPrice = (!empty($discount) ? ($price - ($price * $discount / 100)) : $price);
                                            }
                                        }
                                    @endphp

                                    <tr>
                                        <td class="text-left">
                                            <div class="d-flex align-items-center">
                                                <div class="size-64 rounded-circle bg-gray-100">
                                                    <img src="{{ $instructor->getAvatar(64) }}" alt="{{ $instructor->full_name }}" class="img-cover rounded-circle">
                                                </div>
                                                <div class="ml-8">
                                                    <h5 class="font-16">{{ $instructor->full_name }}</h5>
                                                    @include('design_1.web.components.rate', ['rate' => $instructorRates['rate'], 'showRateStars' => true, 'rateClassName' => 'mt-4'])
                                                </div>
                                            </div>
                                        </td>

                                        {{-- Weekly Hours --}}
                                        <td class="text-center">{{ $instructor->weekly_hours ?? 0 }}</td>

                                        {{-- Total Meetings --}}
                                        <td class="text-center">{{ $instructor->total_meetings ?? 0 }}</td>

                                        {{-- Tutoring Hours --}}
                                        <td class="text-center">{{ $instructor->getTotalHoursTutoring() }}</td>

                                        {{-- Earliest Available Time --}}
                                        <td class="text-center">{{ $instructor->earliestAvailableTime }}</td>

                                        {{-- Hourly Rate --}}
                                        <td class="text-center">
                                            @if(!empty($fromPrice))
                                                {{ trans('public.from') }} {{ handlePrice($fromPrice) }}
                                            @else
                                                -
                                            @endif
                                        </td>

                                        {{-- action --}}
                                        <td class="text-right">
                                            <a href="{{ $instructor->getProfileUrl() }}?tab=appointments" target="_blank" class="btn-flip-effect btn-flip-effect__left-side btn btn-lg btn-primary gap-4 text-white" data-text="{{ trans('update.book') }}">
                                                <span class="btn-flip-effect__text">{{ trans('update.book') }}</span>
                                                <x-iconsax-lin-arrow-right class="icons text-white" width="24px" height="24px"/>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif


            {{-- CTA --}}
            @if(!empty($contents['cta_section']) and !empty($contents['cta_section']['title']))
                <div class="row justify-content-center mt-54">
                    <div class="col-12 col-lg-8 d-flex-center">
                        <div class="d-flex align-items-center gap-64">
                            <div class="">
                                <h3 class="font-24 text-white">{{ $contents['cta_section']['title'] }}</h3>

                                @if(!empty($contents['cta_section']['description']))
                                    <p class="mt-8 text-white font-16 opacity-70">{!! nl2br($contents['cta_section']['description']) !!}</p>
                                @endif

                                @if(!empty($contents['cta_section']['link_title']))
                                    <a href="{{ !empty($contents['cta_section']['url']) ? $contents['cta_section']['url'] : '' }}" class="btn-flip-effect btn-flip-effect__right-0 d-inline-flex align-items-center gap-4 font-16 font-weight-bold text-white mt-12" data-text="{{ $contents['cta_section']['link_title'] }}">
                                        <x-iconsax-lin-arrow-right class="icons text-white" width="16px" height="16px"/>
                                        <span class="btn-flip-effect__text">{{ $contents['cta_section']['link_title'] }}</span>
                                    </a>
                                @endif
                            </div>

                            <div class="meeting-booking-list-section__cta-image d-flex-center">
                                @if(!empty($contents['cta_section']['image']))
                                    <img src="{{ $contents['cta_section']['image'] }}" alt="cta image" class="img-fluid">
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endif

        </div>
    </div>

@endif
