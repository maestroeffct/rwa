@php
    $cardName = "row_card_1";
@endphp

@push('styles_top')
    @if(empty($withoutStyles))
        <link rel="stylesheet" href="{{ getDesign1StylePath("course_cards/{$cardName}") }}">
    @endif
@endpush

@foreach($courses as $course)
    <div class=" {{ !empty($rowCardClassName) ? $rowCardClassName : '' }}">
        @include("design_1.web.courses.components.cards.rows.{$cardName}", ['course' => $course])
    </div>
@endforeach
