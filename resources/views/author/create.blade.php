<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl">Create Author</h2>
    </x-slot>

    <div class="p-6 ">
        <div class="container mx-auto max-w-xl">
            <form action="{{ route('authors.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="flex flex-col ">
                    <label for="">Nama Author</label>
                    <input type="text" name="name" placeholder="Nama Author">
                </div>
                <div class="flex flex-col ">
                    <label for="">Biografi Author</label>
                    <input type="text" name="biography" placeholder="biografi Author">
                </div>
                <div class="flex flex-col ">
                    <label for="">photo</label>
                    <input type="file" name="photo">
                </div>
                <div class="flex flex-col ">
                    <label for="">Kewarganegaraan</label>
                    <input type="text" name="nationality" placeholder="Kewarganegaraan">
                </div>
                <div class="flex flex-col mb-5">
                    <label for="">Tahun Lahir</label>
                    <input type="text" name="birth_year" placeholder="Tahun Lahir">
                </div>
                <button type="submit" class="container mx-auto bg-blue-600 text-white rounded p-4">
                    Simpan Author
                </button>
            </form>
        </div>
    </div>
</x-app-layout>
