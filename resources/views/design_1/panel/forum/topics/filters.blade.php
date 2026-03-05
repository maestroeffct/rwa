<form action="" method="get" class="px-16">
    <div class="row mt-24">

        <div class="col-12 col-lg-3">
            <div class="form-group">
                <label class="form-group-label">{{ trans('public.search') }}</label>
                <input type="text" name="search" class="form-control " placeholder="{{ trans("update.search_title,_description,...") }}">
            </div>
        </div>

        <div class="col-12 col-lg-3">
            <div class="form-group ">
                <label class="form-group-label">{{ trans('update.forums') }}</label>
                <select name="forum_id" class="form-control select2" data-placeholder="{{ trans('public.all') }}">
                    <option value="">{{ trans('public.all') }}</option>

                    @foreach($forums as $forum)
                        @if(!empty($forum->subForums) and count($forum->subForums))
                            <optgroup label="{{ $forum->title }}">
                                @foreach($forum->subForums as $subForum)
                                    <option value="{{ $subForum->id }}" {{ (request()->get('forum_id') == $subForum->id) ? 'selected' : '' }}>{{ $subForum->title }}</option>
                                @endforeach
                            </optgroup>
                        @else
                            <option value="{{ $forum->id }}" {{ (request()->get('forum_id') == $forum->id) ? 'selected' : '' }}>{{ $forum->title }}</option>
                        @endif
                    @endforeach
                </select>
            </div>
        </div>

        <div class="col-12 col-lg-3">
            <div class="form-group ">
                <label class="form-group-label">{{ trans('public.status') }}</label>
                <select class="form-control select2" id="status" name="status" data-minimum-results-for-search="Infinity">
                    <option value="">{{ trans('public.all') }}</option>
                    <option value="published" @if(request()->get('status') == 'published') selected @endif >{{ trans('public.published') }}</option>
                    <option value="closed" @if(request()->get('status') == 'closed') selected @endif >{{ trans('panel.closed') }}</option>
                </select>
            </div>
        </div>

        <div class="col-12 col-lg-3 ml-auto">
            <button type="button" data-container-id="tableListContainer" class="js-get-view-data-by-form btn btn-primary btn-lg btn-block">{{ trans('filter') }}</button>
        </div>
    </div>
</form>
