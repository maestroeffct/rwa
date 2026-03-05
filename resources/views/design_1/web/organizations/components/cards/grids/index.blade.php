@php
    $cardName = getThemeContentCardStyle("organization");
@endphp

@push('styles_top')
    @if(empty($withoutStyles))
        <link rel="stylesheet" href="{{ getDesign1StylePath("organizations_cards/{$cardName}") }}">
    @endif
@endpush

@foreach($organizations as $organization)
    <div class="{{ !empty($gridCardClassName) ? $gridCardClassName : '' }}">
        @include("design_1.web.organizations.components.cards.grids.{$cardName}", ['organization' => $organization, 'organizationCardClassName' => !empty($organizationCardClassName) ? $organizationCardClassName : ''])
    </div>
@endforeach
