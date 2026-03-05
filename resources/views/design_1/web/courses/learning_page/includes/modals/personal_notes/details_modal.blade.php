<div class="my-16">
    <div class="text-gray-500">{!! nl2br($personalNote->note) !!}</div>

    @if(!empty($personalNote->attachment))
        <a href="/course/personal-notes/{{ $personalNote->id }}/download-attachment" target="_blank" class="d-flex align-items-center border-gray-300 p-12 rounded-16 mt-16">
            <div class="d-flex-center size-48 rounded-12 bg-primary-20">
                <x-iconsax-bul-import class="icons text-primary" width="24px" height="24px"/>
            </div>
            <div class="ml-8">
                <h5 class="font-14 text-dark">{{ trans('update.attachment') }}</h5>
                <p class="font-12 text-gray-500 mt-4">{{ trans('update.click_to_download') }}</p>
            </div>
        </a>
    @endif
</div>

