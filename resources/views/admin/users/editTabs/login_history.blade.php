<div class="tab-pane mt-3 fade {{ (request()->get('tab') == "loginHistory") ? 'active show' : '' }}" id="loginHistory" role="tabpanel" aria-labelledby="loginHistory-tab">
    <div class="row">

        <div class="col-12">
            <div class="d-flex align-items-center justify-content-between">
                <h5 class="section-title after-line m-0 mr-12">{{ trans('update.login_history') }}</h5>

                @can('admin_user_login_history_end_session')
                    @include('admin.includes.delete_button',[
                        'url' => getAdminPanelUrl("/users/{$user->id}/end-all-login-sessions"),
                        'noBtnTransparent' => true,
                        'btnText' => trans('update.end_all_sessions'),
                        'btnClass' => "btn btn-primary text-white"
                       ])
                @endcan
            </div>

            <div class="table-responsive mt-1">
                <table class="table custom-table font-14">
                    <tr>
                        <th>{{ trans('update.os') }}</th>
                        <th>{{ trans('update.browser') }}</th>
                        <th>{{ trans('update.device') }}</th>
                        <th>{{ trans('update.ip_address') }}</th>
                        <th>{{ trans('update.country') }}</th>
                        <th>{{ trans('update.city') }}</th>
                        <th>{{ trans('update.lat,long') }}</th>
                        <th>{{ trans('update.session_start') }}</th>
                        <th>{{ trans('update.session_end') }}</th>
                        <th>{{ trans('public.duration') }}</th>
                        <th width="120">{{ trans('admin/main.actions') }}</th>
                    </tr>

                    @if(!empty($userLoginHistories))
                        @foreach($userLoginHistories as $session)

                            <tr>
                                <td>{{ $session->os ?? '-' }}</td>

                                <td>{{ $session->browser ?? '-' }}</td>

                                <td>{{ $session->device ?? '-' }}</td>

                                <td>{{ $session->ip ?? '-' }}</td>

                                <td>{{ $session->country ?? '-' }}</td>

                                <td>{{ $session->city ?? '-' }}</td>

                                <td>{{ $session->location ?? '-' }}</td>

                                <td>{{ dateTimeFormat($session->session_start_at, 'j M Y H:i') }}</td>

                                <td>{{ !empty($session->session_end_at) ? dateTimeFormat($session->session_end_at, 'j M Y H:i') : '-' }}</td>

                                <td>{{ $session->getDuration() }}</td>

                                <td class="text-center">
                                    <div class="btn-group dropdown table-actions position-relative">
                                        <button type="button" class="btn-transparent dropdown-toggle" data-toggle="dropdown">
                                            <x-iconsax-lin-more class="icons text-gray-500" width="20px" height="20px"/>
                                        </button>

                                        <div class="dropdown-menu dropdown-menu-right">
                                        @can('admin_user_login_history_end_session')
                                      @if(empty($session->session_end_at))
                                          @include('admin.includes.delete_button',[
                                              'url' => getAdminPanelUrl().'/users/login-history/'.$session->id.'/end-session',
                                              'btnClass' => 'dropdown-item text-danger mb-3 py-3 px-0 font-14',
                                              'btnText' => trans('update.end_session'),
                                              'btnIcon' => 'logout',
                                              'iconType' => 'lin',
                                              'iconClass' => 'text-danger mr-2'
                                          ])
                                      @endif
                                  @endcan

                                  @can('admin_user_login_history_delete')
                                      @include('admin.includes.delete_button',[
                                          'url' => getAdminPanelUrl().'/users/login-history/'.$session->id.'/delete',
                                          'btnClass' => 'dropdown-item text-danger mb-0 py-3 px-0 font-14',
                                          'btnText' => trans('admin/main.delete'),
                                          'btnIcon' => 'trash',
                                          'iconType' => 'lin',
                                          'iconClass' => 'text-danger mr-2'
                                      ])
                                  @endcan
                                        </div>
                                    </div>
                                </td>

                            </tr>
                        @endforeach
                    @endif
                </table>
            </div>

            @if(!empty($userLoginHistories))
                <div class="card-footer text-center">
                    {{ $userLoginHistories->appends(request()->input())->links() }}
                </div>
            @endif


        </div>
    </div>
</div>
