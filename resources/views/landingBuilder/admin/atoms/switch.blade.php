<div class="form-group {{ $className ?? '' }}">
    <div class="d-flex align-items-center">
        <div class="custom-switch mr-8">
            <input type="hidden" name="{{ $name }}" value="0">
            <input id="{{ $id }}_switch" type="checkbox" name="{{ $name }}" class="custom-control-input" {{ !empty($checked) ? 'checked' : '' }}>
            <label class="custom-control-label cursor-pointer" for="{{ $id }}_switch"></label>
        </div>

        <div class="">
            <label class="cursor-pointer" for="{{ $id }}_switch">{{ $label }}</label>
        </div>
    </div>

    @if($hint)
        <div class="mt-8 font-12 text-gray-500">{{ $hint }}</div>
    @endif
</div>
