<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class BulkImportsHistoriesExport implements FromCollection, WithHeadings, WithMapping
{
    protected $imports;

    public function __construct($imports)
    {
        $this->imports = $imports;
    }

    /**
     * @return Collection
     */
    public function collection()
    {
        return $this->imports;
    }

    /**
     * @inheritDoc
     */
    public function headings(): array
    {
        return [
            trans('admin/main.user'),
            trans('update.data_type'),
            trans('update.total_records'),
            trans('update.valid_records'),
            trans('update.invalid_records'),
            trans('update.import_date'),
        ];
    }

    /**
     * @inheritDoc
     */
    public function map($import): array
    {

        return [
            $import->user->full_name,
            trans("update.{$import->data_type}"),
            $import->valid_items + $import->invalid_items,
            $import->valid_items,
            $import->invalid_items,
            dateTimeFormat($import->created_at, 'j M Y H:i'),
        ];
    }
}
