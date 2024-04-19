@extends('layout')
@section('content')

<main class="h-full pb-16 overflow-y-auto">
    <div class="container px-6 mx-auto grid">
        <h2 class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">
            Create Book List
        </h2>
        <a class="flex items-center justify-between p-4 mb-8 text-sm font-semibold text-purple-100 bg-purple-600 rounded-lg shadow-md focus:outline-none focus:shadow-outline-purple" href="https://github.com/estevanmaito/windmill-dashboard">
            <div class="flex items-center">
                <span>Create </span>
            </div>
        </a>

        <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
            <form action="{{ route('input.book') }}" method="POST" class="form">
                @csrf

                <label class="block text-sm">
                    <span class="text-gray-700 dark:text-gray-400">Book Title</span>
                    <input class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input" placeholder="Little Woman" name="title" />
                </label>

                <label class="block mt-4 text-sm">
                    <span class="text-gray-700 dark:text-gray-400">
                        Book Category
                    </span>
                    <select class="block w-full mt-1 text-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 form-select focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray" name="category_id">
                        <option value="">Choose Category</option>
                        @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </label>

                <label class="block mt-4 text-sm">
                    <span class="text-gray-700 dark:text-gray-400">Author</span>
                    <input class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input" placeholder="John Doe" name="writer" />
                </label>

                <label class="block mt-4 text-sm">
                    <span class="text-gray-700 dark:text-gray-400">Publisher</span>
                    <input class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input" placeholder="Gramedia" name="publisher" />
                </label>

                <label class="block mt-4 text-sm">
                    <span class="text-gray-700 dark:text-gray-400">Publication Year
                    </span>
                    <input type="number" class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input" placeholder="2022" name="pubyear" />
                </label>

                <label class="block mt-4 text-sm">
                    <span class="text-gray-700 dark:text-gray-400">Stock</span>
                    <input type="number" class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input" placeholder="100" name="stock" />
                </label>
                

                @csrf
                <div class="flex justify-end">
                    <button type="submit" class="block px-4 py-2 mt-4 text-sm font-medium leading-5 text-center text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple sm:px-4 sm:py-2 sm:w-auto">
                        Create Book
                    </button>
                    <a href="/book" class="block px-4 py-2 mt-4 ml-2 text-sm font-medium leading-5 text-white text-gray-700 transition-colors duration-150 border border-gray-300 rounded-lg dark:text-gray-400 sm:px-4 sm:py-2 sm:w-auto active:bg-transparent hover:border-gray-500 focus:border-gray-500 active:text-gray-500 focus:outline-none focus:shadow-outline-gray">
                        Cancel
                    </a>
                </div>
            </form>        
    </div>
</main>
@endsection
