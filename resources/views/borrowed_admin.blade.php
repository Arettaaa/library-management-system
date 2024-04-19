@extends('layout')
@section('content')
<main class="h-full overflow-y-auto">
    <div class="container px-6 mx-auto grid">
        <h2 class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">
            Book Borrowed
        </h2>
        <div class="grid gap-6 mb-8 md:grid-cols-2 xl:grid-cols-5">
            <div class="flex items-center p-4 bg-white rounded-lg shadow-xs dark:bg-gray-800">
                <div class="p-3 mr-4 text-orange-500 bg-orange-100 rounded-full dark:text-orange-100 dark:bg-orange-500">
                    <svg class="w-5 h-5  inline-block" fill="currentColor" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                        <path d="M96 0C43 0 0 43 0 96V416c0 53 43 96 96 96H384h32c17.7 0 32-14.3 32-32s-14.3-32-32-32V384c17.7 0 32-14.3 32-32V32c0-17.7-14.3-32-32-32H384 96zm0 384H352v64H96c-17.7 0-32-14.3-32-32s14.3-32 32-32zm32-240c0-8.8 7.2-16 16-16H336c8.8 0 16 7.2 16 16s-7.2 16-16 16H144c-8.8 0-16-7.2-16-16zm16 48H336c8.8 0 16 7.2 16 16s-7.2 16-16 16H144c-8.8 0-16-7.2-16-16s7.2-16 16-16z" />
                    </svg>
                </div>
                <div>
                    <p class="mb-2 text-sm font-medium text-gray-600 dark:text-gray-400">
                        History Book
                    </p>
                    <p class="text-lg font-semibold text-gray-700 dark:text-gray-200">
                        {{ $books->count() }}
                    </p>
                </div>
            </div>
        </div>

        <h4 class="mb-4 text-lg font-semibold flex justify-between items-center text-gray-600 dark:text-gray-300">
            <span>Book List</span>
            <div class="flex justify-between">
                <a href="{{ route('borrows.export.pdf') }}" class="mr-2 px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                    Export PDF
                </a>
                <button class="px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                    <a href="{{ route('export.borrows') }}" class="text-white">Export Excel</a>
                </button>
            </div>
        </h4>

        <div class="w-full overflow-hidden rounded-lg shadow-xs">
            <div class="w-full overflow-x-auto">
                <table class="w-full whitespace-no-wrap">
                    <thead>
                        <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                            <th class="px-4 py-3">Peminjam</th>
                            <th class="px-4 py-3">Buku</th>
                            <th class="px-4 py-3">Tanggal Pinjam</th>
                            <th class="px-4 py-3">Tanggal Kembali</th>
                            <th class="px-4 py-3">Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                        @foreach($books as $borrow)
                        <tr class="text-gray-700 dark:text-gray-400">
                            <td class="px-4 py-3">
                                <div class="font-semibold">{{ optional($borrow->user)->name }}</div>
                                <div class="text-xs text-gray-600 dark:text-gray-400">{{ optional($borrow->user)->username }}</div>
                            </td>
                            <td class="px-4 py-3 text-sm">
                                {{ $borrow->book->title }}
                            </td>
                            <td class="px-4 py-3">{{ $borrow->tanggal_peminjaman }}</td>
                            <td class="px-4 py-3">{{ $borrow->tanggal_pengembalian }}</td>
                            <td class="px-4 py-3 text-xs">
                                <span class="px-2 py-1 font-semibold leading-tight text-red-700 bg-red-100 rounded-full dark:bg-red-700 dark:text-red-100">
                                    {{ $borrow->status }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>
@endsection
