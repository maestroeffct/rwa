<div class="tab-pane fade @if(request()->get('tab') == "payment_channels") active show @endif" id="payment_channels" role="tabpanel" aria-labelledby="payment_channels-tab">
    <div class="card">

        <div class="card-body">
            <div class="table-responsive">
                <table class="table custom-table font-14">
                    <tr>
                        <th class="text-left">{{ trans('admin/main.title') }}</th>
                        <th>{{ trans('public.status') }}</th>
                        <th>{{ trans('admin/main.actions') }}</th>
                    </tr>

                    @foreach($paymentChannels as $paymentChannel)
                        <tr>
                            <td class="text-left">{{ $paymentChannel->title }}</td>
                            <td>
                                @if($paymentChannel->status == 'active')
                                    <span class="text-success">{{ trans('admin/main.active') }}</span>
                                @else
                                    <span class="text-danger">{{ trans('admin/main.inactive') }}</span>
                                @endif
                            </td>

                            <td width="80px">
    <div class="btn-group dropdown table-actions position-relative">
        <button type="button" class="btn-transparent dropdown-toggle" data-toggle="dropdown">
            <x-iconsax-lin-more class="icons text-gray-500" width="20px" height="20px"/>
        </button>

        <div class="dropdown-menu dropdown-menu-right">
            @can('admin_payment_channel_edit')
                <a href="{{ getAdminPanelUrl() }}/settings/payment_channels/{{ $paymentChannel->id }}/edit" class="dropdown-item d-flex align-items-center mb-3 py-3 px-0 gap-4">
                    <x-iconsax-lin-edit-2 class="icons text-gray-500 mr-2" width="18px" height="18px"/>
                    <span class="text-gray-500 font-14">{{ trans('admin/main.edit') }}</span>
                </a>
            @endcan

            @can('admin_payment_channel_toggle_status')
                <a href="{{ getAdminPanelUrl() }}/settings/payment_channels/{{ $paymentChannel->id }}/toggleStatus" class="dropdown-item d-flex align-items-center mb-0 py-3 px-0 gap-4">
                    @if($paymentChannel->status == 'inactive')
                        <x-iconsax-lin-arrow-up class="icons text-success mr-2" width="18px" height="18px"/>
                        <span class="text-success">{{ trans('admin/main.active') }}</span>
                    @else
                        <x-iconsax-lin-arrow-down class="icons text-warning mr-2" width="18px" height="18px"/>
                        <span class="text-warning">{{ trans('admin/main.inactive') }}</span>
                    @endif
                </a>
            @endcan
        </div>
    </div>
</td>
                        </tr>
                    @endforeach

                </table>
            </div>
        </div>

        <div class="card-footer text-center">
            {{ $paymentChannels->appends(['tab' => "payment_channels"])->links() }}
        </div>

    </div>
</div>
