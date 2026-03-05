<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class AttendanceHistoriesExport implements FromCollection, WithHeadings, WithMapping
{
    protected $sessions;

    public function __construct($sessions)
    {
        $this->sessions = $sessions;
    }

    /**
     * @return Collection
     */
    public function collection()
    {
        return $this->sessions;
    }

    /**
     * @inheritDoc
     */
    public function headings(): array
    {
        return [
            trans('admin/main.title'),
            trans('admin/main.course_title'),
            trans('admin/main.instructor'),
            trans('admin/main.start_date'),
            trans('update.session_api'),
            trans('update.students_count'),
            trans('update.present'),
            trans('update.late'),
            trans('update.absent'),
        ];
    }

    /**
     * @inheritDoc
     */
    public function map($session): array
    {

        return [
            $session->title,
            $session->webinar->title,
            $session->creator->full_name,
            dateTimeFormat($session->date, 'j M Y H:i'),
            trans("update.session_api_{$session->session_api}"),
            $session->total_students ?? 0,
            $session->present_count ?? 0,
            $session->late_count ?? 0,
            $session->absent_count ?? 0,
        ];
    }
}
