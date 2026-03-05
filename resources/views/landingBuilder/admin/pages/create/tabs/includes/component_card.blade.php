<div class="d-flex align-items-center justify-content-between mt-16 bg-white p-16 rounded-12 js-landing-builder-component-card js-landing-builder-component-item_{{ $landingBuilderComponent->category }}">
    <div class="d-flex align-items-center">
        <div class="d-flex-center size-48 rounded-12 bg-gray-300">
            @php
                $icon = \App\Enums\LandingBuilderComponentCategories::getIcon($landingBuilderComponent->category);
            @endphp
            @svg("iconsax-bul-{$icon}", ['height' => 24, 'width' => 24, 'class' => "icons text-gray-500"])
        </div>
        <div class="ml-8">
            <h5 class="font-14">{{ trans("update.{$landingBuilderComponent->name}_component_title") }}</h5>
            <p class="font-12 text-gray-500 mt-2">{{ trans("update.{$landingBuilderComponent->name}_component_hint") }}</p>
        </div>
    </div>

    <div class="d-flex align-items-center gap-16">
        <div class="js-view-landing-component d-flex-center cursor-pointer"
             data-path="{{ getLandingBuilderUrl("/components/{$landingBuilderComponent->name}/preview") }}"
             data-title="{{ trans('update.component_preview') }}"
             data-tippy-content="{{ trans('update.preview') }}"
        >
            <x-iconsax-lin-eye class="icons text-gray-400" width="24px" height="24px"/>
        </div>

        <div class="js-add-component-to-landing d-flex-center cursor-pointer"
             data-item="{{ $landingBuilderComponent->id }}"
             data-landing="{{ $landingItem->id }}"
             data-tippy-content="{{ trans('update.add_to_landing') }}"
        >
            <x-iconsax-lin-element-plus class="icons text-gray-400" width="24px" height="24px"/>
        </div>
    </div>
</div>
