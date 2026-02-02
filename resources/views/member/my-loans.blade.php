<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl">Pinjaman Saya</h2>
    </x-slot>

    <div class="p-6">
        <section class="container mx-auto">
            <div class="w-full bg-white overflow-x-auto">
                <table class="table-auto w-full border-collapse">
                    <thead>
                        <tr class="bg-blue-300">
                            <th class="border px-2 py-1 text-center text-xl">No</th>
                            <th class="border px-2 py-1 text-center text-xl">Judul Buku</th>
                            <th class="border px-2 py-1 text-center text-xl">Gambar Sampul</th>
                            <th class="border px-2 py-1 text-center text-xl">Tanggal Pinjam</th>
                            <th class="border px-2 py-1 text-center text-xl">Jatuh Tempo</th>
                            <th class="border px-2 py-1 text-center text-xl">Status</th>
                            <th class="border px-2 py-1 text-center text-xl">ID Peminjaman</th>
                            <th class="border px-2 py-1 text-center text-xl">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($loans as $index => $loan)
                            <tr>
                                <td class="border px-2 py-1 text-center">{{ $loans->firstItem() + $index }}</td>
                                <td class="border px-2 py-1 text-xl text-center first-letter:uppercase">
                                    {{ $loan->book->title }}
                                </td>
                                <td class="border px-2 py-1 justify-center">
                                    <div class="flex justify-center">
                                        <img src="{{ $loan->book->cover_image ? asset('storage/' . $loan->book->cover_image) : asset('images/placeholder.png') }}"
                                            alt="cover_image" class="w-28 h-28">
                                    </div>
                                </td>
                                <td class="border px-2 py-1 text-center">{{ $loan->loan_date ?? '-' }}</td>
                                <td class="border px-2 py-1 text-center">{{ $loan->due_date ?? '-' }}</td>
                                <td class="border px-2 py-1 text-center truncate">
                                    @switch($loan->status)
                                        @case('pending')
                                            <span
                                                class="inline-flex w-20 h-7 justify-center items-center rounded-xl px-2 py-1 text-sm text-white font-medium bg-yellow-400">
                                                Pending</span>
                                        @break

                                        @case('approved')
                                            <span
                                                class="inline-flex w-20 h-7 justify-center items-center rounded-xl px-2 py-1 text-sm text-white font-medium bg-green-500">
                                                Approved</span>
                                        @break

                                        @case('borrowed')
                                            <span
                                                class="inline-flex w-20 h-7 justify-center items-center rounded-xl px-2 py-1 text-sm text-white font-medium bg-blue-500">
                                                Borrowed</span>
                                        @break

                                        @case('overdue')
                                            <span
                                                class="inline-flex w-20 h-7 justify-center items-center rounded-xl px-2 py-1 text-sm text-white font-medium bg-red-500">
                                                Overdue</span>
                                        @break

                                        @case('returned')
                                            <span
                                                class="inline-flex w-20 h-7 justify-center items-center rounded-xL px-2 py-1 text-sm text-white font-medium bg-slate-500">
                                                Returned</span>
                                        @break

                                        @default
                                    @endswitch
                                </td>

                                <td class="border px-2 py-1 text-lg text-center">
                                    {{ Str::limit($loan->qr_token, 8) ?? '-' }}
                                </td>

                                <td class="border px-2 py-1 text-center">
                                    @switch($loan->status)
                                        @case('pending')
                                            <span
                                                class="inline-flex w-20 h-7 justify-center items-center bg-transparent px-2 py-1 text-sm font-medium">
                                                Menunggu persetujuan admin</span>
                                        @break

                                        @case('approved')
                                            <div class="flex grid grid-rows-2 justify-center py-2">
                                                <div class="py-1">
                                                    <span
                                                        class="inline-flex w-20 h-7 justify-center bg-transparent text-sm font-medium">
                                                        Silakan ambil buku di perpustakaan</span>
                                                </div>
                                                <div class="flex mt-4 items-center justify-center">
                                                    <button class="btn" onclick="my_modal_3.showModal()"><span
                                                            class="iconify w-12 h-12 bg-blue-400 rounded-lg p-2"
                                                            data-icon="grommet-icons:qr"></span></button>
                                                    <dialog id="my_modal_3" class="modal">
                                                        <div class="modal-box p-20 ">
                                                            <form method="dialog">
                                                                <button
                                                                    class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
                                                            </form>
                                                            <div class="flex flex-col items-center gap-1">
                                                                <span class="text-xs font-medium">QR Peminjaman</span>
                                                                <img src="{{ $loan->qr_path ? asset('storage/' . $loan->qr_path) : asset('images/placeholder.png') }}"
                                                                    alt="QR" class="w-20 h-20">
                                                                <span class="text-xs font-medium">Tunjukkan QR code ini kepada
                                                                    petugas</span>
                                                            </div>

                                                        </div>
                                                    </dialog>
                                                </div>
                                            </div>
                                        @break

                                        @case('borrowed')
                                            <div class="flex flex-col items-center gap-1">
                                                <span class="text-xs font-medium">QR Pengembalian</span>
                                                <img src="{{ $loan->qr_path ? asset('storage/' . $loan->qr_path) : asset('images/placeholder.png') }}"
                                                    alt="QR" class="w-20 h-20">
                                            </div>
                                        @break

                                        @case('overdue')
                                            <div class="flex flex-col items-center gap-1">
                                                <span class="text-xs font-medium">QR Pengembalian</span>
                                                <img src="{{ $loan->qr_path ? asset('storage/' . $loan->qr_path) : asset('images/placeholder.png') }}"
                                                    alt="QR" class="w-20 h-20">
                                                <span class="text-red-500 font-xs font-medium">Terlambat, Denda akan
                                                    dihitung</span>
                                            </div>
                                        @break

                                        @case('returned')
                                            <span
                                                class="inline-flex w-20 h-7 justify-center items-center rounded-xl bg-transparent outline outline-1 outline-slate-500 px-2 py-1 text-sm font-medium text-slate-500">
                                                Selesai</span>
                                        @break

                                        @default
                                    @endswitch
                                </td>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-xl text-center p-4">
                                        Silakan pinjam buku dari katalog
                                    </td>
                                </tr>
                            @endforelse
                    </table>
                </div>
            </section>
        </div>
    </x-app-layout>
