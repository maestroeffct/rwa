<div class="panel-course-card-1 position-relative {{ !empty($isInvitedCoursesPage) ? 'is-invited-course-card' : '' }}">
    <div class="card-mask"></div>

    <div class="position-relative d-flex flex-column flex-lg-row gap-12 z-index-2 bg-white p-12 rounded-24">
        <a href="{{ $event->getUrl() }}" target="_blank" class="d-flex flex-column flex-lg-row gap-12 flex-grow-1 text-decoration-none">
            {{-- Image --}}
            <div class="panel-course-card-1__image position-relative rounded-16 bg-gray-100">
                <img src="{{ $event->thumbnail }}" alt="" class="img-cover rounded-16">
                {{-- Badges On Image --}}
                @include("design_1.panel.events.my_events.event_card.badges")

                @if($event->type == "in_person")
                    <div class="is-live-course-icon d-flex-center size-64 rounded-circle">
                        <x-iconsax-bol-location class="icons text-white" width="24px" height="24px"/>
                    </div>
                @else
                    <div class="is-live-course-icon d-flex-center size-64 rounded-circle">
                        <x-iconsax-bol-monitor class="icons text-white" width="24px" height="24px"/>
                    </div>
                @endif
            </div>

            {{-- Content --}}
            <div class="panel-course-card-1__content flex-1 d-flex flex-column">
                <div class="bg-gray-100 p-16 rounded-16 mb-12">
                    <div class="d-flex align-items-start justify-content-between gap-12">
                        <div class="">
                            <h3 class="font-16 text-dark">{{ truncate($event->title, 46) }}</h3>

                            @include("design_1.web.components.rate", [
                                'rate' => round($event->getRate(), 1),
                                'rateCount' => $event->reviews()->where('status', 'active')->count(),
                                'rateClassName' => 'mt-8',
                            ])
                        </div>
                    </div>

                    {{-- Stats --}}
                    @include("design_1.panel.events.my_events.event_card.stats")
                    {{-- End Stats --}}
                </div>

                {{-- Progress & Price --}}
                <div class="row align-items-center justify-content-between mt-auto">
                    <div class="col-10">
                        @include("design_1.panel.events.my_events.event_card.progress_and_chart")
                    </div>

                    {{-- Price --}}
                    <div class="col-2 d-flex align-items-center justify-content-end font-16 font-weight-bold text-primary">
                        @php
                            $getMinAndMaxPrice = $event->getMinAndMaxPrice();
                        @endphp

                        @if($getMinAndMaxPrice['min'] == $getMinAndMaxPrice['max'])
                            <span class="">{{ ($getMinAndMaxPrice['min'] > 0) ? handlePrice($getMinAndMaxPrice['min'], true, true, false, null, true) : trans('update.free') }}</span>
                        @else
                            <span class="">{{ ($getMinAndMaxPrice['min'] > 0) ? handlePrice($getMinAndMaxPrice['min'], true, true, false, null, true) : trans('update.free') }}</span>
                            -
                            <span class="">{{ ($getMinAndMaxPrice['max'] > 0) ? handlePrice($getMinAndMaxPrice['max'], true, true, false, null, true) : trans('update.free') }}</span>
                        @endif
                    </div>
                </div>
            </div>
        </a>
    </div>

    {{-- Actions Dropdown (positioned outside the link) --}}
    <div class="item-card-actions-dropdown-container">
        @include("design_1.panel.events.my_events.event_card.actions_dropdown")
    </div>
</div>
