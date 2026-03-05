<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class UsersExport implements FromCollection, WithHeadings, WithMapping
{
    protected $users;

    public function __construct($users)
    {
        $this->users = $users;
    }

    /**
     * @return Collection
     */
    public function collection()
    {
        return $this->users;
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            trans('admin/main.id'),
            trans('admin/main.name'),
            trans('admin/main.role_name'),
            trans('admin/main.email'),
            trans('admin/main.mobile'),
            trans('admin/main.status'),
            trans('admin/main.verified'),
            trans('admin/main.created_at'),
        ];
    }

    /**
     * @param mixed $user
     * @return array
     */
    public function map($user): array
    {
        return [
            $user->id,
            $user->full_name,
            $user->role->caption,
            $user->email,
            $user->mobile,
            $user->status,
            ($user->verified ? trans('admin/main.yes') : trans('admin/main.no')),
            dateTimeFormat($user->created_at, 'Y/m/d'),
        ];
    }
}
