<p>Halo {{ $loan->user->name }},</p>

<p>
    Pengingat bahwa buku
    <strong>{{ $loan->book->title }}</strong>
    akan jatuh tempo pada:
    <strong>{{ $loan->due_date }}</strong>
</p>
