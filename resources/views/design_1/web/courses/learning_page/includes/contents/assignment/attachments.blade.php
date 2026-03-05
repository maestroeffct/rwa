@if(!empty($assignment->attachments) and count($assignment->attachments))
    <div class="bg-gray-100 p-12 rounded-16 mt-16">
        <h4 class="font-14 text-dark">{{ trans('public.attachments') }}</h4>

        <div class="d-grid grid-columns-4 gap-12 mt-12">
            @foreach($assignment->attachments as $attachment)
                <a href="{{ $attachment->getDownloadUrl() }}" target="_blank" class="d-flex align-items-center p-16 rounded-16 bg-white text-dark">
                    <div class="d-flex-center size-56 bg-gray-100 rounded-circle">
                        <div class="d-flex-center size-40 bg-gray-200 rounded-circle">
                            <x-iconsax-bul-document-download class="icons text-primary" width="24px" height="24px"/>
                        </div>
                    </div>
                    <div class="ml-8">
                        <h5 class="font-14 text-dark">{{ $attachment->title }}</h5>
                        <div class="d-flex align-items-center gap-4 font-12 text-gray-500 mt-4">
                            <span class="">{{ $attachment->getFileSize() }}</span>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

    </div>
@endif
