@if(!empty($themeHeaderData['contents']))
    <div id="themeHeaderVacuum"></div>
    <div class="theme-header-1">
        {{-- Top Nav --}}
        @if(!empty($themeHeaderData['contents']['top_navbar']))
            @include('design_1.web.theme.headers.header_1.top_nav', ['themeHeaderTopNavData' => $themeHeaderData['contents']['top_navbar']])
        @endif

        {{-- Main --}}
        @include('design_1.web.theme.headers.header_1.main')
    </div>
@endif
