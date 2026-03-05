<form action="/panel/events/{{ $event->id }}/sold-tickets" method="get" class="px-16">
    <div class="row mt-24">

        <div class="col-6 col-md-3">
            <div class="form-group">
                <label class="form-group-label">{{ trans('public.search') }}</label>
                <input type="text" name="search" class="form-control " placeholder="{{ trans('update.search_ticket_code') }}">
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="form-group">
                <label class="form-group-label">{{ trans('update.ticket_types') }}</label>
                <select name="ticket_id" class="form-control select2"  @if(count($allTickets) < 5) data-minimum-results-for-search="Infinity" @endif>
                    <option value="">{{ trans('public.all') }}</option>

                    @foreach($allTickets as $allTicket)
                        <option value="{{ $allTicket->id }}">{{ $allTicket->title }}</option>
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
