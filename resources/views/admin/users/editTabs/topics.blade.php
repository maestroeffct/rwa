<div class="tab-pane mt-0 fade" id="topics" role="tabpanel" aria-labelledby="topics-tab">
    <div class="row">

        <div class="col-12">
            <h5 class="section-title after-line">{{ trans('update.forum_topics') }}</h5>

            <div class="table-responsive mt-1">
                <table class="table custom-table table-md">
                    <tr>
                        <th>{{ trans('public.topic') }}</th>
                        <th>{{ trans('admin/main.category') }}</th>
                        <th>{{ trans('site.posts') }}</th>
                        <th>{{ trans('admin/main.created_at') }}</th>
                        <th>{{ trans('admin/main.updated_at') }}</th>
                        <th class="text-right">{{ trans('admin/main.actions') }}</th>
                    </tr>

                    @if(!empty($topics))
                        @foreach($topics as $topic)

                            <tr>
                                <td width="25%">
                                    <a href="{{ $topic->getPostsUrl() }}" target="_blank" class="">{{ $topic->title }}</a>
                                </td>

                                <td>
                                    {{ $topic->forum->title }}
                                </td>
                                <td>{{ $topic->posts_count }}</td>
                                <td class="text-center">{{ dateTimeFormat($topic->created_at,'j M Y | H:i') }}</td>
                                <td class="text-center">{{ (!empty($topic->posts) and count($topic->posts)) ? dateTimeFormat($topic->posts->first()->created_at,'j M Y | H:i') : '-' }}</td>
                                <td class="text-right">
                                    <div class="btn-group dropdown table-actions position-relative">
                                        <button type="button" class="btn-transparent dropdown-toggle" data-toggle="dropdown">
                                            <x-iconsax-lin-more class="icons text-gray-500" width="20px" height="20px"/>
                                        </button>

                                        <div class="dropdown-menu dropdown-menu-right">
                                            @can('admin_forum_topics_lists')
                                                @if(!$topic->close)
                                                    @include('admin.includes.delete_button',[
                                                        'url' => "/admin/forums/{$topic->forum_id}/topics/{$topic->id}/close",
                                                        'btnClass' => 'dropdown-item text-warning mb-3 py-3 px-0 font-14',
                                                        'btnText' => trans('public.close'),
                                                        'btnIcon' => 'lock',
                                                        'iconType' => 'lin',
                                                        'iconClass' => 'text-warning mr-2',
                                                    ])
                                                @else
                                                    @include('admin.includes.delete_button',[
                                                        'url' => "/admin/forums/{$topic->forum_id}/topics/{$topic->id}/open",
                                                        'btnClass' => 'dropdown-item text-success mb-3 py-3 px-0 font-14',
                                                        'btnText' => trans('public.open'),
                                                        'btnIcon' => 'unlock',
                                                        'iconType' => 'lin',
                                                        'iconClass' => 'text-success mr-2',
                                                    ])
                                                @endif
                                            @endcan
                                                    
                                            @can('admin_forum_topics_posts')
                                                <a href="{{ getAdminPanelUrl() }}/forums/{{ $topic->forum_id }}/topics/{{ $topic->id }}/posts"
                                                   class="dropdown-item d-flex align-items-center mb-3 py-3 px-0 gap-4">
                                                    <x-iconsax-lin-eye class="icons text-gray-500 mr-2" width="18px" height="18px"/>
                                                    <span class="text-gray-500 font-14">{{ trans('site.posts') }}</span>
                                                </a>
                                            @endcan
                                                    
                                            @can('admin_enrollment_block_access')
                                                @include('admin.includes.delete_button',[
                                                    'url' => "/admin/forums/{$topic->forum_id}/topics/{$topic->id}/delete?no_redirect=true",
                                                    'btnClass' => 'dropdown-item text-danger mb-0 py-3 px-0 font-14',
                                                    'btnText' => trans('admin/main.delete'),
                                                    'btnIcon' => 'trash',
                                                    'iconType' => 'lin',
                                                    'iconClass' => 'text-danger mr-2',
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
        </div>
    </div>
</div>
