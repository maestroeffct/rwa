<?php

namespace App\Mixins\Logs;


use App\Models\VisitLog;
use Illuminate\Http\Request;

class VisitLogMixin
{

    public function __construct()
    {

    }

    public function storeVisit(Request $request, $ownerId, $targetableId, $targetableType)
    {
        $user = auth()->user();
        $fingerprint = $request->fingerprint(); // unique for users devices

        $time = time();
        $beginOfDay = strtotime("today", $time);
        $endOfDay = strtotime("tomorrow", $beginOfDay) - 1;

        $query = VisitLog::query();
        $query->where('targetable_id', $targetableId);
        $query->where('targetable_type', $targetableType);
        $query->where('visitor_uid', $fingerprint);
        $query->whereBetween('visited_at', [$beginOfDay, $endOfDay]);
        $visit = $query->first();

        if (empty($visit)) {
            VisitLog::query()->create([
                'owner_id' => $ownerId,
                'targetable_id' => $targetableId,
                'targetable_type' => $targetableType,
                'visitor_id' => !empty($user) ? $user->id : null,
                'visitor_uid' => $fingerprint,
                'visited_at' => $time,
            ]);
        }

    }

}
