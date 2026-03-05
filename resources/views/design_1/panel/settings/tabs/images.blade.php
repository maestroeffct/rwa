<div class="custom-tabs-content active">
    <div class="row">
        <div class="col-12 col-md-6 col-lg-4">

            {{-- profile_image --}}
            <div class="bg-white p-16 rounded-16 border-gray-200">
                <h3 class="font-14 font-weight-bold">{{ trans('auth.profile_image') }}</h3>

                <div class="d-flex-center flex-column mt-24">
                    <div class="position-relative size-80 rounded-circle bg-gray-100">
                        <img src="{{ $user->getAvatar(80) }}" id="userProfileImage" alt="" class="img-cover rounded-circle">

                        @if(!empty($user->avatar))
                            <a href="/panel/setting/media/avatar/delete" class="delete-action user-avatar-remove-btn d-flex-center size-32 bg-white rounded-circle">
                                <x-iconsax-lin-trash class="icons text-danger" width='16px' height='16px'/>
                            </a>
                        @endif
                    </div>

                    <h3 class="font-14 font-weight-bold mt-12">{{ $user->full_name }}</h3>
                </div>

                <div class="position-relative custom-input-file">
                    <input type="file" name="avatar" id="profileImage" class="" accept="image/*">

                    <label for="profileImage" class="d-flex-center w-100 p-20 rounded-12 border-gray-300 border-dashed cursor-pointer bg-hover-gray-100 mt-16">
                        <x-iconsax-lin-direct-send class="icons text-primary" width='16px' height='16px'/>
                        <span class="font-12 ml-8 text-primary">{{ trans('auth.select_image') }}</span>
                    </label>
                </div>
            </div>

            {{-- Profile Cover --}}
            <div class="bg-white p-16 rounded-16 border-gray-200 mt-20">
                <h3 class="font-14 font-weight-bold">{{ trans('auth.profile_cover') }}</h3>

                <div id="profileCoverBox" class="user-setting-profile-cover d-flex-center flex-column rounded-15 mt-16 bg-gray-100 w-100">
                    @if(!empty($user->cover_img))
                        <img src="{{ $user->cover_img }}" alt="" class="img-cover rounded-15">
                    @else
                        <div class="d-flex-center size-48 rounded-12 bg-primary-30">
                            <x-iconsax-bul-image class="icons text-primary" width="24px" height="24px"/>
                        </div>
                    @endif
                </div>

                <p class="mt-16 text-gray-500">{{ trans('update.user_setting_profile_cover_hint') }}</p>

                <div class="d-flex align-items-center mt-16">
                    <div class="position-relative custom-input-file flex-1">
                        <input type="file" name="cover_img" id="coverImageInput" class="" accept="image/*">

                        <label for="coverImageInput" class="d-flex-center w-100 p-20 rounded-12 border-gray-300 border-dashed cursor-pointer bg-hover-gray-100">
                            <x-iconsax-lin-direct-send class="icons text-primary" width='16px' height='16px'/>
                            <span class="font-12 ml-8 text-primary">{{ trans('auth.select_image') }}</span>
                        </label>
                    </div>

                    @if(!empty($user->cover_img))
                        <a href="/panel/setting/media/cover_img/delete" class="delete-action d-flex-center size-56 border-danger border-dashed rounded-8 ml-16 bg-hover-gray-100">
                            <x-iconsax-lin-trash class="icons text-danger" width='24px' height='24px'/>
                        </a>
                    @endif
                </div>
            </div>

        </div>

        <div class="col-12 col-md-6 col-lg-8 mt-20 mt-lg-0">

            <div class="row">
                {{-- secondary image --}}
                <div class="col-12 col-lg-4">
                    <div class="d-flex flex-column bg-white p-16 rounded-16 border-gray-200 h-100">
                        <h3 class="font-14 font-weight-bold">{{ trans('update.profile_secondary_image') }}</h3>

                        <div class="d-flex-center flex-column mt-16">
                            <div id="profileSecondaryImageBox" class="user-setting-profile-video-box d-flex-center position-relative rounded-16 px-16">
                                @if(!empty($user->profile_secondary_image))
                                    <img src="{{ $user->profile_secondary_image }}" id="profileSecondaryImage" alt="" class="img-cover mh-100 rounded-16 bg-gray-100">
                                @else
                                    <div class="d-flex-center size-48 rounded-12 bg-primary-30">
                                        <x-iconsax-bul-image class="icons text-primary" width="24px" height="24px"/>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <p class="my-16 text-gray-500">{{ trans('update.user_setting_secondary_profile_image_hint') }}</p>

                        <div class="d-flex align-items-center mt-auto">
                            <div class="position-relative custom-input-file flex-1">
                                <input type="file" name="profile_secondary_image" id="profileSecondaryImageInput" class="" accept="image/*">

                                <label for="profileSecondaryImageInput" class="d-flex-center w-100 p-20 rounded-12 border-gray-300 border-dashed cursor-pointer bg-hover-gray-100">
                                    <x-iconsax-lin-direct-send class="icons text-primary" width='16px' height='16px'/>
                                    <span class="font-12 ml-8 text-primary">{{ trans('auth.select_image') }}</span>
                                </label>
                            </div>

                            @if(!empty($user->profile_secondary_image))
                                <a href="/panel/setting/media/profile_secondary_image/delete" class="delete-action d-flex-center size-56 border-danger border-dashed rounded-8 ml-16 bg-hover-gray-100">
                                    <x-iconsax-lin-trash class="icons text-danger" width='24px' height='24px'/>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- profile_video --}}
                <div class="col-12 col-lg-4 mt-20 mt-lg-0">
                    <div class="d-flex flex-column bg-white p-16 rounded-16 border-gray-200 h-100">
                        <h3 class="font-14 font-weight-bold">{{ trans('update.profile_video') }}</h3>

                        <div class="user-setting-profile-video-box d-flex-center flex-column rounded-15 mt-16 bg-gray-100 w-100">
                            @if(!empty($user->profile_video))
                                <video class="img-cover rounded-15" data-value-1="1" data-value-2="1.1" autoplay="autoplay" loop="loop" muted="" playsinline="" controls oncontextmenu="return false" preload="auto">
                                    <source src="{{ $user->profile_video }}" type="video/mp4"/>
                                </video>
                            @else
                                <div class="d-flex-center size-48 rounded-12 bg-primary-30">
                                    <x-iconsax-bul-video-circle class="icons text-primary" width="24px" height="24px"/>
                                </div>
                            @endif
                        </div>

                        <p class="my-16 text-gray-500">{{ trans('update.user_setting_profile_video_hint') }}</p>

                        <div class="d-flex align-items-center mt-auto">
                            <div class="position-relative custom-input-file flex-1">
                                <input type="file" name="profile_video" id="profileVideo" class="" accept="video/*">

                                <label for="profileVideo" class="d-flex-center w-100 p-20 rounded-12 border-gray-300 border-dashed cursor-pointer bg-hover-gray-100">
                                    <x-iconsax-lin-direct-send class="icons text-primary" width='16px' height='16px'/>
                                    <span class="font-12 ml-8 text-primary">{{ trans('update.select_a_video') }}</span>
                                </label>
                            </div>

                            @if(!empty($user->profile_video))
                                <a href="/panel/setting/media/profile_video/delete" class="delete-action d-flex-center size-56 border-danger border-dashed rounded-8 ml-16 bg-hover-gray-100">
                                    <x-iconsax-lin-trash class="icons text-danger" width='24px' height='24px'/>
                                </a>
                            @endif

                        </div>
                    </div>
                </div>

                {{-- signature --}}
                @php
                    $userSignature = $user->getSignature(true);
                @endphp

                <div class="col-12 col-lg-4 mt-20 mt-lg-0">
                    <div class="d-flex flex-column bg-white p-16 rounded-16 border-gray-200 h-100">
                        <h3 class="font-14 font-weight-bold">{{ trans('update.signature') }}</h3>

                        <div id="signatureImageBox" class="user-setting-profile-video-box d-flex-center flex-column rounded-15 mt-16 bg-gray-100 w-100">
                            @if(!empty($userSignature))
                                <img src="{{ $userSignature }}" alt="" class="img-cover rounded-15">
                            @else
                                <div class="d-flex-center size-48 rounded-12 bg-primary-30">
                                    <x-iconsax-bul-image class="icons text-primary" width="24px" height="24px"/>
                                </div>
                            @endif
                        </div>

                        <p class="my-16 text-gray-500">{{ trans('update.user_setting_signature_hint') }}</p>

                        <div class="d-flex align-items-center mt-auto">
                            <div class="position-relative custom-input-file flex-1">
                                <input type="file" name="signature_img" id="signatureImageInput" class="" accept="image/*">

                                <label for="signatureImageInput" class="d-flex-center w-100 p-20 rounded-12 border-gray-300 border-dashed cursor-pointer bg-hover-gray-100">
                                    <x-iconsax-lin-direct-send class="icons text-primary" width='16px' height='16px'/>
                                    <span class="font-12 ml-8 text-primary">{{ trans('auth.select_image') }}</span>
                                </label>
                            </div>

                            @if(!empty($userSignature))
                                <a href="/panel/setting/media/signature_img/delete" class="delete-action d-flex-center size-56 border-danger border-dashed rounded-8 ml-16 bg-hover-gray-100">
                                    <x-iconsax-lin-trash class="icons text-danger" width='24px' height='24px'/>
                                </a>
                            @endif

                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
</div>
