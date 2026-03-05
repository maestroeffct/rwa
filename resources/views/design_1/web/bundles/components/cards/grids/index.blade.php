@php
    $cardName = getThemeContentCardStyle("bundle");
@endphp

@push('styles_top')
    @if(empty($withoutStyles))
        <link rel="stylesheet" href="{{ getDesign1StylePath("bundle_cards/{$cardName}") }}">
    @endif
@endpush

@foreach($bundles as $bundle)
    <div class="{{ !empty($gridCardClassName) ? $gridCardClassName : '' }}">
        @include("design_1.web.bundles.components.cards.grids.{$cardName}", ['bundle' => $bundle])
    </div>
@endforeach
