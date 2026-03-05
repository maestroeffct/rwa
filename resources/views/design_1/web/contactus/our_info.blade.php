<div class="d-flex flex-column bg-primary p-16 rounded-16 w-100 h-100">

    <div class="mb-40">
        {{-- Contact Numbers --}}
        <div class="d-flex align-items-start">
            <x-iconsax-bul-call class="icons text-white" width="32px" height="32px"/>
            <div class="ml-8 mt-4">
                <h4 class="font-16 font-weight-bold text-white">{{ trans('update.contact_numbers') }}</h4>

                @if(!empty($contactSettings['phones']))
                    @php
                        $contactNumbers = explode(',', $contactSettings['phones']);
                    @endphp

                    @if(!empty($contactNumbers) and is_array($contactNumbers))
                        @foreach($contactNumbers as $contactNumber)
                            <p class="font-14 text-white mt-8">{{ $contactNumber }}</p>
                        @endforeach
                    @endif
                @else
                    <p class="font-14 text-white mt-8">{{ trans('site.not_defined') }}</p>
                @endif
            </div>
        </div>

        {{-- Email --}}
        <div class="d-flex align-items-start mt-32">
            <x-iconsax-bul-sms class="icons text-white" width="32px" height="32px"/>
            <div class="ml-8 mt-4">
                <h4 class="font-16 font-weight-bold text-white">{{ trans('public.email') }}</h4>

                @if(!empty($contactSettings['emails']))
                    @php
                        $contactEmails = explode(',', $contactSettings['emails']);
                    @endphp

                    @if(!empty($contactEmails) and is_array($contactEmails))
                        @foreach($contactEmails as $contactEmail)
                            <p class="font-14 text-white mt-8">{{ $contactEmail }}</p>
                        @endforeach
                    @endif
                @else
                    <p class="font-14 text-white mt-8">{{ trans('site.not_defined') }}</p>
                @endif
            </div>
        </div>

        {{-- location --}}
        <div class="d-flex align-items-start mt-32">
            <x-iconsax-bul-location class="icons text-white" width="32px" height="32px"/>
            <div class="ml-8 mt-4">
                <h4 class="font-16 font-weight-bold text-white">{{ trans('update.address') }}</h4>

                @if(!empty($contactSettings['address']))
                    <p class="font-14 text-white mt-8">{!! nl2br($contactSettings['address']) !!}</p>
                @else
                    <p class="font-14 text-white mt-8">{{ trans('site.not_defined') }}</p>
                @endif
            </div>
        </div>
    </div>


    @if(!empty($contactSettings['additional_information_title']) and !empty($contactSettings['additional_information_subtitle']))
        <div class="contact-page-additional-information-card bg-white p-16 rounded-12 mt-auto">
            <h5 class="font-14 font-weight-bold">{{ $contactSettings['additional_information_title'] }}</h5>
            <div class="mt-8 font-12 text-gray-500">{!! nl2br($contactSettings['additional_information_subtitle']) !!}</div>

            @if(!empty($contactSettings['additional_information_image']))
                <div class="contact-page-adif__image d-flex-center">
                    <img src="{{ $contactSettings['additional_information_image'] }}" alt="" class="img-fluid">
                </div>
            @endif
        </div>
    @endif

</div>
