<div class="js-addable-items {{ $className ?? '' }}"
     data-name="{{ $inputName }}"
     data-label="{{ $inputLabel }}"
     data-placeholder="{{ $placeholder ?? '' }}"
>
    <div class="d-flex align-items-center justify-content-between mb-24">
        <h5 class="font-14 {{ $titleClass ?? 'text-gray-500' }}">{{ $title }}</h5>

        <div class="js-addable-items-add-btn d-flex align-items-center gap-4 text-primary cursor-pointer">
            <x-iconsax-lin-add class="icons text-primary" width="16px" height="16px"/>
            <span class="">{{ !empty($addText) ? $addText : trans('update.add_items') }}</span>
        </div>
    </div>

    <div class="js-addable-items-lists">

        @if(!empty($items) and count($items))
            @foreach($items as $itemKey => $item)
                @if(!empty($item))
                    <div class="js-addable-item-card d-flex align-items-center gap-12 mt-24">
                        <div class="form-group mb-0 flex-1">
                            <label class="form-group-label bg-white">{{ $inputLabel }}</label>

                            <div class="custom-file bg-white">
                                <input type="hidden" name="{{ str_replace('record', $itemKey, $inputName) }}" value="{!! $item !!}">
                                <input type="file" name="{{ str_replace('record', $itemKey, $inputName) }}" class="custom-file-input" id="{{ $inputName }}_file_{{ $itemKey }}" {{ !empty($accept) ? 'accept='.$accept : '' }}>
                                <span class="custom-file-text text-dark">{{ getFileNameByPath($item) }}</span>

                                <label class="custom-file-label bg-transparent" for="{{ $inputName }}_file_{{ $itemKey }}">
                                <span class="has-translation bg-transparent w-auto h-auto">
                                    <x-iconsax-lin-export class="icons text-gray-400" width="24px" height="24px"/>
                                </span>
                                </label>
                            </div>
                        </div>

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
            <div class="form-group mb-0 flex-1">
                <label class="form-group-label bg-white">__l</label>

                <div class="custom-file bg-white">
                    <input type="file" name="__n" class="custom-file-input" id="__n_file" {{ !empty($accept) ? 'accept='.$accept : '' }}>
                    <span class="custom-file-text text-dark">__p</span>

                    <label class="custom-file-label bg-transparent" for="__n_file">
                        <span class="has-translation bg-transparent w-auto h-auto">
                            <x-iconsax-lin-export class="icons text-gray-400" width="24px" height="24px"/>
                        </span>
                    </label>
                </div>
            </div>

            <div class="js-addable-items-remove-btn d-flex-center size-48 rounded-12 bg-danger-30 text-danger cursor-pointer">__i</div>
        </div>
    </div>

</div>
