<section>
    <h2 class="section-title after-line mt-2 mb-4">{{ trans('update.images') }}</h2>

    <div class="row">
        <div class="col-12 col-md-6 mt-15">

            <div class="form-group mt-15">
                <label class="input-label">{{ trans('public.thumbnail_image') }}</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <button type="button" class="input-group-text admin-file-manager" data-input="thumbnail" data-preview="holder">
                            <i class="fa fa-upload"></i>
                        </button>
                    </div>
                    <input type="text" name="thumbnail" id="thumbnail" value="{{ !empty($product) ? $product->thumbnail : old('thumbnail') }}" class="form-control @error('thumbnail')  is-invalid @enderror" placeholder="{{ trans('update.thumbnail_images_size') }}"/>
                    <div class="input-group-append">
                        <button type="button" class="input-group-text admin-file-view" data-input="thumbnail">
                            <i class="fa fa-eye"></i>
                        </button>
                    </div>
                    @error('thumbnail')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>
            </div>

            <div id="productImagesInputs" class="form-group mt-15">
                <label class="input-label mb-0">{{ trans('update.images') }}</label>

                <div class="main-row input-group product-images-input-group mt-8">
                    <div class="input-group-prepend">
                        <button type="button" class="input-group-text admin-file-manager" data-input="images_record" data-preview="holder">
                            <i class="fa fa-upload"></i>
                        </button>
                    </div>
                    <input type="text" name="images[]" id="images_record" value="" class="form-control" placeholder="{{ trans('update.product_images_size') }}"/>

                    <button type="button" class="btn ml-4 size-36 p-8 mt-6 btn-primary btn-sm add-btn">
                        <i class="fa fa-plus"></i>
                    </button>
                </div>

                @if(!empty($product->images) and count($product->images))
                    @foreach($product->images as $productImage)
                        <div class="input-group product-images-input-group mt-8">
                            <div class="input-group-prepend">
                                <button type="button" class="input-group-text admin-file-manager" data-input="images_{{ $productImage->id }}" data-preview="holder">
                                    <i class="fa fa-upload"></i>
                                </button>
                            </div>
                            <input type="text" name="images[]" id="images_{{ $productImage->id }}" value="{{ $productImage->path }}" class="form-control" placeholder="{{ trans('update.product_images_size') }}"/>

                        <button type="button" class="btn remove-btn position-absolute" style="right: 0px; top: 4px;">
                            <x-iconsax-lin-close-square class="icons text-danger" width="24px" height="24px"/>
                        </button>
                        </div>
                    @endforeach
                @endif

                @error('images')
                <div class="invalid-feedback d-block">
                    {{ $message }}
                </div>
                @enderror
            </div>

            <div class="form-group mt-25">
                <label class="input-label">{{ trans('public.demo_video') }} ({{ trans('public.optional') }})</label>

                <div class="input-group">
                    <div class="input-group-prepend">
                        <button type="button" class="input-group-text admin-file-manager" data-input="demo_video" data-preview="holder">
                            <i class="fa fa-upload"></i>
                        </button>
                    </div>
                    <input type="text" name="video_demo" id="demo_video" value="{{ !empty($product) ? $product->video_demo : old('video_demo') }}" class="form-control @error('video_demo')  is-invalid @enderror"/>
                    @error('video_demo')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>
            </div>
        </div>

        @if($product->isVirtual())
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <h2 class="section-title after-line">{{ trans('public.files') }}</h2>

                    <div class="px-2 mt-3">
                        <button id="productAddFile" data-product-id="{{ $product->id }}" type="button" class="btn btn-primary btn-sm">{{ trans('public.add_new_files') }}</button>
                    </div>
                </div>

                <div class="mt-1">
                    <p class="font-14 text-gray-500">- {{ trans('update.product_files_hint_1') }}</p>
                </div>


                <div class="mt-2">
                    @if(!empty($product->files) and count($product->files))
                        <div class="table-responsive">
                            <table class="table custom-table text-center font-14">

                                <tr>
                                    <th>{{ trans('public.title') }}</th>
                                    <th>{{ trans('admin/main.description') }}</th>
                                    <th>{{ trans('admin/main.status') }}</th>
                                    <th width="80px">{{ trans('admin/main.action') }}</th>
                                </tr>

                                @foreach($product->files as $file)
                                    <tr>
                                        <td>
                                            <span class="d-block">{{ $file->title }}</span>
                                        </td>
                                        <td>
                                            <input type="hidden" value="{{ nl2br($file->description) }}">
                                            <button type="button" class="js-show-description btn btn-sm btn-light">{{ trans('admin/main.show') }}</button>
                                        </td>
                                        <td>{{ trans('admin/main.'.$file->status) }}</td>

                                        <td>
                                            <div class="btn-group dropdown table-actions position-relative">
                                                <button type="button" class="btn-transparent dropdown-toggle" data-toggle="dropdown">
                                                    <x-iconsax-lin-more class="icons text-gray-500" width="20px" height="20px"/>
                                                </button>

                                                <div class="dropdown-menu dropdown-menu-right">
                                                    <button type="button"
                                                            data-file-id="{{ $file->id }}"
                                                            data-product-id="{{ !empty($product) ? $product->id : '' }}"
                                                            class="dropdown-item d-flex align-items-center mb-3 py-3 px-0 gap-4 edit-file">
                                                        <x-iconsax-lin-edit-2 class="icons text-gray-500 mr-2" width="18px" height="18px"/>
                                                        <span class="text-gray-500 font-14">{{ trans('admin/main.edit') }}</span>
                                                    </button>

                                                    @include('admin.includes.delete_button',[
                                                        'url' => getAdminPanelUrl().'/store/products/files/'.$file->id.'/delete',
                                                        'btnClass' => 'dropdown-item text-danger mb-0 py-3 px-0 font-14',
                                                        'btnText' => trans('admin/main.delete'),
                                                        'btnIcon' => 'trash',
                                                        'iconType' => 'lin',
                                                        'iconClass' => 'text-danger mr-2'
                                                    ])
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </table>
                        </div>
                    @else
                        <div class="d-flex-center flex-column px-32 py-120 text-center">
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
</section>
