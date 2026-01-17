<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl">Edit Author</h2>
    </x-slot>

    <section class="p-6">
        <div class="container mx-auto max-w-xl">
            <form action="{{ route('authors.update', $author->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="flex flex-col gap-2 mb-5">
                    <label for="">Nama Author</label>
                    <input type="text" name="name" placeholder="Nama Author"
                        value="{{ old('name', $author->name) }}">
                </div>
                <div class="flex flex-col gap-2 mb-5">
                    <label for="">Biografi Author</label>
                    <input type="text" name="biography" placeholder="biografi Author"
                        value="{{ old('biography', $author->biography) }}">
                </div>
                <div class="flex flex-col gap-2 mb-5">
                    <img src="{{ asset('storage/' . $author->photo) }}" alt="author photo">
                    <label for="">photo</label>
                    <input type="file" name="photo">
                </div>
                <div class="flex flex-col gap-2 mb-5">
                    <label for="">Kewarganegaraan</label>
                    <input type="text" name="nationality" placeholder="Kewarganegaraan"
                        value="{{ old('biography', $author->nationality) }}">
                </div>
                <div class="flex flex-col gap-2 mb-5">
                    <label for="">Tahun Lahir</label>
                    <input type="text" name="birth_year" placeholder="Tahun Lahir"
                        value="{{ old('birthy_year', $author->birth_year) }}">
                </div>
                <button type="submit" class="bg-blue-600 text-white rounded py-2">
                    Update Author
                </button>
            </form>
        </div>
    </section>
</x-app-layout>
