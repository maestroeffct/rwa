<div class="forum-search-container">
    <div class="position-relative d-flex-center flex-column text-center p-60 bg-white w-100 h-100">
        <div class="js-close-search-drawer close-search-drawer-btn d-flex-center size-36 rounded-4 bg-gray-100 cursor-pointer">
            <x-iconsax-lin-add class="close-icon" width="25px" height="25px"/>
        </div>

        <div class="font-12 text-center">{{ trans('update.looking_for_something') }}</div>
        <h4 class="font-16 mt-16">{{ trans('update.search_in_forums') }} 🧐</h4>

        <form action="" class="px-24" method="get">
            <div class="form-group mt-24 mb-0 w-100 h-100">
                <input type="text" name="search" class="form-control rounded-12 bg-gray-100 border-0 w-100 h-100" value="{{ request()->get('search') }}" placeholder="{{ trans('update.search_a_keyword') }}">

                <button type="submit" class="has-translation bg-transparent border-0">
                    <x-iconsax-lin-search-normal class="icons text-gray-500 mt-6 text-hover-primary" width="24px" height="24px"/>
                </button>
            </div>
        </form>
    </div>
</div>
<div class="forum-search-container__mask"></div>
