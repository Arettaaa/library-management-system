<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class BooksExport implements FromCollection, WithHeadings
{
    protected $books;

    public function __construct(Collection $books)
    {
        $this->books = $books;
    }

    public function collection()
    {
        return $this->books->map(function ($book) {
            return [
                'Book Title' => $book->title,
                'Category' => $book->category->name ?? '', 
                'Writer' => $book->writer,
                'Publisher' => $book->publisher,
                'Publication Year' => $book->pubyear,
                'Stock' => $book->stock,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Book Title',
            'Category',
            'Writer',
            'Publisher',
            'Publication Year',
            'Stock',
        ];
    }
}
