@extends('admin.layouts.app')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{ $pageTitle }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ getAdminPanelUrl() }}">{{trans('admin/main.dashboard')}}</a>
                </div>
                <div class="breadcrumb-item">{{ trans('admin/main.comments') }}</div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card-statistic">
                    <div class="card-statistic__mask"></div>
                    <div class="card-statistic__wrap">
                        <div class="d-flex align-items-start justify-content-between">
                            <span class="text-gray-500 mt-8">{{trans('admin/main.total_comments')}}</span>
                            <div class="d-flex-center size-48 bg-primary-30 rounded-12">
                                <x-iconsax-bul-message-2 class="icons text-primary" width="24px" height="24px"/>
                            </div>
                        </div>
                        <h5 class="font-24 mt-12 line-height-1 text-black">{{ $totalComments }}</h5>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card-statistic">
                    <div class="card-statistic__mask"></div>
                    <div class="card-statistic__wrap">
                        <div class="d-flex align-items-start justify-content-between">
                            <span class="text-gray-500 mt-8">{{trans('admin/main.published_comments')}}</span>
                            <div class="d-flex-center size-48 bg-success-30 rounded-12">
                                <x-iconsax-bul-message-2 class="icons text-success" width="24px" height="24px"/>
                            </div>
                        </div>
                        <h5 class="font-24 mt-12 line-height-1 text-black">{{ $publishedComments }}</h5>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card-statistic">
                    <div class="card-statistic__mask"></div>
                    <div class="card-statistic__wrap">
                        <div class="d-flex align-items-start justify-content-between">
                            <span class="text-gray-500 mt-8">{{trans('admin/main.pending_comments')}}</span>
                            <div class="d-flex-center size-48 bg-warning-30 rounded-12">
                                <x-iconsax-bul-message-2 class="icons text-warning" width="24px" height="24px"/>
                            </div>
                        </div>
                        <h5 class="font-24 mt-12 line-height-1 text-black">{{ $pendingComments }}</h5>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card-statistic">
                    <div class="card-statistic__mask"></div>
                    <div class="card-statistic__wrap">
                        <div class="d-flex align-items-start justify-content-between">
                            <span class="text-gray-500 mt-8">{{trans('admin/main.comments_reports')}}</span>
                            <div class="d-flex-center size-48 bg-danger-30 rounded-12">
                                <x-iconsax-bul-message-2 class="icons text-danger" width="24px" height="24px"/>
                            </div>
                        </div>
                        <h5 class="font-24 mt-12 line-height-1 text-black">{{ $commentReports }}</h5>
                    </div>
                </div>
            </div>
        </div>

        <div class="section-body">

            <section class="card mt-32">
                <div class="card-body pb-4">
                    <form method="get" class="mb-0">

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="input-label">{{ trans('admin/main.search') }}</label>
                                    <input type="text" class="form-control" name="title" value="{{ request()->get('title') }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="input-label">{{ trans('admin/main.date') }}</label>
                                    <div class="input-group">
                                        <input type="date" id="fsdate" class="text-center form-control" name="date" value="{{ request()->get('date') }}" placeholder="Date">
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="input-label">{{ trans('admin/main.status') }}</label>
                                    <select name="status" data-plugin-selectTwo class="form-control populate">
                                        <option value="">{{ trans('admin/main.all_status') }}</option>
                                        <option value="pending" @if(request()->get('status') == 'pending') selected @endif>{{ trans('admin/main.pending') }}</option>
                                        <option value="active" @if(request()->get('status') == 'active') selected @endif>{{ trans('admin/main.published') }}</option>
                                    </select>
                                </div>
                            </div>

                            @if($page == 'webinars')
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="input-label">{{ trans('admin/main.class') }}</label>
                                        <select name="webinar_ids[]" multiple="multiple" class="form-control search-webinar-select2 " data-placeholder="{{ trans('admin/main.search_webinar') }}">

                                            @if(!empty($webinars) and $webinars->count() > 0)
                                                @foreach($webinars as $webinar)
                                                    <option value="{{ $webinar->id }}" selected>{{ $webinar->title }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                            @elseif($page == 'bundles')
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="input-label">{{ trans('update.bundle') }}</label>
                                        <select name="bundle_ids[]" multiple="multiple" class="form-control search-bundle-select2 " data-placeholder="Search bundles">

                                            @if(!empty($bundles) and $bundles->count() > 0)
                                                @foreach($bundles as $bundle)
                                                    <option value="{{ $bundle->id }}" selected>{{ $bundle->title }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                            @elseif($page == 'blog')
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="input-label">{{ trans('admin/main.blog') }}</label>
                                        <select name="post_ids[]" multiple="multiple" class="form-control search-blog-select2 " data-placeholder="Search blog">

                                            @if(!empty($blog) and $blog->count() > 0)
                                                @foreach($blog as $post)
                                                    <option value="{{ $post->id }}" selected>{{ $post->title }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                            @elseif($page == 'products')
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="input-label">{{ trans('update.products') }}</label>
                                        <select name="product_ids[]" multiple="multiple" class="form-control search-product-select2 " data-placeholder="Search products">

                                            @if(!empty($products) and $products->count() > 0)
                                                @foreach($products as $product)
                                                    <option value="{{ $product->id }}" selected>{{ $product->title }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                            @elseif($page == 'events')
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="input-label">{{ trans('update.events') }}</label>
                                        <select name="event_ids[]" multiple="multiple" class="form-control search-event-select2 " data-placeholder="Search events">

                                            @if(!empty($events) and $events->count() > 0)
                                                @foreach($events as $event)
                                                    <option value="{{ $event->id }}" selected>{{ $event->title }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                            @endif


                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="input-label">{{ trans('admin/main.user') }}</label>
                                    <select name="user_ids[]" multiple="multiple" class="form-control search-user-select2"
                                            data-placeholder="Search users">

                                        @if(!empty($users) and $users->count() > 0)
                                            @foreach($users as $user)
                                                <option value="{{ $user->id }}" selected>{{ $user->full_name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-4 d-flex align-items-center ">
                                <button type="submit" class="btn btn-primary btn-block btn-lg">{{trans('admin/main.show_results')}}</button>
                            </div>

                        </div>

                    </form>
                </div>
            </section>


            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div>
                                <table class="table custom-table font-14">
                                    <tr>
                                        <th>{{ trans('admin/main.comment') }}</th>
                                        <th>{{ trans('admin/main.created_date') }}</th>
                                        <th class="text-left">{{ trans('admin/main.user') }}</th>
                                        @if($page == 'webinars')
                                            <th class="text-left">{{ trans('admin/main.class') }}</th>
                                        @elseif($page == 'bundles')
                                            <th class="text-left">{{ trans('update.bundle') }}</th>
                                        @elseif($page == 'blog')
                                            <th class="text-left">{{ trans('admin/main.blog') }}</th>
                                        @elseif($page == 'products')
                                            <th class="text-left">{{ trans('update.product') }}</th>
                                        @elseif($page == 'events')
                                            <th class="text-left">{{ trans('update.event') }}</th>
                                        @endif
                                        <th>{{ trans('admin/main.type') }}</th>
                                        <th>{{ trans('admin/main.status') }}</th>
                                        <th width="150">{{ trans('admin/main.action') }}</th>
                                    </tr>
                                    @foreach($comments as $comment)
                                        <tr>
                                            <td>
                                                <button type="button" class="js-show-description btn-sm btn btn-outline-primary">{{ trans('admin/main.show') }}</button>
                                                <input type="hidden" value="{!! nl2br($comment->comment) !!}">
                                            </td>

                                            <td>{{ dateTimeFormat($comment->created_at, 'j M Y | H:i') }}</td>

                                            <td class="text-left">
                                                <a href="{{ $comment->user->getProfileUrl() }}" target="_blank" class="text-dark">{{ $comment->user->full_name }}</a>
                                            </td>

                                            <td class="text-left">
                                                <a class="text-dark" href="{{ $comment->$itemRelation->getUrl() }}" target="_blank">
                                                    {{ $comment->$itemRelation->title }}
                                                </a>
                                            </td>

                                            <td>
                                                <span>
                                                    {{ (empty($comment->reply_id)) ? trans('admin/main.main_comment') : trans('admin/main.replied') }}
                                                </span>
                                            </td>

                                            <td>
                                                <span class="badge-status {{ ($comment->status == 'pending') ? 'text-warning bg-warning-30' : 'text-success bg-success-30' }}">{{ ($comment->status == 'pending') ? trans('admin/main.pending') : trans('admin/main.published') }}</span>
                                            </td>

                                            <td>
                                                <div class="btn-group dropdown table-actions position-relative">
                                                    <button type="button" class="btn-transparent dropdown-toggle" data-toggle="dropdown">
                                                        <x-iconsax-lin-more class="icons text-gray-500" width="20px" height="20px"/>
                                                    </button>

                                                    <div class="dropdown-menu dropdown-menu-right">
                                                        @can('admin_comments_status')
                                                            <a href="{{ getAdminPanelUrl() }}/comments/{{ $page }}/{{ $comment->id }}/toggle"
                                                               class="dropdown-item d-flex align-items-center mb-3 py-3 px-0 gap-4">
                                                                @if($comment->status == 'pending')
                                                                    <x-iconsax-lin-eye class="icons text-success mr-2" width="18px" height="18px"/>
                                                                    <span class="text-success">{{ trans('admin/main.publish') }}</span>
                                                                @else
                                                                    <x-iconsax-lin-eye-slash class="icons text-warning mr-2" width="18px" height="18px"/>
                                                                    <span class="text-warning">{{ trans('admin/main.pending') }}</span>
                                                                @endif
                                                            </a>
                                                        @endcan

                                                        @can('admin_comments_reply')
                                                            <a href="{{ getAdminPanelUrl() }}/comments/{{ $page }}/{{ !empty($comment->reply_id) ? $comment->reply_id : $comment->id }}/reply"
                                                               class="dropdown-item d-flex align-items-center mb-3 py-3 px-0 gap-4">
                                                                <x-iconsax-lin-messages-2 class="icons text-gray-500 mr-2" width="18px" height="18px"/>
                                                                <span class="text-gray-500 font-14">{{ trans('admin/main.reply') }}</span>
                                                            </a>
                                                        @endcan

                                                        @can('admin_comments_edit')
                                                            <a href="{{ getAdminPanelUrl() }}/comments/{{ $page }}/{{ $comment->id }}/edit"
                                                               class="dropdown-item d-flex align-items-center mb-3 py-3 px-0 gap-4">
                                                                <x-iconsax-lin-edit-2 class="icons text-gray-500 mr-2" width="18px" height="18px"/>
                                                                <span class="text-gray-500 font-14">{{ trans('admin/main.edit') }}</span>
                                                            </a>
                                                        @endcan

                                                        @can('admin_comments_delete')
                                                            @include('admin.includes.delete_button',[
                                                                'url' => getAdminPanelUrl().'/comments/'.$page.'/'.$comment->id.'/delete',
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
                            {{ $comments->appends(request()->input())->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Modal -->
    <div class="modal fade" id="contactMessage" tabindex="-1" aria-labelledby="contactMessageLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="contactMessageLabel">{{ trans('admin/main.message') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ trans('admin/main.close') }}</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts_bottom')
    <script src="/assets/admin/js/parts/comments.min.js"></script>
@endpush
