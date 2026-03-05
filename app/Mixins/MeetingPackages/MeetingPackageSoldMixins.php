<?php

namespace App\Mixins\MeetingPackages;

use App\Models\MeetingPackage;
use App\Models\MeetingPackageSold;
use App\Models\OrderItem;
use App\Models\Sale;
use App\Models\Session;
use App\Models\Translation\SessionTranslation;

class MeetingPackageSoldMixins
{

    public function makeUserSessions(OrderItem $orderItem, Sale $sale)
    {
        $meetingPackage = $sale->meetingPackage;
        $packageExpireAt = $this->getPackageExpireAtBySale($meetingPackage, $sale);

        $meetingPackageSold = MeetingPackageSold::query()->create([
            'meeting_package_id' => $meetingPackage->id,
            'user_id' => $sale->buyer_id,
            'sale_id' => $sale->id,
            'session_duration' => $meetingPackage->session_duration,
            'paid_amount' => $sale->total_amount,
            'paid_at' => $sale->created_at,
            'expire_at' => $packageExpireAt,
        ]);

        // Sessions
        $this->makeMeetingPackageSessionsForBuyer($meetingPackage, $meetingPackageSold);


        $notifyOptions = [
            '[u.name]' => $orderItem->user->full_name,
            '[item_title]' => $meetingPackage->title,
            '[amount]' => handlePrice($sale->total_amount),
        ];
        sendNotification('user_purchased_meeting_package', $notifyOptions, $meetingPackageSold->user_id);
    }

    private function getPackageExpireAtBySale(MeetingPackage $meetingPackage, Sale $sale)
    {
        $daysMap = [
            'day' => 1,
            'week' => 7,
            'month' => 30,
            'year' => 365,
        ];

        $durationType = ($daysMap[$meetingPackage->duration_type] ?? 1) * 24 * 60 * 60;
        $duration = $meetingPackage->duration * $durationType;

        return $sale->created_at + $duration;
    }

    private function makeMeetingPackageSessionsForBuyer(MeetingPackage $meetingPackage, MeetingPackageSold $meetingPackageSold)
    {
        for ($i = 1; $i <= $meetingPackage->sessions; $i++) {
            $session = Session::query()->create([
                'creator_id' => $meetingPackage->creator_id,
                'meeting_package_sold_id' => $meetingPackageSold->id,
                'date' => null,
                'link' => null,
                'duration' => $meetingPackageSold->session_duration,
                'order' => $i,
                'status' => 'draft',
                'updated_at' => time() + $i,
                'created_at' => time() + $i,
            ]);

            SessionTranslation::updateOrCreate([
                'session_id' => $session->id,
                'locale' => mb_strtolower(app()->getLocale()),
            ], [
                'title' => trans('update.session_for_meeting_package'),
                'description' => trans('update.session_for_meeting_package'),
            ]);
        }
    }

}
