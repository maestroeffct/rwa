@if(!empty($advertisingBannersSidebar) and count($advertisingBannersSidebar))
    <div class="row">
        @foreach($advertisingBannersSidebar as $sidebarBanner)
            <div class="mt-32 mt-16-48 col-{{ $sidebarBanner->size }}">
                <a href="{{ $sidebarBanner->link }}" class="d-flex sidebar-ads rounded-16">
                    <img src="{{ $sidebarBanner->image }}" class="img-cover rounded-16" alt="{{ $sidebarBanner->title }}">
                </a>
            </div>
        @endforeach
    </div>
@endif
