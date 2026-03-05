<div class="form-group {{ $className ?? '' }}">
    <label class="form-group-label bg-white">{{ $label }}</label>

    <select name="{{ $name }}" class="form-control bg-white {{ $selectClassName ?? '' }}" data-change-action="{{ !empty($changeActionEls) ? $changeActionEls : '' }}" data-change-parent="{{ !empty($changeActionParent) ? $changeActionParent : '' }}">
        @if(!empty($items) and is_array($items))
            @foreach($items as $item)
                <option value="{{ $item }}" {{ (!empty($value) and $value == $item) ? 'selected' : '' }}>{{ !empty($selectItemDontTrans) ? $item : trans("update.{$item}") }}</option>
            @endforeach
        @endif
    </select>

    <div class="invalid-feedback"></div>

    @if($hint)
        <div class="mt-8 font-12 text-gray-500">{{ $hint }}</div>
    @endif
</div>
