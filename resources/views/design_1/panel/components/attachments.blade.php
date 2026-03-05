<div id="{{ !empty($cardId) ? $cardId : 'attachmentsCard' }}" class="d-grid grid-column-138-auto gap-16 mt-16">

    @if(!empty($itemRow))
        @foreach($itemRow->attachments as $attach)
            <div class="js-create-media-col">
                <div class="create-media-card position-relative p-4">
                    <div class="create-media-card__img position-relative d-flex align-items-center justify-content-center w-100 h-100">
                        @if($attach->isImage())
                            <img src="{{ !empty($attach) ? $attach->path : '' }}" alt="" class="img-cover rounded-15">
                        @else
                            <div class="create-media-card__filename">{{ $attach->getName() }}</div>
                        @endif

                        <a href="{{ !empty($itemAttachDeleteUrlPrefix) ? "{$itemAttachDeleteUrlPrefix}/{$attach->id}/delete" : '' }}" class="delete-action create-media-card__delete-btn d-flex align-items-center justify-content-center">
                            <span class="d-flex align-items-center justify-content-center p-4">
                                <x-iconsax-lin-add class="icons text-danger" width="24" height="24"/>
                            </span>
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    @endif

    @if(empty($dontAllowNewAttach))
        <div class="js-create-media-col">
            <div class="create-media-card position-relative p-4">
                <label for="createMedia_{{ !empty($itemRow) ? $itemRow->id : '' }}" class="create-media-card__label w-100 h-100 rounded-15 flex-column align-items-center justify-content-center cursor-pointer">
                    <div class="create-media-card__circle d-flex align-items-center justify-content-center rounded-circle">
                        <x-iconsax-lin-direct-send class="icons text-primary" width="24" height="24"/>
                    </div>

                    <div class="mt-8 font-14 text-primary">{{ trans('upload_a_file') }}</div>
                </label>

                <input type="file" name="attachments[]" id="createMedia_{{ !empty($itemRow) ? $itemRow->id : '' }}" class="js-create-media" data-col="" data-parent-id="{{ !empty($cardId) ? $cardId : 'attachmentsCard' }}" data-label="{{ trans('upload_a_file') }}" accept="">
            </div>
        </div>
    @endif

</div>
