<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl">Edit Kategori</h2>
    </x-slot>

    <div class="p-6">
        <div class="max-w-md mx-auto bg-white p-6 rounded shadow">
            <form class="flex flex-col gap-4" method="POST" action="{{ route('categories.update', $category->slug) }}">
                @csrf
                @method('PUT')
                <div>
                    <label class="block mb-1">Nama Kategori</label>
                    <input type="text" name="name" class="w-full border rounded px-3 py-2"
                        placeholder="Nama Kategori" value="{{ old('name', $category->name) }}">
                </div>

                <div>
                    <label class="block mb-1">Deskripsi Kategori</label>
                    <textarea class="w-full border rounded px-3 py-2" name="description" placeholder="Deskripsi kategori">{{ old('description', $category->description) }}</textarea>
                </div>

                <button type="submit" class="bg-blue-600 text-white rounded py-2">
                    Update Kategori
                </button>

            </form>
        </div>
    </div>
</x-app-layout>
