<div id="companyLogos" class="d-grid grid-column-138-auto gap-16 ">

    @foreach($webinar->webinarExtraDescription->where('type', 'company_logos') as $companyLogo)
        <div class="js-create-media-col">
            <div class="create-media-card position-relative p-4">
                <div class="create-media-card__img position-relative d-flex align-items-center justify-content-center w-100 h-100">
                    <img src="{{ $companyLogo->value }}" alt="" class="img-cover rounded-15">

                    <a href="/panel/webinar-extra-description/{{ $companyLogo->id }}/delete" class="delete-action create-media-card__delete-btn d-flex align-items-center justify-content-center">
                        <span class="d-flex align-items-center justify-content-center p-4">
                            <x-iconsax-lin-add class="icons text-danger" width="24" height="24"/>
                        </span>
                    </a>
                </div>
            </div>
        </div>
    @endforeach

    <div class="js-create-media-col">
        <div class="create-media-card position-relative p-4">
            <label for="createMedia" class="create-media-card__label w-100 h-100 rounded-15 flex-column align-items-center justify-content-center cursor-pointer">
                <div class="create-media-card__circle d-flex align-items-center justify-content-center rounded-circle">
                    <x-iconsax-lin-direct-send class="icons text-primary" width="24" height="24"/>
                </div>

                <div class="mt-8 font-12 text-primary">{{ trans('update.select_a_logo') }}</div>
            </label>

            <input type="file" name="companyLogos[]" id="createMedia" class="js-create-media" data-col="" data-parent-id="companyLogos" data-label="{{ trans('update.select_a_logo') }}" accept="">
        </div>
    </div>

</div>
