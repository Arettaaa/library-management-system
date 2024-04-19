<?php

namespace App\Exports;

use App\Models\Borrow;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class BorrowsExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $borrows = Borrow::all();

        $exportData = new Collection();
        foreach ($borrows as $borrow) {
            $exportData->push([
                'Peminjam' => $borrow->user->name,
                'Buku' => $borrow->book->title,
                'Tanggal Pinjam' => $borrow->tanggal_peminjaman,
                'Tanggal Kembali' => $borrow->tanggal_pengembalian,
                'Status' => $borrow->status,
            ]);
        }

        return $exportData;
    }

    public function headings(): array
    {
        return [
            'Peminjam',
            'Buku',
            'Tanggal Pinjam',
            'Tanggal Kembali',
            'Status',
        ];
    }
}
