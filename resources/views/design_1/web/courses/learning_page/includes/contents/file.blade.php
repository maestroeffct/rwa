<div class="bg-white rounded-24 p-16">
    @if($file->online_viewer)
        <div class="learning-page__file-player-card mb-16">
            <iframe src="/ViewerJS/index.html#{{ $filePath }}" class="file-online-viewer rounded-sm {{ $file->downloadable ? 'has-download-card' : '' }} " frameborder="0" allowfullscreen></iframe>
        </div>
    @elseif($file->storage == 'youtube')
        <div class="learning-page__file-player-card mb-16 bg-gray-400">
            <div class="js-file-player-el plyr__video-embed w-100 h-100" id="fileVideo{{ $file->id }}">
                <iframe
                    src="{{ $file->file }}?origin={{ url('/') }}&amp;iv_load_policy=0&amp;modestbranding=0&amp;playsinline=0&amp;showinfo=0&amp;rel=0&amp;enablejsapi=0"
                    allowfullscreen
                    allowtransparency
                    allow="autoplay"
                    class="img-cover rounded-16"
                ></iframe>
            </div>
        </div>
    @elseif($file->storage == 'vimeo')
        <div class="learning-page__file-player-card mb-16 bg-gray-400">
            <div class="js-file-player-el plyr__video-embed w-100 h-100" id="fileVideo{{ $file->id }}">
                <iframe
                    src="{{ $file->getVimeoPath() }}?loop=false&amp;byline=false&amp;portrait=false&amp;title=false&amp;speed=true&amp;transparent=0&amp;gesture=media"
                    allowfullscreen
                    allowtransparency
                    allow="autoplay"
                    class="img-cover rounded-16"
                ></iframe>
            </div>
        </div>
    @elseif($file->storage == 'secure_host')
        <div class="learning-page__file-player-card js-learning-file-video-player-box mb-16 bg-gray-400" data-id="{{ $file->id }}">
            <img src="{{ $course->getImageCover() }}" class="img-cover rounded-12" alt="{{ $course->title }}"/>

            <div class="file-player-button js-learning-file-video-player-btn d-flex-center rounded-circle size-92 cursor-pointer" data-id="{{ $file->id }}">
                <x-iconsax-bol-play class="icons text-white" width="32px" height="32px"/>
            </div>
        </div>
    @elseif(in_array($file->storage, ['google_drive', 'iframe']))
        <div class="learning-page__file-player-card mb-16 ">
            {!! $file->file !!}
        </div>
    @elseif($file->storage == "upload_archive")
        <div class="d-flex-center flex-column text-center border-gray-200 rounded-12 py-160 px-48 mb-16">
            <div class="">
                <img src="/assets/design_1/img/courses/learning_page/file_downloadable.svg" alt="" class="img-fluid" width="285px" height="212px">
            </div>
            <h4 class="font-16 mt-12">{{ trans('update.show_html_file') }}</h4>
            <div class="mt-8 font-12 text-gray-500">{{ trans('update.you_can_show_online_the_file_from_the_following_link') }}</div>


            <a href="{{ $course->getUrl() }}/file/{{ $file->id }}/showHtml" class="btn btn-primary btn-lg mt-24" target="_blank">{{ trans('update.show_html_file') }}</a>
        </div>
    @elseif($file->downloadable and !$file->isVideo())
        <div class="d-flex-center flex-column text-center border-gray-200 rounded-12 py-160 px-48 mb-16">
            <div class="">
                <img src="/assets/design_1/img/courses/learning_page/file_downloadable.svg" alt="" class="img-fluid" width="285px" height="212px">
            </div>
            <h4 class="font-16 mt-12">{{ trans('update.download_the_file') }}</h4>
            <div class="mt-8 font-12 text-gray-500">{{ trans('update.you_can_download_the_file_from_the_following_link') }}</div>

            <div class="d-flex align-items-center gap-40 mt-16 ">
                <div class="d-flex align-items-center text-left">
                    <div class="d-flex-center size-40 rounded-circle bg-gray-100">
                        <x-iconsax-lin-document-1 class="icons text-gray-500" width="20px" height="20px"/>
                    </div>
                    <div class="ml-8">
                        <span class="d-block font-12 text-gray-400">{{ trans('public.file_type') }}</span>
                        <span class="d-block font-14 text-gray-500 font-weight-bold mt-2">{{ trans("update.file_type_{$file->file_type}") }}</span>
                    </div>
                </div>

                @if($file->volume > 0)
                    <div class="d-flex align-items-center text-left">
                        <div class="d-flex-center size-40 rounded-circle bg-gray-100">
                            <x-iconsax-lin-ram class="icons text-gray-500" width="20px" height="20px"/>
                        </div>
                        <div class="ml-8">
                            <span class="d-block font-12 text-gray-400">{{ trans('public.volume') }}</span>
                            <span class="d-block font-14 text-gray-500 font-weight-bold mt-2">{{ $file->getVolume() }}</span>
                        </div>
                    </div>
                @endif

            </div>

            <a href="{{ $course->getUrl() }}/file/{{ $file->id }}/download" class="btn btn-primary btn-lg mt-24" target="_blank">{{ trans('home.download') }}</a>
        </div>
    @elseif($file->isVideo())
        <div class="learning-page__file-player-card mb-16 bg-gray-400">
            <video id="fileVideo{{ $file->id }}" class="js-file-player-el plyr-io-video" controls preload="auto" width="100%" height="426" data-poster="{{ $course->getImageCover() }}">
                <source src="{{ $file->file }}" type="video/mp4"/>
            </video>
        </div>
    @endif

    {{-- Footer Actions And Desc --}}
    @include('design_1.web.courses.learning_page.includes.contents.includes.item_footer_actions_and_desc', [
        'item' => $file,
        'itemType' => 'file',
        'courseSlug' => $course->slug,
        'courseUrl' => $course->getUrl(),
        'itemHasPersonalNote' => $hasPersonalNote
    ])

</div>
