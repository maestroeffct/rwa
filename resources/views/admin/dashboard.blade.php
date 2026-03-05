@extends('admin.layouts.app')

@push('libraries_top')
    <link rel="stylesheet" href="/assets/admin/vendor/owl.carousel/owl.carousel.min.css">
    <link rel="stylesheet" href="/assets/admin/vendor/owl.carousel/owl.theme.min.css">

@endpush

@section('content')

    <section class="section">
        <div class="row">
            <div class="col-12 mb-3">
                <div class="hero rounded-12 text-white hero-bg-image bg-secondary" data-background="{{ !empty(getThemePageBackgroundSettings('admin_dashboard')) ? getThemePageBackgroundSettings('admin_dashboard') : '' }}">
                    <div class="hero-inner">
                        <h2>{{trans('admin/main.welcome')}}, {{ $authUser->full_name }}!</h2>

                        <div class="d-flex flex-column flex-lg-row align-items-center justify-content-between">
                            @can('admin_general_dashboard_quick_access_links')
                                <div>
                                    <p class="lead">{{trans('admin/main.welcome_card_text')}}</p>

                                    <div class="mt-4 mb-2 d-flex align-items-center gap-16 rounded-24 flex-column flex-md-row">
                                        <a href="{{ getAdminPanelUrl() }}/comments/webinars" class="mt-2 mt-md-0 rounded-16 btn btn-outline-white btn-lg btn-icon icon-left">
                                            <x-iconsax-lin-message class="mr-1 icons" width="24px" height="24px"/>{{trans('admin/main.comments')}} </a>
                                        <a href="{{ getAdminPanelUrl() }}/supports" class="mt-2 mt-md-0 btn rounded-16 btn-outline-white btn-lg btn-icon icon-left">
                                            <x-iconsax-lin-sms class="mr-1 icons" width="24px" height="24px"/>{{trans('admin/main.tickets')}}</a>
                                        <a href="{{ getAdminPanelUrl() }}/reports/webinars" class="mt-2 mt-md-0 btn rounded-16 btn-outline-white btn-lg btn-icon icon-left">
                                            <x-iconsax-lin-info-circle class="mr-1 icons" width="24px" height="24px"/>{{trans('admin/main.reports')}}</a>
                                    </div>
                                </div>
                            @endcan

                            @can('admin_clear_cache')
                                <div class="w-xs-to-lg-100">
                                    <p class="lead d-none d-lg-block">&nbsp;</p>

                                    @include('admin.includes.delete_button',[
                                                     'url' => getAdminPanelUrl().'/clear-cache',
                                                     'btnClass' => 'rounded-16 text-white border btn-outline-white font-14 btn-lg btn-icon icon-left mt-6',
                                                     'btnText' => trans('admin/main.clear_all_cache'),
                                                     'btnIcon' => 'trash',
                                                     'iconType' => 'lin',
                                                     'iconClass' => 'text-white mr-2',
                                                  ])
                                </div>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-4 col-md-4 col-sm-12">
                @can('admin_general_dashboard_daily_sales_statistics')
                    @if(!empty($dailySalesTypeStatistics))
                        <div class="card card-statistic-2">
                            <div class="card-stats rounded-12">
                                <div class="card-stats-title">{{trans('admin/main.daily_sales_type_statistics')}}</div>

                                <div class="card-stats-items">
                                    <div class="card-stats-item">
                                        <div class="card-stats-item-count">{{ $dailySalesTypeStatistics['webinarsSales'] }}</div>
                                        <div class="text-gray-500 card-stats-item-label">{{trans('admin/main.live_class')}}</div>
                                    </div>

                                    <div class="card-stats-item">
                                        <div class="card-stats-item-count">{{ $dailySalesTypeStatistics['courseSales'] }}</div>
                                        <div class="text-gray-500 card-stats-item-label">{{trans('admin/main.course')}}</div>
                                    </div>

                                    <div class="card-stats-item">
                                        <div class="card-stats-item-count">{{ $dailySalesTypeStatistics['appointmentSales'] }}</div>
                                        <div class="text-gray-500 card-stats-item-label">{{trans('admin/main.appointment')}}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-icon size-48 bg-primary-30 rounded-12">
                                <x-iconsax-bul-bag class="icons text-primary" width="24px" height="24px"/>
                            </div>
                            <div class="card-wrap">
                                <div class="card-header">
                                    <h4>{{trans('admin/main.today_sales')}}</h4>
                                </div>
                                <div class="card-body">
                                    {{ $dailySalesTypeStatistics['allSales'] }}
                                </div>
                            </div>
                        </div>
                    @endif
                @endcan
            </div>


            <div class="col-lg-4 col-md-4 col-sm-12">
                @can('admin_general_dashboard_income_statistics')
                    @if(!empty($getIncomeStatistics))
                        <div class="card card-statistic-2">
                            <div class="card-stats">
                                <div class="card-stats-title">{{trans('admin/main.income_statistics')}}</div>

                                <div class="card-stats-items">
                                    <div class="card-stats-item">
                                        <div class="card-stats-item-count">{{ handlePrice($getIncomeStatistics['todaySales']) }}</div>
                                        <div class="text-gray-500 card-stats-item-label">{{trans('admin/main.today')}}</div>
                                    </div>

                                    <div class="card-stats-item">
                                        <div class="card-stats-item-count">{{ handlePrice($getIncomeStatistics['monthSales']) }}</div>
                                        <div class="text-gray-500 card-stats-item-label">{{trans('admin/main.this_month')}}</div>
                                    </div>

                                    <div class="card-stats-item">
                                        <div class="card-stats-item-count">{{ handlePrice($getIncomeStatistics['yearSales']) }}</div>
                                        <div class="text-gray-500 card-stats-item-label">{{trans('admin/main.this_year')}}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-icon size-48 bg-primary-30 rounded-12">
                                <x-iconsax-bul-dollar-square class="icons text-primary" width="24px" height="24px"/>
                            </div>
                            <div class="card-wrap">
                                <div class="card-header">
                                    <h4>{{trans('admin/main.total_incomes')}}</h4>
                                </div>
                                <div class="card-body">
                                    {{ handlePrice($getIncomeStatistics['totalSales']) }}
                                </div>
                            </div>
                        </div>
                    @endif
                @endcan
            </div>

            <div class="col-lg-4 col-md-4 col-sm-12">
                @can('admin_general_dashboard_total_sales_statistics')
                    @if(!empty($getTotalSalesStatistics))
                        <div class="card card-statistic-2">
                            <div class="card-stats">
                                <div class="card-stats-title">{{trans('admin/main.salescount')}}</div>

                                <div class="card-stats-items">
                                    <div class="card-stats-item">
                                        <div class="card-stats-item-count">{{ $getTotalSalesStatistics['todaySales'] }}</div>
                                        <div class="text-gray-500 card-stats-item-label">{{trans('admin/main.today')}}</div>
                                    </div>
                                    <div class="card-stats-item">
                                        <div class="card-stats-item-count">{{ $getTotalSalesStatistics['monthSales'] }}</div>
                                        <div class="text-gray-500 card-stats-item-label">{{trans('admin/main.this_month')}}</div>
                                    </div>
                                    <div class="card-stats-item">
                                        <div class="card-stats-item-count">{{ $getTotalSalesStatistics['yearSales'] }}</div>
                                        <div class="text-gray-500 card-stats-item-label">{{trans('admin/main.this_year')}}</div>
                                    </div>
                                </div>
                            </div>

                            <div class="card-icon size-48 bg-primary-30 rounded-12">
                                <x-iconsax-bul-chart-square class="icons text-primary" width="24px" height="24px"/>
                            </div>

                            <div class="card-wrap">
                                <div class="card-header">
                                    <h4>{{trans('admin/main.total_sales')}}</h4>
                                </div>
                                <div class="card-body">
                                    {{ $getTotalSalesStatistics['totalSales'] }}
                                </div>
                            </div>
                        </div>
                    @endif
                @endcan
            </div>
        </div>

        <div class="row">

            @can('admin_general_dashboard_new_sales')
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <a href="{{ getAdminPanelUrl() }}/financial/sales" class="card card-statistic-1">
                        <div class="card-icon bg-primary-40 rounded-12">
                            <x-iconsax-bul-shopping-cart class="icons text-primary" width="32px" height="32px"/>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>{{trans('admin/main.new_sale')}}</h4>
                            </div>
                            <div class="card-body">
                                {{ $getNewSalesCount }}
                            </div>
                        </div>
                    </a>
                </div>
            @endcan

            @can('admin_general_dashboard_new_comments')
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <a href="{{ getAdminPanelUrl() }}/comments/webinars" class="card card-statistic-1">
                        <div class="card-icon bg-danger-30 rounded-12">
                            <x-iconsax-bul-message-2 class="icons text-danger" width="32px" height="32px"/>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>{{trans('admin/main.new_comment')}}</h4>
                            </div>
                            <div class="card-body">
                                {{ $getNewCommentsCount }}
                            </div>
                        </div>
                    </a>
                </div>
            @endcan

            @can('admin_general_dashboard_new_tickets')
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <a href="{{ getAdminPanelUrl() }}/supports" class="card card-statistic-1">
                        <div class="card-icon bg-warning-30 rounded-12">
                            <x-iconsax-bul-sms class="icons text-warning" width="32px" height="32px"/>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>{{trans('admin/main.new_ticket')}}</h4>
                            </div>
                            <div class="card-body">
                                {{ $getNewTicketsCount }}
                            </div>
                        </div>
                    </a>
                </div>
            @endcan

            @can('admin_general_dashboard_new_reviews')
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <a class="card card-statistic-1">
                        <div class="card-icon bg-success-30 rounded-12">
                            <x-iconsax-bul-video-time class="icons text-success" width="32px" height="32px"/>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>{{trans('admin/main.pending_review_classes')}}</h4>
                            </div>
                            <div class="card-body">
                                {{ $getPendingReviewCount }}
                            </div>
                        </div>
                    </a>
                </div>
            @endcan

        </div>


        <div class="row">
            @can('admin_general_dashboard_sales_statistics_chart')
                <div class="col-lg-8 col-md-12 col-12 col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>{{trans('admin/main.sales_statistics')}}</h4>
                            <div class="card-header-action">
                                <div class="btn-group">
                                    <button type="button" class="js-sale-chart-month btn">{{trans('admin/main.month')}}</button>
                                    <button type="button" class="js-sale-chart-year btn btn-primary">{{trans('admin/main.year')}}</button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <div class="position-relative">
                                        <canvas id="saleStatisticsChart"></canvas>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    @if(!empty($getMonthAndYearSalesChartStatistics))
                                        <div class="statistic-details mt-4 position-relative">
                                            <div class="statistic-details-item">
                                                <span class="text-gray-500">
                                                    @if($getMonthAndYearSalesChartStatistics['todaySales']['grow_percent']['status'] == 'up')
                                                        <span class="text-primary"><i class="fas fa-caret-up"></i></span>
                                                    @else
                                                        <span class="text-danger"><i class="fas fa-caret-down"></i></span>
                                                    @endif

                                                    {{ $getMonthAndYearSalesChartStatistics['todaySales']['grow_percent']['percent'] }}
                                                </span>

                                                <div class="detail-value">{{ handlePrice($getMonthAndYearSalesChartStatistics['todaySales']['amount']) }}</div>
                                                <div class="detail-name">{{trans('admin/main.today_sales')}}</div>
                                            </div>
                                            <div class="statistic-details-item">
                                                <span class="text-gray-500">
                                                    @if($getMonthAndYearSalesChartStatistics['weekSales']['grow_percent']['status'] == 'up')
                                                        <span class="text-primary"><i class="fas fa-caret-up"></i></span>
                                                    @else
                                                        <span class="text-danger"><i class="fas fa-caret-down"></i></span>
                                                    @endif

                                                    {{ $getMonthAndYearSalesChartStatistics['weekSales']['grow_percent']['percent'] }}
                                                </span>

                                                <div class="detail-value">{{ handlePrice($getMonthAndYearSalesChartStatistics['weekSales']['amount']) }}</div>
                                                <div class="detail-name">{{trans('admin/main.week_sales')}}</div>
                                            </div>
                                            <div class="statistic-details-item">
                                                <span class="text-gray-500">
                                                    @if($getMonthAndYearSalesChartStatistics['monthSales']['grow_percent']['status'] == 'up')
                                                        <span class="text-primary"><i class="fas fa-caret-up"></i></span>
                                                    @else
                                                        <span class="text-danger"><i class="fas fa-caret-down"></i></span>
                                                    @endif

                                                    {{ $getMonthAndYearSalesChartStatistics['monthSales']['grow_percent']['percent'] }}
                                                </span>

                                                <div class="detail-value">{{ handlePrice($getMonthAndYearSalesChartStatistics['monthSales']['amount']) }}</div>
                                                <div class="detail-name">{{trans('admin/main.month_sales')}}</div>
                                            </div>
                                            <div class="statistic-details-item">
                                                <span class="text-gray-500">
                                                    @if($getMonthAndYearSalesChartStatistics['yearSales']['grow_percent']['status'] == 'up')
                                                        <span class="text-primary"><i class="fas fa-caret-up"></i></span>
                                                    @else
                                                        <span class="text-danger"><i class="fas fa-caret-down"></i></span>
                                                    @endif

                                                    {{ $getMonthAndYearSalesChartStatistics['yearSales']['grow_percent']['percent'] }}
                                                </span>

                                                <div class="detail-value">{{ handlePrice($getMonthAndYearSalesChartStatistics['yearSales']['amount']) }}</div>
                                                <div class="detail-name">{{trans('admin/main.year_sales')}}</div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endcan

            @can('admin_general_dashboard_recent_comments')
                <div class="col-lg-4 col-md-12 col-12 col-sm-12 @if(count($recentComments) < 6) pb-30 @endif">
                    <div class="card @if(count($recentComments) < 6) h-100 @endif">
                        <div class="card-header">
                            <h4>{{trans('admin/main.recent_comments')}}</h4>
                        </div>

                        <div class="card-body d-flex flex-column justify-content-between">
                            <ul class="list-unstyled list-unstyled-border">
                                @foreach($recentComments as $recentComment)
                                    <li class="media">
                                        <img class="mr-3 rounded-circle" width="50" height="50" src="{{ $recentComment->user->getAvatar() }}" alt="avatar">
                                        <div class="media-body">
                                            <div class="float-right text-gray-500 font-12">{{ dateTimeFormat($recentComment->created_at, 'j M Y | H:i') }}</div>
                                            <div class="media-title">{{ $recentComment->user->full_name }}</div>
                                            <span class="text-small text-gray-500">{{ truncate($recentComment->comment, 150) }}</span>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>

                            <div class="text-center pt-1 pb-1">
                                <a href="{{ getAdminPanelUrl() }}/comments/webinars" class="btn rounded-16 btn-primary btn-lg btn-round ">
                                    {{trans('admin/main.view_all')}}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endcan
        </div>


        <div class="row">

            @can('admin_general_dashboard_recent_tickets')
                @if(!empty($recentTickets))
                    <div class="col-md-4">
                        <div class="card card-hero">
                            <div class="card-header">
                                <div class="card-icon">
                                    <x-iconsax-bul-sms class="icons" width="80px" height="80px"/>
                                </div>
                                <h4>{{trans('admin/main.recent_tickets')}}</h4>
                                <div class="card-description">{{ $recentTickets['pendingReply'] }} {{ trans('admin/main.pending_reply') }}</div>
                            </div>

                            <div class="card-body p-0">
                                <div class="tickets-list">

                                    @foreach($recentTickets['tickets'] as $ticket)
                                        <a href="{{ getAdminPanelUrl() }}/supports/{{ $ticket->id }}/conversation" class="ticket-item">
                                            <div class="ticket-title">
                                                <h4 class="text-dark">{{ $ticket->title }}</h4>
                                            </div>
                                            <div class="ticket-info">
                                                <div class="text-gray-500">{{ $ticket->user->full_name }}</div>
                                                <div class="bullet"></div>
                                                @if($ticket->status == 'replied' or $ticket->status == 'open')
                                                    <span class="badge-status-card text-warning bg-warning-30">{{ trans('admin/main.pending_reply') }}</span>
                                                @elseif($ticket->status == 'close')
                                                    <span class="badge-status-card text-danger bg-danger-30">{{ trans('admin/main.close') }}</span>
                                                @else
                                                    <span class="badge-status-card text-success bg-success-30">{{ trans('admin/main.replied') }}</span>
                                                @endif
                                            </div>
                                        </a>
                                    @endforeach

                                    <div class="text-center pt-10 pb-10">
                                        <a href="{{ getAdminPanelUrl() }}/supports" class="btn rounded-16 btn-primary btn-lg btn-round ">
                                            {{trans('admin/main.view_all')}}
                                        </a>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @endcan

            @can('admin_general_dashboard_recent_webinars')
                @if(!empty($recentWebinars))
                    <div class="col-md-4">
                        <div class="card card-hero">
                            <div class="card-header">
                                <div class="card-icon">
                                    <x-iconsax-bul-video class="icons" width="80px" height="80px"/>
                                </div>
                                <h4>{{trans('admin/main.recent_live_classes')}}</h4>
                                <div class="card-description">{{ $recentWebinars['pendingReviews'] }} {{trans('admin/main.pending_review')}}</div>
                            </div>
                            <div class="card-body p-0">
                                <div class="tickets-list">
                                    @foreach($recentWebinars['webinars'] as $webinar)
                                        <a href="{{ getAdminPanelUrl() }}/webinars/{{ $webinar->id }}/edit" class="ticket-item">
                                            <div class="ticket-title">
                                                <h4 class="text-dark">{{ $webinar->title }}</h4>
                                            </div>

                                            <div class="ticket-info">
                                                <div class="text-gray-500">{{ $webinar->teacher->full_name }}</div>
                                                <div class="bullet"></div>
                                                @switch($webinar->status)
                                                    @case(\App\Models\Webinar::$active)
                                                        @if($webinar->isProgressing())
                                                            <span class="badge-status-card text-warning bg-warning-30">{{ trans('webinars.in_progress') }}</span>
                                                        @elseif($webinar->start_date > time())
                                                            <span class="badge-status-card text-success bg-success-30">{{ trans('admin/main.not_conducted') }}</span>
                                                        @else
                                                            <span class="badge-status-card text-success bg-success-30">{{ trans('public.finished') }}</span>
                                                        @endif
                                                        @break
                                                    @case(\App\Models\Webinar::$isDraft)
                                                        <span class="badge-status-card text-dark bg-dark-30">{{ trans('admin/main.is_draft') }}</span>
                                                        @break
                                                    @case(\App\Models\Webinar::$pending)
                                                        <span class="badge-status-card text-warning bg-warning-30">{{ trans('admin/main.waiting') }}</span>
                                                        @break
                                                    @case(\App\Models\Webinar::$inactive)
                                                        <span class="badge-status-card text-danger bg-danger-30">{{ trans('public.rejected') }}</span>
                                                        @break
                                                @endswitch
                                            </div>
                                        </a>
                                    @endforeach

                                    <div class="text-center pt-10 pb-10">
                                        <a href="{{ getAdminPanelUrl() }}/webinars?type=webinar" class="btn rounded-16 btn-primary btn-lg btn-round ">
                                            {{trans('admin/main.view_all')}}
                                        </a>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @endcan

            @can('admin_general_dashboard_recent_courses')
                @if(!empty($recentCourses))
                    <div class="col-md-4">
                        <div class="card card-hero">
                            <div class="card-header">
                                <div class="card-icon">
                                    <x-iconsax-bul-video-circle class="icons" width="80px" height="80px"/>
                                </div>
                                <h4>{{trans('admin/main.recent_courses')}}</h4>
                                <div class="card-description">{{ $recentCourses['pendingReviews'] }} {{trans('admin/main.pending_review')}}</div>
                            </div>
                            <div class="card-body p-0">
                                <div class="tickets-list">


                                    @foreach($recentCourses['courses'] as $course)
                                        <a href="{{ getAdminPanelUrl() }}/webinars/{{ $course->id }}/edit" class="ticket-item">
                                            <div class="ticket-title">
                                                <h4 class="text-dark">{{ $course->title }}</h4>
                                            </div>

                                            <div class="ticket-info">
                                                <div class="text-gray-500">{{ $course->teacher->full_name }}</div>
                                                <div class="bullet"></div>
                                                @switch($course->status)
                                                    @case(\App\Models\Webinar::$active)
                                                        @if($course->isProgressing())
                                                            <span class="badge-status-card text-warning bg-warning-30">{{ trans('webinars.in_progress') }}</span>
                                                        @elseif($course->start_date > time())
                                                            <span class="badge-status-card text-success bg-success-30">{{ trans('admin/main.not_conducted') }}</span>
                                                        @else
                                                            <span class="badge-status-card text-success bg-success-30">{{ trans('public.finished') }}</span>
                                                        @endif
                                                        @break
                                                    @case(\App\Models\Webinar::$isDraft)
                                                        <span class="badge-status-card text-dark bg-dark-30">{{ trans('admin/main.is_draft') }}</span>
                                                        @break
                                                    @case(\App\Models\Webinar::$pending)
                                                        <span class="badge-status-card text-warning bg-warning-30">{{ trans('admin/main.waiting') }}</span>
                                                        @break
                                                    @case(\App\Models\Webinar::$inactive)
                                                        <span class="badge-status-card text-danger bg-danger-30">{{ trans('public.rejected') }}</span>
                                                        @break
                                                @endswitch
                                            </div>
                                        </a>
                                    @endforeach

                                    <div class="text-center pt-10 pb-10">
                                        <a href="{{ getAdminPanelUrl() }}/webinars?type=course" class="btn rounded-16 btn-primary btn-lg btn-round ">
                                            {{trans('admin/main.view_all')}}
                                        </a>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @endcan
        </div>

        @can('admin_general_dashboard_users_statistics_chart')
            <div class="row">
                <div class="col-lg-12 col-md-12 col-12 col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>{{trans('admin/main.new_registration_statistics')}}</h4>
                            <div class="card-header-action">
                                <div class="btn-group">
                                    {{--<a href="#" class="btn">Views
                                    </a>--}}
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <div class="position-relative">
                                        <canvas id="usersStatisticsChart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endcan
    </section>
@endsection

@push('scripts_bottom')
    <script src="/assets/default/vendors/chartjs/chart.min.js"></script>
    <script src="/assets/admin/vendor/owl.carousel/owl.carousel.min.js"></script>

    <script src="/assets/admin/js/parts/dashboard.min.js"></script>

    <script>
        (function ($) {
            "use strict";

            @if(!empty($getMonthAndYearSalesChart))
            makeStatisticsChart('saleStatisticsChart', saleStatisticsChart, 'Sale', @json($getMonthAndYearSalesChart['labels']),@json($getMonthAndYearSalesChart['data']));
            @endif

            @if(!empty($usersStatisticsChart))
            makeStatisticsChart('usersStatisticsChart', usersStatisticsChart, 'Users', @json($usersStatisticsChart['labels']),@json($usersStatisticsChart['data']));
            @endif

        })(jQuery)
    </script>
@endpush
