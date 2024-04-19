@extends('layout')
@section('content')
    <main class="h-full overflow-y-auto">
        <div class="container px-6 mx-auto grid">
            <h2 class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">
                Search Results
            </h2>
            @if($books->isEmpty())
                <p class="text-gray-600 dark:text-gray-400">No books found for your search query.</p>
            @else
            <div class="container px-6 mx-auto mt-6">
                <h4 class="mb-4 text-lg font-semibold flex justify-between items-center text-gray-600 dark:text-gray-300">
                    <span>Book List</span>
                    <div class="flex justify-between">
                        <button class="mr-2 px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                            <a href="{{ route('books.export.pdf') }}" class="text-white">Export PDF</a>
                        </button>
                        <button class="px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                            <a href="{{ route('export.books', ['cari' => $cari]) }}" class="text-white">Export Excel</a>
                        </button>
                    </div>
                </h4>
                <div class="w-full overflow-hidden rounded-lg shadow-xs">
                    <div class="w-full overflow-x-auto">
                        <table class="w-full whitespace-no-wrap">
                            <thead>
                                <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                                    <th class="px-4 py-3">Book Title</th>
                                    <th class="px-4 py-3">Writer</th>
                                    <th class="px-4 py-3">Publisher</th>
                                    <th class="px-4 py-3">Publication Year</th>
                                    <th class="px-4 py-3">Stock</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                                @foreach($books as $book)
                                <tr class="text-gray-700 dark:text-gray-400">
                                    <td class="px-4 py-3">
                                        <div class="flex items-center text-sm">
                                            <div class="relative hidden w-8 h-8 mr-3 rounded-full md:block">
                                                <img class="object-cover w-full h-full rounded-full" src="assets/img/book (1).png" alt="" loading="lazy" />
                                                <div class="absolute inset-0 rounded-full shadow-inner" aria-hidden="true"></div>
                                            </div>
                                            <div>
                                                <p class="font-semibold">{{ $book->title }}</p>
                                                <p class="text-xs text-gray-600 dark:text-gray-400">
                                                    {{ optional($book->category)->name }}
                                                </p>
    
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 text-sm">{{ $book->writer }}</td>
                                    <td class="px-4 py-3 text-sm">{{ $book->category->name }}</td>
                                    <td class="px-4 py-3 text-sm">{{ $book->publisher }}
                                    <td class="px-4 py-3 text-sm">{{ $book->pubyear}}</td>
                                    <td class="px-4 py-3 text-sm">{{ $book->stock}}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </main>

@endsection
