<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl">Manajemen Authors</h2>
    </x-slot>

    <div class="p-6">
        <section class="container mx-auto">
            <div class="w-full bg-white mb-5">
                <table class=" table-auto border-collapse">
                    <thead>
                        <tr>
                            <th class="border border-gray-400 text-center px-2">no</th>
                            <th class="border border-gray-400 text-center px-2">nama</th>
                            <th class="border border-gray-400 text-center px-2">Biografi</th>
                            <th class="border border-gray-400 text-center px-2">foto</th>
                            <th class="border border-gray-400 text-center px-2">Kewarganegaraan</th>
                            <th class="border border-gray-400 text-center px-2">Tahun Kelahiran</th>
                            <th class="border border-gray-400 text-center px-2">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($authors as $index => $author)
                            <tr>
                                <td class="border border-gray-400 text-center px-2">{{ $index + 1 }}</td>
                                <td class="border border-gray-400 text-center px-2">{{ $author->name }}</td>
                                <td class="border border-gray-400 px-2">{{ $author->biography }}</td>
                                <td class="border border-gray-400 px-2">
                                    @if ($author->photo)
                                        <img src="{{ asset('storage/' . $author->photo) }}"
                                            class="w-12 h-12 object-cover rounded mx-auto">
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="border border-gray-400 px-2">{{ $author->nationality }}</td>
                                <td class="border border-gray-400 px-2">{{ $author->birth_year }}</td>
                                <td class="border border-gray-400 text-center px-2">
                                    <div class="flex justify-center gap-2">
                                        <a href="{{ route('authors.edit', $author->id) }}"
                                            class="text-blue-500">Edit</a>
                                        <form action="{{ route('authors.destroy', $author->id) }}" method="POST"
                                            onsubmit="return confirm('Apakah anda yakin untuk menghapus Author {{ $author->name }} ?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>

                </table>
            </div>
            <div class="block mx-auto bg-blue-400 p-5 max-w-md">
                <a href="{{ route('authors.create') }}" class="flex justify-center text-center">Tambah Author</a>
            </div>
        </section>
    </div>

</x-app-layout>
