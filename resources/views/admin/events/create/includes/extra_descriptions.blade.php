@foreach(\App\Models\WebinarExtraDescription::$types as $extraDescriptionType)
    <section class="mt-30">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="section-title after-line">{{ trans('update.'.$extraDescriptionType) }}</h2>

            <button type="button"
                    class="js-event-contents-action-btn btn btn-primary btn-sm mt-3"
                    data-path="{{ getAdminPanelUrl("/events/{$event->id}/extra-description/get-form?type={$extraDescriptionType}") }}"
                    data-title="{{ trans('update.add_'.$extraDescriptionType) }}"
            >
                {{ trans('update.add_'.$extraDescriptionType) }}
            </button>

        </div>

        @php
            $extraDescriptionValues = $event->extraDescriptions->where('type', $extraDescriptionType);
        @endphp

        <div class="row mt-10">
            <div class="col-12">
                @if(!empty($extraDescriptionValues) and count($extraDescriptionValues))
                    <div class="table-responsive">
                        <table class="table custom-table border-0 text-center font-14">

                            <tr>
                                @if($extraDescriptionType == \App\Models\WebinarExtraDescription::$COMPANY_LOGOS)
                                    <th>{{ trans('admin/main.icon') }}</th>
                                @else
                                    <th>{{ trans('public.title') }}</th>
                                @endif
                                <th width="80px">{{ trans('admin/main.action') }}</th>
                            </tr>

                            @foreach($extraDescriptionValues as $extraDescription)
                                <tr>
                                    @if($extraDescriptionType == \App\Models\WebinarExtraDescription::$COMPANY_LOGOS)
                                        <td>
                                            <img src="{{ $extraDescription->value }}" class="webinar-extra-description-company-logos" alt="">
                                        </td>
                                    @else
                                        <td>{{ $extraDescription->value }}</td>
                                    @endif

                                    <td>
                                        <div class="btn-group dropdown table-actions position-relative">
                                            <button type="button" class="btn-transparent dropdown-toggle" data-toggle="dropdown">
                                                <x-iconsax-lin-more class="icons text-gray-500" width="20px" height="20px"/>
                                            </button>

                                            <div class="dropdown-menu dropdown-menu-right">
                                                <button type="button" class="js-event-contents-action-btn dropdown-item d-flex align-items-center mb-3 py-3 px-0 gap-4"
                                                        data-path="{{ getAdminPanelUrl("/events/{$event->id}/extra-description/{$extraDescription->id}/edit") }}"
                                                        data-title="{{ trans("update.{$extraDescriptionType}") }}"
                                                >
                                                    <x-iconsax-lin-edit-2 class="icons text-gray-500 mr-2" width="18px" height="18px"/>
                                                    <span class="text-gray-500 font-14">{{ trans('admin/main.edit') }}</span>
                                                </button>

                                                @include('admin.includes.delete_button',[
                                                    'url' => getAdminPanelUrl("/events/{$event->id}/extra-description/{$extraDescription->id}/delete"),
                                                    'btnClass' => 'dropdown-item text-danger mb-0 py-3 px-0 font-14',
                                                    'btnText' => trans("admin/main.delete"),
                                                    'btnIcon' => 'trash',
                                                    'iconType' => 'lin',
                                                    'iconClass' => 'text-danger mr-2',
                                                ])
                                            </div>
                                        </div>
                                    </td>


                                </tr>
                            @endforeach

                        </table>
                    </div>
                @else
                    <div class="d-flex-center flex-column px-32 py-120 text-center">
                        <div class="d-flex-center size-64 rounded-12 bg-primary-30">
                            @if($extraDescriptionType == "learning_materials")
                                <x-iconsax-bul-teacher class="icons text-primary" width="32px" height="32px"/>
                            @elseif($extraDescriptionType == "company_logos")
                                <x-iconsax-bul-sticker class="icons text-primary" width="32px" height="32px"/>
                            @else
                                <x-iconsax-bul-shield-tick class="icons text-primary" width="32px" height="32px"/>
                            @endif

                        </div>
                        <h3 class="font-16 font-weight-bold mt-12">{{ trans("update.{$extraDescriptionType}_no_result") }}</h3>
                        <p class="mt-4 font-12 text-gray-500">{!! trans("update.{$extraDescriptionType}_no_result_hint") !!}</p>
                    </div>
                @endif
            </div>
        </div>
    </section>
@endforeach
