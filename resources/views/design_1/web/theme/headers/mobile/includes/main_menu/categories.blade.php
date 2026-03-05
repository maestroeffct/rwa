@if(!empty($categories))
    @foreach($categories as $category)
        @if(!empty($category->subCategories) and count($category->subCategories))
            <div class="accordion py-12">
                <div class="accordion__title d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center gap-8 font-20 text-gray-500" href="#mobileHeaderCategory_{{ $category->id }}" role="button" data-toggle="collapse">
                        @if(!empty($category->icon))
                            <div class="size-24">
                                <img src="{{ $category->icon }}" class="img-fluid" alt="{{ $category->title }} icon">
                            </div>
                        @endif

                        <span class="">{{ $category->title }}</span>
                    </div>

                    <span class="collapse-arrow-icon d-flex cursor-pointer" href="#mobileHeaderCategory_{{ $category->id }}" role="button" data-toggle="collapse">
                                        <x-iconsax-lin-arrow-up-1 class="icons text-gray-500" width="16px" height="16px"/>
                                    </span>
                </div>

                <div id="mobileHeaderCategory_{{ $category->id }}" class="accordion__collapse pt-12 mt-0 border-0 pl-16" role="tabpanel">
                    @foreach($category->subCategories as $subCategory)
                        <a href="{{ $subCategory->getUrl() }}" class="d-flex align-items-center gap-8 py-12 font-20 text-gray-500">
                            @if(!empty($subCategory->icon))
                                <div class="size-24">
                                    <img src="{{ $subCategory->icon }}" class="img-fluid" alt="{{ $subCategory->title }} icon">
                                </div>
                            @endif

                            <span class="">{{ $subCategory->title }}</span>
                        </a>
                    @endforeach
                </div>
            </div>
        @else
            <a href="{{ $category->getUrl() }}" class="d-flex align-items-center justify-content-between w-100 py-12">
                <div class="d-flex align-items-center gap-8 font-20 text-gray-500">
                    @if(!empty($category->icon))
                        <div class="size-24">
                            <img src="{{ $category->icon }}" class="img-fluid" alt="{{ $category->title }} icon">
                        </div>
                    @endif

                    <span class="">{{ $category->title }}</span>
                </div>

                <x-iconsax-lin-arrow-right-1 class="icons text-gray-500" width="16px" height="16px"/>
            </a>
        @endif
    @endforeach
@endif
