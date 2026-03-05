<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Mixins\Certificate\MakeCertificate;
use App\Models\Certificate;
use App\Models\Quiz;
use App\Models\Webinar;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class GeneratedCertificatesController extends Controller
{

    public function index(Request $request, $type = null, $typeItemId = null)
    {
        $user = auth()->user();

        if (!$user->isUser()) {
            $query = $this->getCertificateQuery($user, $type, $typeItemId);
            $query = $this->handleFilters($request, $query);

            $pageListData = $this->getPageListData($request, $query);

            if ($request->ajax()) {
                return $pageListData;
            }

            $topStats = $this->handleTopStats($user);
            $mostActiveCourses = $this->getMostActiveCourses($user);

            $breadcrumbs = [
                ['text' => trans('update.platform'), 'url' => '/'],
                ['text' => trans('panel.dashboard'), 'url' => '/panel'],
                ['text' => trans('panel.certificates'), 'url' => null],
            ];

            $data = [
                'pageTitle' => trans('panel.certificates'),
                'breadcrumbs' => $breadcrumbs,
                'mostActiveCourses' => $mostActiveCourses,
                ...$topStats,
                ...$pageListData,
            ];

            return view('design_1.panel.certificates.students.index', $data);
        }

        abort(403);
    }

    public function getCertificateQuery($user, $type = null, $typeItemId = null): Builder
    {
        return Certificate::query()
            ->where(function (Builder $query) use ($user, $type, $typeItemId) {

                if (empty($type) or $type == "quiz") {
                    $query->whereHas('quiz', function (Builder $query) use ($user, $type, $typeItemId) {
                        $query->where('creator_id', $user->id)
                            ->where('status', Quiz::ACTIVE);

                        if ($type == "quiz") {
                            $query->where('id', $typeItemId);
                        }
                    });
                }

                if (empty($type) or $type == "courses") {
                    $query->orWhereHas('webinar', function (Builder $query) use ($user, $type, $typeItemId) {
                        $query->where('status', 'active')
                            ->where('certificate', true)
                            ->where(function (Builder $query) use ($user, $type, $typeItemId) {
                                $query->where('creator_id', $user->id);
                                $query->orWhere('teacher_id', $user->id);
                            });

                        if ($type == "courses") {
                            $query->where('id', $typeItemId);
                        }
                    });
                }

                if (empty($type) or $type == "bundles") {
                    $query->orWhereHas('bundle', function (Builder $query) use ($user, $type, $typeItemId) {
                        $query->where('status', 'active')
                            ->where('certificate', true)
                            ->where(function (Builder $query) use ($user, $type, $typeItemId) {
                                $query->where('creator_id', $user->id);
                                $query->orWhere('teacher_id', $user->id);
                            });

                        if ($type == "bundles") {
                            $query->where('id', $typeItemId);
                        }
                    });
                }
            });
    }

    private function handleTopStats($user): array
    {
        $query = $this->getCertificateQuery($user);

        $totalGeneratedCertificatesCount = deepClone($query)->count();
        $quizCertificatesCount = deepClone($query)->where('type', 'quiz')->count();
        $completionCertificatesCount = deepClone($query)->whereIn('type', ['course', 'bundle'])->count();
        $totalStudentsCount = deepClone($query)
            ->groupBy('student_id')
            ->get()
            ->count();

        return [
            'totalGeneratedCertificatesCount' => $totalGeneratedCertificatesCount,
            'quizCertificatesCount' => $quizCertificatesCount,
            'completionCertificatesCount' => $completionCertificatesCount,
            'totalStudentsCount' => $totalStudentsCount,
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

        $certificates = $query
            ->with([
                'quiz',
                'student',
                'quizzesResult',
                'webinar',
                'bundle',
            ])
            ->get();

        if ($request->ajax()) {
            return $this->handleAjaxResponse($request, $certificates, $total, $count, $source);
        }

        return [
            'certificates' => $certificates,
            'pagination' => $this->makePagination($request, $certificates, $total, $count, true),
        ];
    }

    private function handleAjaxResponse(Request $request, $certificates, $total, $count, $source)
    {
        $html = "";

        foreach ($certificates as $certificate) {
            $html .= (string)view()->make('design_1.panel.certificates.students.item_table', ['certificate' => $certificate]);
        }

        return response()->json([
            'data' => $html,
            'pagination' => $this->makePagination($request, $certificates, $total, $count, true)
        ]);
    }

    private function getMostActiveCourses($user)
    {

        return null;
    }


    public function download($certificateId)
    {
        $user = auth()->user();

        if (!$user->isUser()) {
            $query = $this->getCertificateQuery($user);
            $query->where('id', $certificateId);
            $certificate = $query->first();

            if (!empty($certificate)) {
                $makeCertificate = new MakeCertificate();
                return $makeCertificate->showCertificateByType($certificate);
            }
        }

        abort(403);
    }

}
