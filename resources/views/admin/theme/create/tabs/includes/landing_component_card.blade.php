<li data-id="{{ $landingComponent->id }}">
    <div class="d-flex align-items-center justify-content-between mt-16 bg-white p-16 rounded-12 border-gray-200">
        <div class="d-flex align-items-center">
            <div class="position-relative d-flex-center size-48 rounded-12 bg-gray-300">
                @php
                    $icon = \App\Enums\LandingBuilderComponentCategories::getIcon($landingComponent->landingBuilderComponent->category);
                @endphp
                @svg("iconsax-bul-{$icon}", ['height' => 24, 'width' => 24, 'class' => "icons text-gray-500"])

                {{-- If Disabled --}}
                @if(!$landingComponent->enable)
                    <div class="landing-component-disabled d-flex-center size-16 bg-white rounded-circle">
                        <x-iconsax-bol-close-circle class="icons text-danger" width="14px" height="14px"/>
                    </div>
                @endif
            </div>

            <div class="ml-8">
                <h5 class="font-14 mb-0">{{ trans("update.{$landingComponent->landingBuilderComponent->name}_component_title") }}</h5>
                <p class="font-12 text-gray-500 mb-0">{{ trans("update.{$landingComponent->landingBuilderComponent->name}_component_hint") }}</p>
            </div>
        </div>

        <div class="d-flex align-items-center gap-16">
            <div class="actions-dropdown position-relative d-flex justify-content-end align-items-center">
                <button type="button" class="d-flex-center btn-transparent">
                    <x-iconsax-lin-more class="icons text-gray-400" width="24px" height="24px"/>
                </button>

                <div class="actions-dropdown__dropdown-menu dropdown-menu-width-220">
                    <ul class="my-8">

                        @if($landingComponent->enable)
                            <li class="actions-dropdown__dropdown-menu-item">
                                <a href="{{ getLandingBuilderUrl("/{$landingItemId}/components/{$landingComponent->id}/disable") }}"
                                   class="delete-action"
                                   data-title="{{ trans('update.landing_component_disable_msg_hit') }}"
                                   data-confirm="{{ trans('public.disable') }}"
                                >{{ trans('public.disable') }}</a>
                            </li>
                        @else
                            <li class="actions-dropdown__dropdown-menu-item">
                                <a href="{{ getLandingBuilderUrl("/{$landingItemId}/components/{$landingComponent->id}/enable") }}"
                                   class="delete-action"
                                   data-title="{{ trans('update.landing_component_enable_msg_hit') }}"
                                   data-confirm="{{ trans('update.enable') }}"
                                >{{ trans('update.enable') }}</a>
                            </li>
                        @endif

                        <li class="actions-dropdown__dropdown-menu-item">
                            <a href="{{ getLandingBuilderUrl("/{$landingItemId}/components/{$landingComponent->id}/delete") }}" class="delete-action text-danger ">{{ trans('public.delete') }}</a>
                        </li>
                    </ul>
                </div>
            </div>

            <a href="{{ getLandingBuilderUrl("/{$landingItemId}/components/{$landingComponent->id}/edit") }}" target="_blank" class="d-flex-center cursor-pointer" data-toggle="tooltip" data-placement="top" title="{{ trans('public.edit') }}">
                <x-iconsax-lin-setting-4 class="icons text-gray-400" width="24px" height="24px"/>
            </a>

            <div class="move-icon d-flex-center cursor-pointer">
                <x-iconsax-lin-arrow-3 class="icons text-gray-400" width="24px" height="24px"/>
            </div>
        </div>
    </div>
</li>
