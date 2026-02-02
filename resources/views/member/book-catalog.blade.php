<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl">Katalog Buku</h2>
    </x-slot>

    <div class="p-6">
        <section class="container mx-auto">
            <div class="grid grid-cols-4 gap-6">
                @foreach ($books as $book)
                    <div class="card max-w-sm p-4 rounded-2xl bg-white hover:scale-110 transition">
                        <div class="w-full h-64 rounded-t-2xl mb-1 overflow-hidden">
                            <img src="{{ asset('storage/' . $book->cover_image) }}" alt="cover buku"
                                class="w-full h-full object-fit">
                        </div>
                        <div class="card-body w-full flex flex-col text-xl mb-4">
                            <h2 class="font-semibold text-2xl mb-3 truncate">{{ $book->title }}</h2>
                            <div class="grid grid-cols-[auto_auto]">
                                <div class="flex flex-col">
                                    <div class="grid grid-cols-[auto_1fr] gap-2">
                                        <span class="iconify flex mt-1 w-5 h-5"
                                            data-icon="icon-park-outline:edit-name"></span>
                                        <span class="text-xl font-medium truncate">{{ $book->author->name }}</span>
                                    </div>

                                    <div class=" grid grid-cols-[auto_1fr] gap-2">
                                        <span class="iconify flex mt-1 w-5 h-5"
                                            data-icon="cuida:calendar-outline"></span>
                                        <span class="text-xl font-medium">{{ $book->published_year }}</span>
                                    </div>

                                    <div class="grid grid-cols-[auto_1fr] gap-2">
                                        <span class="iconify flex mt-1 w-5 h-5"
                                            data-icon="streamline-ultimate:book-book-pages"></span>
                                        <span class="text-xl font-medium">{{ $book->pages }}</span>
                                    </div>

                                </div>

                                <div class="flex justify-end">
                                    @if ($book->available_stock > 0)
                                        <span
                                            class="inline-flex w-20 h-7 justify-center items-center rounded-xl bg-transparent outline outline-1 outline-green-400 px-2 py-1 text-sm font-medium text-green-700">
                                            Tersedia</span>
                                    @else
                                        <span
                                            class="inline-flex h-7 justify-center items-center rounded-xl bg-transparent outline outline-1 outline-red-400 px-2 py-1 text-sm font-medium text-red-700">Tidak
                                            Tersedia</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @if ($book->available_stock > 0)
                            <form action="{{ route('members.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="book_id" value="{{ $book->id }}">
                                <button type="submit" class="w-full rounded-lg bg-green-400 py-2 px-2">Pinjam
                                    Buku</button>
                            </form>
                        @endif
                    </div>
                @endforeach
        </section>
    </div>
</x-app-layout>
