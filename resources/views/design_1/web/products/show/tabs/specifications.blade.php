<div class="bg-white p-16 rounded-24 mt-24">
    <h3 class="font-16">{{ trans('update.product_specifications') }}</h3>

    <div class="">
        @if(!empty($selectedSpecifications) and count($selectedSpecifications))
            @foreach($selectedSpecifications as $selectedSpecification)
                <div class="product-show__specification-item d-flex align-items-start mt-16 {{ (!$loop->first) ? 'pt-16 border-top-gray-200' : '' }} ">
                    <div class="specification-item-name font-weight-bold text-gray-500 ">
                        {{ $selectedSpecification->specification->title }}
                    </div>

                    <div class="specification-item-value flex-grow-1 px-16">
                        @if($selectedSpecification->type == 'textarea')
                            {!! nl2br($selectedSpecification->value) !!}
                        @elseif(!empty($selectedSpecification->selectedMultiValues))
                            @foreach($selectedSpecification->selectedMultiValues as $selectedSpecificationValue)
                                @if(!empty($selectedSpecificationValue->multiValue))
                                    <span class="d-block">{{ $selectedSpecificationValue->multiValue->title }}</span>
                                @endif
                            @endforeach
                        @endif
                    </div>
                </div>
            @endforeach
        @endif
    </div>
</div>
