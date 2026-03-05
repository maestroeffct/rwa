@extends('admin.layouts.app')

@push('styles_top')

@endpush

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{ trans('update.featured_contents') }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ getAdminPanelUrl() }}">{{ trans('admin/main.dashboard') }}</a></div>
                <div class="breadcrumb-item">{{ trans('update.featured_contents') }}</div>
            </div>
        </div>

        <div class="section-body">

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">


                            <form action="{{ getAdminPanelUrl() }}/settings/main" method="post">
                                {{ csrf_field() }}
                                <input type="hidden" name="page" value="general">
                                <input type="hidden" name="name" value="{{ \App\Models\Setting::$blogFeaturedContentsSettingsName }}">

                                <div class="row">
                                    <div class="col-12 col-md-6">

                                        <div class="form-group mt-3">
                                            <label class="input-label">{{ trans('update.featured_posts') }}</label>
                                            <select name="value[featured_posts][]" multiple="multiple" class="form-control select2" data-placeholder="{{ trans('update.select_a_post') }}">

                                                @foreach($posts as $post)
                                                    <option value="{{ $post->id }}" {{ (!empty($settingValues) and !empty($settingValues['featured_posts']) and in_array($post->id, $settingValues['featured_posts'])) ? 'selected' : '' }}>{{ $post->title }}</option>
                                                @endforeach
                                            </select>

                                            <p class="font-12 text-gray-500 mt-2">{{ trans('update.blog_featured_posts_hint') }}</p>
                                        </div>


                                        <div class="form-group mt-3">
                                            <label class="input-label">{{ trans('update.featured_authors') }}</label>
                                            <select name="value[featured_authors][]" multiple="multiple" class="form-control select2" data-placeholder="{{ trans('update.select_a_post') }}">

                                                @foreach($authors as $author)
                                                    <option value="{{ $author->id }}" {{ (!empty($settingValues) and !empty($settingValues['featured_authors']) and in_array($author->id, $settingValues['featured_authors'])) ? 'selected' : '' }}>{{ $author->full_name }}</option>
                                                @endforeach
                                            </select>

                                            <p class="font-12 text-gray-500 mt-2">{{ trans('update.blog_featured_authors_hint') }}</p>
                                        </div>

                                    </div>
                                </div>

                                <div class="text-right col-6">
                                <button type="submit" class="btn btn-primary mt-1">{{ trans('admin/main.submit') }}</button>
                                </div>

                            </form>


                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

