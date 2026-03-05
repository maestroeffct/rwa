<form action="/panel/meetings/sold-packages" method="get" class="px-16">
    <div class="row mt-24">

        <div class="col-6 col-md-3">
            <div class="form-group">
                <label class="form-group-label">{{ trans('public.search') }}</label>
                <input type="text" name="search" class="form-control " placeholder="{{ trans('update.search_in_students') }}">
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="form-group">
                <label class="form-group-label">{{ trans('update.meeting_package') }}</label>
                <select name="meeting_package_id" class="form-control select2" @if(count($allMeetingPackages) < 5) data-minimum-results-for-search="Infinity" @endif>
                    <option value="">{{ trans('public.all') }}</option>

                    @foreach($allMeetingPackages as $allMeetingPackage)
                        <option value="{{ $allMeetingPackage->id }}">{{ $allMeetingPackage->title }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="col-12 col-lg-3">
            <div class="form-group">
                <span class="has-translation bg-transparent"><x-iconsax-lin-calendar-2 class="text-gray-border" width="24px" height="24px"/></span>
                <label class="form-group-label">{{ trans('update.purchase_start_date') }}</label>
                <input type="text" name="purchase_start_date" class="form-control datepicker js-default-init-date-picker" data-format="YYYY/MM/DD" value="{{ request()->get('purchase_start_date') }}">
            </div>
        </div>

        <div class="col-12 col-lg-3">
            <div class="form-group">
                <span class="has-translation bg-transparent"><x-iconsax-lin-calendar-2 class="text-gray-border" width="24px" height="24px"/></span>
                <label class="form-group-label">{{ trans('update.purchase_end_date') }}</label>
                <input type="text" name="purchase_end_date" class="form-control datepicker js-default-init-date-picker" data-format="YYYY/MM/DD" value="{{ request()->get('purchase_end_date') }}">
            </div>
        </div>

        <div class="col-12 col-lg-3">
            <div class="form-group ">
                <label class="form-group-label">{{ trans('quiz.student') }}</label>
                <select name="student_id" class="form-control select2">
                    <option value="">{{ trans('public.all') }}</option>

                    @foreach($allStudents as $allStudent)
                        <option value="{{ $allStudent->id }}">{{ $allStudent->full_name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="col-12 col-lg-3">
            <div class="form-group ">
                <label class="form-group-label">{{ trans('public.status') }}</label>
                <select name="status" class="form-control select2" data-minimum-results-for-search="Infinity">
                    <option value="">{{ trans('public.all') }}</option>

                    @foreach(['open', 'finished'] as $status)
                        <option value="{{ $status }}">{{ trans("update.{$status}") }}</option>
                    @endforeach
                </select>
            </div>
        </div>


        @php
            $sorts = [
                'paid_amount_asc',
                'paid_amount_desc',
                'purchase_date_asc',
                'purchase_date_desc',
                'expiry_date_asc',
                'expiry_date_desc',
            ];
        @endphp

        <div class="col-6 col-md-3">
            <div class="form-group ">
                <label class="form-group-label">{{ trans('update.filters') }}</label>

                <select class="form-control select2" id="sort" name="sort" data-minimum-results-for-search="Infinity">
                    <option value="all">{{ trans('public.all') }}</option>

                    @foreach($sorts as $sort)
                        <option value="{{ $sort }}">{{ trans("update.{$sort}") }}</option>
                    @endforeach
                </select>

            </div>
        </div>

        <div class="col-6 col-md-3 ml-auto">
            <button type="button" data-container-id="tableListContainer" class="js-get-view-data-by-form btn btn-primary btn-lg btn-block">{{ trans('filter') }}</button>
        </div>
    </div>
</form>
