@if(!empty($bundle->bundleWebinars) and $bundle->bundleWebinars->count() > 0)
    @php
        $bundleWebinars = [];

        foreach($bundle->bundleWebinars as $bundleWebinar) {
            if(!empty($bundleWebinar->webinar)) {
                $bundleWebinars[] = $bundleWebinar->webinar;
            }
        }
    @endphp

    @if(count($bundleWebinars))
        <div class="row">
            @include('design_1.web.courses.components.cards.rows.index',['courses' => $bundleWebinars, 'rowCardClassName' => "col-12 mt-16"])
        </div>
    @endif
@endif
