<div class="bg-white p-16 rounded-24 mt-24">
    <h3 class="font-16">{{ trans('update.about_this_product') }}</h3>

    @if($product->description)
        <div class="product-show__description mt-12">
            {!! $product->description !!}
        </div>
    @endif

    {{-- FAQ --}}
    @if(!empty($product->faqs) and $product->faqs->count() > 0)
        <div id="productFAQParent" class="mt-32">
            <div class="">
                <h2 class="font-16 font-weight-bold">{{ trans('public.faq') }}</h2>
                <p class="mt-4 font-12 text-gray-500">{{ trans('update.check_frequently_asked_questions_about_this_product') }}</p>
            </div>

            @foreach($product->faqs as $faq)
                <div class="accordion p-20 rounded-24 border-gray-200 bg-white {{ $loop->first ? 'mt-16' : 'mt-20' }}">
                    <div class="accordion__title d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center cursor-pointer" href="#productFAQ_{{ $faq->id }}" data-parent="#productFAQParent" role="button" data-toggle="collapse">
                            <div class="size-24">
                                <x-iconsax-lin-message-question class="icons text-primary" width="24px" height="24px"/>
                            </div>

                            <div class="font-14 font-weight-bold ml-8">
                                {{ clean($faq->title,'title') }}
                            </div>
                        </div>

                        <div class="collapse-arrow-icon d-flex cursor-pointer" href="#productFAQ_{{ $faq->id }}" data-parent="#productFAQParent" role="button" data-toggle="collapse">
                            <x-iconsax-lin-arrow-up-1 class="icons text-gray-400" width="16px" height="16px"/>
                        </div>
                    </div>

                    <div id="productFAQ_{{ $faq->id }}" class="accordion__collapse pt-0 mt-20 border-0 " role="tabpanel">
                        <div class="p-16 rounded-8 border-gray-200 text-gray-500 mt-8">
                            {{ clean($faq->answer,'answer') }}
                        </div>
                    </div>
                </div>
            @endforeach

        </div>
    @endif

</div>
