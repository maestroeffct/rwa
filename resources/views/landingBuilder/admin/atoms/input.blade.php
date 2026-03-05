<div class="form-group {{ $className ?? '' }}">
    <label class="form-group-label bg-white">{{ $label }}</label>
    <input type="{{ !empty($type) ? $type : 'text' }}" name="{{ $name }}" class="form-control bg-white {{ !empty($inputClass) ? $inputClass : '' }}" value="{!! $value ?? '' !!}" placeholder="{{ $placeholder ?? '' }}">
    <div class="invalid-feedback"></div>
    @if($hint)
        <div class="mt-8 font-12 text-gray-500">{{ $hint }}</div>
    @endif

    @if(!empty($icon))
        <span class="has-translation bg-transparent">
            @svg("iconsax-lin-{$icon}", ['height' => 24, 'width' => 24, 'class' => "icons text-gray-400"])
        </span>
    @endif
</div>
