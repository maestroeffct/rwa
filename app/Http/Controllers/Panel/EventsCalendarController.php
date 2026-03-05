<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Mixins\RegistrationPackage\UserPackage;
use App\Models\Bundle;
use App\Models\InstallmentOrder;
use App\Models\InstallmentOrderPayment;
use App\Models\Event;
use App\Models\MeetingPackageSold;
use App\Models\Quiz;
use App\Models\ReserveMeeting;
use App\Models\Session;
use App\Models\Subscribe;
use App\Models\Webinar;
use App\Models\WebinarAssignment;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Spatie\CalendarLinks\Link;

class EventsCalendarController extends Controller
{
    public $user;
    public $userBoughtWebinarsIds;

    public function index(Request $request)
    {
        $user = auth()->user();
        $this->user = $user;
        $this->userBoughtWebinarsIds = $user->getPurchasedCoursesIds();


        $dayTimestamp = $request->get('date', time());

        $dayEvents = $this->handleEventsByDate($dayTimestamp);
        $getUpcomingEvents = $this->getUpcomingEvents();
        $upcomingEvents = $getUpcomingEvents['upcomingEvents'];

        $eventsWithTimestamp = $this->getAllEventsReturnWithTimestamp();

        $data = [
            'pageTitle' => trans('update.events_calendar'),
            'dayEvents' => $dayEvents,
            'dayTimestamp' => $dayTimestamp,
            'upcomingEvents' => $upcomingEvents,
            'eventsWithTimestamp' => $eventsWithTimestamp,
        ];

        return view('design_1.panel.events_calender.index', $data);
    }

    public function getEventsByDay(Request $request)
    {
        $this->validate($request, [
            'timestamp' => 'required',
        ]);

        $user = auth()->user();
        $this->user = $user;
        $this->userBoughtWebinarsIds = $user->getPurchasedCoursesIds();

        $dayTimestamp = $request->get('timestamp');
        $dayEvents = $this->handleEventsByDate($dayTimestamp);

        $html = (string)view()->make("design_1.panel.events_calender.day_events", [
            'dayEvents' => $dayEvents,
            'dayTimestamp' => $dayTimestamp,
        ]);

        return response()->json([
            'code' => 200,
            'html' => $html,
        ]);
    }

    public function getAllEventsReturnWithTimestamp()
    {
        $result = [];
        $events = $this->getAllEvents();

        if (!empty($events) and count($events)) {
            foreach ($events as $eventName => $eventItems) {
                if (!empty($eventItems) and is_array($eventItems)) {
                    foreach ($eventItems as $eventTimestamp => $eventItem) {
                        if (!empty($eventItem) and is_array($eventItem)) {
                            $startOfDayTimestamp = startOfDayTimestamp($eventTimestamp);
                            $endOfDayTimestamp = endOfDayTimestamp($eventTimestamp);

                            if ($startOfDayTimestamp) {
                                $result[$eventTimestamp] = [
                                    'title' => $eventName,
                                    'start_day' => $startOfDayTimestamp,
                                    'end_day' => $endOfDayTimestamp,
                                    ...$eventItem
                                ];
                            }
                        }
                    }
                }
            }
        }

        return $result;
    }

    public function getUpcomingEvents($count = 5)
    {
        $result = [];
        $events = $this->getAllEvents();

        if (!empty($events) and count($events)) {
            foreach ($events as $eventName => $eventItems) {
                if (!empty($eventItems) and is_array($eventItems)) {
                    foreach ($eventItems as $eventTimestamp => $eventItem) {
                        if (!empty($eventItem) and is_array($eventItem)) {
                            $result[] = [
                                'title' => $eventName,
                                ...$eventItem
                            ];
                        }
                    }
                }
            }
        }

        $upcomingEvents = [];

        if (count($result) > 0) {
            uasort($result, function ($a, $b) {
                return $a['event_at'] <=> $b['event_at'];
            });

            $upcomingEvents = array_slice($result, 0, $count, true); // take 5 item
        }

        return [
            'upcomingEvents' => $upcomingEvents,
            'total' => (!empty($events['total'])) ? $events['total'] : 0,
        ];
    }

    private function handleEventsByDate($dateTimestamp)
    {
        $carbonDate = Carbon::createFromTimestamp($dateTimestamp);
        $carbonDate->setTimezone(getTimezone());
        $startAt = $carbonDate->startOfDay()->timestamp;
        $endAt = $carbonDate->endOfDay()->timestamp;

        return $this->getAllEvents($startAt, $endAt);
    }

    private function getAllEvents($startAt = null, $endAt = null)
    {
        $total = 0;
        $events = [];

        // Course Expiration
        $events['courses_expirations'] = $this->getCourseExpirationEvent($startAt, $endAt);
        $total += count($events['courses_expirations']);

        // Quiz Expiration
        $events['quiz_expirations'] = $this->getQuizExpirationEvent($startAt, $endAt);
        $total += count($events['quiz_expirations']);

        // Live Session
        $events['live_sessions'] = $this->getLiveSessionEvent($startAt, $endAt);
        $total += count($events['live_sessions']);

        // Assignment Expiration
        $events['assignment_expirations'] = $this->getAssignmentExpirationEvent($startAt, $endAt);
        $total += count($events['assignment_expirations']);

        // Bundle Expiration
        $events['bundle_expirations'] = $this->getBundleExpirationEvent($startAt, $endAt);
        $total += count($events['bundle_expirations']);

        // Subscription Expiration
        $events['subscription_expirations'] = $this->getSubscriptionExpirationEvent($startAt, $endAt);
        $total += count($events['subscription_expirations']);

        // Registration Package Expiration
        $events['registration_package_expirations'] = $this->getRegistrationPackageExpirationEvent($startAt, $endAt);
        $total += count($events['registration_package_expirations']);

        // Installment
        $events['installments'] = $this->getInstallmentExpirationEvent($startAt, $endAt);
        $total += count($events['installments']);

        // Meeting
        $events['meetings'] = $this->getMeetingExpirationEvent($startAt, $endAt);
        $total += count($events['meetings']);

        // Live Class Start
        $events['live_class_start'] = $this->getLiveClassStartEvent($startAt, $endAt);
        $total += count($events['live_class_start']);

        // Events Start Date
        $events['events_start_date'] = $this->getEventsStartDateEvent($startAt, $endAt);
        $total += count($events['events_start_date']);

        $events['total'] = $total;
        return $events;
    }

    private function getCourseExpirationEvent($startAt = null, $endAt = null)
    {
        $courses = [];

        if (!empty($this->userBoughtWebinarsIds) and count($this->userBoughtWebinarsIds)) {
            $webinars = Webinar::query()->whereIn('id', $this->userBoughtWebinarsIds)
                ->whereNotNull('access_days')
                ->get();

            foreach ($webinars as $webinar) {
                $expiredItem = false;
                $sale = $webinar->getSaleItem($this->user, true);


                if (!empty($sale)) {
                    $expireAt = $webinar->getExpiredAccessDays($sale->created_at, $sale->gift_id);

                    if (!empty($startAt) and !empty($endAt)) {
                        if ($expireAt >= $startAt and $expireAt <= $endAt) {
                            $expiredItem = true;
                        }
                    } elseif ($expireAt > time()) {
                        $expiredItem = true;
                    }

                    if ($expiredItem) {
                        $courses[$expireAt] = [
                            'subtitle' => $webinar->title,
                            'add_to_calendar_url' => $webinar->addToCalendarLink(),
                            'event_at' => $expireAt,
                            'time' => null,
                        ];;
                    }
                }
            }
        }

        return $courses;
    }

    private function getBundleExpirationEvent($startAt = null, $endAt = null)
    {
        $bundlesHasExpiration = [];
        $userBoughtBundlesIds = $this->user->getPurchasedBundlesIds();

        if (count($userBoughtBundlesIds)) {
            $bundles = Bundle::query()->whereIn('id', $userBoughtBundlesIds)
                ->whereNotNull('access_days')
                ->get();

            foreach ($bundles as $bundle) {
                $expiredItem = false;
                $sale = $bundle->getSaleItem($this->user);

                if (!empty($sale)) {
                    $expireAt = $bundle->getExpiredAccessDays($sale->created_at, $sale->gift_id);

                    if (!empty($startAt) and !empty($endAt)) {
                        if ($expireAt >= $startAt and $expireAt <= $endAt) {
                            $expiredItem = true;
                        }
                    } elseif ($expireAt > time()) {
                        $expiredItem = true;
                    }

                    if ($expiredItem) {
                        $bundlesHasExpiration[$expireAt] = [
                            'subtitle' => $bundle->title,
                            'add_to_calendar_url' => '',
                            'event_at' => $expireAt,
                            'time' => null,
                        ];;
                    }
                }
            }
        }

        return $bundlesHasExpiration;
    }

    private function getQuizExpirationEvent($startAt = null, $endAt = null)
    {
        $hasExpiration = [];

        if (!empty($this->userBoughtWebinarsIds) and count($this->userBoughtWebinarsIds)) {
            $quizzes = Quiz::whereIn('webinar_id', $this->userBoughtWebinarsIds)
                ->where('status', 'active')
                ->get();

            foreach ($quizzes as $quiz) {
                $expiredItem = false;
                $expireAt = $quiz->getExpireTimestamp($this->user);

                if (!empty($startAt) and !empty($endAt)) {
                    if ($expireAt >= $startAt and $expireAt <= $endAt) {
                        $expiredItem = true;
                    }
                } elseif ($expireAt > time()) {
                    $expiredItem = true;
                }

                if ($expiredItem) {
                    $title = $quiz->title;
                    if (!empty($quiz->webinar)) {
                        $title .= " - " . $quiz->webinar->title;
                    }

                    $hasExpiration[$expireAt] = [
                        'subtitle' => $title,
                        'add_to_calendar_url' => $this->addToCalendarLink($title, $expireAt),
                        'event_at' => $expireAt,
                        'time' => null,
                    ];
                }
            }
        }

        return $hasExpiration;
    }

    private function getLiveSessionEvent($startAt = null, $endAt = null)
    {
        $hasExpiration = [];

        if (!empty($this->userBoughtWebinarsIds) and count($this->userBoughtWebinarsIds)) {
            $sessions = Session::whereIn('webinar_id', $this->userBoughtWebinarsIds)
                ->where('status', 'active')
                ->where('date', '>=', time())
                ->get();

            foreach ($sessions as $session) {
                $expiredItem = false;
                $sessionDate = $session->date;

                if (!empty($startAt) and !empty($endAt)) {
                    if ($sessionDate >= $startAt and $sessionDate <= $endAt) {
                        $expiredItem = true;
                    }
                } elseif ($sessionDate > time()) {
                    $expiredItem = true;
                }

                if ($expiredItem) {
                    $title = $session->title;
                    if (!empty($session->webinar)) {
                        $title .= " - " . $session->webinar->title;
                    }

                    $hasExpiration[$sessionDate] = [
                        'subtitle' => $title,
                        'add_to_calendar_url' => $this->addToCalendarLink($title, $sessionDate),
                        'event_at' => $sessionDate,
                        'time' => dateTimeFormat($sessionDate, 'H:i'),
                    ];
                }
            }
        }

        return $hasExpiration;
    }

    private function getAssignmentExpirationEvent($startAt = null, $endAt = null)
    {
        $hasExpiration = [];

        if (!empty($this->userBoughtWebinarsIds) and count($this->userBoughtWebinarsIds)) {
            $assignments = WebinarAssignment::whereIn('webinar_id', $this->userBoughtWebinarsIds)
                ->where('status', 'active')
                ->get();

            foreach ($assignments as $assignment) {
                $expiredItem = false;
                $expireAt = $assignment->getDeadlineTimestamp($this->user);

                if (!empty($expireAt)) {
                    if (!empty($startAt) and !empty($endAt)) {
                        if ($expireAt >= $startAt and $expireAt <= $endAt) {
                            $expiredItem = true;
                        }
                    } elseif ($expireAt > time()) {
                        $expiredItem = true;
                    }
                }

                if ($expiredItem) {
                    $title = $assignment->title;
                    if (!empty($assignment->webinar)) {
                        $title .= " - " . $assignment->webinar->title;
                    }

                    $hasExpiration[$expireAt] = [
                        'subtitle' => $title,
                        'add_to_calendar_url' => $this->addToCalendarLink($title, $expireAt),
                        'event_at' => $expireAt,
                        'time' => null,
                    ];
                }
            }
        }

        return $hasExpiration;
    }

    private function getSubscriptionExpirationEvent($startAt = null, $endAt = null)
    {
        $hasExpiration = [];

        $activeSubscribe = Subscribe::getActiveSubscribe($this->user->id);

        if (!empty($activeSubscribe) and !empty($activeSubscribe->expire_at) and $activeSubscribe->expire_at > time()) {
            $expiredItem = false;
            $expireAt = $activeSubscribe->expire_at;

            if (!empty($startAt) and !empty($endAt)) {
                if ($expireAt >= $startAt and $expireAt <= $endAt) {
                    $expiredItem = true;
                }
            } elseif ($expireAt > time()) {
                $expiredItem = true;
            }

            if ($expiredItem) {
                $title = $activeSubscribe->title;

                $hasExpiration[$expireAt] = [
                    'subtitle' => $title,
                    'add_to_calendar_url' => $this->addToCalendarLink($title, $expireAt),
                    'event_at' => $expireAt,
                    'time' => null,
                ];
            }
        }

        return $hasExpiration;
    }

    private function getRegistrationPackageExpirationEvent($startAt = null, $endAt = null)
    {
        $hasExpiration = [];

        $userPackage = new UserPackage($this->user);
        $activePackage = $userPackage->getPackage();

        if (!empty($activePackage) and !empty($activePackage->expire_at) and $activePackage->expire_at > time()) {
            $expiredItem = false;
            $expireAt = $activePackage->expire_at;

            if (!empty($startAt) and !empty($endAt)) {
                if ($expireAt >= $startAt and $expireAt <= $endAt) {
                    $expiredItem = true;
                }
            } elseif ($expireAt > time()) {
                $expiredItem = true;
            }

            if ($expiredItem) {
                $title = $activePackage->title;

                $hasExpiration[$expireAt] = [
                    'subtitle' => $title,
                    'add_to_calendar_url' => $this->addToCalendarLink($title, $expireAt),
                    'event_at' => $expireAt,
                    'time' => null,
                ];
            }
        }

        return $hasExpiration;
    }

    private function getInstallmentExpirationEvent($startAt = null, $endAt = null)
    {
        $installmentOrders = InstallmentOrder::query()
            ->where('user_id', $this->user->id)
            ->where('status', '!=', 'paying')
            ->with([
                'selectedInstallment' => function ($query) {
                    $query->with([
                        'steps' => function ($query) {
                            $query->orderBy('deadline', 'asc');
                        }
                    ]);
                    $query->withCount([
                        'steps'
                    ]);
                }
            ])
            ->get();

        $hasExpiration = [];

        foreach ($installmentOrders as $installmentOrder) {

            foreach ($installmentOrder->selectedInstallment->steps as $step) {
                $expiredItem = false;

                $payment = InstallmentOrderPayment::query()
                    ->where('installment_order_id', $installmentOrder->id)
                    ->where('selected_installment_step_id', $step->id)
                    ->where('status', 'paid')
                    ->first();

                if (empty($payment)) {
                    $expireAt = ($step->deadline * 86400) + $installmentOrder->created_at;

                    if (!empty($startAt) and !empty($endAt)) {
                        if ($expireAt >= $startAt and $expireAt <= $endAt) {
                            $expiredItem = true;
                        }
                    } elseif ($expireAt > time()) {
                        $expiredItem = true;
                    }

                    if ($expiredItem) {
                        $title = $step->installmentStep->title;

                        if (!empty($step->selectedInstallment->order) and !empty($step->selectedInstallment->order->webinar)) {
                            $title .= " - " . $step->selectedInstallment->order->webinar->title;
                        }


                        $hasExpiration[$expireAt] = [
                            'subtitle' => $title,
                            'add_to_calendar_url' => $this->addToCalendarLink($title, $expireAt),
                            'event_at' => $expireAt,
                            'time' => null,
                        ];
                    }
                }
            }

        }

        return $hasExpiration;
    }

    private function getMeetingExpirationEvent($startAt = null, $endAt = null)
    {
        $hasExpiration = [];

        $reserveMeetings = ReserveMeeting::query()->where('user_id', $this->user->id)
            ->whereNotNull('reserved_at')
            ->whereHas('sale', function ($query) {
                //$query->whereNull('refund_at');
            })
            ->where('date', '>', time())
            ->get();

        foreach ($reserveMeetings as $reserveMeeting) {
            $expiredItem = false;
            $expireAt = $reserveMeeting->start_at;

            if (!empty($startAt) and !empty($endAt)) {
                if ($expireAt >= $startAt and $expireAt <= $endAt) {
                    $expiredItem = true;
                }
            } elseif ($expireAt > time()) {
                $expiredItem = true;
            }

            if ($expiredItem) {
                $title = $reserveMeeting->meeting->creator->full_name;

                $hasExpiration[$expireAt] = [
                    'subtitle' => $title,
                    'add_to_calendar_url' => $this->addToCalendarLink("Meeting - $title", $expireAt),
                    'event_at' => $expireAt,
                    'time' => dateTimeFormat($reserveMeeting->start_at, 'H:i') . ' - ' . dateTimeFormat($reserveMeeting->end_at, 'H:i'),
                ];
            }
        }

        $meetingPackagesSoldIds = MeetingPackageSold::query()
            ->where('user_id', $this->user->id)
            ->pluck('id')
            ->toArray();

        if (!empty($meetingPackagesSoldIds)) {
            $meetingPackageScheduledSessions = Session::query()
                ->whereIn('meeting_package_sold_id', $meetingPackagesSoldIds)
                ->where('status', '!=', 'finished')
                ->whereNotNull('date')
                ->with([
                    'meetingPackageSold' => function ($query) {
                        $query->with([
                            'meetingPackage'
                        ]);
                    }
                ])
                ->get();

            foreach ($meetingPackageScheduledSessions as $scheduledSession) {
                $expiredItem = false;
                $expireAt = $scheduledSession->date;

                if (!empty($startAt) and !empty($endAt)) {
                    if ($expireAt >= $startAt and $expireAt <= $endAt) {
                        $expiredItem = true;
                    }
                } elseif ($expireAt > time()) {
                    $expiredItem = true;
                }

                if ($expiredItem) {
                    $title = $scheduledSession->meetingPackageSold->meetingPackage->title;
                    $title .= " (" . trans('update.session') . " #" . $scheduledSession->order . ")";

                    $hasExpiration[$expireAt] = [
                        'subtitle' => $title,
                        'add_to_calendar_url' => $this->addToCalendarLink($title, $expireAt),
                        'event_at' => $expireAt,
                        'time' => dateTimeFormat($scheduledSession->date, 'H:i') . ' - ' . dateTimeFormat(($scheduledSession->date + ($scheduledSession->duration * 60)), 'H:i'),
                    ];
                }
            }
        }

        return $hasExpiration;
    }

    private function getLiveClassStartEvent($startAt = null, $endAt = null)
    {
        $hasExpiration = [];

        if (!empty($this->userBoughtWebinarsIds) and count($this->userBoughtWebinarsIds)) {
            $webinars = Webinar::query()->whereIn('id', $this->userBoughtWebinarsIds)
                ->whereNotNull('access_days')
                ->where('type', Webinar::$webinar)
                ->where('start_date', '>', time())
                ->get();

            foreach ($webinars as $webinar) {
                $expiredItem = false;
                $expireAt = $webinar->start_date;

                if (!empty($startAt) and !empty($endAt)) {
                    if ($expireAt >= $startAt and $expireAt <= $endAt) {
                        $expiredItem = true;
                    }
                } elseif ($expireAt > time()) {
                    $expiredItem = true;
                }

                if ($expiredItem) {
                    $title = $webinar->title;

                    $hasExpiration[$expireAt] = [
                        'subtitle' => $title,
                        'add_to_calendar_url' => $this->addToCalendarLink($title, $expireAt),
                        'event_at' => $expireAt,
                        'time' => dateTimeFormat($webinar->start_date, 'H:i'),
                    ];
                }
            }
        }

        return $hasExpiration;
    }

    public function getEventsStartDateEvent($startAt = null, $endAt = null)
    {
        $hasExpiration = [];

        $eventsQuery = Event::query()
            ->where(function ($query) {
                // User Purchased
                $query->whereHas('tickets', function ($query) {
                    $query->whereHas('eventTicketsSolds', function ($query) {
                        $query->where('user_id', $this->user->id);
                    });
                });

                // User Created
                $query->orWhere('creator_id', $this->user->id);
            })
            ->whereNotNull('start_date');

        if (!empty($startAt) and !empty($endAt)) {
            $eventsQuery->where(function ($query) use ($startAt, $endAt) {
                $query->where('start_date', '>=', $startAt);
                $query->where('start_date', '<', $endAt);
            });
        } else {
            $eventsQuery->where('start_date', '>=', time());
        }

        $events = $eventsQuery->get();

        foreach ($events as $event) {
            $title = $event->title;
            $eventStartDate = $event->start_date;

            $hasExpiration[$eventStartDate] = [
                'subtitle' => $title,
                'add_to_calendar_url' => $this->addToCalendarLink($title, $eventStartDate),
                'event_at' => $eventStartDate,
                'time' => dateTimeFormat($eventStartDate, 'H:i'),
            ];
        }

        return $hasExpiration;
    }

    private function addToCalendarLink($title, $timestamp)
    {

        $date = \DateTime::createFromFormat('j M Y H:i', dateTimeFormat($timestamp, 'j M Y H:i', false));

        $link = Link::create($title, $date, $date); //->description('Cookies & cocktails!')

        return $link->google();
    }

}
