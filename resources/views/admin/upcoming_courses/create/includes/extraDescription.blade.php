@foreach(\App\Models\WebinarExtraDescription::$types as $extraDescriptionType)
    <section class="mt-30">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="section-title after-line">{{ trans('update.'.$extraDescriptionType) }}</h2>
            <button id="add_new_{{ $extraDescriptionType }}" type="button" class="btn btn-primary btn-sm mt-3">{{ trans('update.add_'.$extraDescriptionType) }}</button>
        </div>

        @php
            $webinarExtraDescriptionValues = $upcomingCourse->extraDescriptions->where('type',$extraDescriptionType);
        @endphp

        <div class="row mt-10">
            <div class="col-12">
                @if(!empty($webinarExtraDescriptionValues) and count($webinarExtraDescriptionValues))
                    <div class="table-responsive">
                        <table class="table custom-table text-center font-14">

                            <tr>
                                @if($extraDescriptionType == \App\Models\WebinarExtraDescription::$COMPANY_LOGOS)
                                    <th>{{ trans('admin/main.icon') }}</th>
                                @else
                                    <th>{{ trans('public.title') }}</th>
                                @endif
                                <th></th>
                            </tr>

                            @foreach($webinarExtraDescriptionValues as $extraDescription)
                                <tr>
                                    @if($extraDescriptionType == \App\Models\WebinarExtraDescription::$COMPANY_LOGOS)
                                        <td>
                                            <img src="{{ $extraDescription->value }}" class="webinar-extra-description-company-logos" alt="">
                                        </td>
                                    @else
                                        <td>{{ $extraDescription->value }}</td>
                                    @endif

                                    <td class="text-right">
                                        <button type="button" data-item-id="{{ $extraDescription->id }}" data-webinar-id="{{ !empty($upcomingCourse) ? $upcomingCourse->id : '' }}" class="edit-extraDescription btn-transparent text-primary mt-1" data-toggle="tooltip" data-placement="top" title="{{ trans('admin/main.edit') }}">
                                            <i class="fa fa-edit"></i>
                                        </button>

                                        @include('admin.includes.delete_button',['url' => getAdminPanelUrl().'/webinar-extra-description/'. $extraDescription->id .'/delete', 'btnClass' => ' mt-1'])
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
