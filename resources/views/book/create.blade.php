<x-app-layout>

    <x-slot name="header">
        <h2 class="font-bold text-2xl">
            Create Buku
        </h2>
    </x-slot>

    <div class="p-6">
        <section class="container mx-auto">
            <div class="w-full bg-white">
                <form action="{{ route('books.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="grid grid-cols-2">
                        <div class="flex flex-col px-2 py-1 mb-2">
                            <label class="text-2xl font-bold  mb-2">Judul</label>
                            <input type="text" name="title" placeholder="Judul Buku">
                        </div>

                        <div class="flex flex-col px-2 py-1 mb-2">
                            <label class="text-2xl font-bold  mb-2">ISBN</label>
                            <input type="text" name="isbn" placeholder="ISBN">
                        </div>
                    </div>

                    <div class="grid grid-cols-2">
                        <div class="flex flex-col px-2 py-1 mb-2">
                            <label class="text-2xl font-bold  mb-2">Author</label>
                            <select name="author_id" class="form-select">
                                <option value="">Pilih author</option>
                                @foreach ($authors as $author)
                                    <option value="{{ $author->id }}">
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
                                        <input type="checkbox" name="categories_id[]" value="{{ $category->id }}">
                                        <span>{{ $category->name }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-2">
                        <div class="flex flex-col px-2 py-1 mb-2">
                            <label class="text-2xl font-bold  mb-2">Penerbit</label>
                            <input type="text" name="publisher" placeholder="Penerbit">
                        </div>

                        <div class="flex flex-col px-2 py-1 mb-2">
                            <label class="text-2xl font-bold  mb-2">Tahun Terbit</label>
                            <input type="number" name="published_year" placeholder="Tahun Terbit">
                        </div>
                    </div>

                    <div class="grid grid-cols-2">
                        <div class="flex flex-col px-2 py-1 mb-2">
                            <label class="text-2xl font-bold  mb-2">Jumlah Halaman</label>
                            <input type="number" name="pages" placeholder="Jumlah Halaman">
                        </div>

                        <div class="flex flex-col px-2 py-1 mb-2">
                            <label class="text-2xl font-bold  mb-2">Stok</label>
                            <input type="number" name="stock">
                        </div>

                    </div>

                    <div class="flex flex-col border px-2 py-1 mb-2">
                        <label class="text-2xl font-bold  mb-2">Gambar Sampul</label>
                        <input type="file" name="cover_image">
                    </div>

                    <div class="flex flex-col border px-2 py-1 mb-2">
                        <label class="text-2xl font-bold  mb-2">Deskripsi</label>
                        <textarea type="text" name="description" placeholder="Deskripsi"></textarea>
                    </div>

                    <button type="submit" class="w-full bg-blue-500 py-4 text-2xl">
                        Simpan Buku
                    </button>

                </form>
            </div>
            @if ($errors->any())
                <div class="text-red-500">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </section>
    </div>
</x-app-layout>
