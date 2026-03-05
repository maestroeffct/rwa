<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ForumRecommendedTopic;
use App\Models\ForumRecommendedTopicItem;
use App\Models\Translation\ForumRecommendedTopicTranslation;
use Illuminate\Http\Request;

class RecommendedTopicsController extends Controller
{
    public function index()
    {
        $this->authorize('admin_recommended_topics_list');
        removeContentLocale();

        $recommendedTopics = ForumRecommendedTopic::orderBy('created_at', 'desc')
            ->with([
                'topics'
            ])
            ->paginate(10);

        $data = [
            'pageTitle' => trans('update.recommended_topics'),
            'recommendedTopics' => $recommendedTopics
        ];

        return view('admin.forums.recommended_topics.lists', $data);
    }

    public function create()
    {
        $this->authorize('admin_recommended_topics_create');

        removeContentLocale();

        $data = [
            'pageTitle' => trans('update.new_recommended_topic'),
        ];

        return view('admin.forums.recommended_topics.create', $data);
    }

    public function store(Request $request)
    {
        $this->authorize('admin_recommended_topics_create');

        $this->validate($request, [
            'topic_ids' => 'required|array|min:1',
            'title' => 'required|max:255',
            'subtitle' => 'required|string',
            'icon' => 'required|max:255',
        ]);

        $storeData = $this->handleStoreData($request);
        $recommended = ForumRecommendedTopic::query()->create($storeData);

        $this->handleExtraData($request, $recommended);

        return redirect(getAdminPanelUrl("/recommended-topics"));
    }

    public function edit(Request $request, $id)
    {
        $this->authorize('admin_recommended_topics_edit');

        $recommended = ForumRecommendedTopic::where('id', $id)
            ->with([
                'topics'
            ])
            ->first();

        if (!empty($recommended)) {

            $locale = $request->get('locale', app()->getLocale());
            storeContentLocale($locale, $recommended->getTable(), $recommended->id);

            $data = [
                'pageTitle' => trans('update.edit_recommended_topic'),
                'recommended' => $recommended,
                'locale' => $locale,
            ];

            return view('admin.forums.recommended_topics.create', $data);
        }

        abort(404);
    }

    public function update(Request $request, $id)
    {
        $this->authorize('admin_recommended_topics_edit');

        $this->validate($request, [
            'topic_ids' => 'required|array|min:1',
            'title' => 'required|max:255',
            'subtitle' => 'required|string',
            'icon' => 'required|max:255',
        ]);

        $recommended = ForumRecommendedTopic::findOrFail($id);

        $storeData = $this->handleStoreData($request, $recommended);
        $recommended->update($storeData);

        $this->handleExtraData($request, $recommended);

        return redirect(getAdminPanelUrl("/recommended-topics"));
    }

    public function destroy($id)
    {
        $this->authorize('admin_recommended_topics_delete');

        $recommended = ForumRecommendedTopic::findOrFail($id);

        $recommended->delete();

        return back();
    }

    private function handleStoreData(Request $request, $recommended = null)
    {
        $data = $request->all();

        return [
            'icon' => $data['icon'],
            'created_at' => !empty($recommended) ? $recommended->created_at : time(),
        ];
    }

    private function handleExtraData(Request $request, $recommended)
    {
        $data = $request->all();

        ForumRecommendedTopicTranslation::query()->updateOrCreate([
            'forum_recommended_topic_id' => $recommended->id,
            'locale' => mb_strtolower($data['locale']),
        ], [
            'title' => $data['title'],
            'subtitle' => $data['subtitle'],
        ]);

        $topicIds = $data['topic_ids'];
        ForumRecommendedTopicItem::where('recommended_topic_id', $recommended->id)
            ->delete();

        if (!empty($topicIds)) {
            foreach ($topicIds as $topicId) {
                ForumRecommendedTopicItem::create([
                    'recommended_topic_id' => $recommended->id,
                    'topic_id' => $topicId,
                    'created_at' => time(),
                ]);
            }
        }
    }


}
