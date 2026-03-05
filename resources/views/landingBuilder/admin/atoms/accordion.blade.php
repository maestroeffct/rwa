<div class="accordion p-16 pb-0 rounded-16 border-gray-200 mt-16 {{ $className ?? '' }}">
    <div class="accordion__title d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center cursor-pointer" href="#accordion_{{ $id }}" data-parent="#{{ $parent ?? 'parent' }}" role="button" data-toggle="collapse">
            <h4 class="font-14 text-dark">{{ $title }}</h4>
        </div>

        <div class="d-flex align-items-center gap-16">
            <div class="collapse-arrow-icon d-flex cursor-pointer" href="#accordion_{{ $id }}" data-parent="#{{ $parent ?? 'parent' }}" role="button" data-toggle="collapse">
                <x-iconsax-lin-arrow-up-1 class="icons text-gray-400" width="16px" height="16px"/>
            </div>
        </div>
    </div>

    <div id="accordion_{{ $id }}" class="accordion__collapse pt-16 border-0 {{ !empty($show) ? 'show' : '' }}" role="tabpanel">
        {{ !empty($slot) ? $slot : '' }}
    </div>
</div>
