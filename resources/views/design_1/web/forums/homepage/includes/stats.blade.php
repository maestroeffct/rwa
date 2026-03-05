<div class="container position-relative mt-64 p-12 rounded-24 bg-white z-index-3">
    <div class="forum-stat-spiral-arrow">
        <svg xmlns="http://www.w3.org/2000/svg" width="113" height="120" viewBox="0 0 113 120" fill="none">
            <path fill-rule="evenodd" clip-rule="evenodd"
                  d="M111.261 114.04C110.816 113.707 109.618 112.874 108.554 112.209C106.16 110.714 102.99 107.524 100.433 103.901C98.455 101.16 97.3441 100.326 96.4334 100.897C95.2188 101.644 97.2689 106.007 100.536 109.745C101.52 110.881 102.327 111.849 102.329 111.977C102.339 112.532 95.7836 108.804 92.7231 106.509C86.5913 101.919 81.1288 95.1878 76.9531 87.1469C74.2759 82.0265 72.3476 77.1117 68.6084 66.1589C67.0813 61.6161 64.8335 55.9809 63.6524 53.6528C60.1568 46.8812 57.8777 44.4497 49.8024 38.7411C49.0029 38.1573 48.9545 37.9019 49.3515 35.5459C49.824 32.5053 49.3756 26.9666 48.4046 24.1223C44.932 13.6827 34.406 5.75377 19.5749 2.34242C12.0909 0.637949 3.27765 0.323549 0.801751 1.64845C0.367936 1.91234 0.0270547 2.47356 0.0345834 2.90051C0.0481351 3.66901 0.356478 3.74901 3.73163 3.6895C5.74796 3.65394 9.69972 3.96861 12.464 4.38966C30.7028 7.1003 42.8543 15.2087 45.8628 26.6815C46.5231 29.3177 46.7334 36.2701 46.1303 36.8786C46.0441 36.9656 44.9884 36.7707 43.7981 36.4073C42.2111 35.9228 39.9718 35.7487 35.6816 35.8244C30.5532 35.9148 29.4158 36.063 27.2885 36.9546C21.6716 39.1891 18.3184 42.6649 17.9915 46.509C17.7782 49.3315 18.1492 50.4781 19.8472 52.2846C22.3055 54.9318 24.247 55.6236 29.0631 55.5387C33.0081 55.4691 33.4011 55.3768 36.5738 53.7833C40.5288 51.7917 44.4473 48.0498 46.4859 44.3036C47.207 42.9242 47.9757 41.7575 48.1072 41.7552C48.8523 41.742 54.4105 46.1284 56.0184 48.1073C60.0024 52.7349 61.6443 56.3308 66.5358 70.4663C72.7263 88.3318 77.2925 96.4459 85.9883 104.706C90.0909 108.606 93.6888 111.233 98.108 113.498L100.275 114.571L97.2123 114.923C93.5802 115.329 90.4052 116.795 90.254 118.164C90.0582 119.491 91.2893 119.683 95.0446 118.805C96.8357 118.432 101.036 117.931 104.404 117.744C107.777 117.556 110.883 117.202 111.318 116.981C111.709 116.761 112.049 116.157 112.039 115.602C112.029 115.047 111.667 114.37 111.266 114.035L111.261 114.04ZM43.7319 42.9107C42.032 45.973 38.1904 49.4146 34.7615 51.0926C29.4575 53.6632 23.7043 52.8251 21.6248 49.2743C20.8563 47.9639 20.8533 47.7932 21.5653 45.9014C22.8208 42.5054 26.2891 40.2661 31.9653 39.2265C33.888 38.8509 36.4273 38.6353 37.6576 38.7844C40.3805 39.0354 44.9595 40.1077 44.9663 40.492C44.9686 40.6201 44.4183 41.7402 43.7373 42.9053L43.7319 42.9107Z"
                  fill="#121F3E"/>
        </svg>
    </div>

    <div class="row">
        <div class="col-12 col-lg-6">
            <div class="px-4 py-8">
                <div class="d-flex align-items-center">
                    <x-iconsax-bul-messages-1 class="icons text-gray-500" width="24px" height="24px"/>
                    <span class="ml-8 font-14 text-gray-500">{{ trans('update.communicate_with_other_users_and_share_your_knowledge') }}</span>
                </div>

                <div class="d-flex align-items-center gap-20 mt-16">

                    @if($randomlyActiveUsers->isNotEmpty())
                        @foreach($randomlyActiveUsers as $randomlyActiveUser)
                            <div class="size-48 rounded-circle">
                                <img src="{{ $randomlyActiveUser->getAvatar(48) }}" alt="{{ $randomlyActiveUser->full_name }}" class="img-cover rounded-circle">
                            </div>
                        @endforeach
                    @endif

                </div>
            </div>
        </div>

        <div class="col-12 col-lg-6 mt-16 mt-lg-0">
            <div class="d-flex align-items-center justify-content-between gap-24 p-32 rounded-12 bg-gray-100">
                <div class="">
                    <span class="d-block font-24 font-weight-bold text-dark">{{ $forumsCount }}</span>
                    <span class="d-block font-14 text-gray-500">{{ trans('update.forums') }}</span>
                </div>

                <div class="">
                    <span class="d-block font-24 font-weight-bold text-dark">{{ $topicsCount }}</span>
                    <span class="d-block font-14 text-gray-500">{{ trans('update.forum_topics') }}</span>
                </div>

                <div class="">
                    <span class="d-block font-24 font-weight-bold text-dark">{{ $postsCount }}</span>
                    <span class="d-block font-14 text-gray-500">{{ trans('update.forum_posts') }}</span>
                </div>

                <div class="">
                    <span class="d-block font-24 font-weight-bold text-dark">{{ $membersCount }}</span>
                    <span class="d-block font-14 text-gray-500">{{ trans('update.members') }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
