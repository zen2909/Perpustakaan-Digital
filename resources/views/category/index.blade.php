<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl">Manajemen Kategori</h2>
    </x-slot>

    <div class="p-6">
        <section class="list-category container mx-auto">
            <div class="flex justify-center mb-5">
                <table class="table-auto border-collapse">
                    <thead>
                        <tr>
                            <th class="border border-gray-400 text-center px-3">No</th>
                            <th class="border border-gray-400 text-center px-3">Nama Kategori</th>
                            <th class="border border-gray-400 text-center px-3">Deskripsi Kategori</th>
                            <th class="border border-gray-400 text-center px-3">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($categories as $category)
                            <tr>
                                <td class="border border-gray-400 text-center px-3">{{ $category->id }}</td>
                                <td class="border border-gray-400 px-3">{{ $category->name }}</td>
                                <td class="border border-gray-400 px-3">{{ $category->description }}</td>
                                <td class="flex border border-gray-400 text-center px-3 py-2 gap-2">
                                    <a href="{{ route('categories.edit', $category->slug) }}"
                                        class="text-blue-600">Edit</a>

                                    <form action="{{ route('categories.destroy', $category->slug) }}" method="POST"
                                        onsubmit="return confirm('Apakah anda yakin untuk menghapus kategori {{ $category->name }} ?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="container mx-auto max-w-sm bg-slate-900">
                <a href="{{ route('categories.create') }}" class="flex justify-center p-4 bg-blue-400">Tambah
                    Kategori</a>
            </div>
        </section>
    </div>
</x-app-layout>
