<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Bundle;
use App\Models\Faq;
use App\Models\Event;
use App\Models\Translation\FaqTranslation;
use App\Models\UpcomingCourse;
use App\Models\Webinar;
use Illuminate\Http\Request;
use Validator;

class FAQController extends Controller
{
    private function getItemColumnName($data)
    {
        $columnName = "webinar_id";

        if (!empty(!empty($data['bundle_id']))) {
            $columnName = "bundle_id";
        } else if (!empty(!empty($data['upcoming_course_id']))) {
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
            "{$columnName}" => 'required',
            'title' => 'required|max:255',
            'answer' => 'required',
        ]);

        if ($validator->fails()) {
            return response([
                'code' => 422,
                'errors' => $validator->errors(),
            ], 422);
        }

        $canStore = $this->checkItem($user, $data);

        if ($canStore) {
            $columnValue = $data[$columnName];

            $order = Faq::query()
                    ->where(function ($query) use ($user, $columnName, $columnValue) {
                        $query->where('creator_id', $user->id);
                        $query->orWhere($columnName, $columnValue);
                    })
                    ->count() + 1;

            $faq = Faq::create([
                'creator_id' => $user->id,
                "{$columnName}" => $columnValue,
                'order' => $order,
                'created_at' => time()
            ]);

            $locale = $request->get("locale", getDefaultLocale());

            if (!empty($faq)) {
                FaqTranslation::updateOrCreate([
                    'faq_id' => $faq->id,
                    'locale' => mb_strtolower($locale),
                ], [
                    'title' => $data['title'],
                    'answer' => $data['answer'],
                ]);
            }

            return response()->json([
                'code' => 200,
            ], 200);
        }

        abort(403);
    }

    private function checkItem($user, $data)
    {
        $canStore = false;

        if (!empty($data['webinar_id'])) {
            $webinar = Webinar::find($data['webinar_id']);

            if (!empty($webinar) and $webinar->canAccess($user)) {
                $canStore = true;
            }
        } elseif (!empty($data['bundle_id'])) {
            $bundle = Bundle::find($data['bundle_id']);

            if (!empty($bundle) and $bundle->canAccess($user)) {
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
            "{$columnName}" => 'required',
            'title' => 'required|max:255',
            'answer' => 'required',
        ]);

        if ($validator->fails()) {
            return response([
                'code' => 422,
                'errors' => $validator->errors(),
            ], 422);
        }

        $canStore = $this->checkItem($user, $data);

        if ($canStore) {
            $columnValue = $data[$columnName];

            $faq = Faq::where('id', $id)
                ->where(function ($query) use ($user, $columnName, $columnValue) {
                    $query->where('creator_id', $user->id);
                    $query->orWhere($columnName, $columnValue);
                })
                ->first();

            if (!empty($faq)) {
                $faq->update([
                    'updated_at' => time()
                ]);

                $locale = $request->get("locale", getDefaultLocale());

                FaqTranslation::updateOrCreate([
                    'faq_id' => $faq->id,
                    'locale' => mb_strtolower($locale),
                ], [
                    'title' => $data['title'],
                    'answer' => $data['answer'],
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
        $faq = Faq::where('id', $id)
            ->first();

        if (!empty($faq)) {
            $data = [
                'webinar_id' => $faq->webinar_id,
                'bundle_id' => $faq->bundle_id,
                'upcoming_course_id' => $faq->upcoming_course_id,
                'event_id' => $faq->event_id,
            ];

            $canAccess = $this->checkItem($user, $data);

            if ($faq->creator_id == $user->id or $canAccess) {
                $faq->delete();
            }
        }

        return response()->json([
            'code' => 200
        ], 200);
    }
}
