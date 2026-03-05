@php
    $saleItem = !empty($sale->webinar) ? $sale->webinar : $sale->bundle;

    $lastSession = !empty($sale->webinar) ? $sale->webinar->lastSession() : null;
    $nextSession = !empty($sale->webinar) ? $sale->webinar->nextSession() : null;
    $isProgressing = false;

    if(!empty($sale->webinar) and $sale->webinar->start_date <= time() and !empty($lastSession) and $lastSession->date > time()) {
        $isProgressing = true;
    }
@endphp

@if(!empty($saleItem))
    <div class="panel-course-card-1 position-relative">
        <div class="card-mask"></div>

        <div class="position-relative d-flex flex-column flex-lg-row  gap-12 z-index-2 bg-white p-12 rounded-24">
            {{-- Image --}}
            <div class="panel-course-card-1__image position-relative rounded-16 bg-gray-100">
                <a href="{{ $saleItem->getUrl() }}" target="_blank">
                    <img src="{{ $saleItem->getImage() }}" alt="" class="img-cover rounded-16">
                </a>
                {{-- Badges On Image --}}
                @include("design_1.panel.webinars.my_purchases.item_card.badges")

                @if($saleItem->type == 'webinar')
                    <div class="is-live-course-icon d-flex-center size-64 rounded-circle">
                        <a href="{{ $saleItem->getUrl() }}" target="_blank" class="d-flex-center w-100 h-100">
                            <x-iconsax-bol-video class="icons text-white" width="24px" height="24px"/>
                        </a>
                    </div>
                @elseif($saleItem->type == "text_lesson")
                    <div class="is-live-course-icon d-flex-center size-64 rounded-circle">
                        <a href="{{ $saleItem->getUrl() }}" target="_blank" class="d-flex-center w-100 h-100">
                            <x-iconsax-bol-note-2 class="icons text-white" width="24px" height="24px"/>
                        </a>
                    </div>
                @elseif($saleItem->type == "course")
                    <div class="is-live-course-icon d-flex-center size-64 rounded-circle">
                        <a href="{{ $saleItem->getUrl() }}" target="_blank" class="d-flex-center w-100 h-100">
                            <x-iconsax-bol-video-play class="icons text-white" width="24px" height="24px"/>
                        </a>
                    </div>
                @endif
            </div>

            {{-- Content --}}
            <div class="panel-course-card-1__content flex-1 d-flex flex-column">
                <div class="bg-gray-100 p-16 rounded-16 mb-12">
                    <div class="d-flex align-items-start justify-content-between gap-12">
                        <div class="">
                            <h3 class="font-16 text-dark">
                                <a href="{{ $saleItem->getUrl() }}" target="_blank" class="text-decoration-none text-dark">
                                    {{ truncate($saleItem->title, 46) }}
                                </a>
                            </h3>

                            @include("design_1.web.components.rate", [
                                'rate' => round($saleItem->getRate(),1),
                                'rateCount' => $saleItem->reviews()->where('status', 'active')->count(),
                                'rateClassName' => 'mt-8',
                            ])
                        </div>

                        {{-- Actions Dropdown --}}
                        @include("design_1.panel.webinars.my_purchases.item_card.actions_dropdown")
                    </div>
                    {{-- Stats --}}
                    <a href="{{ $saleItem->getUrl() }}" target="_blank" class="text-decoration-none">
                        @include("design_1.panel.webinars.my_purchases.item_card.stats")
                    </a>
                </div>

                {{-- Progress & Price --}}
                <div class="row align-items-center justify-content-between mt-auto">
                    <div class="col-10">
                        @include("design_1.panel.webinars.my_purchases.item_card.progress_and_chart")
                    </div>

                    {{-- Continue Learning Button --}}
                    @if(!empty($sale->webinar))
                        <div class="col-2 d-flex align-items-center justify-content-end">
                            <a href="{{ $saleItem->getLearningPageUrl() }}" target="_blank" class="continue-learning-link d-flex align-items-center cursor-pointer text-decoration-none">
                                <span class="font-12 text-primary mr-4">{{ trans('update.continue_learning') }}</span>
                                <x-iconsax-lin-arrow-right class="icons text-primary mt-2" width="16px" height="16px"/>
                            </a>
                        </div>
                    @elseif(!empty($sale->bundle))
                        <div class="col-2 d-flex align-items-center justify-content-end">
                            <a href="{{ $saleItem->getUrl() }}" target="_blank" class="continue-learning-link d-flex align-items-center cursor-pointer text-decoration-none">
                                <span class="font-12 text-primary mr-4">{{ trans('update.details') }}</span>
                                <x-iconsax-lin-arrow-right class="icons text-primary mt-2" width="16px" height="16px"/>
                            </a>
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </div>
@endif
