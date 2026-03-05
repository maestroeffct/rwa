<div class="cart-discount-card {{ !empty($className) ? $className : '' }}">
    <div class="cart-discount-card__mask"></div>

    <div class="position-relative d-flex align-items-center justify-content-between bg-gray-100 rounded-16 z-index-2 w-100 h-100 px-16">
        <div class="d-flex align-items-center flex-1 h-100">
            <div class="cart-discount-card__code form-group mb-0 d-flex-center pr-20">
                <span class="font-16 font-weight-bold">{{ $cartDiscount->discount->code }}</span>

                <button type="button" class="js-copy-input btn-transparent d-flex ml-4" data-tippy-content="{{ trans('copy') }}" data-copy-text="{{ trans('copy') }}" data-done-text="{{ trans('copied') }}">
                    <x-iconsax-lin-document-copy class="icons text-gray-500" width="16px" height="16px"/>
                </button>
                <input type="hidden" class="form-control" readonly value="{{ $cartDiscount->discount->code }}">
            </div>

            <div class="text-left px-20 py-16">
                <h4 class="font-14">{{ $cartDiscount->title }}</h4>
                <p class="mt-4 font-12 text-gray-500">{{ $cartDiscount->subtitle }}</p>
            </div>
        </div>

        <div class="d-flex-center size-48">
            <svg xmlns="http://www.w3.org/2000/svg" width="66" height="66" viewBox="0 0 66 66" fill="none">
                <path d="M26.6163 29.9416L23.7863 25.0399C23.0762 25.4499 22.1473 25.201 21.7373 24.4909L19.4873 20.5938L15.5902 22.8438C7.95181 27.2537 6.97086 30.9147 11.3809 38.553L11.8809 39.4191C12.2909 40.1292 13.2198 40.3781 13.9299 39.9681C15.5927 39.0081 17.751 39.5864 18.711 41.2492C19.671 42.912 19.0927 45.0703 17.4299 46.0303C16.7198 46.4403 16.4709 47.3692 16.8809 48.0793L17.3809 48.9453C21.7909 56.5837 25.4518 57.5646 33.0902 53.1546L36.9873 50.9046L34.7373 47.0075C34.3273 46.2974 34.5762 45.3685 35.2863 44.9585L32.4563 40.0568C31.7462 40.4668 30.8173 40.2179 30.4073 39.5077L26.0673 31.9906C25.6573 31.2805 25.9062 30.3516 26.6163 29.9416Z" fill="url(#paint0_linear_2656_36272)"/>
                <path opacity="0.4" d="M47.7887 25.6152C48.7487 27.278 50.907 27.8563 52.5698 26.8963C53.2799 26.4863 54.2088 26.7352 54.6188 27.4453C59.0288 35.0837 58.0479 38.7446 50.4095 43.1546L39.5842 49.4046L37.3342 45.5075C36.9242 44.7974 35.9953 44.5485 35.2852 44.9585L32.4552 40.0568C33.1653 39.6468 33.4142 38.7179 33.0042 38.0077L28.6642 30.4906C28.2542 29.7805 27.3253 29.5316 26.6152 29.9416L23.7852 25.0399C24.4953 24.6299 24.7442 23.701 24.3342 22.9909L22.0842 19.0938L32.9095 12.8438C40.5479 8.43375 44.2088 9.4147 48.6188 17.053L49.6188 18.7851C50.0288 19.4952 49.7799 20.4241 49.0698 20.8341C47.407 21.7941 46.8287 23.9525 47.7887 25.6152Z" fill="url(#paint1_linear_2656_36272)"/>
                <defs>
                    <linearGradient id="paint0_linear_2656_36272" x1="13.2086" y1="24.2188" x2="30.7086" y2="54.5296" gradientUnits="userSpaceOnUse">
                        <stop stop-color="#121F3E"/>
                        <stop offset="1" stop-color="#121F3E" stop-opacity="0"/>
                    </linearGradient>
                    <linearGradient id="paint1_linear_2656_36272" x1="31.827" y1="13.4688" x2="49.327" y2="43.7796" gradientUnits="userSpaceOnUse">
                        <stop stop-color="#121F3E"/>
                        <stop offset="1" stop-color="#121F3E" stop-opacity="0"/>
                    </linearGradient>
                </defs>
            </svg>
        </div>
    </div>

</div>
