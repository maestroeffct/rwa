<div class="col-6 col-md-4 col-lg-2 mt-16">
    <div class="create-media-just-image-card position-relative p-4">
        <label for="{{ $mediaName }}" class="create-media-just-image-card__label w-100 h-100 rounded-15 flex-column align-items-center justify-content-center cursor-pointer {{ (!empty($media)) ? 'd-none' : '' }}">
            <div class="create-media-just-image-card__circle d-flex-center size-44 rounded-circle bg-primary-20">
                <x-iconsax-lin-direct-send class="icons text-primary" width="24" height="24"/>
            </div>

            <div class="mt-8 font-12 text-primary">{{ $mediaTitle }}</div>
        </label>

        <div class="create-media-just-image-card__img align-items-center justify-content-center w-100 h-100 {{ (!empty($media)) ? '' : 'd-none' }}">
            <img src="{{ (!empty($media)) ? $media : '' }}" alt="" class="img-cover rounded-15">

            <div class="create-media-just-image-card__delete-btn d-flex-center cursor-pointer">
                <span class="d-flex-center p-4">
                    <x-iconsax-lin-add class="icons text-danger" width="24" height="24"/>
                </span>
            </div>
        </div>

        <input type="file" name="{{ $mediaName }}" id="{{ $mediaName }}" class="js-create-media-just-image" accept="image/*">
    </div>

    @error($mediaName)
    <div class="invalid-feedback d-block mt-8">{{ $message }}</div>
    @enderror
</div>
