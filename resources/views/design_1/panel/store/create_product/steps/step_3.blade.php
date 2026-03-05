@push('styles_top')
    <link rel="stylesheet" href="/assets/default/vendors/sortable/jquery-ui.min.css"/>
@endpush

<div class="bg-white rounded-16 p-16 mt-32">

    <h3 class="font-14 font-weight-bold">{{ trans('update.thumbnail_&_cover') }}</h3>

    <div class="row">

        @include('design_1.panel.store.create_product.includes.media',[
            'media' => !empty($product) ? $product->thumbnail : null,
            'mediaName' => 'thumbnail',
            'mediaTitle' => trans('update.thumbnail'),
        ])

        <div class="col-12 mt-8">
            @error('thumbnail')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
        </div>
    </div>

    {{-- Images --}}
    <div class="row mt-32">
        <div class="col-lg-12">
            <h3 class="font-16 font-weight-bold">{{ trans('update.images') }}</h3>

            @error('images')
            <div class="invalid-feedback d-block mt-4">{{ $message }}</div>
            @enderror

            <div id="productImagesRow" class="row">

                @if(!empty($product->images) and count($product->images))
                    @foreach($product->images as $productImage)
                        <div class="col-6 col-md-4 col-lg-2 mt-12 js-product-image-col">
                            <div class="create-media-card position-relative p-4">
                                <div class="create-media-card__img d-flex align-items-center justify-content-center w-100 h-100">
                                    <img src="{{ !empty($productImage) ? $productImage->path : '' }}" alt="" class="img-cover rounded-15">

                                    <a href="/panel/store/products/{{ $product->id }}/media/{{ $productImage->id }}/delete" class="delete-action create-media-card__delete-btn d-flex align-items-center justify-content-center">
                                    <span class="d-flex align-items-center justify-content-center p-4">
                                        <x-iconsax-lin-add class="icons text-danger" width="24" height="24"/>
                                    </span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif

                <div class="col-6 col-md-4 col-lg-2 mt-12 js-product-image-col">
                    <div class="create-media-card position-relative p-4">
                        <label for="images" class="create-media-card__label w-100 h-100 rounded-15 flex-column align-items-center justify-content-center cursor-pointer">
                            <div class="create-media-card__circle d-flex align-items-center justify-content-center rounded-circle">
                                <x-iconsax-lin-direct-send class="icons text-primary" width="24" height="24"/>
                            </div>

                            <div class="mt-8 font-12 text-primary">{{ trans('update.upload_an_image') }}</div>
                        </label>

                        <input type="file" name="images[]" id="images" class="js-create-property-images" data-col="col-6 col-md-4 col-lg-2" data-parent-id="productImagesRow" accept="image/*">
                    </div>
                </div>

            </div>
        </div>
    </div>


    {{-- Video --}}
    <h3 class="font-14 font-weight-bold my-24">{{ trans('public.demo_video') }} ({{ trans('public.optional') }})</h3>

    <div class="js-inputs-with-source row">
        @php
            $selectedVideoSource = (!empty($product) and !empty($product->video_demo_source)) ? $product->video_demo_source : null;
        @endphp

        <div class="col-12 col-md-6">
            <div class="form-group ">
                <label class="form-group-label">{{ trans('update.video_source') }}</label>
                <select name="video_demo_source" class="js-upload-source-input form-control select2 @error('video_demo_source') is-invalid @enderror" data-minimum-results-for-search="Infinity">
                    @foreach(getAvailableUploadFileSources() as $source)
                        @php
                            if($loop->first and empty($selectedVideoSource)) {
                                $selectedVideoSource = $source;
                            }
                        @endphp

                        <option value="{{ $source }}" {{ (!empty($product) and $product->video_demo_source == $source) ? 'selected' : '' }}>{{ trans('update.file_source_'.$source) }}</option>
                    @endforeach
                </select>

                @error('video_demo_source')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="col-12 col-md-6">
            <div class="form-group js-online-upload {{ (!in_array($selectedVideoSource, \App\Enums\UploadSource::uploadItems)) ? '' : 'd-none' }}">
                <span class="has-translation bg-transparent">
                    <x-iconsax-lin-link-21 class="icons text-gray-400" width="24px" height="24px"/>
                </span>
                <label class="form-group-label">{{ trans('update.path') }}</label>
                <input type="text" name="demo_video_path" class="form-control" value="{{ !empty($product) ? $product->video_demo : old('demo_video_path') }}" placeholder="{{ trans('update.insert_demo_video_link') }}">
            </div>

            <div class="form-group js-local-upload {{ (in_array($selectedVideoSource, \App\Enums\UploadSource::uploadItems)) ? '' : 'd-none' }}">
                <span class="has-translation bg-transparent">
                    <x-iconsax-lin-export class="icons text-gray-400" width="24px" height="24px"/>
                </span>

                <label class="form-group-label">{{ trans('update.upload_video') }}</label>
                <div class="custom-file bg-white">
                    <input type="file" name="demo_video_local" class="custom-file-input" id="demo_video_local" accept="video/*">
                    <span class="custom-file-text text-dark">{{ trans('update.select_a_video') }}</span>
                    <label class="custom-file-label bg-gray-100" for="demo_video_local">{{ trans('update.browse') }}</label>
                </div>
            </div>
        </div>

    </div>


    {{-- Files --}}
    @if($product->isVirtual())
        <div class="d-flex align-items-center justify-content-between p-12 rounded-16 border-gray-300 border-dashed mt-32">
            <div class="d-flex align-items-center">
                <div class="d-flex-center size-48 bg-primary-20 rounded-12">
                    <x-iconsax-bul-video-tick class="icons text-primary" width="24px" height="24px"/>
                </div>

                <div class="ml-8">
                    <h5 class="font-14 font-weight-bold">{{ trans('public.files') }}</h5>
                    <p class="mt-4 font-12 text-gray-500">{{ trans('update.product_files_hint_1') }}</p>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-6">
                @include('design_1.panel.store.create_product.includes.accordions.file')
            </div>

            <div class="col-lg-6 mt-16">
                @if(!empty($product->files) and count($product->files))
                    <div class="p-16 rounded-16 border-gray-200">
                        <h3 class="font-14 font-weight-bold">{{ trans('public.files') }}</h3>

                        <ul class="draggable-content-lists file-draggable-lists" data-path="" data-drag-class="file-draggable-lists">
                            @foreach($product->files as $fileInfo)
                                @include('design_1.panel.store.create_product.includes.accordions.file',['file' => $fileInfo])
                            @endforeach
                        </ul>
                    </div>
                @else
                    <div class="d-flex-center flex-column px-32 py-120 text-center rounded-16 border-gray-200">
                        <div class="d-flex-center size-64 rounded-12 bg-primary-30">
                            <x-iconsax-bul-document-download class="icons text-primary" width="32px" height="32px"/>
                        </div>
                        <h3 class="font-16 font-weight-bold mt-12">{{ trans('public.files_no_result') }}</h3>
                        <p class="mt-4 font-12 text-gray-500">{!! trans('public.files_no_result_hint') !!}</p>
                    </div>
                @endif
            </div>
        </div>
    @endif

</div>

@push('scripts_bottom')
    <script src="/assets/default/vendors/sortable/jquery-ui.min.js"></script>

@endpush
