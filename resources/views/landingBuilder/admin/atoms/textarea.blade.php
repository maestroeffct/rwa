<div class="form-group {{ $className ?? '' }}">
    <label class="form-group-label bg-white">{{ $label }}</label>
    <textarea type="text" name="{{ $name }}" class="form-control bg-white" rows="{{ $rows ?? 4 }}" placeholder="{{ $placeholder ?? '' }}">{{ $value ?? '' }}</textarea>
    <div class="invalid-feedback"></div>
    @if($hint)
        <div class="mt-8 font-12 text-gray-500">{{ $hint }}</div>
    @endif
</div>
