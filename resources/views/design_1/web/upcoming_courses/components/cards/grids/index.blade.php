@php
    $cardName = getThemeContentCardStyle("upcoming_course");
@endphp

@push('styles_top')
    @if(empty($withoutStyles))
        <link rel="stylesheet" href="{{ getDesign1StylePath("upcoming_course_cards/{$cardName}") }}">
    @endif
@endpush

@foreach($upcomingCourses as $upcomingCourse)
    <div class="{{ !empty($gridCardClassName) ? $gridCardClassName : '' }}">
        @include("design_1.web.upcoming_courses.components.cards.grids.{$cardName}", ['upcomingCourse' => $upcomingCourse])
    </div>
@endforeach
