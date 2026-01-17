<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl">Create Kategori</h2>
    </x-slot>

    <div class="p-6">
        <div class="max-w-md mx-auto bg-white p-6 rounded shadow">
            <form action="{{ route('categories.store') }}" method="POST" class="flex flex-col gap-4">
                @csrf
                <div>
                    <label class="block mb-1">Nama Kategori</label>
                    <input type="text" name="name" class="w-full border rounded px-3 py-2"
                        placeholder="Nama Kategori">
                </div>

                <div>
                    <label class="block mb-1">Deskripsi Kategori</label>
                    <textarea class="w-full border rounded px-3 py-2" name="description" placeholder="Deskripsi kategori"></textarea>
                </div>

                <button type="submit" class="bg-blue-600 text-white rounded py-2">
                    Simpan Kategori
                </button>

            </form>
        </div>
    </div>
</x-app-layout>
