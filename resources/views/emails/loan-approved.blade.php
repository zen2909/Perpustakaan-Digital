<h2>Peminjaman Disetujui</h2>

<p>Halo {{ $loan->user->name }},</p>

<p>
    Buku <strong>{{ $loan->book->title }}</strong>
    telah disetujui.
</p>

<ul>
    <li>Tanggal Pinjam: {{ $loan->loan_date }}</li>
    <li>Jatuh Tempo: {{ $loan->due_date }}</li>
</ul>

<p>Harap dikembalikan tepat waktu.</p>
