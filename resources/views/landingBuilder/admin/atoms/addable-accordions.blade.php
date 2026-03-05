<div class="js-addable-accordions {{ $className ?? '' }}" id="allAccordions">
    <div class="d-flex align-items-center justify-content-between mb-24">
        <div class="">
            <h5 class="font-14 {{ !empty($titleClassName) ? $titleClassName : '' }}">{{ $title }}</h5>

            @if(!empty($hint))
                <p class="font-12 text-gray-500 mt-4">{{ $hint }}</p>
            @endif
        </div>

        <div class="js-addable-accordions-add-btn d-flex align-items-center gap-4 text-primary cursor-pointer" data-main-row="{{ !empty($mainRow) ? $mainRow : '' }}">
            <x-iconsax-lin-add class="icons text-primary" width="16px" height="16px"/>
            <span class="">{{ !empty($addText) ? $addText : trans('update.add_an_item') }}</span>
        </div>
    </div>

    <div class="js-addable-accordions-lists">
        {{ !empty($slot) ? $slot : '' }}
    </div>
</div>
