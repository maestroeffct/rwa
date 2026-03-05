@if(!empty($recentStudentCertificates) and count($recentStudentCertificates))
    <div class="mt-28">
        <div class="">
            <h2 class="font-16 text-dark">{{ trans('update.recent_student_certificates') }}</h2>
            <p class="font-12 mt-4 text-gray-500">{{ trans('update.you_can_check_all_student_certificates_in_your_panel') }}</p>
        </div>

        <div class="row">
            @foreach($recentStudentCertificates as $recentStudentCertificate)
                <div class="col-12 col-md-6 col-lg-3 mt-16">
                    <div class="position-relative most-active-assignment-card">
                        <div class="most-active-assignment-card__mask rounded-32"></div>

                        <div class="position-relative z-index-2 bg-white p-16 rounded-24">
                            <div class="d-flex align-items-center p-4">
                                <div class="d-flex-center size-58 rounded-circle bg-gray-100">
                                    <div class="size-48 rounded-circle">
                                        <img src="{{ $recentStudentCertificate->student->getAvatar(48) }}" alt="{{ $recentStudentCertificate->student->full_name }}" class="img-cover rounded-circle">
                                    </div>
                                </div>
                                <div class="ml-8">
                                    <h4 class="font-14 text-dark">{{ $recentStudentCertificate->student->full_name }}</h4>
                                    <p class="font-12 text-gray-500 mt-4">{{ dateTimeFormat($recentStudentCertificate->created_at, 'j M Y H:i') }}</p>
                                </div>
                            </div>

                            <div class="d-flex align-items-center mt-28 rounded-12 bg-gray-100 p-12">

                                @if(!empty($recentStudentCertificate->quiz))
                                    @if(!empty($recentStudentCertificate->quiz->icon))
                                        <div class="d-flex-center size-48 bg-gray-100">
                                            <img src="{{ $recentStudentCertificate->quiz->icon }}" alt="" class="img-fluid">
                                        </div>
                                    @else
                                        <div class="d-flex-center size-48 rounded-circle bg-gray-100">
                                            <x-iconsax-bul-clipboard-tick class="icons text-primary" width="24px" height="24px"/>
                                        </div>
                                    @endif

                                    <div class="ml-8">
                                        <div class="font-12 text-dark font-weight-bold">{{ $recentStudentCertificate->quiz->title }}</div>

                                        @if(!empty($recentStudentCertificate->quiz->webinar))
                                            <div class="font-12 text-gray-500 mt-4">{{ $recentStudentCertificate->quiz->webinar->title }}</div>
                                        @endif
                                    </div>
                                @elseif(!empty($recentStudentCertificate->webinar) or !empty($recentStudentCertificate->bundle))
                                    @php
                                        $courseTitle = !empty($recentStudentCertificate->webinar) ? $recentStudentCertificate->webinar->title : $recentStudentCertificate->bundle->title;
                                        $courseUrl = !empty($recentStudentCertificate->webinar) ? $recentStudentCertificate->webinar->getUrl() : $recentStudentCertificate->bundle->getUrl();
                                    @endphp

                                    <div class="d-flex-center size-48 rounded-circle bg-gray-100">
                                        <x-iconsax-bul-teacher class="icons text-primary" width="24px" height="24px"/>
                                    </div>
                                    <div class="ml-12">
                                        <div class="">{{ trans('update.course_completion') }}</div>
                                        <a href="{{ $courseUrl }}" target="_blank" class="mt-4 font-12 text-gray-500">{{ $courseTitle }}</a>
                                    </div>
                                @endif

                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endif
