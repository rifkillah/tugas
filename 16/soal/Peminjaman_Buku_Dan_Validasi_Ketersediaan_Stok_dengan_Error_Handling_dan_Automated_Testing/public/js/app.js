document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('[data-dismiss]').forEach(function (tombol) {
        tombol.addEventListener('click', function () {
            var pesan = tombol.closest('.flash');

            if (pesan) {
                pesan.remove();
            }
        });
    });

    document.querySelectorAll('form[data-confirm]').forEach(function (form) {
        form.addEventListener('submit', function (event) {
            if (!window.confirm(form.getAttribute('data-confirm'))) {
                event.preventDefault();
            }
        });
    });

    document.querySelectorAll('[data-print]').forEach(function (tombol) {
        tombol.addEventListener('click', function () {
            window.print();
        });
    });

    var pilihanBuku = document.getElementById('buku_id');

    if (!pilihanBuku) {
        return;
    }

    var jumlah = document.getElementById('jumlah');
    var durasi = document.getElementById('durasi');
    var ringkasanBuku = document.getElementById('ringkasan-buku');
    var ringkasanStok = document.getElementById('ringkasan-stok');
    var ringkasanJatuhTempo = document.getElementById('ringkasan-jatuh-tempo');
    var catatanStok = document.getElementById('catatan-stok');

    function perbaruiRingkasan() {
        var opsi = pilihanBuku.options[pilihanBuku.selectedIndex];
        var adaPilihan = pilihanBuku.value !== '';
        var stok = parseInt(opsi ? opsi.getAttribute('data-stok') || '0' : '0', 10);
        var judul = opsi ? opsi.getAttribute('data-judul') || '-' : '-';
        var lama = parseInt(durasi.value || '14', 10);
        var batas = Math.min(3, Math.max(1, stok));

        if (ringkasanBuku) {
            ringkasanBuku.textContent = adaPilihan ? judul : '\u2014';
        }

        if (ringkasanStok) {
            ringkasanStok.textContent = adaPilihan ? (stok > 0 ? stok + ' eksemplar' : 'Stok habis') : '\u2014';
        }

        if (jumlah) {
            jumlah.max = String(adaPilihan ? batas : 3);

            if (parseInt(jumlah.value || '1', 10) > parseInt(jumlah.max, 10)) {
                jumlah.value = jumlah.max;
            }
        }

        if (catatanStok) {
            if (! adaPilihan) {
                catatanStok.textContent = 'Pilih buku untuk melihat ketersediaan stok.';
                catatanStok.className = 'hint';
            } else if (stok <= 0) {
                catatanStok.textContent = 'Buku ini sedang habis. Pilih judul lain untuk melanjutkan.';
                catatanStok.className = 'hint hint--merah';
            } else if (stok === 1) {
                catatanStok.textContent = 'Tersisa satu eksemplar. Maksimal peminjaman 1 eksemplar.';
                catatanStok.className = 'hint hint--merah';
            } else {
                catatanStok.textContent = 'Stok tersedia. Maksimal 3 eksemplar per transaksi.';
                catatanStok.className = 'hint';
            }
        }

        if (ringkasanJatuhTempo) {
            var tanggal = new Date();
            tanggal.setDate(tanggal.getDate() + lama);
            ringkasanJatuhTempo.textContent = tanggal.toLocaleDateString('id-ID', {
                day: '2-digit',
                month: 'short',
                year: 'numeric',
            });
        }
    }

    pilihanBuku.addEventListener('change', perbaruiRingkasan);
    durasi.addEventListener('change', perbaruiRingkasan);
    perbaruiRingkasan();
});
