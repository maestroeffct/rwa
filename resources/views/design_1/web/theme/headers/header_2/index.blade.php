@if(!empty($themeHeaderData['contents']))
    <div id="themeHeaderVacuum"></div>
    <div class="theme-header-2">
        {{-- Top Nav --}}
        @if(!empty($themeHeaderData['contents']['top_navbar']))
            @include('design_1.web.theme.headers.header_2.top_nav', ['themeHeaderTopNavData' => $themeHeaderData['contents']['top_navbar']])
        @endif

        {{-- Main --}}
        @include('design_1.web.theme.headers.header_2.main')
    </div>
@endif
