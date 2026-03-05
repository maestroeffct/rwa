<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Bundle;
use App\Models\Event;
use App\Models\Translation\WebinarExtraDescriptionTranslation;
use App\Models\UpcomingCourse;
use App\Models\Webinar;
use App\Models\WebinarExtraDescription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class WebinarExtraDescriptionController extends Controller
{

    private function getItemColumnName($data)
    {
        $columnName = "webinar_id";

        if (!empty(!empty($data['upcoming_course_id']))) {
            $columnName = "upcoming_course_id";
        } else if (!empty(!empty($data['event_id']))) {
            $columnName = "event_id";
        }

        return $columnName;
    }


    public function store(Request $request)
    {
        $user = auth()->user();
        $data = $request->get('ajax')['new'];
        $columnName = $this->getItemColumnName($data);

        $validator = Validator::make($data, [
            'type' => 'required|in:' . implode(',', WebinarExtraDescription::$types),
            'value' => 'required',
            "{$columnName}" => 'required',
        ]);

        if ($validator->fails()) {
            return response([
                'code' => 422,
                'errors' => $validator->errors(),
            ], 422);
        }

        $canStore = $this->checkItem($user, $data);

        if ($canStore) {
            $locale = $request->get("locale", getDefaultLocale());
            $columnValue = $data[$columnName];

            $order = WebinarExtraDescription::query()
                    ->where($columnName, $columnValue)
                    ->where('type', $data['type'])
                    ->count() + 1;

            $webinarExtraDescription = WebinarExtraDescription::create([
                'creator_id' => $user->id,
                "{$columnName}" => $columnValue,
                'type' => $data['type'],
                'order' => $order,
                'created_at' => time()
            ]);

            if (!empty($webinarExtraDescription)) {
                WebinarExtraDescriptionTranslation::updateOrCreate([
                    'webinar_extra_description_id' => $webinarExtraDescription->id,
                    'locale' => mb_strtolower($locale),
                ], [
                    'value' => $data['value'],
                ]);
            }

            return response()->json([
                'code' => 200,
            ], 200);
        }

        abort(403);
    }

    public function storeCompanyLogos(Request $request, $columnName, $columnValue, $folder)
    {
        if (!empty($request->file('companyLogos'))) {
            $user = auth()->user();
            $locale = getDefaultLocale();

            foreach ($request->file('companyLogos') as $logo) {
                $tmpName = random_str(6) . "_" . time();

                $logoPath = $this->uploadFile($logo, "{$folder}/{$columnValue}", $tmpName, $user->id);

                $order = WebinarExtraDescription::query()
                        ->where($columnName, $columnValue)
                        ->where('type', 'company_logos')
                        ->count() + 1;

                $webinarExtraDescription = WebinarExtraDescription::create([
                    'creator_id' => $user->id,
                    "{$columnName}" => $columnValue,
                    'type' => 'company_logos',
                    'order' => $order,
                    'created_at' => time()
                ]);

                WebinarExtraDescriptionTranslation::updateOrCreate([
                    'webinar_extra_description_id' => $webinarExtraDescription->id,
                    'locale' => mb_strtolower($locale),
                ], [
                    'value' => $logoPath,
                ]);

            }
        }
    }

    private function checkItem($user, $data)
    {
        $canStore = false;

        if (!empty($data['webinar_id'])) {
            $webinar = Webinar::find($data['webinar_id']);

            if (!empty($webinar) and $webinar->canAccess($user)) {
                $canStore = true;
            }
        } elseif (!empty($data['upcoming_course_id'])) {
            $upcomingCourse = UpcomingCourse::find($data['upcoming_course_id']);

            if (!empty($upcomingCourse) and $upcomingCourse->canAccess($user)) {
                $canStore = true;
            }
        } elseif (!empty($data['event_id'])) {
            $event = Event::find($data['event_id']);

            if (!empty($event) and $event->creator_id == $user->id) {
                $canStore = true;
            }
        }

        return $canStore;
    }

    public function update(Request $request, $id)
    {
        $user = auth()->user();
        $data = $request->get('ajax')[$id];
        $columnName = $this->getItemColumnName($data);

        $validator = Validator::make($data, [
            'type' => 'required|in:' . implode(',', WebinarExtraDescription::$types),
            'value' => 'required',
            "{$columnName}" => 'required',
        ]);

        if ($validator->fails()) {
            return response([
                'code' => 422,
                'errors' => $validator->errors(),
            ], 422);
        }

        $canStore = $this->checkItem($user, $data);

        if ($canStore) {
            $locale = $request->get("locale", getDefaultLocale());
            $columnValue = $data[$columnName];

            $webinarExtraDescription = WebinarExtraDescription::where('id', $id)
                ->where(function ($query) use ($user, $columnName, $columnValue) {
                    $query->where('creator_id', $user->id);
                    $query->orWhere($columnName, $columnValue);
                })
                ->first();

            if (!empty($webinarExtraDescription)) {

                WebinarExtraDescriptionTranslation::updateOrCreate([
                    'webinar_extra_description_id' => $webinarExtraDescription->id,
                    'locale' => mb_strtolower($locale),
                ], [
                    'value' => $data['value'],
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
        $webinarExtraDescription = WebinarExtraDescription::where('id', $id)
            ->first();

        if (!empty($webinarExtraDescription)) {

            $data = [
                'webinar_id' => $webinarExtraDescription->webinar_id,
                'upcoming_course_id' => $webinarExtraDescription->upcoming_course_id,
                'event_id' => $webinarExtraDescription->event_id,
            ];

            $canAccess = $this->checkItem($user, $data);

            if ($webinarExtraDescription->creator_id == $user->id or $canAccess) {
                $filePath = null;

                if ($webinarExtraDescription->type == WebinarExtraDescription::$COMPANY_LOGOS) {
                    $filePath = $webinarExtraDescription->value;
                }

                $webinarExtraDescription->delete();


                if (!empty($filePath)) {
                    $this->removeFile($filePath);
                }

            }
        }

        return response()->json([
            'code' => 200
        ], 200);
    }
}
