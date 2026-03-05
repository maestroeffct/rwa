@extends('admin.layouts.app')

@push('libraries_top')

@endpush

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{ $pageTitle }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ getAdminPanelUrl() }}">{{trans('admin/main.dashboard')}}</a>
                </div>
                <div class="breadcrumb-item">{{ $pageTitle }}</div>
            </div>
        </div>


        <div class="section-body">

            <div class="row">
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card-statistic">
                        <div class="card-statistic__mask"></div>
                        <div class="card-statistic__wrap">
                            <div class="d-flex align-items-start justify-content-between">
                                <span class="text-gray-500 mt-8">{{trans('update.total_forums')}}</span>
                                <div class="d-flex-center size-48 bg-primary-30 rounded-12">
                                    <x-iconsax-bul-note-2 class="icons text-primary" width="24px" height="24px"/>
                                </div>
                            </div>
                            <h5 class="font-24 mt-12 line-height-1 text-black">{{ $totalForums }}</h5>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card-statistic">
                        <div class="card-statistic__mask"></div>
                        <div class="card-statistic__wrap">
                            <div class="d-flex align-items-start justify-content-between">
                                <span class="text-gray-500 mt-8">{{trans('update.total_topics')}}</span>
                                <div class="d-flex-center size-48 bg-secondary-30 rounded-12">
                                    <x-iconsax-bul-message-square class="icons text-secomndary" width="24px" height="24px"/>
                                </div>
                            </div>
                            <h5 class="font-24 mt-12 line-height-1 text-black">{{ $totalTopics }}</h5>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card-statistic">
                        <div class="card-statistic__mask"></div>
                        <div class="card-statistic__wrap">
                            <div class="d-flex align-items-start justify-content-between">
                                <span class="text-gray-500 mt-8">{{trans('update.total_posts')}}</span>
                                <div class="d-flex-center size-48 bg-success-30 rounded-12">
                                    <x-iconsax-bul-message-edit class="icons text-success" width="24px" height="24px"/>
                                </div>
                            </div>
                            <h5 class="font-24 mt-12 line-height-1 text-black">{{ $postsCount }}</h5>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card-statistic">
                        <div class="card-statistic__mask"></div>
                        <div class="card-statistic__wrap">
                            <div class="d-flex align-items-start justify-content-between">
                                <span class="text-gray-500 mt-8">{{trans('update.active_members')}}</span>
                                <div class="d-flex-center size-48 bg-accent-30 rounded-12">
                                    <x-iconsax-bul-people class="icons text-accent" width="24px" height="24px"/>
                                </div>
                            </div>
                            <h5 class="font-24 mt-12 line-height-1 text-black">{{ $membersCount }}</h5>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="card mt-32">

                        <div class="card-header justify-content-between">
                            
                            <div>
                               <h5 class="font-14 mb-0">{{ $pageTitle }}</h5>
                               <p class="font-12 mt-4 mb-0 text-gray-500">{{ trans('update.manage_all_items_in_a_single_place') }}</p>
                           </div>
                           
                            <div class="d-flex align-items-center gap-12">

                                @can('admin_forum_create')
                                   <a href="{{ getAdminPanelUrl("/forums/create") }}" target="_blank" class="btn btn-primary">
                                       <x-iconsax-lin-add class="icons text-white" width="18px" height="18px"/>
                                       <span class="ml-4 font-12">{{ trans('admin/main.add_new') }}</span>
                                   </a>
                               @endcan

                            </div>
                           
                       </div>

                        <div class="card-body pb-0">
                            <div class="table-responsive">
                                <table class="table custom-table font-14">
                                    <tr>
                                        <th>{{ trans('admin/main.icon') }}</th>
                                        <th class="text-left">{{ trans('admin/main.title') }}</th>
                                        @if(empty(request()->get('subForums')))
                                            <th>{{ trans('update.sub_forums') }}</th>
                                        @endif
                                        <th>{{ trans('update.topics') }}</th>
                                        <th>{{ trans('site.posts') }}</th>
                                        <th>{{ trans('admin/main.status') }}</th>
                                        <th>{{ trans('admin/main.closed') }}</th>
                                        <th>{{ trans('admin/main.action') }}</th>
                                    </tr>
                                    @foreach($forums as $forum)

                                        <tr>
                                            <td>
                                                <img src="{{ $forum->icon }}" width="30" alt="">
                                            </td>
                                            <td class="text-left text-dark">
                                                @if(!empty($forum->subForums) and count($forum->subForums))
                                                    <a class="text-dark" href="{{ getAdminPanelUrl() }}/forums?subForums={{ $forum->id }}">{{ $forum->title }}</a>
                                                @else
                                                    <a class="text-dark" href="{{ getAdminPanelUrl() }}/forums/{{ $forum->id }}/topics">{{ $forum->title }}</a>
                                                @endif
                                            </td>
                                            @if(empty(request()->get('subForums')))
                                                <td>
                                                    @if(!empty($forum->subForums))
                                                        {{ count($forum->subForums) }}
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                            @endif
                                            <td>{{ $forum->topics_count }}</td>
                                            <td>{{ $forum->posts_count }}</td>
                                            <td>
                                            <span class="badge-status {{ ($forum->status == 'active') ? 'text-success bg-success-30' : 'text-danger bg-danger-30' }}">{{ trans('admin/main.'.$forum->status) }}</span>
                                            </td>
                                            <td>
                                                @if($forum->close)
                                                    {{ trans('admin/main.yes') }}
                                                @else
                                                    {{ trans('admin/main.no') }}
                                                @endif
                                            </td>
                                            <td>
    <div class="btn-group dropdown table-actions position-relative">
        <button type="button" class="btn-transparent dropdown-toggle" data-toggle="dropdown">
            <x-iconsax-lin-more class="icons text-gray-500" width="20px" height="20px"/>
        </button>

        <div class="dropdown-menu dropdown-menu-right">
            @if(!empty($forum->subForums) && count($forum->subForums))
                <a href="{{ getAdminPanelUrl() }}/forums?subForums={{ $forum->id }}"
                   class="dropdown-item d-flex align-items-center mb-3 py-3 px-0 gap-4">
                    <x-iconsax-lin-eye class="icons text-gray-500 mr-2" width="18px" height="18px"/>
                    <span class="text-gray-500 font-14">{{ trans('update.forums') }}</span>
                </a>
            @else
                @can('admin_forum_topics_lists')
                    <a href="{{ getAdminPanelUrl() }}/forums/{{ $forum->id }}/topics"
                       class="dropdown-item d-flex align-items-center mb-3 py-3 px-0 gap-4">
                        <x-iconsax-lin-eye class="icons text-gray-500 mr-2" width="18px" height="18px"/>
                        <span class="text-gray-500 font-14">{{ trans('update.topics') }}</span>
                    </a>
                @endcan
            @endif

            @can('admin_forum_edit')
                <a href="{{ getAdminPanelUrl() }}/forums/{{ $forum->id }}/edit"
                   class="dropdown-item d-flex align-items-center mb-3 py-3 px-0 gap-4">
                    <x-iconsax-lin-edit-2 class="icons text-gray-500 mr-2" width="18px" height="18px"/>
                    <span class="text-gray-500 font-14">{{ trans('admin/main.edit') }}</span>
                </a>
            @endcan

            @can('admin_forum_delete')
                @include('admin.includes.delete_button',[
                    'url' => getAdminPanelUrl().'/forums/'.$forum->id.'/delete',
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
                            {{ $forums->appends(request()->input())->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
