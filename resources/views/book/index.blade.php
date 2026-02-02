<x-app-layout>

    <x-slot name="header">
        <h2 class="font-bold text-2xl">
            Manajemen Buku
        </h2>
    </x-slot>

    <div class="p-6">
        <section class="container mx-auto">
            <div class="grid grid-cols-[1fr_auto] mb-4 justify-end">
                <div class="flex justify-start">
                    <form action="{{ route('books.index') }}" method="GET" class="flex">
                        <div>
                            <input type="search" name="search" placeholder="Cari Buku" value="{{ request('search') }}">
                            <select name="author">
                                <option value="">Semua Penulis</option>
                                @foreach ($authors as $author)
                                    <option value="{{ $author->id }}""
                                        {{ request('author') == $author->id ? 'selected' : '' }}>
                                        {{ $author->name }}</option>
                                @endforeach
                            </select>

                            <select name="category">
                                <option value="">Semua Kategori</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}""
                                        {{ request('category') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex items-center justify-center text-center ml-5 bg-blue-600 rounded-xl">
                            <button type="submit" class="text-xl px-5 text-white rounded-lg">Filter</button>
                        </div>
                    </form>
                </div>

                @role('admin,librarian')
                    <a href="{{ route('books.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded">
                        Tambah Buku
                    </a>
                @endrole
            </div>

            <div class="w-full bg-white overflow-x-auto">
                <table class="table-auto w-full border-collapse">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="border px-2 py-1">No</th>
                            <th class="border px-2 py-1">Judul</th>
                            <th class="border px-2 py-1">ISBN</th>
                            <th class="border px-2 py-1">Penulis</th>
                            <th class="border px-2 py-1">Kategori</th>
                            <th class="border px-2 py-1">Penerbit</th>
                            <th class="border px-2 py-1">Tahun Terbit</th>
                            <th class="border px-2 py-1">Jumlah Halaman</th>
                            <th class="border px-2 py-1">Deskripsi</th>
                            <th class="border px-2 py-1">Gambar Sampul</th>
                            <th class="border px-2 py-1">Stok</th>
                            <th class="border px-2 py-1">Tersedia</th>
                            <th class="border px-2 py-1">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($books as $index => $book)
                            <tr>
                                <td class="border px-2 py-1 text-center">
                                    {{ $books->firstItem() + $index }}
                                </td>
                                <td class="border px-2 py-1">
                                    {{ $book->title }}
                                </td>
                                <td class="border px-2 py-1">
                                    {{ $book->isbn }}
                                </td>
                                <td class="border px-2 py-1">
                                    {{ $book->author->name ?? '-' }}
                                </td>
                                <td class="border px-2 py-1">
                                    {{ $book->categories->pluck('name')->join(', ') }}
                                </td>
                                <td class="border px-2 py-1">
                                    {{ $book->publisher ?? '-' }}
                                </td>
                                <td class="border px-2 py-1 text-center">
                                    {{ $book->published_year ?? '-' }}
                                </td>
                                <td class="border px-2 py-1 text-center">
                                    {{ $book->pages }} <span>Halaman</span>
                                </td>
                                <td class="border px-2 py-1 text-center">
                                    {{ $book->description }}
                                </td>
                                <td class="border px-2 py-1 text-center">
                                    <img src="{{ $book->cover_image ? asset('storage/' . $book->cover_image) : asset('images/placeholder.png') }}"
                                        alt="cover_image" class="container mx-auto w-20 h-auto">
                                </td>
                                <td class="border px-2 py-1 text-center">
                                    {{ $book->stock }}
                                </td>
                                <td class="border px-2 py-1 text-center">
                                    @if ($book->available_stock > 0)
                                        <span
                                            class="inline-flex items-center rounded-lg bg-green-100 px-2 py-1 text-xs font-medium text-green-700 inset-ring inset-ring-green-600/20">Tersedia</span>
                                    @else
                                        <span
                                            class="inline-flex items-center rounded-lg bg-red-100 px-2 py-1 text-xs font-medium text-green-700 inset-ring inset-ring-green-600/20">Tidak
                                            Tersedia</span>
                                    @endif
                                </td>
                                <td class="border px-2 py-1 text-center space-x-2">
                                    <a href="{{ route('books.show', $book) }}" class="text-blue-600">Detail</a>

                                    @role('admin,librarian')
                                        <a href="{{ route('books.edit', $book) }}" class="text-yellow-600">Edit</a>
                                    @endrole

                                    @role('admin')
                                        <form action="{{ route('books.destroy', $book) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button class="text-red-600" onclick="return confirm('Hapus buku ini?')">
                                                Hapus
                                            </button>
                                        </form>
                                    @endrole
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="13" class="text-center p-4">
                                    Data buku belum tersedia
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $books->links() }}
            </div>

        </section>
    </div>

</x-app-layout>
