@php
    $cardName = getThemeContentCardStyle("instructor");
@endphp

@push('styles_top')
    @if(empty($withoutStyles))
        <link rel="stylesheet" href="{{ getDesign1StylePath("instructors_cards/{$cardName}") }}">
    @endif
@endpush

@foreach($instructors as $instructor)
    <div class="{{ !empty($gridCardClassName) ? $gridCardClassName : '' }}">
        @include("design_1.web.instructors.components.cards.grids.{$cardName}", ['instructor' => $instructor])
    </div>
@endforeach
