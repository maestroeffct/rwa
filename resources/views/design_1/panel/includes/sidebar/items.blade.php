@foreach(\App\Mixins\Panel\SidebarItems::getItems() as $sidebarSection => $sidebarMenus)
    @if(!empty($sidebarMenus) and count($sidebarMenus))
        <div class="mt-16">
            <span class="panel-sidebar__item-spacing d-block font-12 font-weight-bold text-gray-400 text-uppercase mb-8">{{ trans("update.{$sidebarSection}") }}</span>

            @foreach($sidebarMenus as $sidebarMenuName => $sidebarMenu)
                @php
                    $isActiveMainManu = false;
                    $mainUrl = str_replace('/panel', 'panel', $sidebarMenu['url']);

                    if ($mainUrl == "panel" and request()->path() == "panel") {
                        $isActiveMainManu = true;
                    } else if ($mainUrl != "panel") {
                        $isActiveMainManu = (request()->is($mainUrl) or request()->is($mainUrl.'*'));

                        if (!$isActiveMainManu and !empty($sidebarMenu['extraUrl'])) {
                            $extraUrl = str_replace('/panel', 'panel', $sidebarMenu['extraUrl']);
                            $isActiveMainManu = (request()->is($extraUrl) or request()->is($extraUrl.'*'));
                        }
                    }
                @endphp

                @if(!empty($sidebarMenu['items']))
                    <div class="accordion ">
                        <div class="panel-sidebar__menu panel-sidebar__item-spacing accordion__title d-flex align-items-center justify-content-between {{ ($isActiveMainManu) ? 'sidenav-item-active' : '' }}">
                            <div class="d-flex align-items-center flex-1 cursor-pointer" href="#collapseSidebar{{ $sidebarMenuName }}" data-parent="#sidebarAccordions" role="button" data-toggle="collapse">
                                <span class=" {{ $isActiveMainManu ? 'text-primary' : 'text-gray-500' }}">{!! $sidebarMenu['icon'] !!}</span>
                                <span class="ml-8 font-14 {{ $isActiveMainManu ? 'text-primary' : 'text-dark' }}">{{ $sidebarMenu['text'] }}</span>
                            </div>

                            <span class="collapse-arrow-icon d-flex cursor-pointer" href="#collapseSidebar{{ $sidebarMenuName }}" data-parent="#sidebarAccordions" role="button" data-toggle="collapse">
                                <x-iconsax-lin-arrow-up-1 class="icons text-gray-400" width="12px"/>
                            </span>
                        </div>

                        <div id="collapseSidebar{{ $sidebarMenuName }}" class="accordion__collapse border-top-0 pt-0 mt-0 {{ $isActiveMainManu ? 'show' : '' }}" role="tabpanel">

                            @foreach($sidebarMenu['items'] as $sidebarMenuItem)
                                @php
                                    $itemUrl = str_replace('/panel', 'panel', $sidebarMenuItem['url']);
                                    $isActiveItemManu = (request()->is($itemUrl));
                                @endphp

                                <a href="{{ $sidebarMenuItem['url'] }}" class="panel-sidebar__menu-item panel-sidebar__item-spacing d-flex align-items-center text-gray-500 font-14 {{ ($isActiveItemManu) ? 'text-primary' : '' }}">
                                    <span class="">{{ $sidebarMenuItem['text'] }}</span>
                                </a>
                            @endforeach

                        </div>
                    </div>
                @else
                    <a href="{{ $sidebarMenu['url'] }}" class="panel-sidebar__menu panel-sidebar__item-spacing d-flex align-items-center justify-content-between {{ ($isActiveMainManu) ? 'sidenav-item-active' : '' }}">
                        <div class="d-flex align-items-center flex-1 cursor-pointer">
                            <span class=" {{ $isActiveMainManu ? 'text-primary' : (!empty($sidebarMenu['className']) ? $sidebarMenu['className'] : 'text-gray-500') }}">{!! $sidebarMenu['icon'] !!}</span>
                            <span class="ml-8 font-14 {{ $isActiveMainManu ? 'text-primary' : (!empty($sidebarMenu['className']) ? $sidebarMenu['className'] : 'text-dark') }}">{{ $sidebarMenu['text'] }}</span>
                        </div>

                    </a>
                @endif
            @endforeach
        </div>
    @endif
@endforeach
