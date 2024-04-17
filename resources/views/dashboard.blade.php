
@extends('layout')
@section('content')
<main class="h-full overflow-y-auto">
    <div class="container px-6 mx-auto grid">
        <h2 class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">
            Book List
        </h2>
        <div class="grid gap-6 mb-8 md:grid-cols-2 xl:grid-cols-5">
            <div class="flex items-center p-4 bg-white rounded-lg shadow-xs dark:bg-gray-800">
                <div class="p-3 mr-4 text-orange-500 bg-orange-100 rounded-full dark:text-orange-100 dark:bg-orange-500">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"></path>
                    </svg>
                </div>
                <div>
                    <p class="mb-2 text-sm font-medium text-gray-600 dark:text-gray-400">
                        Total Admin
                    </p>
                    <p class="text-lg font-semibold text-gray-700 dark:text-gray-200">
                    </p>
                </div>
            </div>
        </div>

        <div class="w-full overflow-hidden rounded-lg shadow-xs">
            <div class="w-full overflow-x-auto">
                <table class="w-full whitespace-no-wrap">
                    <thead>
                        <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                            <th class="px-4 py-3">Book Title</th>
                            <th class="px-4 py-3">Writer</th>
                            <th class="px-4 py-3">Publisher</th>
                            <th class="px-4 py-3 text-center">Publication Year</th>
                            <th class="px-4 py-3">Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                        <tr class="text-gray-700 dark:text-gray-400">
                            <td class="px-4 py-3">
                                <a href="" class="text-purple-600 hover:underline">
                                    <div class="flex items-center text-sm">
                                        <div class="relative hidden w-8 h-8 mr-3 rounded-full md:block">
                                            <img class="object-cover w-full h-full rounded-full" src="assets/img/book (1).png" alt="" loading="lazy" />
                                            <div class="absolute inset-0 rounded-full shadow-inner" aria-hidden="true"></div>
                                        </div>
                                        <div>
                                            <p class="font-semibold"></p>
                                            <p class="text-xs text-gray-600 dark:text-gray-400">
                                            </p>
                                        </div>
                                    </div>
                            </td>
                            <td class="px-4 py-3 text-sm"></td>
                            <td class="px-4 py-3 text-sm">
                            <td class="px-4 py-3 text-sm text-center"></td>
                            <td class="px-4 py-3 text-xs">
                                
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>
@endsection
