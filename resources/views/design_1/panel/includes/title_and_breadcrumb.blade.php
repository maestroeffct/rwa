<div class="panel-title-and-breadcrumb d-flex align-items-center justify-content-between bg-white px-20 px-md-32">
    <div class="d-flex align-items-center gap-12">
        <div class="js-show-panel-sidebar cursor-pointer d-flex d-lg-none">
            <x-iconsax-lin-menu class="icons text-dark" width="24px" height="24px"/>
        </div>

        <h3 class="font-16 font-weight-bold">{{ $pageTitle ?? '' }}</h3>
    </div>

    @include('design_1.panel.includes.breadcrumb')
</div>
