<div class="custom-tabs-content active">
    <div class="bg-white rounded-16 py-16 border-gray-200">
        <h3 class="font-14 font-weight-bold px-16">{{ trans('update.login_history') }}</h3>

        <div class="table-responsive-lg mt-16">
            <table class="table panel-table">
                <thead>
                <tr>
                    <th class="text-left">{{ trans('update.os') }}</th>
                    <th class="text-center">{{ trans('update.browser') }}</th>
                    <th class="text-center">{{ trans('update.device') }}</th>
                    <th class="text-center">{{ trans('update.ip_address') }}</th>
                    <th class="text-center">{{ trans('update.country') }}</th>
                    <th class="text-center">{{ trans('update.city') }}</th>
                    <th class="text-center">{{ trans('update.session_start') }}</th>
                    <th class="text-center">{{ trans('update.session_end') }}</th>
                    <th class="text-center">{{ trans('public.duration') }}</th>
                    <th class="text-right">{{ trans('admin/main.actions') }}</th>
                </tr>
                </thead>
                <tbody class="">
                @if(!empty($userLoginHistories))
                    @foreach($userLoginHistories as $session)

                        <tr>
                            <td class="text-left">{{ $session->os ?? '-' }}</td>

                            <td class="text-center">{{ $session->browser ?? '-' }}</td>

                            <td class="text-center">{{ $session->device ?? '-' }}</td>

                            <td class="text-center">{{ $session->ip ?? '-' }}</td>

                            <td class="text-center">{{ $session->country ?? '-' }}</td>

                            <td class="text-center">{{ $session->city ?? '-' }}</td>

                            <td class="text-center">{{ dateTimeFormat($session->session_start_at, 'j M Y H:i') }}</td>

                            <td class="text-center">{{ !empty($session->session_end_at) ? dateTimeFormat($session->session_end_at, 'j M Y H:i') : '-' }}</td>

                            <td class="text-center">{{ $session->getDuration() }}</td>

                            <td class="text-right">

                                @if(empty($session->session_end_at))
                                    <div class="actions-dropdown position-relative d-flex justify-content-end align-items-center">
                                        <button type="button" class="d-flex-center size-36 bg-gray border-gray-200 rounded-10">
                                            <x-iconsax-lin-more class="icons text-gray-500" width="18"/>
                                        </button>

                                        <div class="actions-dropdown__dropdown-menu dropdown-menu-top-32">
                                            <ul class="my-8">

                                                <li class="actions-dropdown__dropdown-menu-item">
                                                    <a
                                                        href="/panel/users/login-history/{{ $session->id }}/end-session"
                                                        data-msg="{{ trans('update.this_device_will_be_logout_from_your_account') }}"
                                                        data-confirm="{{ trans('update.end_session') }}"
                                                        class="delete-action ">
                                                        {{ trans('update.end_session') }}
                                                    </a>
                                                </li>

                                            </ul>
                                        </div>
                                    </div>
                                @else
                                    -
                                @endif

                            </td>

                        </tr>
                    @endforeach
                @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
