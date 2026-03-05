<section class="mt-30">
    <div class="d-flex justify-content-between align-items-center">
        <h2 class="section-title after-line">{{ trans('update.related_products') }}</h2>
        <button id="addRelatedCourse" type="button" class="btn btn-primary btn-sm mt-3" data-path="{{ getAdminPanelUrl("/relatedProducts/get-form") }}?item={{ $relatedProductItemId }}&item_type={{ $relatedProductItemType }}">{{ trans('update.add_related_products') }}</button>
    </div>

    <div class="row mt-10">
        <div class="col-12">
            @if(!empty($relatedProducts) and !$relatedProducts->isEmpty())
                <div class="table-responsive">
                    <table class="table custom-table border-0 text-center font-14">

                        <tr>
                            <th>{{ trans('public.title') }}</th>
                            <th class="text-left">{{ trans('public.instructor') }}</th>
                            <th>{{ trans('public.price') }}</th>
                            <th>{{ trans('public.publish_date') }}</th>
                            <th width="80px">{{ trans('admin/main.action') }}</th>
                        </tr>

                        @foreach($relatedProducts as $relatedProduct)
                            @if(!empty($relatedProduct->product->title))
                                <tr>
                                    <th>{{ $relatedProduct->product->title }}</th>
                                    <td class="text-left">{{ $relatedProduct->product->creator->full_name }}</td>
                                    <td>{{  handlePrice($relatedProduct->product->price) }}</td>
                                    <td>{{ dateTimeFormat($relatedProduct->product->created_at,'j F Y | H:i') }}</td>

                                    <td>
                                        <div class="btn-group dropdown table-actions position-relative">
                                            <button type="button" class="btn-transparent dropdown-toggle" data-toggle="dropdown">
                                                <x-iconsax-lin-more class="icons text-gray-500" width="20px" height="20px"/>
                                            </button>

                                            <div class="dropdown-menu dropdown-menu-right">
                                                <button type="button"
                                                        class="dropdown-item d-flex align-items-center mb-3 py-3 px-0 gap-4 js-edit-related-course"
                                                        data-path="{{ getAdminPanelUrl("/relatedProducts/{$relatedProduct->id}/edit") }}?item={{ $relatedProductItemId }}&item_type={{ $relatedProductItemType }}">
                                                    <x-iconsax-lin-edit-2 class="icons text-gray-500 mr-2" width="18px" height="18px"/>
                                                    <span class="text-gray-500 font-14">{{ trans('admin/main.edit') }}</span>
                                                </button>

                                                @include('admin.includes.delete_button',[
                                                    'url' => getAdminPanelUrl().'/relatedProducts/'.$relatedProduct->id.'/delete',
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
                            @endif
                        @endforeach

                    </table>
                </div>
            @else
                <div class="d-flex-center flex-column px-32 py-120 text-center">
                    <div class="d-flex-center size-64 rounded-12 bg-primary-30">
                        <x-iconsax-bul-arrange-circle-2 class="icons text-primary" width="32px" height="32px"/>
                    </div>
                    <h3 class="font-16 font-weight-bold mt-12">{{ trans('update.related_products_no_result') }}</h3>
                    <p class="mt-4 font-12 text-gray-500">{!! trans('update.related_products_no_result_hint') !!}</p>
                </div>
            @endif
        </div>
    </div>
</section>

@push('scripts_bottom')
    <!-- Modal -->
    <script src="/assets/admin/js/parts/related_products.min.js"></script>
@endpush
