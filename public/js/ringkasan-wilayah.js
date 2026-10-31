const pilihanWilayah = document.getElementById('wilayah_id');
const ringkasanWilayah = document.getElementById('ringkasan-wilayah');
let permintaanWilayah = null;

async function tampilkanRingkasanWilayah() {
    if (permintaanWilayah) {
        permintaanWilayah.abort();
    }

    if (!pilihanWilayah.value) {
        ringkasanWilayah.textContent = 'Pilih wilayah untuk melihat jumlah laporan aktif.';
        return;
    }

    const pengendali = new AbortController();
    permintaanWilayah = pengendali;
    ringkasanWilayah.textContent = 'Memuat informasi wilayah...';

    try {
        const url = `${pilihanWilayah.dataset.ringkasanUrl}/${encodeURIComponent(pilihanWilayah.value)}/ringkasan`;
        const response = await fetch(url, {
            headers: { Accept: 'application/json' },
            signal: pengendali.signal,
        });

        if (!response.ok) {
            throw new Error('Informasi wilayah tidak tersedia.');
        }

        const data = await response.json();

        if (!pengendali.signal.aborted) {
            ringkasanWilayah.textContent = `Di ${data.wilayah.nama} terdapat ${data.jumlah_laporan_aktif} laporan aktif.`;
        }
    } catch (error) {
        if (error.name !== 'AbortError' && !pengendali.signal.aborted) {
            ringkasanWilayah.textContent = 'Informasi wilayah gagal dimuat. Silakan pilih wilayah kembali.';
        }
    }
}

pilihanWilayah.addEventListener('change', tampilkanRingkasanWilayah);
tampilkanRingkasanWilayah();
