<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="flex flex-col p-6 text-gray-900 dark:text-gray-100 gap-5">
                    {{ __("You're logged in!") }}
                    <div class="p-4 bg-blue-400 max-w-sm">
                        <a href={{ route('books.index') }} class="flex justify-center">Manajemen Buku</a>
                    </div>
                    <div class="p-4 bg-blue-400 max-w-sm">
                        <a href={{ route('authors.index') }} class="flex justify-center">Manajemen Author</a>
                    </div>
                    <div class="p-4 bg-blue-400 max-w-sm">
                        <a href={{ route('categories.index') }} class="flex justify-center">Manajemen Kategori</a>
                    </div>
                    <div class="p-4 bg-blue-400 max-w-sm">
                        <a href={{ route('members.catalog') }} class="flex justify-center">Katalog Buku</a>
                    </div>
                    <div class="p-4 bg-blue-400 max-w-sm">
                        <a href={{ route('members.loan') }} class="flex justify-center">Peminjaman Saya</a>
                    </div>
                    <div class="p-4 bg-blue-400 max-w-sm">
                        <a href={{ route('admin.loans.index') }} class="flex justify-center">Manajemen Peminjaman</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
