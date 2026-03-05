<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Models\Webinar;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class CertificatesListsController extends Controller
{

    public function index(Request $request)
    {
        $user = auth()->user();

        if (!$user->isUser()) {
            $source = $request->query('source', 'quiz');
            $query = $this->getQueryBySource($user, $source);
            $query = $this->handleFilters($request, $query);

            $pageListData = $this->getPageListData($request, $query, $source);

            if ($request->ajax()) {
                return $pageListData;
            }

            $topStats = $this->handleTopStats($user);
            $recentStudentCertificates = $this->getRecentStudentCertificates($user);

            $breadcrumbs = [
                ['text' => trans('update.platform'), 'url' => '/'],
                ['text' => trans('panel.dashboard'), 'url' => '/panel'],
                ['text' => trans('panel.certificates'), 'url' => null],
            ];

            $data = [
                'pageTitle' => trans('panel.certificates'),
                'breadcrumbs' => $breadcrumbs,
                'recentStudentCertificates' => $recentStudentCertificates,
                ...$topStats,
                ...$pageListData,
            ];

            return view('design_1.panel.certificates.lists.index', $data);
        }

        abort(403);
    }

    private function getQueryBySource($user, $source): Builder
    {
        if ($source == 'quiz') {
            $query = Quiz::query()->where('creator_id', $user->id)
                ->where('status', Quiz::ACTIVE);
        } else {
            $query = Webinar::query()->where('status', 'active')
                ->where('certificate', true)
                ->where(function (Builder $query) use ($user) {
                    $query->where('creator_id', $user->id);
                    $query->orWhere('teacher_id', $user->id);
                });
        }

        return $query;
    }

    private function handleTopStats($user): array
    {
        $quizQuery = Quiz::query()->where('creator_id', $user->id)
            ->where('status', Quiz::ACTIVE);

        $courseQuery = Webinar::query()->where('status', 'active')
            ->where('certificate', true)
            ->where(function (Builder $query) use ($user) {
                $query->where('creator_id', $user->id);
                $query->orWhere('teacher_id', $user->id);
            });

        $totalCertificatesCount = deepClone($quizQuery)->count() + deepClone($courseQuery)->count();

        $quizCertificatesCount = deepClone($quizQuery)->count();
        $completionCertificatesCount = deepClone($courseQuery)->count();

        $generatedCertificatesController = new GeneratedCertificatesController();
        $generatedQuery = $generatedCertificatesController->getCertificateQuery($user);

        $totalGeneratedCertificatesCount = deepClone($generatedQuery)->count();

        return [
            'totalCertificatesCount' => $totalCertificatesCount,
            'quizCertificatesCount' => $quizCertificatesCount,
            'completionCertificatesCount' => $completionCertificatesCount,
            'totalGeneratedCertificatesCount' => $totalGeneratedCertificatesCount,
        ];
    }

    private function handleFilters(Request $request, Builder $query): Builder
    {

        return $query;
    }

    private function getPageListData(Request $request, Builder $query, $source = "quiz")
    {
        $page = $request->get('page') ?? 1;
        $count = $this->perPage;

        $total = $query->count();

        $query->limit($count);
        $query->offset(($page - 1) * $count);

        if ($source == 'quiz') {
            $certificatesItems = $query
                ->with([
                    'webinar',
                    'certificates' => function ($query) {
                        $query->orderBy('created_at', 'desc');
                    },
                    /*'quizResults' => function ($query) {
                        $query->orderBy('id', 'desc');
                    },*/
                ])
                ->get();

        } else {
            $certificatesItems = $query
                ->with([
                    'certificates' => function ($query) {
                        $query->orderBy('created_at', 'desc');
                    }
                ])
                ->get();
        }

        if ($request->ajax()) {
            return $this->handleAjaxResponse($request, $certificatesItems, $total, $count, $source);
        }

        return [
            'certificatesItems' => $certificatesItems,
            'pagination' => $this->makePagination($request, $certificatesItems, $total, $count, true),
        ];
    }

    private function handleAjaxResponse(Request $request, $certificatesItems, $total, $count, $source)
    {
        $html = "";

        foreach ($certificatesItems as $certificateItem) {
            if ($source == 'quiz') {
                $html .= (string)view()->make('design_1.panel.certificates.lists.quiz_item_table', ['quizItem' => $certificateItem]);
            } else {
                $html .= (string)view()->make('design_1.panel.certificates.lists.course_item_table', ['course' => $certificateItem]);
            }
        }

        return response()->json([
            'data' => $html,
            'pagination' => $this->makePagination($request, $certificatesItems, $total, $count, true)
        ]);
    }

    private function getRecentStudentCertificates($user)
    {
        $generatedCertificatesController = new GeneratedCertificatesController();
        $query = $generatedCertificatesController->getCertificateQuery($user);
        $query->orderBy('created_at', 'desc');
        $query->limit(4);
        $query->with([
            'quiz',
            'student',
            'webinar',
        ]);

        return $query->get();
    }
}
