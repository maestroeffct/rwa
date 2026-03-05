@if(!empty($advertisingBanners) and count($advertisingBanners))
    <div class="row">
        @foreach($advertisingBanners as $banner)
            <div class="mt-32 mt-lg-48 col-{{ $banner->size }}">
                <a href="{{ $banner->link }}" class="d-flex rounded-16">
                    <img src="{{ $banner->image }}" class="img-cover rounded-16" alt="{{ $banner->title }}">
                </a>
            </div>
        @endforeach
    </div>
@endif
