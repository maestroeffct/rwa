@extends('admin.layouts.app')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{ trans('admin/main.blog') }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ getAdminPanelUrl() }}">{{trans('admin/main.dashboard')}}</a>
                </div>
                <div class="breadcrumb-item">{{ trans('admin/main.blog') }}</div>
            </div>
        </div>

        <div class="section-body">

            <section class="card">
                <div class="card-body">
                    <form action="{{ getAdminPanelUrl() }}/blog" method="get" class="mb-0">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="input-label">{{ trans('admin/main.search') }}</label>
                                    <input name="title" type="text" class="form-control" value="{{ request()->get('title') }}">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="input-label">{{ trans('admin/main.start_date') }}</label>
                                    <div class="input-group">
                                        <input type="date" id="from" class="text-center form-control" name="from" value="{{ request()->get('from') }}" placeholder="Start Date">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="input-label">{{ trans('admin/main.end_date') }}</label>
                                    <div class="input-group">
                                        <input type="date" id="to" class="text-center form-control" name="to" value="{{ request()->get('to') }}" placeholder="End Date">
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="input-label">{{ trans('admin/main.category') }}</label>
                                    <select name="category_id" data-plugin-selectTwo class="form-control populate">
                                        <option value="">{{ trans('admin/main.all_categories') }}</option>

                                        @foreach($blogCategories as $category)
                                            <option value="{{ $category->id }}" @if(request()->get('category_id') == $category->id) selected="selected" @endif>{{ $category->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="input-label">{{ trans('admin/main.author') }}</label>
                                    <select name="author_id" data-plugin-selectTwo class="form-control populate">
                                        <option value="">{{ trans('admin/main.all_authors') }}</option>

                                        @foreach($authors as $author)
                                            <option value="{{ $author->id }}" @if(request()->get('author_id') == $author->id) selected="selected" @endif>{{ $author->full_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="input-label">{{ trans('admin/main.status') }}</label>
                                    <select name="status" data-plugin-selectTwo class="form-control populate">
                                        <option value="">{{ trans('admin/main.all_status') }}</option>
                                        <option value="pending" @if(request()->get('status') == 'pending') selected @endif>{{ trans('admin/main.draft') }}</option>
                                        <option value="publish" @if(request()->get('status') == 'publish') selected @endif>{{ trans('admin/main.publish') }}</option>
                                    </select>
                                </div>
                            </div>


                            <div class="col-md-3 d-flex align-items-center ">
                                <button type="submit" class="btn btn-primary btn-block btn-lg">{{trans('admin/main.show_results')}}</button>
                            </div> 

                        </div>
                    </form>
                </div>
            </section>

            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="card">
      
                        <div class="card-header justify-content-between">
                            
                            <div>
                               <h5 class="font-14 mb-0">{{ $pageTitle }}</h5>
                               <p class="font-12 mt-4 mb-0 text-gray-500">{{ trans('update.manage_all_blogs_in_a_single_place') }}</p>
                           </div>
                           
                            <div class="d-flex align-items-center gap-12">

                            @can('admin_blog_create')
                                   <a href="{{ getAdminPanelUrl("/blog/create") }}" target="_blank" class="btn btn-primary">
                                       <x-iconsax-lin-add class="icons text-white" width="18px" height="18px"/>
                                       <span class="ml-4 font-12">{{ trans('admin/main.create_blog') }}</span>
                                   </a>
                               @endcan

                               @can('admin_blog_categories')
                                   <a href="{{ getAdminPanelUrl("/blog/categories") }}" target="_blank" class="btn btn-primary">
                                       <x-iconsax-lin-add class="icons text-white" width="18px" height="18px"/>
                                       <span class="ml-4 font-12">{{ trans('admin/main.create_category') }}</span>
                                   </a>
                               @endcan

                            </div>
                           
                       </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table custom-table font-14">
                                    <tr>
                                        <th>{{ trans('admin/main.title') }}</th>
                                        <th>{{ trans('admin/main.category') }}</th>
                                        <th>{{ trans('admin/main.author') }}</th>
                                        <th>{{ trans('admin/main.comments') }}</th>
                                        <th>{{ trans('public.date') }}</th>
                                        <th>{{ trans('admin/main.status') }}</th>
                                        <th>{{ trans('admin/main.action') }}</th>
                                    </tr>
                                    @foreach($blog as $post)
                                        <tr>
                                            <td>
                                                <a class="text-dark" href="{{ $post->getUrl() }}" target="_blank">{{ $post->title }}</a>
                                            </td>
                                            <td>{{ $post->category->title }}</td>
                                            @if(!empty($post->author->full_name))
                                            <td>{{ $post->author->full_name }}</td>
                                            @else
                                            <td class="text-danger">Deleted</td>
                                            @endif
                                            <td>
                                                <a class="text-dark" href="{{ $post->getUrl() }}" target="_blank">{{ $post->comments_count }}</a>
                                            </td>
                                            <td>{{ dateTimeFormat($post->created_at, 'j M Y | H:i') }}</td>
                                            <td>
                                                <span class="badge-status {{ ($post->status == 'pending') ? 'text-warning bg-warning-30' : 'text-success bg-success-30' }}">{{ ($post->status == 'pending') ? trans('admin/main.pending') : trans('admin/main.published') }}</span>
                                            </td>

                                            <td width="150px">
    <div class="btn-group dropdown table-actions position-relative">
        <button type="button" class="btn-transparent dropdown-toggle" data-toggle="dropdown">
            <x-iconsax-lin-more class="icons text-gray-500" width="20px" height="20px"/>
        </button>

        <div class="dropdown-menu dropdown-menu-right">
            @can('admin_blog_edit')
                <a href="{{ getAdminPanelUrl() }}/blog/{{ $post->id }}/edit"
                   class="dropdown-item d-flex align-items-center mb-3 py-3 px-0 gap-4">
                    <x-iconsax-lin-edit-2 class="icons text-gray-500 mr-2" width="18px" height="18px"/>
                    <span class="text-gray-500 font-14">{{ trans('admin/main.edit') }}</span>
                </a>
            @endcan

            @can('admin_blog_delete')
                @include('admin.includes.delete_button',[
                    'url' => getAdminPanelUrl().'/blog/'.$post->id.'/delete',
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
                            {{ $blog->appends(request()->input())->links() }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

