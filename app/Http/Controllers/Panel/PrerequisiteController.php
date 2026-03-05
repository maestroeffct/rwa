<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Prerequisite;
use App\Models\Webinar;
use Illuminate\Http\Request;
use Validator;

class PrerequisiteController extends Controller
{
    private function getColumnName($data)
    {
        $name = "webinar_id";

        if (!empty($data['event_id'])) {
            $name = "event_id";
        }

        return $name;
    }

    private function checkUserCanAccessToItem($data)
    {
        $access = true;
        $user = auth()->user();

        if (!empty($data['event_id'])) {
            $event = Event::query()->where('id', $data['event_id'])
                ->where('creator_id', $user->id)
                ->first();

            $access = !empty($event);
        } else {
            $webinar = Webinar::find($data['webinar_id']);
            $access = (!empty($webinar) and $webinar->canAccess());
        }

        return $access;
    }

    public function store(Request $request)
    {
        $data = $request->get('ajax')['new'];
        $columnName = $this->getColumnName($data);

        $validator = Validator::make($data, [
            "{$columnName}" => "required",
            'prerequisite_id' => "required|unique:prerequisites,prerequisite_id,null,id,{$columnName},{$data[$columnName]}",
        ]);

        if ($validator->fails()) {
            return response([
                'code' => 422,
                'errors' => $validator->errors(),
            ], 422);
        }

        if ($this->checkUserCanAccessToItem($data)) {
            $required = (!empty($data['required']) and $data['required'] == 'on');

            Prerequisite::create([
                "{$columnName}" => $data[$columnName],
                'prerequisite_id' => $data['prerequisite_id'],
                'required' => $required,
                'created_at' => time()
            ]);

            return response()->json([
                'code' => 200,
            ], 200);
        }

        abort(403);
    }

    public function update(Request $request, $id)
    {
        $user = auth()->user();
        $data = $request->get('ajax')[$id];
        $columnName = $this->getColumnName($data);

        $validator = Validator::make($data, [
            "{$columnName}" => "required",
            'prerequisite_id' => "required|unique:prerequisites,prerequisite_id,{$id},id,{$columnName},{$data[$columnName]}",
        ]);

        if ($validator->fails()) {
            return response([
                'code' => 422,
                'errors' => $validator->errors(),
            ], 422);
        }

        if ($this->checkUserCanAccessToItem($data)) {
            $required = (!empty($data['required']) and $data['required'] == 'on');

            $prerequisite = Prerequisite::where('id', $id)
                ->where($columnName, $data[$columnName])
                ->first();

            if (!empty($prerequisite)) {
                $prerequisite->update([
                    "{$columnName}" => $data[$columnName],
                    'prerequisite_id' => $data['prerequisite_id'],
                    'required' => $required,
                    'updated_at' => time()
                ]);

                return response()->json([
                    'code' => 200,
                ], 200);
            }
        }

        abort(403);
    }

    public function destroy(Request $request, $id)
    {
        $user = auth()->user();

        $webinarIds = $user->webinars()->pluck('id')->toArray();

        $prerequisite = Prerequisite::where('id', $id)
            ->where(function ($query) use ($webinarIds, $user) {
                $query->whereIn('webinar_id', $webinarIds);

                $query->orWhereHas('event', function ($query) use ($user) {
                    $query->where('creator_id', $user->id);
                });
            })
            ->first();

        if (!empty($prerequisite)) {
            $prerequisite->delete();
        }

        return response()->json([
            'code' => 200
        ], 200);
    }
}
