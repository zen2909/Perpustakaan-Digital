<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl">Manajemen Pinjaman</h2>
    </x-slot>

    <div class="p-6">
        <section class="container mx-auto">
            <div class="w-full bg-white overflow-x-auto">
                <table class="table-auto w-full border-collapse">
                    <thead>
                        <tr class="bg-blue-300">
                            <th class="border px-2 py-1 text-center text-xl">No</th>
                            <th class="border px-2 py-1 text-center text-xl">ID Peminjaman</th>
                            <th class="border px-2 py-1 text-center text-xl">Nama Peminjam</th>
                            <th class="border px-2 py-1 text-center text-xl">Judul Buku</th>
                            <th class="border px-2 py-1 text-center text-xl">Tanggal Request</th>
                            <th class="border px-2 py-1 text-center text-xl">Jatuh Tempo</th>
                            <th class="border px-2 py-1 text-center text-xl">Status</th>
                            <th class="border px-2 py-1 text-center text-xl">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($loans as $index => $loan)
                            <tr>
                                <td class="border px-2 py-1 text-center">{{ $loans->firstItem() + $index }}</td>
                                <td class="border px-2 py-1 text-center">{{ Str::limit($loan->qr_token, 8) ?? '-' }}
                                </td>
                                <td class="border px-2 py-1 text-center first-letter:uppercase">{{ $loan->user->name }}
                                </td>
                                <td class="border px-2 py-1 text-center first-letter:uppercase">{{ $loan->book->title }}
                                </td>
                                <td class="border px-2 py-1 text-center">{{ $loan->created_at->format('d M Y') }}</td>
                                <td class="border px-2 py-1 text-center">{{ $loan->due_date ?? '-' }}</td>
                                <td class="border px-2 py-1 text-center truncate">
                                    @switch($loan->status)
                                        @case('pending')
                                            <span
                                                class="inline-flex w-20 h-10 justify-center items-center bg-white px-2 py-1 text-sm text-yellow-400 font-semibold">
                                                Menunggu approval</span>
                                        @break

                                        @case('approved')
                                            <span
                                                class="inline-flex w-20 h-10 justify-center items-center bg-white px-2 py-1 text-sm text-green-400 font-semibold">
                                                Sudah disetujui</span>
                                        @break

                                        @case('borrowed')
                                            <span
                                                class="inline-flex w-20 h-10 justify-center items-center bg-white px-2 py-1 text-sm text-blue-400 font-semibold">
                                                Buku sedang dipinjam</span>
                                        @break

                                        @case('overdue')
                                            <span
                                                class="inline-flex w-20 h-10 justify-center items-center bg-white px-2 py-1 text-sm text-red-400 font-semibold">
                                                terlambat</span>
                                        @break

                                        @case('returned')
                                            <span
                                                class="inline-flex w-20 h-10 justify-center items-center bg-white px-2 py-1 text-sm font-semibold text-slate-400">
                                                Selesai</span>
                                        @break

                                        @default
                                    @endswitch
                                </td>

                                <td>
                                    <div class="flex justify-center gap-2">
                                        @if ($loan->status == 'pending')
                                            <form action="{{ route('admin.loans.approve', $loan->id) }}" method="POST">
                                                @csrf
                                                <div class="flex h-10 rounded-lg px-4 items-center bg-blue-500">
                                                    <button type="submit" class="iconify text-white"><span
                                                            class="iconify w-5 h-5"
                                                            data-icon = "mdi:success-bold"></span></button>
                                                </div>
                                            </form>
                                        @endif

                                        <form action="{{ route('admin.loans.destroy', $loan->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <div class="flex h-10 rounded-lg px-4 items-center bg-red-500">
                                                <button type="submit" class="iconify text-white"><span
                                                        class="iconify w-5 h-5"
                                                        data-icon = "material-symbols:delete-outline"></span></button>
                                            </div>
                                        </form>
                                    </div>
                                </td>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-xl text-center p-4">
                                        Belum ada peminjaman
                                    </td>
                                </tr>
                            @endforelse
                    </table>
                </div>
            </section>
        </div>
    </x-app-layout>
