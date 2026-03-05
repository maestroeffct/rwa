<div class="js-addable-items {{ $className ?? '' }}"
     data-name="{{ $inputName }}"
     data-label="{{ $inputLabel }}"
     data-placeholder="{{ $placeholder ?? '' }}"
>
    <div class="d-flex align-items-center justify-content-between mb-24">
        <h5 class="font-14 {{ !empty($titleClassName) ? $titleClassName : 'text-gray-500' }}">{{ $title }}</h5>

        <div class="js-addable-items-add-btn d-flex align-items-center gap-4 text-primary cursor-pointer">
            <x-iconsax-lin-add class="icons text-primary" width="16px" height="16px"/>
            <span class="">{{ trans('update.add_items') }}</span>
        </div>
    </div>

    <div class="js-addable-items-lists">

        @if(!empty($items) and count($items))
            @foreach($items as $itemKey => $item)
                @php
                    $clearName = str_replace(['record', 'nabat'], $itemKey, $inputName);
                @endphp

                <div class="js-addable-item-card d-flex align-items-center gap-12 mt-24">
                    <div class="form-group mb-0 flex-1">
                        <label class="form-group-label bg-white">{{ $inputLabel }}</label>
                        <input type="text" name="{{ $clearName }}" class="form-control bg-white" value="{!! $item !!}" placeholder="{{ $placeholder ?? '' }}">
                    </div>

                    <div class="js-addable-items-remove-btn d-flex-center size-48 rounded-12 bg-danger-30 text-danger cursor-pointer">
                        <x-iconsax-lin-add class="icons close-icon text-danger" width="24px" height="24px"/>
                    </div>
                </div>
            @endforeach
        @endif
    </div>


    {{-- Main Row --}}
    <div class="js-addable-main-row d-none">
        <div class="js-addable-item-card d-flex align-items-center gap-12 mt-24">
            <div class="form-group mb-0 flex-1">
                <label class="form-group-label bg-white">__l</label>
                <input type="text" name="__n" class="form-control bg-white" value="" placeholder="__p">
            </div>

            <div class="js-addable-items-remove-btn d-flex-center size-48 rounded-12 bg-danger-30 text-danger cursor-pointer">__i</div>
        </div>
    </div>

</div>
