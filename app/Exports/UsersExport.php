<?php

namespace App\Exports;

use App\Models\User;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class UsersExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $users = User::all();

        $exportData = new Collection();
        foreach ($users as $user) {
            $exportData->push([
                'Nama' => $user->name,
                'Email' => $user->email,
                'Alamat' => $user->address,
                'Role' => $user->role,
            ]);
        }

        return $exportData;
    }

    public function headings(): array
    {
        return [
            'Nama',
            'Email',
            'Alamat',
            'Role',
        ];
    }
}
