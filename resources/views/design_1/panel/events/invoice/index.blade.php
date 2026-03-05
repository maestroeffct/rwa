<html lang="fa">
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>{{ $pageTitle ?? '' }} </title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- General CSS File -->
    <link rel="stylesheet" href="/assets/admin/vendor/bootstrap/bootstrap.min.css"/>
    <link rel="stylesheet" href="/assets/vendors/fontawesome/css/all.min.css"/>


    <link rel="stylesheet" href="/assets/admin/css/style.css">
    <link rel="stylesheet" href="/assets/admin/css/custom.css">
    <link rel="stylesheet" href="/assets/admin/css/components.css">

    <style>
        @php
            $themeCustomCssAndJs = getThemeCustomCssAndJs();
        @endphp

        {!! !empty($themeCustomCssAndJs['css']) ? $themeCustomCssAndJs['css'] : '' !!}
    </style>
</head>
<body>

<div id="app">
    <section class="section">
        <div class="container mt-5">
            <div class="row">
                <div class="col-12 col-md-10 offset-md-1 col-lg-10 offset-lg-1">

                    <div class="card card-primary">
                        <div class="row m-0">
                            <div class="col-12 col-md-12">
                                <div class="card-body">

                                    <div class="section-body">
                                        <div class="invoice">
                                            <div class="invoice-print">
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <div class="invoice-title">
                                                            <h2>{{ $generalSettings['site_name'] }}</h2>
                                                            <div class="invoice-number">{{ trans('public.item_id') }}: #{{ $event->id }}</div>
                                                        </div>
                                                        <hr>
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <address>
                                                                    <strong>{{ trans('admin/main.buyer') }}:</strong>
                                                                    <br>
                                                                    {{ !empty($buyer) ? $buyer->full_name : trans('update.deleted_user') }}
                                                                </address>

                                                                <address class="mt-2">
                                                                    <strong>{{ trans('update.buyer_address') }}:</strong><br>
                                                                    {{ !empty($buyer) ? $buyer->getAddress(true) : '' }}
                                                                </address>
                                                            </div>
                                                            <div class="col-md-6 text-md-right">
                                                                <address>
                                                                    <strong>{{ trans('home.platform_address') }}:</strong><br>
                                                                    {!! nl2br(getContactPageSettings('address')) !!}
                                                                </address>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <address>
                                                                    <strong>{{ trans('admin/main.seller') }}:</strong><br>
                                                                    {{ $seller->full_name }}
                                                                </address>
                                                            </div>

                                                            <div class="col-md-6 text-md-right">
                                                                <address>
                                                                    <strong>{{ trans('panel.purchase_date') }}:</strong><br>
                                                                    {{ dateTimeFormat($firstPurchasedTicket->paid_at,'Y M j | H:i') }}<br><br>
                                                                </address>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row mt-4">
                                                    <div class="col-md-12">
                                                        <div class="section-title">{{ trans('home.order_summary') }}</div>
                                                        <div class="table-responsive">
                                                            <table class="table table-striped table-hover table-md">
                                                                <tr>
                                                                    <th class="text-center">{{ trans('admin/main.item') }}</th>
                                                                    <th class="text-center">{{ trans('update.quantity') }}</th>
                                                                    <th class="text-center">{{ trans('public.price') }}</th>
                                                                    <th class="text-center">{{ trans('panel.discount') }}</th>
                                                                    <th class="text-right">{{ trans('financial.total_amount') }}</th>
                                                                </tr>

                                                                @foreach($purchasedTickets as $purchasedTicket)
                                                                    <tr>
                                                                        <td class="text-center">
                                                                            <span>{{ $purchasedTicket->eventTicket->title }}</span>
                                                                        </td>

                                                                        <td class="text-center">{{ $purchasedTicket->quantity }} {{ trans('cart.item') }}</td>

                                                                        <td class="text-center">
                                                                            @if($purchasedTicket->paid_amount > 0)
                                                                                {{ handlePrice($purchasedTicket->paid_amount) }}
                                                                            @else
                                                                                {{ trans('public.free') }}
                                                                            @endif
                                                                        </td>

                                                                        <td class="text-center">
                                                                            @if(!empty($purchasedTicket->sale->discount))
                                                                                {{ handlePrice($purchasedTicket->sale->discount) }}
                                                                            @else
                                                                                -
                                                                            @endif
                                                                        </td>

                                                                        <td class="text-right">
                                                                            @if(!empty($purchasedTicket->sale->total_amount))
                                                                                {{ handlePrice($purchasedTicket->sale->total_amount) }}
                                                                            @else
                                                                                0
                                                                            @endif
                                                                        </td>
                                                                    </tr>
                                                                @endforeach

                                                            </table>
                                                        </div>
                                                        <div class="row mt-4">

                                                            <div class="col-lg-6 text-left">
                                                                <div class="invoice-detail-item">
                                                                    <div class="invoice-detail-name">{{ trans('admin/main.item') }}</div>
                                                                    <div class="invoice-detail-value">{{ $event->title }}</div>
                                                                </div>

                                                                <div class="invoice-detail-item">
                                                                    <div class="invoice-detail-name">{{ trans('update.quantity') }}</div>
                                                                    <div class="invoice-detail-value">{{ $purchasedTickets->sum('quantity') }} {{ trans('cart.item') }}</div>
                                                                </div>

                                                            </div>

                                                            <div class="col-lg-6 text-right">
                                                                <div class="invoice-detail-item">
                                                                    <div class="invoice-detail-name">{{ trans('cart.sub_total') }}</div>
                                                                    <div class="invoice-detail-value">{{ handlePrice($salePrices['saleSubtotal']) }}</div>
                                                                </div>
                                                                <div class="invoice-detail-item">
                                                                    <div class="invoice-detail-name">{{ trans('cart.tax') }}</div>
                                                                    <div class="invoice-detail-value">
                                                                        @if(!empty($salePrices['saleTaxAmount']))
                                                                            {{ handlePrice($salePrices['saleTaxAmount']) }}
                                                                        @else
                                                                            -
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                                <div class="invoice-detail-item">
                                                                    <div class="invoice-detail-name">{{ trans('public.discount') }}</div>
                                                                    <div class="invoice-detail-value">
                                                                        @if(!empty($salePrices['saleDiscountAmount']))
                                                                            {{ handlePrice($salePrices['saleDiscountAmount']) }}
                                                                        @else
                                                                            -
                                                                        @endif
                                                                    </div>
                                                                </div>

                                                                <hr class="mt-2 mb-2">
                                                                <div class="invoice-detail-item">
                                                                    <div class="invoice-detail-name">{{ trans('financial.total_amount') }}</div>
                                                                    <div class="invoice-detail-value invoice-detail-value-lg">
                                                                        @if(!empty($salePrices['saleTotalAmount']))
                                                                            {{ handlePrice($salePrices['saleTotalAmount']) }}
                                                                        @else
                                                                            -
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="text-md-right">

                                                <button type="button" onclick="window.print()" class="btn btn-warning btn-icon icon-left"><i class="fas fa-print"></i> {{ trans('admin/main.print') }}</button>
                                            </div>
                                        </div>
                                    </div>


                                </div>
                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>
</div>
</body>
