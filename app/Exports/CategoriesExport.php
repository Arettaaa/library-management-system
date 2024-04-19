<?php

namespace App\Exports;

use App\Models\Category;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CategoriesExport implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $categories = Category::select('name')->get();

        $exportData = [];

        $nomor = 1;
        foreach ($categories as $category) {
            $exportData[] = [
                'No.' => $nomor,
                'Name' => $category->name,
            ];
            $nomor++;
        }

        return collect($exportData);
    }

    public function headings(): array
    {
        return [
            'No.',
            'Name',
        ];
    }
}
