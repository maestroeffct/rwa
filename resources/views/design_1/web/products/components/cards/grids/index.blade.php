@php
    $cardName = getThemeContentCardStyle("product");
@endphp

@push('styles_top')
    @if(empty($withoutStyles))
        <link rel="stylesheet" href="{{ getDesign1StylePath("product_cards/{$cardName}") }}">
    @endif
@endpush

@foreach($products as $product)
    <div class="{{ !empty($gridCardClassName) ? $gridCardClassName : '' }}">
        @include("design_1.web.products.components.cards.grids.{$cardName}", ['product' => $product])
    </div>
@endforeach
