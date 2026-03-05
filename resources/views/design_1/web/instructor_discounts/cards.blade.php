@push('styles_top')
    <link rel="stylesheet" href="{{ getDesign1StylePath("instructor_discounts") }}">
@endpush

{{-- $instructorDiscounts --}}
@foreach($allDiscounts as $discountRow)
    <div class="instructor-discount-card d-flex align-items-center justify-content-between {{ !empty($discountCardClassName) ? $discountCardClassName : 'bg-white rounded-16 px-16' }} {{ !empty($discountCardClassName2) ? $discountCardClassName2 : '' }}">
        <div class="d-flex align-items-center flex-1 h-100">
            <div class="instructor-discount-card__code form-group mb-0 d-flex align-items-center justify-content-center py-16 pr-14 pr-lg-20">
                <span class="font-16 font-weight-bold">{{ $discountRow->code }}</span>

                <button type="button" class="js-copy-input btn-transparent d-flex ml-4" data-tippy-content="{{ trans('copy') }}" data-copy-text="{{ trans('copy') }}" data-done-text="{{ trans('copied') }}">
                    <x-iconsax-lin-document-copy class="icons text-gray-500" width="16px" height="16px"/>
                </button>
                <input type="hidden" class="form-control" readonly value="{{ $discountRow->code }}">
            </div>

            <div class="p-16 px-lg-20 py-lg-16">
                <h4 class="font-14">{{ $discountRow->title }}</h4>
                <p class="mt-4 font-12 text-gray-500">{{ $discountRow->subtitle }}</p>
            </div>
        </div>

        <div class="instructor-discount-card__icon-svg d-flex-center h-100">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 66 66" fill="none">
                <path d="M26.4005 29.9416L23.5705 25.0399C22.8603 25.4499 21.9314 25.201 21.5214 24.4909L19.2714 20.5938L15.3743 22.8438C7.73599 27.2537 6.75504 30.9147 11.165 38.553L11.665 39.4191C12.075 40.1292 13.0039 40.3781 13.7141 39.9681C15.3768 39.0081 17.5352 39.5864 18.4952 41.2492C19.4552 42.912 18.8768 45.0703 17.2141 46.0303C16.5039 46.4403 16.255 47.3692 16.665 48.0793L17.165 48.9453C21.575 56.5837 25.236 57.5646 32.8743 53.1546L36.7714 50.9046L34.5214 47.0075C34.1114 46.2974 34.3603 45.3685 35.0705 44.9585L32.2405 40.0568C31.5303 40.4668 30.6014 40.2179 30.1914 39.5077L25.8514 31.9906C25.4414 31.2805 25.6903 30.3516 26.4005 29.9416Z" fill="url(#paint0_linear_8960_21762)"/>
                <path opacity="0.4" d="M47.5738 25.6152C48.5338 27.278 50.6922 27.8563 52.3549 26.8963C53.0651 26.4863 53.994 26.7352 54.404 27.4453C58.814 35.0837 57.833 38.7446 50.1947 43.1546L39.3694 49.4046L37.1194 45.5075C36.7094 44.7974 35.7805 44.5485 35.0703 44.9585L32.2403 40.0568C32.9505 39.6468 33.1994 38.7179 32.7894 38.0077L28.4494 30.4906C28.0394 29.7805 27.1105 29.5316 26.4003 29.9416L23.5703 25.0399C24.2805 24.6299 24.5294 23.701 24.1194 22.9909L21.8694 19.0938L32.6947 12.8438C40.333 8.43375 43.994 9.4147 48.404 17.053L49.404 18.7851C49.814 19.4952 49.5651 20.4241 48.8549 20.8341C47.1922 21.7941 46.6138 23.9525 47.5738 25.6152Z" fill="url(#paint1_linear_8960_21762)"/>
                <defs>
                    <linearGradient id="paint0_linear_8960_21762" x1="12.9928" y1="24.2188" x2="30.4928" y2="54.5296" gradientUnits="userSpaceOnUse">
                        <stop stop-color="#121F3E"/>
                        <stop offset="1" stop-color="#121F3E" stop-opacity="0"/>
                    </linearGradient>
                    <linearGradient id="paint1_linear_8960_21762" x1="31.6121" y1="13.4688" x2="49.1121" y2="43.7796" gradientUnits="userSpaceOnUse">
                        <stop stop-color="#121F3E"/>
                        <stop offset="1" stop-color="#121F3E" stop-opacity="0"/>
                    </linearGradient>
                </defs>
            </svg>
        </div>
    </div>
@endforeach
