@extends('admin.layouts.app')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{ trans('admin/main.users_groups') }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ getAdminPanelUrl() }}">{{trans('admin/main.dashboard')}}</a>
                </div>
                <div class="breadcrumb-item">{{ trans('admin/main.users_groups') }}</div>
            </div>
        </div>

        <div class="section-body">

            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table custom-table font-14">
                                    <tr>
                                        <th>#</th>
                                        <th class="text-left">{{ trans('admin/main.name') }}</th>
                                        <th>{{ trans('admin/main.users') }}</th>
                                        <th>{{ trans('admin/main.commission') }}</th>
                                        <th>{{ trans('admin/main.discount') }}</th>
                                        <th>{{ trans('admin/main.status') }}</th>
                                        <th>{{ trans('admin/main.actions') }}</th>
                                    </tr>

                                    @foreach($groups as $group)
                                        <tr>
                                            <td>{{ $group->id }}</td>
                                            <td class="text-left">
                                                <span>{{ $group->name }}</span>
                                            </td>
                                            <td>{{ $group->groupUsers->count() }}</td>
                                            <td>{{ $group->commission ?? 0 }}%</td>
                                            <td>{{ $group->discount ?? 0 }}%</td>
                                            <td>
                                                <span class="badge-status {{ ($group->status == 'active') ? 'text-success bg-success-30' : 'text-danger bg-danger-30' }}">{{ trans('admin/main.'.$group->status) }}</span>
                                            </td>
                                            <td>
    <div class="btn-group dropdown table-actions position-relative">
        <button type="button" class="btn-transparent dropdown-toggle" data-toggle="dropdown">
            <x-iconsax-lin-more class="icons text-gray-500" width="20px" height="20px"/>
        </button>

        <div class="dropdown-menu dropdown-menu-right">
            @can('admin_group_edit')
                <a href="{{ getAdminPanelUrl() }}/users/groups/{{ $group->id }}/edit"
                   class="dropdown-item d-flex align-items-center mb-3 py-3 px-0 gap-4">
                    <x-iconsax-lin-edit-2 class="icons text-gray-500 mr-2" width="18px" height="18px"/>
                    <span class="text-gray-500 font-14">{{ trans('admin/main.edit') }}</span>
                </a>
            @endcan

            @can('admin_group_delete')
                @include('admin.includes.delete_button',[
                    'url' => getAdminPanelUrl().'/users/groups/'.$group->id.'/delete',
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
                            {{ $groups->appends(request()->input())->links() }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts_bottom')

@endpush
