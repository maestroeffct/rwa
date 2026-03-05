<div class="row">
    <div class="col-12 col-lg-6 mt-20">
        {{-- General Information --}}
        <div class="p-16 rounded-16 border-gray-200">
            <h3 class="font-14 mb-24">{{ trans('update.general_information') }}</h3>

            @include('design_1.panel.includes.locale.locale_select',[
                'itemRow' => !empty($headerItem) ? $headerItem : null,
                'withoutReloadLocale' => false,
                'extraClass' => ''
            ])

            <x-landingBuilder-input
                label="{{ trans('public.title') }}"
                name="title"
                value="{{ (!empty($headerItem->translate($locale)) and !empty($headerItem->translate($locale)->title)) ? $headerItem->translate($locale)->title : '' }}"
                placeholder=""
                hint=""
                icon=""
                className="mb-0"
            />

        </div>

        <div class="p-16 rounded-16 border-gray-200 mt-20">
            <h3 class="font-14 mb-24">{{ trans('update.top_navbar') }}</h3>

            <x-landingBuilder-switch
                label="{{ trans('update.show_theme_color_mode') }}"
                id="show_color_mode"
                name="contents[top_navbar][show_color_mode]"
                checked="{{ !!(!empty($contents['top_navbar']) and !empty($contents['top_navbar']['show_color_mode'])) }}"
                hint=""
                className="custom-switch-design-2"
            />

            <x-landingBuilder-input
                    label="{{ trans('public.phone') }}"
                    name="contents[top_navbar][phone]"
                    value="{{ (!empty($contents['top_navbar']) and !empty($contents['top_navbar']['phone'])) ? $contents['top_navbar']['phone'] : '' }}"
                    placeholder=""
                    hint=""
                    icon="call-calling"
                    className=""
            />

            <x-landingBuilder-input
                    label="{{ trans('public.email') }}"
                    name="contents[top_navbar][email]"
                    value="{{ (!empty($contents['top_navbar']) and !empty($contents['top_navbar']['email'])) ? $contents['top_navbar']['email'] : '' }}"
                    placeholder=""
                    hint=""
                    icon="sms"
                    className=""
            />

            @foreach(['link_1', 'link_2'] as $linkKey => $link)
                <h4 class="my-24 text-gray-500 font-14">{{ trans('public.link') }} #{{ $linkKey + 1 }}</h4>

                <x-landingBuilder-input
                        label="{{ trans('update.link_title') }}"
                        name="contents[top_navbar][{{ $link }}][title]"
                        value="{{ (!empty($contents['top_navbar']) and !empty($contents['top_navbar'][$link]) and !empty($contents['top_navbar'][$link]['title'])) ? $contents['top_navbar'][$link]['title'] : '' }}"
                        placeholder=""
                        hint=""
                        className=""
                />

                <x-landingBuilder-input
                        label="{{ trans('panel.url') }}"
                        name="contents[top_navbar][{{ $link }}][url]"
                        value="{{ (!empty($contents['top_navbar']) and !empty($contents['top_navbar'][$link]) and !empty($contents['top_navbar'][$link]['url'])) ? $contents['top_navbar'][$link]['url'] : '' }}"
                        placeholder=""
                        hint=""
                        icon="link-1"
                        className="mb-0"
                />
            @endforeach
        </div>

    </div>{{-- End Col --}}

    <div class="col-12 col-lg-6 mt-20">

        {{-- Navbar links --}}
        <div class="p-16 rounded-16 border-gray-200 ">
            <x-landingBuilder-addable-accordions
                    title="{{ trans('update.navbar_links') }}"
                    addText="{{ trans('update.add_link') }}"
                    className="mb-0"
                    mainRow="js-navbar-link-main-card"
            >
                @if(!empty($contents) and !empty($contents['specific_links']) and count($contents['specific_links']))
                    @foreach($contents['specific_links'] as $sKey => $navbarLinkItemData)
                        @if($sKey != 'record')
                            <x-landingBuilder-accordion
                                    title="{{ (!empty($navbarLinkItemData) and !empty($navbarLinkItemData['title'])) ? $navbarLinkItemData['title'] : trans('update.new_link') }}"
                                    id="navbar_link_{{ $sKey }}"
                                    className=""
                                    show=""
                            >
                                @include('admin.theme.headers.components.header_1.navbar_link',['itemKey' => $sKey, 'navbarLinkData' => $navbarLinkItemData])
                            </x-landingBuilder-accordion>
                        @endif
                    @endforeach
                @endif
            </x-landingBuilder-addable-accordions>
        </div>

        {{-- Navbar Button --}}
        <div class="p-16 rounded-16 border-gray-200 mt-20">
            <x-landingBuilder-addable-accordions
                    title="{{ trans('update.navbar_button') }}"
                    addText="{{ trans('update.add_button') }}"
                    className="mb-0"
                    mainRow="js-navbar-button-main-card"
            >
                @if(!empty($contents) and !empty($contents['specific_buttons']) and count($contents['specific_buttons']))
                    @foreach($contents['specific_buttons'] as $sKey => $navbarButtonItemData)
                        @if($sKey != 'record')
                            @php
                                $selectedUserRole = (!empty($navbarButtonItemData) and !empty($navbarButtonItemData['user_role'])) ? $navbarButtonItemData['user_role'] : null;
                                $selectedUserRoleItem = (!empty($selectedUserRole) and $selectedUserRole != "for_guest" and !empty($userRoles) and count($userRoles)) ? $userRoles->where('id', $selectedUserRole)->first() : null;

                                $selectedUserRole = (!empty($selectedUserRoleItem)) ? $selectedUserRoleItem->id : $selectedUserRole;
                                $itemTitle = (!empty($navbarButtonItemData) and !empty($navbarButtonItemData['title'])) ? $navbarButtonItemData['title'] : trans('update.button');

                                if (!empty($selectedUserRoleItem)) {
                                    $itemTitle .= " ({$selectedUserRoleItem->caption})";
                                } else if ($selectedUserRole == "for_guest") {
                                    $itemTitle .= " (". trans('update.guest') .")";
                                }

                            @endphp

                            <x-landingBuilder-accordion
                                    title="{{ $itemTitle }}"
                                    id="navbar_button_{{ $sKey }}"
                                    className=""
                                    show=""
                            >
                                @include('admin.theme.headers.components.header_1.navbar_button',['itemKey' => $sKey, 'navbarButtonData' => $navbarButtonItemData, 'selectedUserRoleId' => $selectedUserRole])
                            </x-landingBuilder-accordion>
                        @endif
                    @endforeach
                @endif
            </x-landingBuilder-addable-accordions>
        </div>

    </div>{{-- End Col --}}

</div>{{-- End Row --}}


<div class="js-navbar-link-main-card d-none">
    <x-landingBuilder-accordion
            title="{{ trans('update.new_link') }}"
            id="record"
            className=""
            show="true"
    >
        @include('admin.theme.headers.components.header_1.navbar_link')
    </x-landingBuilder-accordion>
</div>


<div class="js-navbar-button-main-card d-none">
    <x-landingBuilder-accordion
            title="{{ trans('update.new_button') }}"
            id="record"
            className=""
            show="true"
    >
        @include('admin.theme.headers.components.header_1.navbar_button')
    </x-landingBuilder-accordion>
</div>
