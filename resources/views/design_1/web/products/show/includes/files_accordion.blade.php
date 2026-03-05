<div id="parentFilesAccordion">
    @foreach($files as $productFile)
        <div class="accordion bg-gray-100 border-gray-200 p-14 p-lg-24 rounded-12 mt-16">
            <div class="accordion__title d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center cursor-pointer" href="#collapseFiles{{ $productFile->id }}" data-parent="#parentFilesAccordion" role="button" data-toggle="collapse">
                    <div class="d-flex mr-8">
                        @php
                            $productFileIcon = !empty($productFile) ? $productFile->getIconXByType() : 'document';
                        @endphp

                        @svg("iconsax-lin-{$productFileIcon}", ['height' => 20, 'width' => 20, 'class' => 'text-gray-500'])
                    </div>

                    <div class="font-14 font-weight-bold d-block">{{ $productFile->title }}</div>
                </div>

                <div class="collapse-arrow-icon d-flex cursor-pointer" href="#collapseFiles{{ $productFile->id }}" data-parent="#parentFilesAccordion" role="button" data-toggle="collapse">
                    <x-iconsax-lin-arrow-up-1 class="icons text-gray-400" width="16px" height="16px"/>
                </div>
            </div>

            <div id="collapseFiles{{ $productFile->id }}" class="accordion__collapse pt-0 mt-20 border-0 " role="tabpanel">

                <div class="bg-gray-100 p-16 rounded-12 border-gray-200 text-gray-500">
                    {!! nl2br(clean($productFile->description)) !!}
                </div>

                <div class="position-relative d-flex flex-column flex-lg-row align-items-lg-center justify-content-lg-between mt-24 p-16 bg-white border-gray-200 rounded-12">
                    <div class="product-files-separator-with-circles">
                        <span class="circle-top"></span>
                        <span class="circle-bottom"></span>
                    </div>

                    <div class="d-flex align-items-center">

                        @if(!empty($productFile->file_type))
                            <div class="d-flex align-items-center mr-40">
                                <div class="d-flex-center size-32 rounded-circle bg-gray-100">

                                    @svg("iconsax-lin-{$productFileIcon}", ['height' => 16, 'width' => 16, 'class' => 'icons text-gray-500'])
                                </div>
                                <div class="ml-8">
                                    <span class="d-block font-12 text-gray-400">{{ trans('public.file_type') }}</span>
                                    <span class="d-block font-12 font-weight-bold text-gray-500 mt-4">{{ trans("public.{$productFile->file_type}") }}</span>
                                </div>
                            </div>
                        @endif

                        <div class="d-flex align-items-center">
                            <div class="d-flex-center size-32 rounded-circle bg-gray-100">
                                <x-iconsax-lin-ram-2 class="icons text-gray-500" width="16px" height="16px"/>
                            </div>
                            <div class="ml-8">
                                <span class="d-block font-12 text-gray-400">{{ trans('public.volume') }}</span>
                                <span class="d-block font-12 font-weight-bold text-gray-500 mt-4">{{ ($productFile->volume > 0) ? $productFile->getVolume() : '-' }}</span>
                            </div>
                        </div>

                    </div>

                    <div class="d-flex align-items-center gap-12 mt-16 mt-lg-0">
                        @if($productFile->online_viewer)
                            <button type="button" data-href="{{ $productFile->getOnlineViewUrl() }}" class="js-online-show product-file-download-btn d-flex align-items-center justify-content-center text-white border-0 rounded-circle mr-15">
                                <i data-feather="eye" width="20" height="20" class=""></i>
                            </button>
                        @endif

                        <a href="{{ $productFile->getDownloadUrl() }}" target="_blank" class="btn btn-primary btn-lg">
                            <x-iconsax-lin-direct-inbox class="icons text-white" width="16px" height="16px"/>
                            <span class="ml-4 text-white">{{ trans('home.download') }}</span>
                        </a>
                    </div>
                </div>

            </div>
        </div>
    @endforeach
</div>
