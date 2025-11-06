# Testing Guide - Upload Progress Feature

## 🎯 Cara Testing Fitur Upload Progress

### Metode 1: Testing dengan Demo HTML (Tanpa Backend)

1. **Buka file test**
   ```bash
   # Buka file ini di browser
   test-upload-progress.html
   ```

2. **Test flow:**
   - Pilih file APK (atau file apapun untuk demo)
   - Klik "Test Upload"
   - Lihat progress bar berjalan selama 10 detik
   - Perhatikan perubahan warna, speed, dan ETA

3. **Keuntungan:**
   - Tidak perlu setup backend
   - Cepat untuk testing UI/UX
   - Simulasi upload yang konsisten

### Metode 2: Testing dengan Laravel (Real Upload)

#### Persiapan

1. **Pastikan server Laravel berjalan**
   ```bash
   php artisan serve
   ```

2. **Login ke admin dashboard**
   ```
   URL: http://localhost:8000/admin/login
   Email: admin@example.com (sesuai seeder)
   Password: password (sesuai seeder)
   ```

#### Test Case 1: Upload File Kecil (< 10MB)

1. Navigate ke: **App Updates > Upload Versi Baru**
2. Isi form:
   - Version Name: `1.0.0`
   - Version Code: `1`
   - Release Notes: `Initial release`
   - APK File: Pilih file APK kecil (< 10MB)
3. Klik **Upload**
4. **Expected Result:**
   - Progress bar muncul
   - Upload selesai dengan cepat (< 5 detik)
   - Progress bar berubah hijau
   - Redirect ke index page

#### Test Case 2: Upload File Besar (> 50MB)

1. Navigate ke: **App Updates > Upload Versi Baru**
2. Isi form dengan file APK besar (> 50MB)
3. Klik **Upload**
4. **Expected Result:**
   - Progress bar muncul
   - Dapat melihat progress bertahap (0% → 100%)
   - Speed dan ETA update secara real-time
   - Warna berubah: Biru → Kuning → Hijau
   - Upload selesai dan redirect

#### Test Case 3: Edit dengan File Baru

1. Navigate ke: **App Updates > [Pilih versi] > Edit**
2. Pilih file APK baru
3. Klik **Update Version**
4. **Expected Result:**
   - Progress bar muncul (karena ada file baru)
   - Upload berjalan dengan progress tracking
   - Update berhasil

#### Test Case 4: Edit tanpa File Baru

1. Navigate ke: **App Updates > [Pilih versi] > Edit**
2. Ubah hanya Version Name atau Release Notes
3. **JANGAN** pilih file APK baru
4. Klik **Update Version**
5. **Expected Result:**
   - Progress bar TIDAK muncul
   - Form submit secara normal
   - Update berhasil tanpa upload file

#### Test Case 5: Error Handling - Network Error

1. Mulai upload file besar
2. Saat progress bar di 30-50%, disconnect internet
3. **Expected Result:**
   - Progress bar berubah merah
   - Error message muncul: "Network error occurred..."
   - Button kembali enabled
   - User bisa retry upload

#### Test Case 6: File Info Display

1. Klik input file APK
2. Pilih file
3. **Expected Result:**
   - Muncul info file di bawah input
   - Menampilkan nama file
   - Menampilkan ukuran file dalam format yang mudah dibaca (MB/GB)

### Metode 3: Testing dengan Koneksi Lambat (Chrome DevTools)

1. **Buka Chrome DevTools** (F12)
2. **Go to Network tab**
3. **Throttling:** Pilih "Slow 3G" atau "Fast 3G"
4. **Upload file APK**
5. **Expected Result:**
   - Progress bar bergerak lebih lambat
   - Dapat melihat ETA yang lebih lama
   - Speed menunjukkan kecepatan yang lebih rendah

### 📊 Checklist Testing

#### UI/UX Testing
- [ ] Progress bar muncul saat upload dimulai
- [ ] Persentase update secara real-time
- [ ] Speed ditampilkan dalam format yang benar (KB/s, MB/s)
- [ ] ETA ditampilkan dalam format yang mudah dibaca
- [ ] Warna progress bar berubah sesuai persentase
- [ ] File info muncul setelah pilih file
- [ ] Button disabled saat upload
- [ ] File input disabled saat upload

#### Functional Testing
- [ ] Upload file kecil berhasil
- [ ] Upload file besar berhasil
- [ ] Edit dengan file baru berhasil
- [ ] Edit tanpa file baru berhasil (no progress bar)
- [ ] Redirect setelah upload berhasil
- [ ] Error handling bekerja dengan baik

#### Browser Compatibility
- [ ] Chrome/Edge
- [ ] Firefox
- [ ] Safari (jika ada Mac)
- [ ] Opera

#### Responsive Testing
- [ ] Desktop (1920x1080)
- [ ] Tablet (768x1024)
- [ ] Mobile (375x667)

### 🐛 Known Issues / Limitations

1. **Maximum File Size:** 150MB (sesuai validasi Laravel)
2. **Browser Support:** Hanya modern browsers (IE tidak support)
3. **Progress Accuracy:** Tergantung pada server response time

### 💡 Tips Testing

1. **Gunakan file APK asli** untuk testing yang lebih realistis
2. **Test dengan berbagai ukuran file** (kecil, sedang, besar)
3. **Test dengan koneksi berbeda** (cepat, lambat, unstable)
4. **Perhatikan console browser** untuk error JavaScript
5. **Check network tab** untuk melihat actual upload request

### 📝 Reporting Issues

Jika menemukan bug atau issue:

1. **Screenshot** progress bar saat error
2. **Console log** dari browser DevTools
3. **Network tab** untuk melihat request/response
4. **Deskripsi** langkah-langkah untuk reproduce issue
5. **Browser & OS** yang digunakan

### ✅ Success Criteria

Fitur dianggap berhasil jika:
- ✅ Progress bar muncul dan update secara real-time
- ✅ Upload berhasil untuk berbagai ukuran file
- ✅ Error handling bekerja dengan baik
- ✅ UI responsive di berbagai device
- ✅ Tidak ada error di console browser
- ✅ User experience smooth dan informative

---

**Happy Testing! 🚀**
