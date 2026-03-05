<div class="bundle-grid-card position-relative">
    <div class="bundle-grid-card__image bg-gray-200 ">
        <img src="{{ $bundle->getImage() }}" class="img-cover" alt="{{ $bundle->title }}">
    </div>

    <div class="bundle-grid-card__content d-flex flex-column  p-16 bg-white">

        <div class="d-flex align-items-start justify-content-between">
            <div class="">
                <a href="{{ $bundle->getUrl() }}" target="_blank">
                    <h3 class="bundle-grid-card__title font-14 text-dark">{{ $bundle->title }}</h3>
                </a>

                @include("design_1.web.components.rate", [
                    'rate' => round($bundle->getRate(),1),
                    'rateCount' => $bundle->reviews()->where('status', 'active')->count(),
                    'rateClassName' => 'mt-8',
                ])
            </div>

            <div class="actions-dropdown position-relative ml-16">
                <div class="webinar-card-actions-btn d-flex-center size-40 rounded-8 bg-gray-100">
                    <x-iconsax-lin-more class="icons text-gray-400" width="24px" height="24px"/>
                </div>

                <div class="actions-dropdown__dropdown-menu dropdown-menu-width-220 dropdown-menu-top-32">
                    <ul class="my-8">

                        @can('panel_bundles_create')
                            <li class="actions-dropdown__dropdown-menu-item">
                                <a href="/panel/bundles/{{ $bundle->id }}/edit" class="">{{ trans('public.edit') }}</a>
                            </li>
                        @endcan

                        @can('panel_bundles_courses')
                            <li class="actions-dropdown__dropdown-menu-item">
                                <a href="/panel/bundles/{{ $bundle->id }}/courses" class="">{{ trans('product.courses') }}</a>
                            </li>
                        @endcan

                        @if($authUser->id == $bundle->teacher_id or $authUser->id == $bundle->creator_id)
                            @can('panel_bundles_export_students_list')
                                <li class="actions-dropdown__dropdown-menu-item">
                                    <a href="/panel/bundles/{{ $bundle->id }}/export-students-list" class="">{{ trans('public.export_list') }}</a>
                                </li>
                            @endcan
                        @endif

                        @if($bundle->creator_id == $authUser->id)
                            @can('panel_bundles_delete')
                                <li class="actions-dropdown__dropdown-menu-item">
                                    @include('design_1.panel.includes.content_delete_btn', [
                                        'deleteContentUrl' => "/panel/bundles/{$bundle->id}/delete",
                                        'deleteContentClassName' => ' text-danger',
                                        'deleteContentItem' => $bundle,
                                        'deleteContentItemType' => "bundle",
                                    ])
                                </li>
                            @endcan
                        @endif

                    </ul>
                </div>
            </div>
        </div>

        <div class="d-grid grid-columns-2 gap-16 my-16 p-16 rounded-8 border-gray-200 mb-16">
            <div class="d-flex align-items-center font-12 text-gray-500">
                <x-iconsax-lin-teacher class="icons text-gray-400" width="20px" height="20px"/>
                <span class="ml-4 font-weight-bold">{{ count($bundle->sales) }}</span>
                <span class="ml-4">{{ trans('public.students') }}</span>
            </div>

            <div class="d-flex align-items-center font-12 text-gray-500">
                <x-iconsax-lin-note-2 class="icons text-gray-400" width="20px" height="20px"/>
                <span class="ml-4 font-weight-bold">{{ count($bundle->bundleWebinars) }}</span>
                <span class="ml-4">{{ trans('update.lessons') }}</span>
            </div>

            <div class="d-flex align-items-center font-12 text-gray-500">
                <x-iconsax-lin-moneys class="icons text-gray-400" width="20px" height="20px"/>
                <span class="ml-4 font-weight-bold">{{ handlePrice($bundle->sales->sum('amount')) }}</span>
                <span class="ml-4">{{ trans('panel.sales') }}</span>
            </div>

            <div class="d-flex align-items-center font-12 text-gray-500">
                <x-iconsax-lin-clock-1 class="icons text-gray-400" width="20px" height="20px"/>
                <span class="ml-4 font-weight-bold">{{ convertMinutesToHourAndMinute($bundle->getBundleDuration()) }}</span>
                <span class="ml-4">{{ trans('home.hours') }}</span>
            </div>
        </div>

        <div class="d-flex align-items-center justify-content-between mt-auto">
            <div class="d-flex align-items-center">
                <x-iconsax-lin-video-play class="icons text-gray-500" width="16px" height="16px"/>
                <span class="ml-4 font-12 text-gray-500">{{ count($bundle->bundleWebinars) }} {{ trans('product.courses') }}</span>
            </div>

            <div class="d-flex align-items-center font-16 font-weight-bold text-success">
                @include("design_1.web.bundles.components.price")
            </div>
        </div>

    </div>
</div>
