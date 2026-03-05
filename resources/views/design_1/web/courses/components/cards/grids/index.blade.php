@php
    $cardName = getThemeContentCardStyle("course");
@endphp

@push('styles_top')
    @if(empty($withoutStyles))
        <link rel="stylesheet" href="{{ getDesign1StylePath("course_cards/{$cardName}") }}">
    @endif
@endpush

@foreach($courses as $course)
    <div class="{{ !empty($gridCardClassName) ? $gridCardClassName : '' }}">
        @include("design_1.web.courses.components.cards.grids.{$cardName}", ['course' => $course])
    </div>
@endforeach
