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
                                <td class="border px-2 py-1 text-center first-letter:uppercase truncate">
                                    {{ $loan->user->name }}
                                </td>
                                <td class="border px-2 py-1 text-center first-letter:uppercase truncate">
                                    {{ $loan->book->title }}
                                </td>
                                <td class="border py-1 text-center">{{ $loan->created_at->format('d M Y') }}</td>
                                <td class="border py-1 text-center">{{ $loan->due_date?->format('d M Y') ?? '-' }}</td>
                                <td class="border px-2 py-1 text-center truncate">
                                    @switch($loan->status)
                                        @case('pending')
                                            <span
                                                class="inline-flex w-20 h-10 justify-center items-center text-center bg-white py-1 text-sm text-yellow-400 font-medium">
                                                Menunggu approval</span>
                                        @break

                                        @case('approved')
                                            <span
                                                class="inline-flex w-20 h-10 justify-center items-center text-center bg-white py-1 text-sm text-green-400 font-medium">
                                                Sudah disetujui</span>
                                        @break

                                        @case('borrowed')
                                            <span
                                                class="inline-flex w-20 h-10 justify-center items-center text-center bg-white py-1 text-sm text-blue-400 font-medium">
                                                Buku sedang dipinjam</span>
                                        @break

                                        @case('overdue')
                                            <span
                                                class="inline-flex w-20 h-10 justify-center items-center text-center bg-white py-1 text-sm text-red-400 font-medium">
                                                terlambat</span>
                                        @break

                                        @case('returned')
                                            <span
                                                class="inline-flex w-20 h-10 justify-center items-center text-center bg-white py-1 text-sm font-medium text-slate-500">
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

                                        @if ($loan->status == 'approved')
                                            <div class="flex h-10 rounded-lg px-4 items-center bg-green-500">
                                                <button type="button" class="iconify text-white scan-btn"
                                                    data-loan="{{ $loan->id }}"
                                                    data-modal="modal-scan-loan{{ $loan->id }}">
                                                    <span class="iconify w-5 h-5" data-icon="mdi:camera-outline"></span>
                                                </button>
                                            </div>

                                            <!-- Modal untuk scanning -->
                                            <dialog id="modal-scan-loan{{ $loan->id }}"
                                                class="modal rounded-xl scan-modal-loan">
                                                <div class="modal-box p-5 relative overflow-hidden">

                                                    <button type="button"
                                                        class="btn btn-sm btn-circle absolute right-4 top-4 bg-white close-scan-modal">
                                                        ✕
                                                    </button>

                                                    <div class="flex flex-col items-center gap-4 mt-8">
                                                        <h3 class="text-lg font-bold">Scan QR Code Peminjaman</h3>

                                                        <div id="scan-container-loan{{ $loan->id }}"
                                                            class="w-64 h-64 rounded-lg border-2 border-dashed flex items-center justify-center">
                                                        </div>
                                                    </div>
                                                </div>
                                            </dialog>
                                        @endif

                                        @if ($loan->status == 'borrowed' || $loan->status == 'overdue')
                                            <div class="flex h-10 rounded-lg px-4 items-center bg-yellow-400">
                                                <button type="button" class="iconify text-white scan-btn"
                                                    data-loan="{{ $loan->id }}"
                                                    data-modal="modal-scan-return-{{ $loan->id }}">
                                                    <span class="iconify w-5 h-5" data-icon="mdi:camera-outline"></span>
                                                </button>
                                            </div>

                                            <!-- Modal untuk scanning -->
                                            <dialog id="modal-scan-return-{{ $loan->id }}"
                                                class="modal rounded-xl scan-modal-return">
                                                <div class="modal-box p-5 relative overflow-hidden">

                                                    <button type="button"
                                                        class="btn btn-sm btn-circle absolute right-4 top-4 bg-white close-scan-modal">
                                                        ✕
                                                    </button>

                                                    <div class="flex flex-col items-center gap-4 mt-8">
                                                        <h3 class="text-lg font-bold">Scan QR Code Pengembalian</h3>

                                                        <div id="scan-container-return{{ $loan->id }}"
                                                            class="w-64 h-64 rounded-lg border-2 border-dashed flex items-center justify-center">
                                                        </div>
                                                    </div>
                                                </div>
                                            </dialog>
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
