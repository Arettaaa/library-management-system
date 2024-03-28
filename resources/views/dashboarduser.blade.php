@extends('layout')
@section('content')
<main class="h-full overflow-y-auto">
    <div class="container px-6 mx-auto grid">
        <h2 class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">
            Dashboard
        </h2>
        <div class="grid gap-6 mb-8 md:grid-cols-2 xl:grid-cols-4">
            <div class="flex items-center p-4 bg-white rounded-lg shadow-xs dark:bg-gray-800">
                <div class="p-3 mr-4 text-orange-500 bg-orange-100 rounded-full dark:text-orange-100 dark:bg-orange-500">
                    <svg class="w-5 h-5  inline-block" fill="currentColor" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                        <path d="M96 0C43 0 0 43 0 96V416c0 53 43 96 96 96H384h32c17.7 0 32-14.3 32-32s-14.3-32-32-32V384c17.7 0 32-14.3 32-32V32c0-17.7-14.3-32-32-32H384 96zm0 384H352v64H96c-17.7 0-32-14.3-32-32s14.3-32 32-32zm32-240c0-8.8 7.2-16 16-16H336c8.8 0 16 7.2 16 16s-7.2 16-16 16H144c-8.8 0-16-7.2-16-16zm16 48H336c8.8 0 16 7.2 16 16s-7.2 16-16 16H144c-8.8 0-16-7.2-16-16s7.2-16 16-16z" />
                    </svg>
                </div>
                <div>
                    <p class="mb-2 text-sm font-medium text-gray-600 dark:text-gray-400">
                        Total Book
                    </p>
                    @foreach($books as $book) @endforeach
                    <p class="text-lg font-semibold text-gray-700 dark:text-gray-200">
                        {{ $books->where('status', '!=', 'borrowed')->count() }}
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
                            <th class="px-4 py-3">Publication Year</th>
                            <th class="px-4 py-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                        @foreach($books as $book)
                        @if($book->status !== 'borrowed')
                        <tr class="text-gray-700 dark:text-gray-400">
                            <td class="px-4 py-3">
                                <a href="{{ route('book.show', $book->id) }}" class="text-purple-600 hover:underline">
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
                                </a>
                            </td>
                            <td class="px-4 py-3 text-sm">{{ $book->writer }}</td>
                            <td class="px-4 py-3 text-sm">{{ $book->publisher }}
                            <td class="px-4 py-3 text-sm">{{ $book->pubyear}}</td>
                            <td class="px-4 py-3">
                                <div class="flex items-center space-x-4 text-sm">
                                    <form action="{{ route('borrow.book', $book->id) }}" method="POST">
                                        @csrf
                                        <div class="flex items-center justify-center h-full mt-4">
                                            <button type="submit" class="flex items-center px-2 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple mr-2">
                                                <svg class="w-4 h-4 mr-1 -ml-2 inline-block" fill="currentColor" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512">
                                                    <path d="M352 224H305.5c-45 0-81.5 36.5-81.5 81.5c0 22.3 10.3 34.3 19.2 40.5c6.8 4.7 12.8 12 12.8 20.3c0 9.8-8 17.8-17.8 17.8h-2.5c-2.4 0-4.8-.4-7.1-1.4C210.8 374.8 128 333.4 128 240c0-79.5 64.5-144 144-144h80V34.7C352 15.5 367.5 0 386.7 0c8.6 0 16.8 3.2 23.2 8.9L548.1 133.3c7.6 6.8 11.9 16.5 11.9 26.7s-4.3 19.9-11.9 26.7l-139 125.1c-5.9 5.3-13.5 8.2-21.4 8.2H384c-17.7 0-32-14.3-32-32V224zM80 96c-8.8 0-16 7.2-16 16V432c0 8.8 7.2 16 16 16H400c8.8 0 16-7.2 16-16V384c0-17.7 14.3-32 32-32s32 14.3 32 32v48c0 44.2-35.8 80-80 80H80c-44.2 0-80-35.8-80-80V112C0 67.8 35.8 32 80 32h48c17.7 0 32 14.3 32 32s-14.3 32-32 32H80z" />
                                                </svg>
                                                Borrow
                                            </button>
                                        </div>
                                    </form>

                                    <div class="flex items-center space-x-4 text-sm">
                                        @if($book->isInCollection(auth()->id()))
                                        <button type="button" class="px-4 py-2 text-sm font-medium leading-5 text-red-500 bg-red-100 border border-transparent rounded-lg cursor-not-allowed">
                                            <svg class="w-4 h-4 mr-1 -ml-2 inline-block" fill="currentColor" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512">
                                                <path d="M217.9 105.9L340.7 228.7c7.2 7.2 11.3 17.1 11.3 27.3s-4.1 20.1-11.3 27.3L217.9 406.1c-6.4 6.4-15 9.9-24 9.9c-18.7 0-33.9-15.2-33.9-33.9l0-62.1L32 320c-17.7 0-32-14.3-32-32l0-64c0-17.7 14.3-32 32-32l128 0 0-62.1c0-18.7 15.2-33.9 33.9-33.9c9 0 17.6 3.6 24 9.9zM352 416l64 0c17.7 0 32-14.3 32-32l0-256c0-17.7-14.3-32-32-32l-64 0c-17.7 0-32-14.3-32-32s14.3-32 32-32l64 0c53 0 96 43 96 96l0 256c0 53-43 96-96 96l-64 0c-17.7 0-32-14.3-32-32s14.3-32 32-32z" /></svg>
                                            Colection
                                        </button>
                                        @else
                                        <form action="{{ route('add.to.collection', $book->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                                                <svg class="w-4 h-4 mr-1 -ml-2 inline-block" fill="currentColor" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512">
                                                    <path d="M217.9 105.9L340.7 228.7c7.2 7.2 11.3 17.1 11.3 27.3s-4.1 20.1-11.3 27.3L217.9 406.1c-6.4 6.4-15 9.9-24 9.9c-18.7 0-33.9-15.2-33.9-33.9l0-62.1L32 320c-17.7 0-32-14.3-32-32l0-64c0-17.7 14.3-32 32-32l128 0 0-62.1c0-18.7 15.2-33.9 33.9-33.9c9 0 17.6 3.6 24 9.9zM352 416l64 0c17.7 0 32-14.3 32-32l0-256c0-17.7-14.3-32-32-32l-64 0c-17.7 0-32-14.3-32-32s14.3-32 32-32l64 0c53 0 96 43 96 96l0 256c0 53-43 96-96 96l-64 0c-17.7 0-32-14.3-32-32s14.3-32 32-32z" /></svg>
                                                </svg>
                                                Collection
                                            </button>
                                        </form>
                                        @endif
                                    </div>

                                    </form>

                                    {{-- @if($book->review()->exists())
                                    <button @click="openModal({{ $book->id }})" class="flex items-center px-2 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple mr-2">
                                        <svg class="w-4 h-4 mr-1 -ml-2 inline-block" fill="currentColor" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512">
                                            <path d="M217.9 105.9L340.7 228.7c7.2 7.2 11.3 17.1 11.3 27.3s-4.1 20.1-11.3 27.3L217.9 406.1c-6.4 6.4-15 9.9-24 9.9c-18.7 0-33.9-15.2-33.9-33.9l0-62.1L32 320c-17.7 0-32-14.3-32-32l0-64c0-17.7 14.3-32 32-32l128 0 0-62.1c0-18.7 15.2-33.9 33.9-33.9c9 0 17.6 3.6 24 9.9zM352 416l64 0c17.7 0 32-14.3 32-32l0-256c0-17.7-14.3-32-32-32l-64 0c-17.7 0-32-14.3-32-32s14.3-32 32-32l64 0c53 0 96 43 96 96l0 256c0 53-43 96-96 96l-64 0c-17.7 0-32-14.3-32-32s14.3-32 32-32z" />
                                        </svg>
                                        Edit Review
                                    </button>

                                    @else
                                    <button @click="openModal({{ $book->id }})" class="flex items-center px-2 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple mr-2">
                                        <svg class="w-4 h-4 mr-1 -ml-2 inline-block" fill="currentColor" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512">
                                            <path d="M217.9 105.9L340.7 228.7c7.2 7.2 11.3 17.1 11.3 27.3s-4.1 20.1-11.3 27.3L217.9 406.1c-6.4 6.4-15 9.9-24 9.9c-18.7 0-33.9-15.2-33.9-33.9l0-62.1L32 320c-17.7 0-32-14.3-32-32l0-64c0-17.7 14.3-32 32-32l128 0 0-62.1c0-18.7 15.2-33.9 33.9-33.9c9 0 17.6 3.6 24 9.9zM352 416l64 0c17.7 0 32-14.3 32-32l0-256c0-17.7-14.3-32-32-32l-64 0c-17.7 0-32-14.3-32-32s14.3-32 32-32l64 0c53 0 96 43 96 96l0 256c0 53-43 96-96 96l-64 0c-17.7 0-32-14.3-32-32s14.3-32 32-32z" />
                                        </svg>
                                        Review
                                    </button>
                                    @endif --}}

                                </div>
            </div>
        </div>
        </td>
        </tr>
        @endif
        @endforeach
        </tbody>
        </table>
    </div>
    </div>
    </div>
</main>
</div>

<script>
    const app = {
        data() {
            return {
                isModalOpen: false
                , isBookListModalOpen: false
                , selectedBookId: null // Initialize selectedBookId

            };
        }
        , methods: {
            openModal(selectedBookId) {
                this.selectedBookId = selectedBookId; // Set selected book ID
                this.isModalOpen = true;
            }
            , closeModal() {
                this.isModalOpen = false;
            }
            , openBookListModal() {
                this.isBookListModalOpen = true;
            }
            , closeBookListModal() {
                this.isBookListModalOpen = false;
            }
        }
    }

    window.addEventListener('DOMContentLoaded', () => {
        const modalButton = document.getElementById('modalButton');
        const modal = document.getElementById('modal');
        const closeButton = document.getElementById('closeButton');

        modalButton.addEventListener('click', () => {
            modal.classList.add('opacity-100', 'translate-y-0');
            modal.classList.remove('opacity-0', 'translate-y-1/2');
        });

        closeButton.addEventListener('click', () => {
            modal.classList.remove('opacity-100', 'translate-y-0');
            modal.classList.add('opacity-0', 'translate-y-1/2');
        });

        Vue.createApp(app).mount('#app');

    });

</script>

@endsection
