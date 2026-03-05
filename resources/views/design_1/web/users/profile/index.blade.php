@extends('design_1.web.layouts.app')

@push('styles_top')
    <link rel="stylesheet" href="{{ getDesign1StylePath("profile") }}">
@endpush


@section('content')
    <div class="profile-cover-card">
        <img src="{{ $user->getCover() }}" class="img-cover" alt=""/>
    </div>

    <div class="profile-container">
        <div class="container mb-104">
            <div class="row">

                <div class="col-12 col-md-4 col-lg-3">
                    @include('design_1.web.users.profile.includes.left_side')
                </div>

                <div class="col-12 col-md-8 col-lg-9 mt-32 mt-md-0">
                    <div class="profile-card-has-mask position-relative bg-white pt-24 pb-20 rounded-24">
                        <div class="custom-tabs">

                            <div class="profile-tabs-items d-flex align-items-center gap-16 gap-lg-32 border-bottom-gray-200 px-24">
                                <div class="navbar-item d-flex-center pb-12 cursor-pointer font-12 font-weight-bold {{ (empty(request()->get('tab')) or request()->get('tab') == 'about') ? 'active' : ''  }}" data-tab-toggle data-tab-href="#aboutTab">
                                    <x-iconsax-lin-profile class="icons" width="16px" height="16px"/>
                                    <span class="ml-4">{{ trans('public.about') }}</span>
                                </div>

                                <div class="navbar-item d-flex-center pb-12 cursor-pointer font-12 font-weight-bold {{ (request()->get('tab') == 'webinars') ? 'active' : ''  }}" data-tab-toggle data-tab-href="#coursesTab">
                                    <x-iconsax-lin-video-play class="icons" width="16px" height="16px"/>
                                    <span class="ml-4">{{ trans('update.courses') }}</span>
                                </div>

                                @if($user->isOrganization())
                                    <div class="navbar-item d-flex-center pb-12 cursor-pointer font-12 font-weight-bold {{ (request()->get('tab') == 'instructors') ? 'active' : ''  }}" data-tab-toggle data-tab-href="#instructorsTab">
                                        <x-iconsax-lin-teacher class="icons" width="16px" height="16px"/>
                                        <span class="ml-4">{{ trans('home.instructors') }}</span>
                                    </div>
                                @endif

                                @if(!empty(getStoreSettings('status')) and getStoreSettings('status'))
                                    <div class="navbar-item d-flex-center pb-12 cursor-pointer font-12 font-weight-bold {{ (request()->get('tab') == 'products') ? 'active' : ''  }}" data-tab-toggle data-tab-href="#productsTab">
                                        <x-iconsax-lin-box class="icons" width="16px" height="16px"/>
                                        <span class="ml-4">{{ trans('update.products') }}</span>
                                    </div>
                                @endif

                                <div class="navbar-item d-flex-center pb-12 cursor-pointer font-12 font-weight-bold {{ (request()->get('tab') == 'posts') ? 'active' : ''  }}" data-tab-toggle data-tab-href="#articlesTab">
                                    <x-iconsax-lin-note-2 class="icons" width="16px" height="16px"/>
                                    <span class="ml-4">{{ trans('update.articles') }}</span>
                                </div>

                                <div class="navbar-item d-flex-center pb-12 cursor-pointer font-12 font-weight-bold {{ (request()->get('tab') == 'forum') ? 'active' : ''  }}" data-tab-toggle data-tab-href="#forumTab">
                                    <x-iconsax-lin-messages class="icons" width="16px" height="16px"/>
                                    <span class="ml-4">{{ trans('update.forum') }}</span>
                                </div>

                                <div class="navbar-item d-flex-center pb-12 cursor-pointer font-12 font-weight-bold {{ (request()->get('tab') == 'badges') ? 'active' : ''  }}" data-tab-toggle data-tab-href="#badgesTab">
                                    <x-iconsax-lin-medal class="icons" width="16px" height="16px"/>
                                    <span class="ml-4">{{ trans('site.badges') }}</span>
                                </div>

                                <div class="navbar-item d-flex-center pb-12 cursor-pointer font-12 font-weight-bold {{ (request()->get('tab') == 'appointments') ? 'active' : ''  }}" data-tab-toggle data-tab-href="#reserveMeetingTab">
                                    <x-iconsax-lin-calendar-2 class="icons" width="16px" height="16px"/>
                                    <span class="ml-4">{{ trans('public.reserve_a_meeting') }}</span>
                                </div>
                            </div>

                            <div class="custom-tabs-body">

                                <div class="custom-tabs-content px-16  {{ (empty(request()->get('tab')) or request()->get('tab') == 'about') ? 'active' : ''  }}" id="aboutTab">
                                    @include('design_1.web.users.profile.tabs.about')
                                </div>

                                <div class="custom-tabs-content px-16 {{ (request()->get('tab') == 'webinars') ? 'active' : ''  }}" id="coursesTab">
                                    @include('design_1.web.users.profile.tabs.courses')
                                </div>

                                @if($user->isOrganization())
                                    <div class="custom-tabs-content px-16 {{ (request()->get('tab') == 'instructors') ? 'active' : ''  }}" id="instructorsTab">
                                        @include('design_1.web.users.profile.tabs.instructors')
                                    </div>
                                @endif

                                <div class="custom-tabs-content px-16 {{ (request()->get('tab') == 'products') ? 'active' : ''  }}" id="productsTab">
                                    @include('design_1.web.users.profile.tabs.products')
                                </div>

                                <div class="custom-tabs-content px-16 {{ (request()->get('tab') == 'posts') ? 'active' : ''  }}" id="articlesTab">
                                    @include('design_1.web.users.profile.tabs.articles')
                                </div>

                                <div class="custom-tabs-content px-16 {{ (request()->get('tab') == 'forum') ? 'active' : ''  }}" id="forumTab">
                                    @include('design_1.web.users.profile.tabs.forum')
                                </div>

                                <div class="custom-tabs-content px-16 {{ (request()->get('tab') == 'badges') ? 'active' : ''  }}" id="badgesTab">
                                    @include('design_1.web.users.profile.tabs.badges')
                                </div>

                                <div class="custom-tabs-content px-16 {{ (request()->get('tab') == 'appointments') ? 'active' : ''  }}" id="reserveMeetingTab">
                                    @include('design_1.web.users.profile.tabs.reserveMeeting.index')
                                </div>

                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts_bottom')
    <script>
        var unFollowLang = '{{ trans('panel.unfollow') }}';
        var followLang = '{{ trans('panel.follow') }}';
        var sendMessageLang = '{{ trans('site.send_message') }}';
        var reservedLang = '{{ trans('meeting.reserved') }}';
        var messageSuccessSentLang = '{{ trans('site.message_success_sent') }}';
    </script>


    <script src="{{ getDesign1ScriptPath("profile") }}"></script>

    @if(!empty($user->live_chat_js_code) and !empty(getFeaturesSettings('show_live_chat_widget')))
        <script>
            (function () {
                "use strict"

                {!! $user->live_chat_js_code !!}
            })(jQuery)
        </script>
    @endif
@endpush
