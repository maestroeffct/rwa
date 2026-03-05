<?php

namespace App\Mixins\Events;


use App\Models\Event;
use App\Models\EventTicketSold;

class EventNotificationsMixins
{

    public function sendEventsReminders()
    {
        $hour = getRemindersSettings('event_reminder_schedule') ?? 1;
        $time = time();
        $hoursLater = $time + ($hour * 60 * 60);


        $purchasedTickets = EventTicketSold::query()
            ->whereHas('eventTicket', function ($query) use ($time, $hoursLater) {
                $query->whereHas('event', function ($query) use ($time, $hoursLater) {
                    $query->whereNotNull('start_date');
                    $query->whereBetween('start_date', [$time, $hoursLater]);
                });
            })
            ->get();

        if ($purchasedTickets->isNotEmpty()) {
            foreach ($purchasedTickets as $purchasedTicket) {
                $event = $purchasedTicket->eventTicket->event;

                $notifyOptions = [
                    '[time.date]' => dateTimeFormat($event->start_date, 'j M Y H:i'),
                    '[instructor.name]' => $event->creator->full_name,
                ];

                sendNotification('events_start_reminder', $notifyOptions, $purchasedTicket->user_id);
            }
        }
    }

    public function sendCanceledEventNotification(Event $event)
    {
        $userIds = $event->getAllStudentsIds();

        if (count($userIds)) {
            $notifyOptions = [
                '[time.date]' => dateTimeFormat(time(), 'j M Y H:i'),
                '[event_title]' => $event->title,
            ];

            foreach ($userIds as $userId) {
                sendNotification('cancel_event_for_students', $notifyOptions, $userId);
            }
        }
    }

}
