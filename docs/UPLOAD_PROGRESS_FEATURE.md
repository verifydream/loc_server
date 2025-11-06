# Fitur Upload Progress untuk APK

## Deskripsi
Fitur ini menambahkan progress bar real-time saat upload file APK untuk manajemen versi aplikasi. User dapat melihat:
- Persentase upload (0-100%)
- Kecepatan upload (KB/s, MB/s)
- Estimasi waktu tersisa (ETA)
- Informasi file yang dipilih (nama dan ukuran)

## Implementasi

### 1. Halaman Create (Upload Baru)
**File:** `resources/views/admin/app-versions/create.blade.php`

Fitur yang ditambahkan:
- Display informasi file yang dipilih (nama & ukuran)
- Progress bar dengan animasi
- Indikator persentase upload
- Kecepatan upload real-time
- Estimasi waktu tersisa (ETA)
- Perubahan warna progress bar berdasarkan persentase:
  - 0-49%: Biru (primary)
  - 50-89%: Kuning (warning)
  - 90-100%: Hijau (success)

### 2. Halaman Edit (Update Versi)
**File:** `resources/views/admin/app-versions/edit.blade.php`

Fitur yang sama dengan halaman create, dengan tambahan:
- Progress bar hanya muncul jika user memilih file APK baru
- Jika tidak ada file baru, form submit secara normal tanpa progress bar

## Cara Kerja

### JavaScript XMLHttpRequest
Menggunakan `XMLHttpRequest` dengan event listener `upload.progress` untuk tracking upload:

```javascript
xhr.upload.addEventListener('progress', function(e) {
    if (e.lengthComputable) {
        const percentComplete = (e.loaded / e.total) * 100;
        // Update UI
    }
});
```

### Perhitungan Kecepatan & ETA
- **Kecepatan:** `bytes uploaded / elapsed time`
- **ETA:** `remaining bytes / upload speed`

### Format Display
- File size: Otomatis convert ke Bytes, KB, MB, atau GB
- Time: Format dalam detik, menit, atau jam
- Speed: Menampilkan dalam KB/s atau MB/s

## UI/UX Features

1. **File Selection Feedback**
   - Menampilkan nama file dan ukuran setelah dipilih
   - Icon file untuk visual feedback

2. **Progress Bar**
   - Animasi striped saat upload
   - Perubahan warna dinamis
   - Teks persentase di dalam bar

3. **Upload Stats**
   - Badge persentase di pojok kanan
   - Kecepatan upload real-time
   - Estimasi waktu tersisa

4. **Button States**
   - Disable semua button saat upload
   - Disable file input saat upload
   - Re-enable jika terjadi error

5. **Error Handling**
   - Network error detection
   - Upload abort handling
   - Visual feedback dengan progress bar merah
   - Auto-hide error message setelah 3 detik

## Testing

### Test Upload File Kecil (< 10MB)
1. Pilih file APK kecil
2. Klik Upload
3. Progress bar akan terisi dengan cepat
4. Redirect otomatis setelah selesai

### Test Upload File Besar (> 50MB)
1. Pilih file APK besar
2. Klik Upload
3. Monitor progress bar, speed, dan ETA
4. Pastikan semua informasi update secara real-time

### Test Error Handling
1. Mulai upload
2. Disconnect internet saat upload
3. Pastikan error message muncul
4. Pastikan button kembali enabled

## Browser Compatibility
- Chrome/Edge: ✅ Full support
- Firefox: ✅ Full support
- Safari: ✅ Full support
- Opera: ✅ Full support

## Notes
- Maximum file size: 150MB (sesuai validasi Laravel)
- Progress tracking menggunakan native browser API
- Tidak memerlukan library tambahan
- Compatible dengan semua modern browsers
