<button class="d-flex align-items-center gap-4 {{ !empty($noBtnTransparent) ? '' : 'btn-transparent' }} {{ $btnClass ?? '' }}"
        data-confirm="{{ $deleteConfirmMsg ?? trans('admin/main.delete_confirm_msg') }}"
        data-confirm-href="{{ $url }}"
        data-confirm-text-yes="{{ trans('admin/main.yes') }}"
        data-confirm-text-cancel="{{ trans('admin/main.cancel') }}"
        @if(empty($btnText))
            data-toggle="tooltip" data-placement="top" title="{{ !empty($tooltip) ? $tooltip : trans('admin/main.delete') }}"
    @endif
>
    @if(!empty($btnIcon))
        @php
            $btnIconType = (!empty($iconType) and in_array($iconType, ['lin', 'bol', 'bul'])) ? $iconType : 'lin';
            $btnIconClass = !empty($iconClass) ? $iconClass : 'text-black';
        @endphp

        @svg("iconsax-{$btnIconType}-{$btnIcon}", ['width' => '18px', 'height' => '18px', 'class' => "icons {$btnIconClass}"])
    @endif

    <span class="">{!! $btnText ?? '' !!}</span>
</button>
