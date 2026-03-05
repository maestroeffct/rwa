@extends('admin.layouts.app')

@push('libraries_top')

@endpush

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{ trans('admin/main.supports') }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ getAdminPanelUrl() }}">{{trans('admin/main.dashboard')}}</a>
                </div>
                <div class="breadcrumb-item">{{ trans('admin/main.supports') }}</div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card-statistic">
                    <div class="card-statistic__mask"></div>
                    <div class="card-statistic__wrap">
                        <div class="d-flex align-items-start justify-content-between">
                            <span class="text-gray-500 mt-8">{{trans('admin/main.total_conversations')}}</span>
                            <div class="d-flex-center size-48 bg-primary-30 rounded-12">
                                <x-iconsax-bul-sms class="icons text-primary" width="24px" height="24px"/>
                            </div>
                        </div>
                        <h5 class="font-24 mt-12 line-height-1 text-black">{{ $totalConversations }}</h5>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card-statistic">
                    <div class="card-statistic__mask"></div>
                    <div class="card-statistic__wrap">
                        <div class="d-flex align-items-start justify-content-between">
                            <span class="text-gray-500 mt-8">{{trans('admin/main.pending_reply')}}</span>
                            <div class="d-flex-center size-48 bg-warning-30 rounded-12">
                                <x-iconsax-bul-sms class="icons text-warning" width="24px" height="24px"/>  
                            </div>
                        </div>
                        <h5 class="font-24 mt-12 line-height-1 text-black">{{ $pendingReplySupports }}</h5>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card-statistic">
                    <div class="card-statistic__mask"></div>
                    <div class="card-statistic__wrap">
                        <div class="d-flex align-items-start justify-content-between">
                            <span class="text-gray-500 mt-8">{{trans('admin/main.open_conversations')}}</span>
                            <div class="d-flex-center size-48 bg-success-30 rounded-12">
                                <x-iconsax-bul-sms class="icons text-success" width="24px" height="24px"/>
                            </div>
                        </div>
                        <h5 class="font-24 mt-12 line-height-1 text-black">{{ $openConversationsCount }}</h5>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card-statistic">
                    <div class="card-statistic__mask"></div>
                    <div class="card-statistic__wrap">
                        <div class="d-flex align-items-start justify-content-between">
                            <span class="text-gray-500 mt-8">{{trans('admin/main.closed_conversations')}}</span>
                            <div class="d-flex-center size-48 bg-danger-30 rounded-12">
                                <x-iconsax-bul-sms class="icons text-danger" width="24px" height="24px"/>
                            </div>
                        </div>
                        <h5 class="font-24 mt-12 line-height-1 text-black">{{ $closeConversationsCount }}</h5>
                    </div>
                </div>
            </div>
        </div>

        <div class="section-body">
            <section class="card mt-32">
                <div class="card-body pb-4">
                    <form method="get" class="mb-0">

                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="input-label">{{trans('admin/main.search')}}</label>
                                    <input type="text" name="title" value="{{ request()->get('title') }}" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="input-label">{{trans('admin/main.date')}}</label>
                                    <div class="input-group">
                                        <input type="date" id="fsdate" class="text-center form-control" name="date" value="{{ request()->get('date') }}" placeholder="Date">
                                    </div>
                                </div>
                            </div>


                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="input-label">{{trans('admin/main.department')}}</label>
                                    <select name="department_id" data-plugin-selectTwo class="form-control populate">
                                        <option value="">{{trans('admin/main.all_departments')}}</option>
                                        @foreach($departments as $department)
                                            <option value="{{ $department->id }}" @if(request()->get('department_id') == $department->id) selected @endif>{{ $department->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>


                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="input-label">{{trans('admin/main.role')}}</label>
                                    <select name="role_id" data-plugin-selectTwo class="form-control populate">
                                        <option value="">{{trans('admin/main.all_user_roles')}}</option>
                                        @foreach($roles as $role)
                                            <option value="{{ $role->id }}" @if(request()->get('role_id') == $role->id) selected @endif>{{ $role->caption }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>


                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="input-label">{{trans('admin/main.status')}}</label>
                                    <select name="status" data-plugin-selectTwo class="form-control populate">
                                        <option value="">{{trans('admin/main.all_status')}}</option>
                                        <option value="open" @if(request()->get('status') == 'open') selected @endif>{{trans('admin/main.open')}}</option>
                                        <option value="replied" @if(request()->get('status') == 'replied') selected @endif>{{trans('admin/main.pending_reply')}}</option>
                                        <option value="supporter_replied" @if(request()->get('status') == 'supporter_replied') selected @endif>{{trans('admin/main.replied')}}</option>
                                        <option value="close" @if(request()->get('status') == 'close') selected @endif>{{trans('admin/main.closed')}}</option>
                                    </select>
                                </div>
                            </div>


                            <div class="col-md-2 d-flex align-items-center ">
                                <button type="submit" class="btn btn-primary btn-block btn-lg">{{trans('admin/main.show_results')}}</button>
                            </div>

                        </div>

                    </form>
                </div>
            </section>

            <section class="card">

            <div class="card-header justify-content-between">
                            
                            <div>
                               <h5 class="font-14 mb-0">{{ $pageTitle }}</h5>
                               <p class="font-12 mt-4 mb-0 text-gray-500">{{ trans('update.manage_all_tickets_in_a_single_place') }}</p>
                           </div>
                           
                            <div class="d-flex align-items-center gap-12">

                            @can('admin_support_send')
                                   <a href="{{ getAdminPanelUrl("/supports/create") }}" target="_blank" class="btn btn-primary">
                                       <x-iconsax-lin-add class="icons text-white" width="18px" height="18px"/>
                                       <span class="ml-4 font-12">{{ trans('admin/main.add_new') }}</span>
                                   </a>
                               @endcan

                            </div>
                           
                       </div>

                <div class="card-body">
                    <div class="table-responsive text-center">
                        <table class="table custom-table font-14">

                            <tr>
                                <th>{{trans('admin/main.title')}}</th>
                                <th class="text-center">{{trans('admin/main.created_date')}}</th>
                                <th class="text-center">{{trans('admin/main.last_update')}}</th>
                                <th class="text-left">{{trans('admin/main.user')}}</th>
                                <th class="text-center">{{trans('admin/main.role')}}</th>
                                <th class="text-center">{{trans('admin/main.department')}}</th>
                                <th class="text-center">{{trans('admin/main.status')}}</th>
                                <th class="text-center">{{trans('admin/main.actions')}}</th>
                            </tr>

                            @foreach($supports as $support)
                                <tr>
                                    <td>
                                        <a class="text-dark" href="{{ getAdminPanelUrl() }}/supports/{{ $support->id }}/conversation">
                                            {{ $support->title }}
                                        </a>
                                    </td>

                                    <td class="text-center font-12">{{ dateTimeFormat($support->created_at,'j M Y | H:i') }}</td>

                                    <td class="text-center font-12">{{ (!empty($support->updated_at)) ? dateTimeFormat($support->updated_at,'j M Y | H:i') : '-' }}</td>

                                    <td class="text-left">
                                        <a class="text-dark" title="{{ $support->user->full_name }}" href="{{ $support->user->getProfileUrl() }}" target="_blank">{{ $support->user->full_name }}</a>
                                    </td>

                                    <td class="text-center">
                                        @if($support->user->isUser())
                                            Student
                                        @elseif($support->user->isTeacher())
                                            Teacher
                                        @elseif($support->user->isOrganization())
                                            Organization
                                        @endif
                                    </td>

                                    <td class="text-center">{{ $support->department->title }}</td>

                                    <td class="text-center">
                                        @if($support->status == 'close')
                                            <span class="badge-status text-danger bg-danger-30">{{ trans('public.close') }}</span>
                                        @elseif($support->status == 'replied' or $support->status == 'open')
                                            <span class="badge-status text-warning bg-warning-30">{{trans('admin/main.pending_reply')}}</span>
                                        @else
                                            <span class="badge-status text-success bg-success-30">{{ trans('admin/main.replied') }}</span>
                                        @endif
                                    </td>

                                    <td class="text-center" width="50">
    <div class="btn-group dropdown table-actions position-relative">
        <button type="button" class="btn-transparent dropdown-toggle" data-toggle="dropdown">
            <x-iconsax-lin-more class="icons text-gray-500" width="20px" height="20px"/>
        </button>

        <div class="dropdown-menu dropdown-menu-right">
            @can('admin_supports_reply')
                <a href="{{ getAdminPanelUrl() }}/supports/{{ $support->id }}/conversation"
                   class="dropdown-item d-flex align-items-center mb-3 py-3 px-0 gap-4">
                    <x-iconsax-lin-message-text class="icons text-gray-500 mr-2" width="18px" height="18px"/>
                    <span class="text-gray-500 font-14">{{ trans('admin/main.reply') }}</span>
                </a>
            @endcan

            @can('admin_supports_delete')
                @include('admin.includes.delete_button',[
                    'url' => getAdminPanelUrl().'/supports/'.$support->id.'/delete',
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

                        </table>
                    </div>
                </div>

                <div class="card-footer text-center">
                    {{ $supports->appends(request()->input())->links() }}
                </div>
            </section>

        </div>
    </section>
@endsection

@push('scripts_bottom')

@endpush
