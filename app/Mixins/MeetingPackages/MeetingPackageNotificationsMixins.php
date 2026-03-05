<?php

namespace App\Mixins\MeetingPackages;


use App\Models\Session;

class MeetingPackageNotificationsMixins
{

    public function sendSessionsReminders()
    {
        $hour = getRemindersSettings('meeting_packages_reminder_schedule') ?? 1;
        $time = time();
        $hoursLater = $time + ($hour * 60 * 60);

        $sessions = Session::query()->where('date', '>', $time)
            ->whereNotNull('meeting_package_sold_id')
            ->whereBetween('date', [$time, $hoursLater])
            ->with([
                'creator' => function ($query) {
                    $query->select('id', 'full_name');
                },
                'meetingPackageSold'
            ])
            ->get();

        foreach ($sessions as $session) {
            try {
                $notifyOptions = [
                    '[instructor.name]' => $session->creator->full_name,
                    '[time.date]' => dateTimeFormat($session->date, 'j M Y , H:i'),
                ];

                sendNotification('meeting_reserve_reminder', $notifyOptions, $session->meetingPackageSold->user_id);
            } catch (\Exception $exception) {

            }
        }
    }
}
