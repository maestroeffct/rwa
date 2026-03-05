<div class="subscribe-plan-info position-relative mt-24 mb-32">
    <div class="subscribe-plan-info__mask"></div>


    <div class="position-relative bg-primary p-4 rounded-16 z-index-2">
        <div class="px-4 py-8">
            <h4 class="font-14 font-weight-bold text-white">{{ trans('update.package_overview') }}</h4>
        </div>

        <div class="position-relative bg-white rounded-12 py-16 mt-8 ">

            <div class="row">
                <div class="col-12 col-lg-6 px-16">
                    <div class="d-flex align-items-center">
                        <div class="d-flex-center size-56 bg-gray-200 rounded-circle">
                            <div class="d-flex-center size-40 bg-primary rounded-circle">
                                <img src="{{ $subscribe->icon }}" alt="{{ $subscribe->title }}" class="img-fluid" height="24px">
                            </div>
                        </div>
                        <div class="ml-8">
                            <h5 class="font-14 font-weight-bold">{{ $subscribe->title }}</h5>
                            <p class="font-12 text-gray-500 mt-4">{{ $subscribe->subtitle }}</p>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-lg-6 px-16">
                    <div class="row">
                        {{-- Duration --}}
                        <div class="col-12 col-lg-4 mt-12 mt-lg-0">
                            <div class="d-flex align-items-center">
                                <div class="d-flex-center size-40 rounded-circle bg-gray-100">
                                    <x-iconsax-lin-calendar-2 class="icons text-gray-500" width="20px" height="20px"/>
                                </div>
                                <div class="ml-8">
                                    <span class="d-block font-12 text-gray-400">{{ trans('public.duration') }}</span>
                                    <span class="d-block font-14 font-weight-bold text-gray-500 mt-4">{{ $subscribe->days }} {{ trans('public.days') }}</span>
                                </div>
                            </div>
                        </div>

                        {{-- Number of Subscribes --}}
                        <div class="col-12 col-lg-4 mt-12 mt-lg-0">
                            <div class="d-flex align-items-center">
                                <div class="d-flex-center size-40 rounded-circle bg-gray-100">
                                    <x-iconsax-lin-add-circle class="icons text-gray-500" width="20px" height="20px"/>
                                </div>
                                <div class="ml-8">
                                    <span class="d-block font-12 text-gray-400">{{ trans('update.number_of_subscribes') }}</span>
                                    <span class="d-block font-14 font-weight-bold text-gray-500 mt-4">{{ ($subscribe->infinite_use) ? trans('update.unlimited') : $subscribe->usable_count }}</span>
                                </div>
                            </div>
                        </div>

                        @php
                            $subscribeHasInstallment = $subscribe->hasInstallment();
                        @endphp

                        {{-- Purchase --}}
                        <div class="col-12 col-lg-4 mt-12 mt-lg-0 d-lg-flex align-items-lg-center justify-content-lg-end">
                            <form action="/panel/financial/pay-subscribes" method="post" class="">
                                {{ csrf_field() }}
                                <input name="amount" value="{{ $subscribe->price }}" type="hidden">
                                <input name="id" value="{{ $subscribe->id }}" type="hidden">

                                <div class="d-flex align-items-center gap-8 w-100">
                                    <button type="submit" class="btn btn-primary btn-lg flex-1 ">{{ trans('update.purchase') }}</button>

                                    @if($subscribeHasInstallment)
                                        <a href="/panel/financial/subscribes/{{ $subscribe->id }}/installments" class="d-flex-center size-48 rounded-12 border-2 border-gray-400 bg-white" data-tippy-content="{{ trans('update.installments') }}">
                                            <x-iconsax-lin-moneys class="icons text-gray-500" width="24px" height="24px"/>
                                        </a>
                                    @endif
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-16 pt-16 px-16 border-top-gray-200">
                @if(empty($subscribe->target_type) or $subscribe->target_type == "all")
                    <h4 class="font-16">{{ trans('update.unlimited_content') }}</h4>
                    <p class="mt-4 font-12 text-gray-500">{{ trans('update.you_can_subscribe_to_all_courses_and_bundles_with_this_plan') }}</p>
                @else
                    <h4 class="font-16">{{ trans('update.package_limitation') }}</h4>

                    @if(!empty($subscribe->target))
                        <p class="mt-4 font-12 text-gray-500">{{ trans("update.package_limitation_on_{$subscribe->target}") }}</p>
                    @endif

                    @php
                        $gridColumnCount = 1;

                        if ($subscribe->target == "specific_categories") {
                            $gridColumnCount = $subscribe->categories->count();
                        } else if ($subscribe->target == "specific_instructors") {
                            $gridColumnCount = $subscribe->instructors->count();
                        } else if ($subscribe->target == "specific_courses") {
                            $gridColumnCount = $subscribe->courses->count();
                        } else if ($subscribe->target == "specific_bundles") {
                            $gridColumnCount = $subscribe->bundles->count();
                        }
                    @endphp

                    <div class="d-grid gap-16 mt-16 {{ ($gridColumnCount > 3) ? 'grid-columns-4' : "grid-columns-{$gridColumnCount}" }}">
                        @include('design_1.web.subscribes.details.limitation_items')
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
