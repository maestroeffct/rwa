<div class="tab-pane mt-3 fade" id="support_tickets" role="tabpanel" aria-labelledby="support_tickets-tab">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table custom-table">
                           
                                <tr>
                                    <th>{{ trans('admin/main.title') }}</th>
                                    <th>{{ trans('admin/main.department') }}</th>
                                    <th>{{ trans('admin/main.status') }}</th>
                                    <th>{{ trans('admin/main.created_at') }}</th>
                                    <th>{{ trans('admin/main.updated_at') }}</th>
                                    <th class="text-right">{{ trans('admin/main.actions') }}</th>
                                </tr>
                           
                                @foreach($user->supports as $support)
                                    <tr>
                                        <td>
                                            <a href="{{ getAdminPanelUrl() }}/supports/{{ $support->id }}/conversation">
                                                {{ $support->title }}
                                            </a>
                                        </td>
                                        <td>{{ $support->department ? $support->department->title : trans('admin/main.no_department') }}</td>
                                        <td>
                                            @if($support->status == 'close')
                                                <span class="badge-status-card text-danger bg-danger-30">{{ trans('admin/main.close') }}</span>
                                            @elseif($support->status == 'replied')
                                                <span class="badge-status-card text-warning bg-warning-30">{{ trans('admin/main.pending_reply') }}</span>
                                            @else
                                                <span class="badge-status-card text-success bg-success-30">{{ trans('admin/main.replied') }}</span>
                                            @endif
                                        </td>
                                        <td>{{ dateTimeFormat($support->created_at, 'j M Y | H:i') }}</td>
                                        <td>{{ (!empty($support->updated_at)) ? dateTimeFormat($support->updated_at, 'j M Y | H:i') : '-' }}</td>
                                     <td class="text-right">
                                        <div class="btn-group dropdown table-actions position-relative">
                                            <button type="button" class="btn-transparent dropdown-toggle" data-toggle="dropdown">
                                                <x-iconsax-lin-more class="icons text-gray-500" width="20px" height="20px"/>
                                            </button>

                                            <div class="dropdown-menu dropdown-menu-right">
                                                <a href="{{ getAdminPanelUrl() }}/supports/{{ $support->id }}/conversation" 
                                                   class="dropdown-item d-flex align-items-center mb-3 py-3 px-0 gap-4">
                                                    <x-iconsax-lin-sms class="icons text-gray-500 mr-2" width="18px" height="18px"/>
                                                    <span class="text-gray-500 font-14">{{ trans('public.view') }}</span>
                                                </a>

                                                @if($support->status != 'close')
                                                    @include('admin.includes.delete_button',[
                                                        'url' => getAdminPanelUrl().'/supports/'.$support->id.'/close',
                                                        'btnClass' => 'dropdown-item text-danger mb-0 py-3 px-0 font-14',
                                                        'btnText' => trans('admin/main.close'),
                                                        'btnIcon' => 'close-circle',
                                                        'iconType' => 'lin',
                                                        'iconClass' => 'text-danger mr-2',
                                                    ])
                                                @endif
                                            </div>
                                        </div>
                                    </td>

                                    </tr>
                                @endforeach
                      
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>