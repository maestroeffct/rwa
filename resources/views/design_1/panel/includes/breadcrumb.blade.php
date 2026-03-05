@if(!empty($breadcrumbs))
    <div class="breadcrumb d-flex align-items-center {{ !empty($breadcrumbClassName) ? $breadcrumbClassName : '' }}">
        @foreach($breadcrumbs as $breadcrumb)
            @if(empty($breadcrumb['url']))
                <div class="breadcrumb-item font-14 text-gray-500">{{ $breadcrumb['text'] }}</div>
            @else
                <a href="{{ $breadcrumb['url'] }}" class="breadcrumb-item font-14 text-gray-500">{{ $breadcrumb['text'] }}</a>
                <x-iconsax-lin-arrow-right-1 class="icons text-gray-500 mx-8" width="14px" height="14px"/>
            @endif
        @endforeach
    </div>
@endif
