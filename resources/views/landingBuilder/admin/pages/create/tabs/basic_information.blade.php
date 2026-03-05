<form action="{{ getLandingBuilderUrl(!empty($landingItem) ? ('/'.$landingItem->id ."/update") : '/store') }}" method="post" enctype="multipart/form-data">
    {{ csrf_field() }}

    <div class="row">
        {{-- General Information --}}
        <div class="col-12 col-lg-6">
            <div class="bg-white p-16 rounded-16 border-gray-200">
                <h3 class="font-14 text-dark mb-24">{{ trans('update.general_information') }}</h3>

                @include('design_1.panel.includes.locale.locale_select',[
                        'itemRow' => !empty($landingItem) ? $landingItem : null,
                        'withoutReloadLocale' => false,
                        'extraClass' => '',
                        'extraData' => null
                    ])

                <div class="form-group ">
                    <label class="form-group-label bg-white">{{ trans('public.title') }}</label>
                    <input type="text" name="title" class="form-control bg-white @error('title') is-invalid @enderror" value="{{ (!empty($landingItem) and !empty($landingItem->translate($locale))) ? $landingItem->translate($locale)->title : old('title') }}">
                    @error('title')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-group-label bg-white">{{ trans('update.landing_url') }}</label>
                    <input type="text" name="url" class="form-control bg-white @error('url') is-invalid @enderror" value="{{ !empty($landingItem) ? $landingItem->url : old('url') }}">
                    @error('url')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror

                    <div class="js-copy-input has-translation bg-gray-100 cursor-pointer" data-toggle="tooltip" data-placement="top" title="{{ trans('public.copy') }}" data-copy-text="{{ trans('public.copy') }}" data-done-text="{{ trans('public.copied') }}">
                        <x-iconsax-lin-document-copy class="icons text-gray-400" width="20px" height="20px"/>
                    </div>

                    <div class="mt-8 font-12 text-gray-500">{{ trans('update.landing_url_input_hint') }}</div>
                </div>

                <div class="form-group">
                    <label class="form-group-label bg-white">{{ trans('update.preview') }}</label>

                    <div class="custom-file bg-white">
                        <input type="file" name="preview_img" class="custom-file-input" id="preview_img" accept="image/*">
                        <span class="custom-file-text text-dark">{{ !empty($landingItem->preview_img) ? getFileNameByPath($landingItem->preview_img) : '' }}</span>

                        <label class="custom-file-label bg-transparent" for="preview_img">
                            <x-iconsax-lin-export class="icons text-gray-400" width="24px" height="24px"/>
                        </label>
                    </div>
                    <div class="invalid-feedback"></div>
                    <div class="mt-8 font-12 text-gray-500">{{ trans('update.landing_page_preview_img_input_hint') }}</div>

                    @if(!empty($landingItem) and !empty($landingItem->preview_img))
                        <a href="{{ $landingItem->preview_img }}" target="_blank" class="font-12 text-primary mt-8">{{ trans('update.preview') }}</a>
                    @endif
                </div>

                <div class="form-group">
                    <label class="form-group-label">{{ trans('update.color_combination') }}</label>
                    <select name="color_id" class="form-control @error('color_id') is-invalid @enderror">
                        <option value="">{{ trans('update.theme_color') }}</option>

                        @foreach($themeColors as $themeColor)
                            <option value="{{ $themeColor->id }}" {{ (!empty($landingItem) and $landingItem->color_id == $themeColor->id) ? 'selected' : '' }}>{{ $themeColor->title }}</option>
                        @endforeach
                    </select>

                    @error('color_id')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group d-flex align-items-center">
                    <div class="custom-switch mr-8">
                        <input id="enable_landing_page_switch" type="checkbox" name="enable" class="custom-control-input" {{ (!empty($landingItem) and $landingItem->enable) ? 'checked' : '' }}>
                        <label class="custom-control-label cursor-pointer" for="enable_landing_page_switch"></label>
                    </div>

                    <div class="">
                        <label class="cursor-pointer" for="enable_landing_page_switch">{{ trans('update.enable_landing_page') }}</label>
                    </div>
                </div>
            </div>
        </div>

        {{-- Assigned Pages --}}
        <div class="col-12 col-lg-6">
            @if(!empty($landingItem) and !empty($landingItem->assignedPages) and $landingItem->assignedPages->isNotEmpty())
                <div class="bg-white p-16 rounded-16 border-gray-200">
                    <h3 class="font-14 text-dark mb-24">{{ trans('update.assigned_pages') }}</h3>

                    @foreach($landingItem->assignedPages as $assignedPage)
                        <div class="d-flex align-items-center justify-content-between mt-16 p-16 rounded-12 border-gray-200">
                            <div class="d-flex align-items-center">
                                <div class="d-flex-center size-48 rounded-12 bg-gray-300">
                                    <x-iconsax-bul-home-2 class="icons text-gray-500" width="24px" height="24px"/>
                                </div>
                                <div class="ml-8">
                                    <h5 class="font-14 text-dark">Home Page</h5>
                                    <p class="font-12 text-gray-500 mt-2">Platform Name</p>
                                </div>
                            </div>

                            <div class="actions-dropdown position-relative d-flex justify-content-end align-items-center">
                                <button type="button" class="d-flex-center size-36 bg-gray border-gray-200 rounded-10">
                                    <x-iconsax-lin-more class="icons text-gray-500" width="18"/>
                                </button>

                                <div class="actions-dropdown__dropdown-menu dropdown-menu-width-220 dropdown-menu-top-32">
                                    <ul class="my-8">
                                        <li class="actions-dropdown__dropdown-menu-item">
                                            <a href="" class="delete-action text-danger ">{{ trans('public.delete') }}</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="d-flex-center flex-column text-center py-140 border-dashed border-gray-200 rounded-16">
                    <div class="d-flex-center size-64 rounded-12 bg-warning-30">
                        <x-iconsax-bul-note-remove class="icons text-warning" width="32px" height="32px"/>
                    </div>
                    <h5 class="mt-12 font-16 text-dark">{{ trans('update.no_assigned_page') }}</h5>
                    <p class="font-12 text-gray-500 mt-4">{{ trans('update.this_landing_is_not_assigned_to_any_page') }}</p>
                </div>
            @endif
        </div>

    </div>

    <div class="d-flex align-items-center justify-content-between mt-20 pt-16 border-top-gray-100">
        <div class="d-flex align-items-center">
            <div class="d-flex-center size-48 rounded-12 bg-gray-200">
                <x-iconsax-bol-info-circle class="icons text-gray-400" width="24px" height="24px"/>
            </div>
            <div class="ml-8">
                <h5 class="font-14 text-dark">{{ trans('product.information') }}</h5>
                <p class="font-12 text-gray-500 mt-2">{{ trans('update.sample_information_about_the_landing_page') }}</p>
            </div>
        </div>

        <button type="submit" class="btn btn-primary btn-lg">{{ trans('public.save') }}</button>
    </div>
</form>
