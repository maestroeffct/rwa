
<div class="form-group select2-bg-white {{ $className ?? '' }}">
    <label class="form-group-label bg-white">{{ $label }}</label>

    <select name="{{ $name }}" class="form-control bg-white {{ $selectClassName ?? '' }}" data-allow-clear="true" data-placeholder="{{ $placeholder ?? '' }}" data-dropdown-parent="{{ !empty($dropdownParent) ? $dropdownParent : '' }}">
        <option value="">{{ trans('update.search_icons') }}</option>

        @if(!empty($value))
            <option value="{{ $value }}" selected data-svg="{{ svg("iconsax-{$value}", ['width' => '24px', 'height' => '24px', 'class' => "icons"])->toHtml() }}">{{ $value }}</option>
        @endif
    </select>

    <div class="invalid-feedback"></div>

    @if($hint)
        <div class="mt-8 font-12 text-gray-500">{{ $hint }}</div>
    @endif
</div>
