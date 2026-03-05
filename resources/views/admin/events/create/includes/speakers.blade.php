<section class="mt-30">
    <div class="d-flex justify-content-between align-items-center">
        <h2 class="section-title after-line">{{ trans('update.speakers') }}</h2>
        <button type="button"
                class="js-event-contents-action-btn btn btn-primary btn-sm mt-3"
                data-path="{{ getAdminPanelUrl("/events/{$event->id}/speakers/get-form") }}"
                data-title="{{ trans('update.add_new_speaker') }}"
        >
            {{ trans('update.add_speaker') }}
        </button>
    </div>

    <div class="row mt-10">
        <div class="col-12">

            @if(!empty($event->speakers) and $event->speakers->isNotEmpty())
                <div class="table-responsive">
                    <table class="table custom-table border-0 text-center font-14">

                        <tr>
                            <th>{{ trans('public.name') }}</th>
                            <th>{{ trans('update.job') }}</th>
                            <th>{{ trans('public.status') }}</th>
                            <th width="80px">{{ trans('admin/main.action') }}</th>
                        </tr>

                        @foreach($event->speakers as $speaker)
                            <tr>
                                <th scope="row">{{ $speaker->name }}</th>
                                <td>{{ $speaker->job ?? '-' }}</td>
                                <td>{{ $speaker->enable ? trans('admin/main.active') : trans('admin/main.inactive') }}</td>
                                <td>
                                    <div class="btn-group dropdown table-actions position-relative">
                                        <button type="button" class="btn-transparent dropdown-toggle" data-toggle="dropdown">
                                            <x-iconsax-lin-more class="icons text-gray-500" width="20px" height="20px"/>
                                        </button>

                                        <div class="dropdown-menu dropdown-menu-right">
                                            <button type="button" class="js-event-contents-action-btn dropdown-item d-flex align-items-center mb-3 py-3 px-0 gap-4"
                                                    data-path="{{ getAdminPanelUrl("/events/{$event->id}/speakers/{$speaker->id}/edit") }}"
                                                    data-title="{{ trans('update.edit_speaker') }}"
                                            >
                                                <x-iconsax-lin-edit-2 class="icons text-gray-500 mr-2" width="18px" height="18px"/>
                                                <span class="text-gray-500 font-14">{{ trans('admin/main.edit') }}</span>
                                            </button>

                                            @include('admin.includes.delete_button',[
                                                'url' => getAdminPanelUrl("/events/{$event->id}/speakers/{$speaker->id}/delete"),
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
                        <x-iconsax-bul-receipt-2 class="icons text-primary" width="32px" height="32px"/>
                    </div>
                    <h3 class="font-16 font-weight-bold mt-12">{{ trans('update.speakers_no_result') }}</h3>
                    <p class="mt-4 font-12 text-gray-500">{!! trans('update.speakers_no_result_hint') !!}</p>
                </div>
            @endif
        </div>
    </div>
</section>
