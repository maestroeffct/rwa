@php
    $cardName = getThemeContentCardStyle("meeting_package");
@endphp

@push('styles_top')
    @if(empty($withoutStyles))
        <link rel="stylesheet" href="{{ getDesign1StylePath("meeting_package_cards/{$cardName}") }}">
    @endif
@endpush

@foreach($meetingPackages as $meetingPackage)
    <div class="{{ !empty($gridCardClassName) ? $gridCardClassName : '' }}">
        @include("design_1.web.meeting_packages.components.cards.grids.{$cardName}", ['meetingPackage' => $meetingPackage])
    </div>
@endforeach
