@extends('design_1.panel.layouts.panel')

@push('styles_top')
    <link rel="stylesheet" href="/assets/default/vendors/sortable/jquery-ui.min.css"/>
    <link rel="stylesheet" href="/assets/vendors/summernote/summernote-bs4.min.css">
@endpush

@section('content')
    <form action="/panel/blog/{{ (!empty($post) ? $post->id.'/update' : 'store') }}" method="post" enctype="multipart/form-data">
        {{ csrf_field() }}

        <div class="bg-white pt-16 rounded-24">

            <div class="d-flex align-items-center justify-content-between pb-16 px-16 border-bottom-gray-100">
                <div class="">
                    <h3 class="font-16">{{ trans('update.blog_article') }} {{ trans('update.information') }}</h3>
                </div>
            </div>

            <div class="row p-16">

                <div class="col-12 col-lg-6">
                    <div class="">

                        @include('design_1.panel.includes.locale.locale_select',[
                            'itemRow' => !empty($post) ? $post : null,
                            'withoutReloadLocale' => false,
                            'extraClass' => '',
                            'extraData' => null
                        ])

                        <div class="form-group">
                            <label class="form-group-label">{{ trans('admin/main.title') }}</label>
                            <input type="text" name="title"
                                   class="form-control @error('title') is-invalid @enderror"
                                   value="{{ (!empty($post) and !empty($post->translate($locale))) ? $post->translate($locale)->title : old('title') }}"
                                   placeholder="{{ trans('admin/main.choose_title') }}"/>
                            @error('title')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-group-label">{{ trans('admin/main.subtitle') }}</label>
                            <input type="text" name="subtitle"
                                   class="form-control @error('subtitle') is-invalid @enderror"
                                   value="{{ (!empty($post) and !empty($post->translate($locale))) ? $post->translate($locale)->subtitle : old('subtitle') }}"
                                   placeholder="{{ trans('admin/main.choose_subtitle') }}"/>
                            @error('subtitle')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <div class="form-group ">
                            <label class="form-group-label">{{ trans('/admin/main.category') }}</label>
                            <select name="category_id" class="form-control select2 @error('category_id') is-invalid @enderror">
                                <option selected disabled>{{ trans('admin/main.choose_category') }}</option>

                                @foreach($blogCategories as $blogCategory)
                                    <option value="{{ $blogCategory->id }}" {{ (((!empty($post) and $post->category_id == $blogCategory->id) or (old('category_id') == $blogCategory->id)) ? 'selected="selected"' : '') }}>{{ $blogCategory->title }}</option>
                                @endforeach
                            </select>

                            @error('category_id')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-group-label">{{ trans('public.study_time') }} ({{ trans('update.min') }})</label>
                            <input type="number" name="study_time" class="form-control" value="{{ !empty($post) ? $post->study_time : old('study_time') }}"/>
                        </div>

                        @include('design_1.panel.webinars.create.includes.media',[
                                'media' => !empty($post) ? $post->image : null,
                                'mediaName' => 'image',
                                'mediaTitle' => trans('update.article_cover'),
                                'mediaCardClass' => 'col-12 mt-16'
                            ])


                        <div class="form-group bg-white-editor mt-28">
                            <label class="form-group-label">{{ trans('public.summary') }}</label>
                            <textarea name="description" rows="4" class="form-control @error('description')  is-invalid @enderror" placeholder="{{ trans('update.create_blog_summary_placeholder') }}">{!! (!empty($post) and !empty($post->translate($locale))) ? $post->translate($locale)->description : old('description') !!}</textarea>
                            @error('description')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <div class="form-group bg-white-editor mt-24">
                            <label class="form-group-label">{{ trans('public.content') }}</label>
                            <textarea name="content" data-height="400" class="main-summernote form-control @error('content')  is-invalid @enderror" placeholder="{{ trans('update.create_blog_content_placeholder') }}">{!! (!empty($post) and !empty($post->translate($locale))) ? $post->translate($locale)->content : old('content')  !!}</textarea>
                            @error('content')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                    </div>
                </div>

                <div class="col-12 col-lg-6 mt-20 mt-lg-0">


                    {{-- Related Posts --}}
                    @include("design_1.panel.blog.posts.create.includes.related_posts")

                </div>
            </div>


            <div class="d-flex flex-column flex-lg-row align-items-lg-center justify-content-lg-between mt-16 p-16 border-top-gray-100">
                <div class="d-flex align-items-center">
                    <div class="d-flex-center size-48 rounded-12 bg-gray-200">
                        <x-iconsax-bol-info-circle class="icons text-gray-400" width="24px" height="24px"/>
                    </div>
                    <div class="ml-8">
                        <h5 class="font-14">{{ trans('product.information') }}</h5>
                        <p class="mt-2 font-12 text-gray-500">{{ !empty(getGeneralOptionsSettings('direct_publication_of_blog')) ? trans('update.your_articles_will_be_published_after_save') : trans('update.your_articles_will_be_published_after_admin_approval') }}</p>
                    </div>
                </div>

                <button type="submit" class="btn btn-lg btn-primary mt-20 mt-lg-0">{{ trans('public.save') }}</button>
            </div>
        </div>
    </form>
@endsection


@push('scripts_bottom')
    <script src="/assets/default/vendors/sortable/jquery-ui.min.js"></script>
    <script src="/assets/vendors/summernote/summernote-bs4.min.js"></script>

    <script src="/assets/design_1/js/panel/create_blog.min.js"></script>
@endpush
