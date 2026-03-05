<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class AttendanceHistoryDetailsExport implements FromCollection, WithHeadings, WithMapping
{
    protected $students;

    public function __construct($students)
    {
        $this->students = $students;
    }

    /**
     * @return Collection
     */
    public function collection()
    {
        return $this->students;
    }

    /**
     * @inheritDoc
     */
    public function headings(): array
    {
        return [
            trans('admin/main.title'),
            trans('update.joined_date'),
            trans('update.attendance_status'),
        ];
    }

    /**
     * @inheritDoc
     */
    public function map($student): array
    {
        $status = "absent";

        if (!empty($student->attendance)) {
            $status = $student->attendance->status;
        }

        return [
            $student->full_name,
            !empty($student->joined_at) ? dateTimeFormat($student->joined_at, 'j M Y H:i') : '-',
            trans("update.{$status}"),
        ];
    }
}
