<div class="form-group {{ $className ?? '' }}">
    <label class="form-group-label bg-white">{{ $label }}</label>

    <div class="custom-file bg-white">
        @if(!empty($value))
            <input type="hidden" name="{{ $name }}" value="{!! $value !!}">

            <div class="js-custom-file-clear custom-file__clear" data-text="{{ trans('update.select_a_file') }}">
                <x-iconsax-lin-add class="close-icon" width="24px" height="24px"/>
            </div>
        @endif

        <input type="file" name="{{ $name }}" class="custom-file-input" id="{{ $name }}_file" {{ !empty($accept) ? 'accept='.$accept : '' }}>
        <span class="custom-file-text text-dark">{{ $placeholder ?? '' }}</span>

        <label class="custom-file-label bg-transparent" for="{{ $name }}_file">
            @if(!empty($icon))
                @svg("iconsax-lin-{$icon}", ['height' => 24, 'width' => 24, 'class' => "icons text-gray-400"])
            @else
                {{ trans('update.browse') }}
            @endif
        </label>
    </div>

    <div class="invalid-feedback"></div>

    @if($hint)
        <div class="mt-8 font-12 text-gray-500">{{ $hint }}</div>
    @endif
</div>
