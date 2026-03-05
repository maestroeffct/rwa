<form action="/panel/forums/posts" method="get" class="px-16">
    <div class="row mt-24">

        <div class="col-6 col-md-2">
            <div class="form-group">
                <span class="has-translation bg-transparent"><x-iconsax-lin-calendar-2 class="text-gray-border" width="24px" height="24px"/></span>
                <label class="form-group-label">{{ trans('public.from') }}</label>
                <input type="text" name="from" class="form-control datepicker js-default-init-date-picker" data-format="YYYY/MM/DD" value="{{ request()->get('from') }}">
            </div>
        </div>

        <div class="col-6 col-md-2">
            <div class="form-group">
                <span class="has-translation bg-transparent"><x-iconsax-lin-calendar-2 class="text-gray-border" width="24px" height="24px"/></span>
                <label class="form-group-label">{{ trans('public.to') }}</label>
                <input type="text" name="to" class="form-control datepicker js-default-init-date-picker" data-format="YYYY/MM/DD" value="{{ request()->get('to') }}">
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="form-group ">
                <label class="form-group-label">{{ trans('update.forums') }}</label>
                <select name="forum_id" class="form-control select2" data-placeholder="{{ trans('public.all') }}">
                    <option value="all">{{ trans('public.all') }}</option>

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

        <div class="col-6 col-md-3">
            <div class="form-group ">
                <label class="form-group-label">{{ trans('public.status') }}</label>
                <select class="form-control select2" id="status" name="status" data-minimum-results-for-search="Infinity">
                    <option value="all">{{ trans('public.all') }}</option>
                    <option value="published" @if(request()->get('status') == 'published') selected @endif >{{ trans('public.published') }}</option>
                    <option value="closed" @if(request()->get('status') == 'closed') selected @endif >{{ trans('panel.closed') }}</option>
                </select>
            </div>
        </div>

        <div class="col-6 col-md-2 ml-auto">
            <button type="button" data-container-id="tableListContainer" class="js-get-view-data-by-form btn btn-primary btn-lg btn-block">{{ trans('update.filter') }}</button>
        </div>
    </div>
</form>
