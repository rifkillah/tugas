@props(['peminjaman'])

@if ($peminjaman->status === 'dikembalikan')
    <span class="badge badge--biru">Selesai</span>
@elseif ($peminjaman->isTerlambat())
    <span class="badge badge--merah">Terlambat</span>
@else
    <span class="badge badge--hijau">Dipinjam</span>
@endif
