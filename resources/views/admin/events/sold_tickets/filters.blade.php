<section class="card mt-32">
    <div class="card-body pb-4">
        <form method="get" class="mb-0">

            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="input-label">{{trans('admin/main.search')}}</label>
                        <input name="search" type="text" class="form-control" value="{{ request()->get('search') }}" placeholder="{{ trans('update.search_ticket_code') }}">
                    </div>
                </div>


                <div class="col-md-3">
                    <div class="form-group">
                        <label class="input-label">{{trans('admin/main.instructor')}}</label>
                        <select name="ticket_id" class="form-control select2" data-placeholder="{{ trans('update.ticket_types') }}">

                            @if(!empty($allTickets) and $allTickets->count() > 0)
                                @foreach($allTickets as $allTicket)
                                    <option value="{{ $allTicket->id }}" {{ (request()->get('ticket_id') == $allTicket->id) ? 'selected' : '' }}>{{ $allTicket->title }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>

                @php
                    $sorts = [
                        'paid_amount_asc',
                        'paid_amount_desc',
                        'purchase_date_asc',
                        'purchase_date_desc',
                    ];
                @endphp

                <div class="col-md-3">
                    <div class="form-group">
                        <label class="input-label">{{trans('admin/main.filters')}}</label>
                        <select name="sort" data-plugin-selectTwo class="form-control populate">
                            <option value="">{{ trans('public.all') }}</option>

                            @foreach($sorts as $sort)
                                <option value="{{ $sort }}" {{ (request()->get('sort') == $sort) ? 'selected' : '' }}>{{ trans("update.{$sort}") }}</option>
                            @endforeach
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
