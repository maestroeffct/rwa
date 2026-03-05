<div class="mt-24 font-14 text-gray-500">{!! $cookieSettings['cookie_settings_modal_message'] !!}</div>

@if(!empty($cookieSettings['cookie_settings_modal_items']) and count($cookieSettings['cookie_settings_modal_items']))
    <form class="js-cookie-form-customize-inputs">
        @foreach($cookieSettings['cookie_settings_modal_items'] as $cookieModalItemKey => $cookieModalItem)
            @php
                $isRequiredModalItem = (!empty($cookieModalItem['required']) and $cookieModalItem['required']);
            @endphp
            <div class="accordion border-gray-300 border-dashed p-16 rounded-16 mt-16">
                <div class="accordion__title d-flex align-items-center justify-content-between">
                    <div class="d-flex flex-column cursor-pointer" href="#collapseCookieSecurity{{ $cookieModalItemKey }}" role="button" data-toggle="collapse">
                        <div class="d-flex align-items-center">
                            <h6 class="font-14 mr-8">{{ $cookieModalItem['title'] }}</h6>

                            <div class="collapse-arrow-icon d-flex" role="button" data-toggle="collapse" aria-expanded="true" href="#collapseCookieSecurity{{ $cookieModalItemKey }}">
                                <x-iconsax-lin-arrow-up-1 class="icons text-gray-400" width="16px" height="16px"/>
                            </div>
                        </div>

                        <div class="font-14 mt-4 text-gray-500">{{ $cookieModalItem['subtitle'] }}</div>
                    </div>


                    <div class="d-flex align-items-center form-group mb-0">
                        <div class="custom-switch">
                            <input type="checkbox" name="settings[]" class="custom-control-input" @if(!$isRequiredModalItem) id="cookieModalItem{{ $cookieModalItemKey }}" @endif value="{{ mb_strtolower(str_replace(' ','_',$cookieModalItem['title'])) }}" {{ $isRequiredModalItem ? ' checked="checked" readonly ' : '' }}>
                            <label class="custom-control-label cursor-pointer" @if(!$isRequiredModalItem) for="cookieModalItem{{ $cookieModalItemKey }}" @endif></label>
                        </div>
                    </div>
                </div>

                <div id="collapseCookieSecurity{{ $cookieModalItemKey }}" class="accordion__collapse pt-0 mt-16 border-0" role="tabpanel">
                    <div class="p-16 rounded-8 border-gray-300 bg-gray-100 text-gray-500">{!! $cookieModalItem['description'] !!}</div>
                </div>
            </div>
        @endforeach
    </form>
@endif
