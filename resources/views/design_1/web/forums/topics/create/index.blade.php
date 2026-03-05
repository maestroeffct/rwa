@extends('design_1.web.layouts.app')

@push('styles_top')
    <link rel="stylesheet" href="/assets/vendors/summernote/summernote-bs4.min.css">

    <link rel="stylesheet" href="{{ getDesign1StylePath("forum") }}">
@endpush


@section('content')
    <div class="container mt-40 mb-64">
        {{-- Hero & Cover --}}
        @include('design_1.web.forums.topics.create.includes.hero')

        <div class="bg-white mt-24 p-16 rounded-24">
            <form action="{{ !empty($topic) ? $topic->getEditUrl() : '/forums/create-topic' }}" method="post" enctype="multipart/form-data">
                {{ csrf_field() }}

                <div class="form-group">
                    <label class="form-group-label">{{ trans('update.topic_title') }}</label>
                    <input type="text" name="title" value="{{ !empty($topic) ? $topic->title : old('title') }}" class="form-control @error('title') is-invalid @enderror" placeholder="{{ trans('update.topic_title_placeholder') }}">
                    @error('title')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>

                <div class="form-group ">
                    <label class="form-group-label">{{ trans('update.forum') }}</label>
                    <select name="forum_id" class="form-control select2 @error('forum_id') is-invalid @enderror">
                        <option selected disabled class="text-gray-500">{{ trans('update.choose_a_forum') }}</option>

                        @foreach($forums as $forum)
                            @if(!empty($forum->subForums) and count($forum->subForums))
                                @php
                                    $showOptgroup = false;

                                    foreach($forum->subForums as $subForum) {
                                        if($subForum->checkUserCanCreateTopic() and !$subForum->close) {
                                            $showOptgroup = true;
                                        }
                                    }
                                @endphp

                                @if($showOptgroup)
                                    <optgroup label="{{ $forum->title }}">
                                        @foreach($forum->subForums as $subForum)
                                            @if($subForum->checkUserCanCreateTopic() and !$subForum->close)
                                                <option value="{{ $subForum->id }}" {{ ((!empty($topic) and $topic->forum_id == $subForum->id) or (request()->get('forum_id') == $subForum->id)) ? 'selected' : '' }}>{{ $subForum->title }}</option>
                                            @endif
                                        @endforeach
                                    </optgroup>
                                @endif
                            @elseif($forum->checkUserCanCreateTopic() and !$forum->close)
                                <option value="{{ $forum->id }}" {{ (request()->get('forum_id') == $forum->id) ? 'selected' : '' }}>{{ $forum->title }}</option>
                            @endif
                        @endforeach
                    </select>
                    @error('forum_id')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>

                <div class="form-group bg-white-editor mt-24">
                    <textarea name="description" data-height="500" class="main-summernote form-control @error('description')  is-invalid @enderror" placeholder="{{ trans('update.create_topic_description_placeholder') }}">{!! !empty($topic) ? $topic->description : old('description') !!}</textarea>
                    @error('description')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>

                <div class="form-group mt-24">
                    <label class="form-group-label">{{ trans('update.forum_cover_(optional)') }}</label>

                    <div class="custom-file bg-white">
                        <input type="file" name="cover" class="js-ajax-upload-file-input js-ajax-cover custom-file-input" data-upload-name="cover" id="coverImageInput" accept="image/*">
                        <span class="custom-file-text">{{ (!empty($topic) and !empty($topic->cover)) ? getFileNameByPath($topic->cover) : '' }}</span>
                        <label class="custom-file-label" for="coverImageInput">{{ trans('update.browse') }}</label>
                    </div>

                    @error('cover')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>

                <div class="mt-24">
                    <h4 class="font-14">{{ trans('update.attachments') }} ({{ trans('public.optional') }})</h4>

                    <div class="js-attachments-items">

                        <div class="d-flex align-items-center gap-12 mt-28">
                            <div class="form-group custom-input-file mb-0 flex-1">
                                <label class="form-group-label">{{ trans('update.attachment') }}</label>

                                <div class="custom-file bg-white">
                                    <input type="file" name="attachments[record]" class="custom-file-input" id="attachments_record" >
                                    <span class="custom-file-text text-gray-500"></span>
                                    <label class="custom-file-label bg-transparent" for="attachments_record">
                                        <x-iconsax-lin-export class="icons text-gray-400" width="24px" height="24px"/>
                                    </label>
                                </div>
                            </div>

                            <button type="button" class="js-add-attachment d-flex-center bg-primary bg-hover-primary border-gray-300 size-48 rounded-12">
                                <x-iconsax-lin-add class="icons text-white" width="24px" height="24px"/>
                            </button>
                        </div>

                        @if(!empty($topic) and !empty($topic->attachments) and count($topic->attachments))
                            @foreach($topic->attachments as $topicAttachment)
                                <div class="js-attachment-item-card d-flex align-items-center gap-12 mt-28">
                                    <div class="form-group custom-input-file mb-0 flex-1">
                                        <label class="form-group-label">{{ trans('update.attachment') }}</label>

                                        <div class="custom-file bg-white">
                                            <input type="file" name="attachments[{{ $topicAttachment->id }}]" class="custom-file-input" id="attachments_{{ $topicAttachment->id }}" >
                                            <span class="custom-file-text text-gray-500">{{ getFileNameByPath($topicAttachment->path) }}</span>
                                            <label class="custom-file-label bg-transparent" for="attachments_{{ $topicAttachment->id }}">
                                                <x-iconsax-lin-export class="icons text-gray-400" width="24px" height="24px"/>
                                            </label>
                                        </div>
                                    </div>

                                    <a href="/forums/attachments/{{ $topicAttachment->id }}/delete" class="delete-action d-flex-center bg-danger border-gray-300 size-48 rounded-12"
                                       data-msg="{{ trans('update.this_attachment_will_be_removed') }}"
                                       data-confirm="{{ trans('public.delete') }}"
                                    >
                                        <x-iconsax-lin-trash class="icons text-white" width="24px" height="24px"/>
                                    </a>
                                </div>
                            @endforeach
                        @endif

                    </div>
                </div>


                <div class="d-flex flex-column flex-lg-row align-items-lg-center justify-content-lg-between mt-16 pt-16 border-top-gray-100">
                    <div class="d-flex align-items-center">
                        <div class="d-flex-center size-48 rounded-12 bg-gray-300">
                            <x-iconsax-bol-info-circle class="icons text-gray-500" width="24px" height="24px"/>
                        </div>
                        <div class="ml-8">
                            <h5 class="font-14">{{ trans('update.forum_terms') }}</h5>
                            <div class="mt-4 font-12 text-gray-500">{{ trans('update.create_topic_forum_terms_hint') }}</div>
                        </div>
                    </div>

                    <button type="submit" class="js-submit-form-btn btn btn-primary btn-lg mt-16 mt-lg-0">{{ trans('update.publish_topic') }}</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts_bottom')
    <script>
        var attachmentLang = '{{ trans('update.attachment') }}';
        var trashIcon = `<x-iconsax-lin-trash class="icons text-white" width="24px" height="24px"/>`;
        var exportIcon = `<x-iconsax-lin-export class="icons text-gray-400" width="24px" height="24px"/>`;
    </script>

    <script src="/assets/vendors/summernote/summernote-bs4.min.js"></script>

    <script src="{{ getDesign1ScriptPath("create_topic") }}"></script>
@endpush
