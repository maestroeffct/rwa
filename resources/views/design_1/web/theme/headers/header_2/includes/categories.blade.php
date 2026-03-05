<div class="theme-header-2__dropdown position-relative">
    <div class="d-inline-flex align-items-center gap-8 p-16 rounded-12 bg-gray-100">
        <x-iconsax-lin-category class="icons text-gray-500" width="16px" height="16px"/>
        <span class="text-gray-500">{{ trans('categories.categories') }}</span>
    </div>

    <div class="header-2-dropdown-menu auth-user-info-dropdown-menu py-12">

        <ul class="theme-header-2__categories">
            @foreach($categories as $category)
                <li class="header-2-dropdown-menu__item position-relative">
                    <a href="{{ $category->getUrl() }}" class="d-flex align-items-center justify-content-between w-100 px-16 py-8  {{ (!empty($category->subCategories) and count($category->subCategories)) ? 'js-has-subcategory' : '' }}">
                        <div class="d-flex align-items-center">
                            @if(!empty($category->icon))
                                <img src="{{ $category->icon }}" class="cat-dropdown-menu-icon mr-8" alt="{{ $category->title }} icon">
                            @endif

                            <span class="">{{ $category->title }}</span>
                        </div>

                        @if(!empty($category->subCategories) and count($category->subCategories))
                            <x-iconsax-lin-arrow-right-1 class="icons" width="16px" height="16px"/>
                        @endif
                    </a>

                    @if(!empty($category->subCategories) and count($category->subCategories))
                        <ul class="header-2-dropdown-menu__sub-menu py-12">
                            @foreach($category->subCategories as $subCategory)
                                <li class="">
                                    <a href="{{ $subCategory->getUrl() }}" class="d-flex align-items-center w-100 px-16 py-8">
                                        <div class="d-flex align-items-center w-100">
                                            @if(!empty($subCategory->icon))
                                                <img src="{{ $subCategory->icon }}" class="cat-dropdown-menu-icon mr-8" alt="{{ $subCategory->title }} icon">
                                            @endif

                                            <span class="">{{ $subCategory->title }}</span>
                                        </div>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </li>
            @endforeach
        </ul>

    </div>
</div>
