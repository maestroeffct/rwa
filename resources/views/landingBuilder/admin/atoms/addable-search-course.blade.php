<div class="js-addable-items {{ $className ?? '' }}"
     data-name="{{ $inputName }}"
     data-label="{{ $inputLabel }}"
     data-placeholder="{{ trans('update.search_a_course') }}"
>
    <div class="d-flex align-items-center justify-content-between mb-24">
        <h5 class="font-14 {{ $titleClass ?? 'text-gray-500' }}">{{ $title }}</h5>

        <div class="js-addable-items-add-btn d-flex align-items-center gap-4 text-primary cursor-pointer">
            <x-iconsax-lin-add class="icons text-primary" width="16px" height="16px"/>
            <span class="">{{ !empty($addText) ? $addText : trans('update.add_a_course') }}</span>
        </div>
    </div>

    <div class="js-addable-items-lists">

        @if(!empty($items) and count($items))
            @foreach($items as $itemKey => $item)
                @if(!empty($item))
                    <div class="js-addable-item-card d-flex align-items-center gap-12 mt-24">

                        <x-landingBuilder-searchable-course
                            label="{{ $inputLabel }}"
                            name="{{ str_replace('record', $itemKey, $inputName) }}"
                            value="{{ $item }}"
                            placeholder="{{ trans('update.search_a_course') }}"
                            className=" mb-0 flex-1"
                            hint=""
                            selectClassName=""
                            changeActionEls=""
                            changeActionParent=""
                        />

                        <div class="js-addable-items-remove-btn d-flex-center size-48 rounded-12 bg-danger-30 text-danger cursor-pointer">
                            <x-iconsax-lin-add class="icons close-icon text-danger" width="24px" height="24px"/>
                        </div>
                    </div>
                @endif
            @endforeach
        @endif
    </div>


    {{-- Main Row --}}
    <div class="js-addable-main-row d-none">
        <div class="js-addable-item-card d-flex align-items-center gap-12 mt-24">

            <x-landingBuilder-searchable-course
                label="__l"
                name="__n"
                value=""
                placeholder="{{ trans('update.search_a_course') }}"
                className=" mb-0 flex-1"
                hint=""
                selectClassName=""
                changeActionEls=""
                changeActionParent=""
                searchSelect2Class="js-addable-item-make-select-2"
            />

            <div class="js-addable-items-remove-btn d-flex-center size-48 rounded-12 bg-danger-30 text-danger cursor-pointer">__i</div>
        </div>
    </div>

</div>
