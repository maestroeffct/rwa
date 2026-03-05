<form action="/installments/{{ $installment->id }}/store" method="post" enctype="multipart/form-data">
    {{ csrf_field() }}
    <input type="hidden" name="item" value="{{ request()->get('item') }}">
    <input type="hidden" name="item_type" value="{{ request()->get('item_type') }}">

    <div class="px-16">
        {{-- Verify Section --}}
        @if($installment->request_uploads or $installment->needToVerify())
            <div class="mt-36">
                @if($installment->needToVerify())
                    <h3 class="font-14 font-weight-bold">{{ trans('update.verify_installments') }}</h3>

                    <div class="text-gray-500 mt-8">{!! nl2br($installment->verification_description) !!}</div>

                    {{-- Banner --}}
                    @if(!empty($installment->verification_banner))
                        <div class="d-flex-center w-100 mt-24">
                            <img src="{{ $installment->verification_banner }}" alt="{{ $installment->main_title }}" class="img-fluid">
                        </div>
                    @endif

                    {{-- Video --}}
                    @if(!empty($installment->verification_video))
                        <div class="installment-video-card mt-24 rounded-16">
                            <video id="installmentVerifyVideo" class="js-init-plyr-io plyr-io-video rounded-16" oncontextmenu="return false;" controlsList="nodownload" controls preload="auto" data-setup='{"fluid": true}'>
                                <source src="{{ $installment->verification_video }}" type="video/mp4"/>
                            </video>
                        </div>
                    @endif
                @endif

                {{-- Attachments --}}
                @if($installment->request_uploads)
                    <div class="mt-24">
                        <h4 class="font-14 font-weight-bold">{{ trans('update.attachments') }}</h4>

                        @error('attachments')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror

                        <div class="js-attachment-item-inputs d-flex align-items-center gap-16 position-relative mt-24">
                            <div class="form-group mb-0 flex-1">
                                <label class="form-group-label">{{ trans('public.title') }}</label>
                                <input type="text" name="attachments[record][title]" class="form-control">
                            </div>

                            <div class="form-group mb-0 flex-1">
                                <label class="form-group-label">{{ trans('update.attachment') }}</label>
                                <div class="d-flex align-items-center">
                                    <div class="custom-file bg-white">
                                        <input type="file" name="attachments[record][file]" class="custom-file-input bg-white js-ajax-upload-file-input" data-upload-name="attachments[record][file]" id="attachmentInput1">
                                        <span class="custom-file-text bg-white"></span>
                                        <label class="custom-file-label bg-transparent" for="attachmentInput1">
                                            <x-iconsax-lin-export class="icons text-gray-border" width="24px" height="24px"/>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="js-add-attachment d-flex-center size-48 bg-primary rounded-12 cursor-pointer">
                                <x-iconsax-lin-add class="text-white" width="24px" height="24px"/>
                            </div>
                        </div>

                        <div class="js-attachments-card">

                        </div>


                    </div>
                @endif
            </div>
        @endif


        {{-- Installment Terms & Rules --}}
        <div class="mt-24">
            <h3 class="font-14 font-weight-bold">{{ trans('update.installment_terms_&_rules') }}</h3>

            <div class="text-gray-500 mt-8">{!! nl2br(getInstallmentsTermsSettings('terms_description')) !!}</div>

            <div class="d-flex align-items-center bg-gray-100 mt-16 p-16 border-gray-300 rounded-16">
                <x-iconsax-bol-info-circle class="icons text-gray-500" width="40px" height="40px"/>
                <div class="ml-8">
                    <h4 class="font-14 text-gray-500 font-weight-bold">{{ trans('update.important') }}</h4>
                    <p class="mt-4 font-12 text-gray-500">{{ trans('update.by_purchasing_installment_plans_you_will_accept_installment_terms_and_rules') }}</p>
                </div>
            </div>
        </div>

        @if(!empty($hasPhysicalProduct))
            @include('design_1.web.installments.includes.shipping_and_delivery')
        @endif

        @if(!empty(request()->get('quantity')))
            <input type="hidden" name="quantity" value="{{ request()->get('quantity') }}">
        @endif

        @if(!empty(request()->get('specifications')) and count(request()->get('specifications')))
            @foreach(request()->get('specifications') as $k => $specification)
                <input type="hidden" name="specifications[{{ $k }}]" value="{{ $specification }}">
            @endforeach
        @endif

    </div>

    <div class="d-flex align-items-center justify-content-between mt-16 pt-16 px-16 border-top-gray-200">
        <a href="{{ url()->previous() }}" class="btn btn-lg bg-gray-300 text-gray-500">{{ trans('update.back') }}</a>

        <button type="submit" class="btn btn-lg btn-primary">
            @if($installment->needToVerify())
                @if(!empty($installment->upfront))
                    {{ trans('update.submit_and_checkout') }}
                @else
                    {{ trans('update.submit_request') }}
                @endif
            @else
                @if(!empty($installment->upfront))
                    {{ trans('update.proceed_to_checkout') }}
                @else
                    {{ trans('update.finalize_request') }}
                @endif
            @endif
        </button>
    </div>

</form>
