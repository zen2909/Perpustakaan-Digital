<x-app-layout>

    <x-slot name="header">
        <h2 class="font-bold text-2xl">
            Edit Buku
        </h2>
    </x-slot>

    <div class="p-6">
        <section class="container mx-auto">
            <div class="w-full bg-white">
                <form action="{{ route('books.update', $book->slug) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="grid grid-cols-2">
                        <div class="flex flex-col px-2 py-1 mb-2">
                            <label class="text-2xl font-bold  mb-2">Judul</label>
                            <input type="text" name="title" placeholder="Judul Buku"
                                value="{{ old('title', $book->title) }}">
                        </div>

                        <div class="flex flex-col px-2 py-1 mb-2">
                            <label class="text-2xl font-bold  mb-2">ISBN</label>
                            <input type="text" name="isbn" placeholder="ISBN"
                                value="{{ old('isbn', $book->isbn) }}">
                        </div>
                    </div>

                    <div class="grid
                                grid-cols-2">
                        <div class="flex flex-col px-2 py-1 mb-2">
                            <label class="text-2xl font-bold  mb-2">Author</label>
                            <select name="author_id" class="form-select">
                                <option value="">Pilih author</option>
                                @foreach ($authors as $author)
                                    <option value="{{ $author->id }}"
                                        {{ old('author_id', $book->author_id) == $author->id ? 'selected' : '' }}>
                                        {{ $author->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="flex flex-col px-2 py-1 mb-2">
                            <label class="text-2xl font-bold  mb-2">Kategori</label>
                            <div class="grid grid-cols-2 gap-2">
                                @foreach ($categories as $category)
                                    <label class="flex items-center gap-2">
                                        <input type="checkbox" name="categories_id[]" value="{{ $category->id }}"
                                            {{ in_array($category->id, old('categories_id', $book->categories->pluck('id')->toArray())) ? 'checked' : '' }}>
                                        <span>{{ $category->name }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-2">
                        <div class="flex flex-col px-2 py-1 mb-2">
                            <label class="text-2xl font-bold  mb-2">Penerbit</label>
                            <input type="text" name="publisher" placeholder="Penerbit"
                                value="{{ old('publisher', $book->publisher) }}">
                        </div>

                        <div class="flex
                                    flex-col px-2 py-1 mb-2">
                            <label class="text-2xl font-bold  mb-2">Tahun Terbit</label>
                            <input type="number" name="published_year" placeholder="Tahun Terbit"
                                value="{{ old('published_year', $book->published_year) }}">
                        </div>
                    </div>

                    <div class="grid grid-cols-2">
                        <div class="flex flex-col px-2 py-1 mb-2">
                            <label class="text-2xl font-bold  mb-2">Jumlah Halaman</label>
                            <input type="number" name="pages" placeholder="Jumlah Halaman"
                                value="{{ old('pages', $book->pages) }}">
                        </div>

                        <div class="flex
                                    flex-col px-2 py-1 mb-2">
                            <label class="text-2xl font-bold  mb-2">Stok</label>
                            <p class="text-sm text-gray-600">
                                Sedang dipinjam: {{ $book->stock - $book->available_stock }}
                            </p>
                            <p class="text-sm text-gray-500">
                                Stok tidak boleh kurang dari jumlah yang sedang dipinjam
                            </p>
                            <input type="number" name="stock" value="{{ old('stock', $book->stock) }}">
                        </div>

                    </div>

                    <div class="flex flex-col border px-2 py-1 mb-2">
                        <label class="text-2xl font-bold  mb-2">Gambar Sampul</label>
                        @if ($book->cover_image)
                            <img src="{{ asset('storage/' . $book->cover_image) }}" class="w-32 mb-2">
                        @else
                            <p class="text-gray-500">Belum ada gambar</p>
                        @endif

                        <input type="file" name="cover_image">
                    </div>

                    <div class="flex flex-col border px-2 py-1 mb-2">
                        <label class="text-2xl font-bold  mb-2">Deskripsi</label>
                        <textarea name="description" placeholder="Deskripsi">{{ old('description', $book->description) }}</textarea>
                    </div>

                    <button type="submit" class="w-full bg-blue-500 py-4 text-2xl">
                        Update Buku
                    </button>

                </form>
            </div>
            @if ($errors->any())
                <div class="bg-red-100 text-red-700 p-3 mb-4">
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

        </section>
    </div>
</x-app-layout>
