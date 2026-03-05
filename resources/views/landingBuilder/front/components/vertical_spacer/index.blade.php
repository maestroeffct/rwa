@if(!empty($landingComponent) and $landingComponent->enable)
    @php
        $contents = [];
        if (!empty($landingComponent->content)) {
            $contents = json_decode($landingComponent->content, true);
        }

    @endphp

    @push('styles_top')

    @endpush

    @if(!empty($contents['space_number']) and $contents['space_number'] > 0)
        <div class="position-relative d-flex" style="height: {{ $contents['space_number'] }}px">

        </div>
    @endif
@endif
