<div class="bg-white p-16 rounded-24">
    <div class="d-flex align-items-center justify-content-between">
        <div class="">
            <h3 class="font-14 text-dark">{{ $session->title }}</h3>
            <div class="d-flex align-items-center gap-40 mt-4 font-12 text-gray-500">
                <span class="">{{ dateTimeFormat($session->date, 'j M Y H:i') }}</span>
                <span class="">{{ $session->duration }} {{ trans('minutes') }}</span>
            </div>
        </div>

    </div>

    <div id="hostLiveStream" class="agora-page__player-card mt-16">

        @include('design_1.web.courses.agora.includes.not_started')

    </div>

    {{-- Whiteboard Container --}}
    <div id="whiteboardContainer" class="agora-page__player-card mt-16 d-none">
        <div class="whiteboard-header d-flex align-items-center justify-content-between p-12 bg-gray-100 rounded-t-16">
            <div class="d-flex align-items-center gap-8">
                <x-iconsax-lin-edit-2 class="text-primary" width="20px" height="20px"/>
                <span class="font-14 font-weight-bold text-primary">{{ trans('update.whiteboard') }}</span>
                <div class="whiteboard-page-info ml-16">
                    <span class="font-12 text-gray-500">Page <span id="currentPage">1</span> of <span id="totalPages">1</span></span>
                </div>
            </div>
            @if($isHost)
                <div class="d-flex align-items-center gap-8">
                    <div class="js-whiteboard-undo btn btn-sm btn-outline-primary" title="Undo">
                        <x-iconsax-lin-undo class="text-current" width="16px" height="16px"/>
                    </div>
                    <div class="js-whiteboard-redo btn btn-sm btn-outline-primary" title="Redo">
                        <x-iconsax-lin-redo class="text-current" width="16px" height="16px"/>
                    </div>
                    <div class="js-whiteboard-clear btn btn-sm btn-outline-danger">{{ trans('update.clear') }}</div>
                </div>
            @endif
        </div>
        
        <div class="whiteboard-canvas-container position-relative bg-white rounded-b-16">
            <canvas id="whiteboardCanvas" class="whiteboard-canvas"></canvas>
            
            @if($isHost)
                {{-- Toolbar --}}
                <div class="whiteboard-toolbar position-absolute d-flex flex-column gap-4 p-8">

                    
                    {{-- Drawing Tools --}}
                    <div class="tool-group">
                        <div class="js-whiteboard-tool tool-btn active" data-tool="pen" title="Pen">
                            <x-iconsax-lin-edit class="text-current" width="16px" height="16px"/>
                        </div>
                        <div class="js-whiteboard-tool tool-btn" data-tool="pencil" title="Pencil">
                            <x-iconsax-lin-brush-2 class="text-current" width="16px" height="16px"/>
                        </div>
                        <div class="js-whiteboard-tool tool-btn" data-tool="marker" title="Marker">
                            <x-iconsax-lin-brush-1 class="text-current" width="16px" height="16px"/>
                        </div>
                        <div class="js-whiteboard-tool tool-btn" data-tool="eraser" title="Eraser">
                            <x-iconsax-lin-eraser class="text-current" width="16px" height="16px"/>
                        </div>
                    </div>
                    
                    {{-- Shape Tools --}}
                    <div class="tool-group">
                        <div class="js-whiteboard-tool tool-btn" data-tool="line" title="Line">
                            <x-iconsax-lin-minus class="text-current" width="16px" height="16px"/>
                        </div>
                        <div class="js-whiteboard-tool tool-btn" data-tool="arrow" title="Arrow">
                            <x-iconsax-lin-arrow-right-3 class="text-current" width="16px" height="16px"/>
                        </div>
                        <div class="js-whiteboard-tool tool-btn" data-tool="rectangle" title="Rectangle">
                            <x-iconsax-lin-format-square class="text-current" width="16px" height="16px"/>
                        </div>
                        <div class="js-whiteboard-tool tool-btn" data-tool="circle" title="Circle">
                            <x-iconsax-lin-record-circle class="text-current" width="16px" height="16px"/>
                        </div>
                    </div>
                    
                    {{-- Text Tool --}}
                    <div class="tool-group">
                        <div class="js-whiteboard-tool tool-btn" data-tool="text" title="Text">
                            <x-iconsax-lin-text class="text-current" width="16px" height="16px"/>
                        </div>
                    </div>
                    
                    {{-- Laser Pointer --}}
                    <div class="tool-group">
                        <div class="js-whiteboard-tool tool-btn" data-tool="laser" title="Laser Pointer">
                            <x-iconsax-lin-location class="text-current" width="16px" height="16px"/>
                        </div>
                    </div>
                    
                    {{-- Color & Size Indicators --}}
                    <div class="tool-group">
                        <div class="js-toggle-colors tool-btn" title="Colors">
                            <div class="current-color-indicator" style="background-color: #000000; width: 16px; height: 16px; border-radius: 4px; border: 2px solid white;"></div>
                        </div>
                        <div class="js-toggle-sizes tool-btn" title="Brush Size">
                            <div class="current-size-indicator" style="width: 8px; height: 8px; background-color: #666; border-radius: 50%;"></div>
                        </div>
                    </div>
                </div>
                
                {{-- Color Palette --}}
                <div class="whiteboard-colors position-absolute d-none">
                    <div class="color-group d-flex flex-column gap-4 p-8">
                        <div class="js-color-picker color-btn active" data-color="#000000" style="background-color: #000000" title="Black"></div>
                        <div class="js-color-picker color-btn" data-color="#ff0000" style="background-color: #ff0000" title="Red"></div>
                        <div class="js-color-picker color-btn" data-color="#00ff00" style="background-color: #00ff00" title="Green"></div>
                        <div class="js-color-picker color-btn" data-color="#0000ff" style="background-color: #0000ff" title="Blue"></div>
                        <div class="js-color-picker color-btn" data-color="#ffff00" style="background-color: #ffff00" title="Yellow"></div>
                        <div class="js-color-picker color-btn" data-color="#ff8c00" style="background-color: #ff8c00" title="Orange"></div>
                        <div class="js-color-picker color-btn" data-color="#800080" style="background-color: #800080" title="Purple"></div>
                        <div class="js-color-picker color-btn" data-color="#ffc0cb" style="background-color: #ffc0cb" title="Pink"></div>
                    </div>
                </div>
                
                {{-- Brush Size Selector --}}
                <div class="whiteboard-brush-size position-absolute d-none">
                    <div class="size-group d-flex flex-column gap-4 p-8">
                        <div class="js-size-picker size-btn" data-size="2" title="Small">
                            <div class="size-dot" style="width: 4px; height: 4px; background: #333; border-radius: 50%;"></div>
                        </div>
                        <div class="js-size-picker size-btn active" data-size="4" title="Medium">
                            <div class="size-dot" style="width: 8px; height: 8px; background: #333; border-radius: 50%;"></div>
                        </div>
                        <div class="js-size-picker size-btn" data-size="8" title="Large">
                            <div class="size-dot" style="width: 12px; height: 12px; background: #333; border-radius: 50%;"></div>
                        </div>
                        <div class="js-size-picker size-btn" data-size="12" title="Extra Large">
                            <div class="size-dot" style="width: 16px; height: 16px; background: #333; border-radius: 50%;"></div>
                        </div>
                    </div>
                </div>
            @endif
            
            {{-- Page Navigation --}}
            <div class="whiteboard-pages position-absolute d-flex align-items-center gap-8 p-8">
                @if($isHost)
                    <div class="js-page-prev btn btn-sm btn-outline-primary" title="Previous Page">
                        <x-iconsax-lin-arrow-left-2 class="text-current" width="16px" height="16px"/>
                    </div>
                    <div class="js-page-add btn btn-sm btn-outline-primary" title="Add Page">
                        <x-iconsax-lin-add class="text-current" width="16px" height="16px"/>
                    </div>
                    <div class="js-page-next btn btn-sm btn-outline-primary" title="Next Page">
                        <x-iconsax-lin-arrow-right-2 class="text-current" width="16px" height="16px"/>
                    </div>
                @endif
            </div>
            
            {{-- Zoom Controls --}}
            <div class="whiteboard-zoom position-absolute d-flex align-items-center gap-8 p-8">
                @if($isHost)
                    <div class="js-zoom-out btn btn-sm btn-outline-primary" title="Zoom Out">
                        <x-iconsax-lin-minus-square class="text-current" width="16px" height="16px"/>
                    </div>
                    <span class="zoom-level font-12 text-gray-600">100%</span>
                    <div class="js-zoom-in btn btn-sm btn-outline-primary" title="Zoom In">
                        <x-iconsax-lin-add-square class="text-current" width="16px" height="16px"/>
                    </div>
                    <div class="js-zoom-reset btn btn-sm btn-outline-primary" title="Reset Zoom">
                        <x-iconsax-lin-maximize-4 class="text-current" width="16px" height="16px"/>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div id="remoteLiveStream" class="d-flex flex-wrap gap-16 mt-16"></div>
</div>

<div class="js-stream-bottom-actions mt-16">
    <div class="d-flex flex-column flex-lg-row gap-20 align-items-lg-center justify-content-lg-between bg-white p-24 rounded-24">
        {{-- Timer --}}
        <div class="position-relative">
            <div class="stream-bottom-actions__card-mask"></div>
            <div class="stream-bottom-actions__timer position-relative z-index-2 d-flex-center gap-4 bg-primary p-12 rounded-12">
                <x-iconsax-lin-clock-1 class="icons text-white" width="24px" height="24px"/>
                <div class="js-stream-timer d-flex align-items-center gap-2 text-white flex-lg-grow-1">
                    <span class="hours">00</span>
                    <span>:</span>
                    <span class="minutes">00</span>
                    <span>:</span>
                    <span class="seconds">00</span>
                </div>
            </div>
        </div>

        {{-- Controls --}}
        <div class="d-flex-center gap-20 gap-lg-64">

            @if($sessionStreamType == 'multiple' or $isHost)
                <div class="js-toggle-microphone stream-bottom-actions__toggle-button active d-flex-center size-48 rounded-circle bg-gray-200 bg-hover-gray-300 cursor-pointer" data-tippy-content="{{ trans('update.microphone') }}">
                    <x-iconsax-lin-microphone-2 class="active-icon text-gray-500" width="24px" height="24px"/>
                    <x-iconsax-lin-microphone-slash class="disable-icon text-gray-500" width="24px" height="24px"/>
                </div>

                <div class="js-toggle-camera stream-bottom-actions__toggle-button active d-flex-center size-48 rounded-circle bg-gray-200 bg-hover-gray-300 cursor-pointer" data-tippy-content="{{ trans('update.camera') }}">
                    <x-iconsax-lin-video class="active-icon text-gray-500" width="24px" height="24px"/>
                    <x-iconsax-lin-video-slash class="disable-icon text-gray-500" width="24px" height="24px"/>
                </div>
            @endif

            @if($isHost)
                <div class="js-toggle-share-screen stream-bottom-actions__toggle-button active d-flex-center size-48 rounded-circle bg-gray-200 bg-hover-gray-300 cursor-pointer" data-tippy-content="{{ trans('update.share_screen') }}">
                    <x-iconsax-lin-monitor class="active-icon text-gray-500" width="24px" height="24px"/>
                    <x-iconsax-lin-monitor class="disable-icon text-gray-500" width="24px" height="24px"/>
                </div>

                <div class="js-toggle-whiteboard stream-bottom-actions__toggle-button d-flex-center size-48 rounded-circle bg-gray-200 bg-hover-gray-300 cursor-pointer" data-tippy-content="{{ trans('update.whiteboard') }}">
                    <x-iconsax-lin-edit-2 class="active-icon text-gray-500" width="24px" height="24px"/>
                    <x-iconsax-lin-edit-2 class="disable-icon text-gray-500" width="24px" height="24px"/>
                </div>
            @endif


        </div>

        {{-- End Session --}}
        @if($isHost)
            <div class="position-relative">
                <div class="stream-bottom-actions__card-mask"></div>
                <div class="js-end-live-stream position-relative z-index-2 d-flex-center gap-4 bg-danger p-12 rounded-12 cursor-pointer">
                    <x-iconsax-lin-close-circle class="icons text-white" width="24px" height="24px"/>
                    <div class="text-white">{{ trans('update.end_session') }}</div>
                </div>
            </div>
        @endif

    </div>
</div>
