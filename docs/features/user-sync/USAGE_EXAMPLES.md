# Contoh Penggunaan - User Sync Feature

## Skenario 1: Sync Server DEV Pertama Kali

### Situasi
Anda baru setup aplikasi Laravel dan ingin import semua user dari server DEV.

### Langkah-langkah

1. **Login sebagai Admin**
   - Buka: `http://your-domain.com/admin/login`
   - Email: `admin@locationserver.com`
   - Password: (sesuai setup)

2. **Buka User Management**
   - Klik menu "User Management" di sidebar
   - Atau akses: `http://your-domain.com/admin/users`

3. **Pilih Sync Server DEV**
   - Klik tombol dropdown "Sync from Server"
   - Pilih "Sync Dev"
   - Tunggu loading (5-30 detik)

4. **Review Preview**
   - Lihat summary card:
     - New Users: 25 (misalnya)
     - Deleted Users: 0
     - Unchanged: 0
   - Scroll ke bawah, lihat daftar 25 email yang akan ditambahkan

5. **Execute Sync**
   - Klik "Confirm & Execute Sync"
   - Konfirmasi popup: Klik "OK"
   - Tunggu proses selesai

6. **Verifikasi Hasil**
   - Notifikasi muncul: "Sync completed successfully! 25 users added, 0 users deactivated, 0 users unchanged."
   - Lihat tabel User Management, sekarang ada 25 user baru dengan location "Dev"

### Expected Result
✅ 25 user baru ditambahkan  
✅ Semua user status "Active"  
✅ Semua user location_id = 8 (Dev)  

---

## Skenario 2: Sync Server TEST yang Sudah Ada Data

### Situasi
Server TEST sudah punya beberapa user di database, tapi ada perubahan di server eksternal:
- 3 user baru ditambahkan di server
- 2 user dihapus dari server
- 10 user tidak berubah

### Langkah-langkah

1. **Pilih Sync Server TEST**
   - Klik "Sync from Server" → "Sync Test"

2. **Review Preview**
   - Summary:
     - New Users: 3
     - Deleted Users: 2
     - Unchanged: 10
   
3. **Cek Detail**
   - **New Users Table**: Lihat 3 email baru
     - `new_user1@dkm`
     - `new_user2@dkm`
     - `new_user3@dkm`
   
   - **Deleted Users Table**: Lihat 2 email yang akan dinonaktifkan
     - `old_user1@dkm`
     - `old_user2@dkm`
   
   - **Unchanged Users**: Klik untuk expand, lihat 10 email yang tidak berubah

4. **Execute Sync**
   - Klik "Confirm & Execute Sync"
   - Konfirmasi

5. **Verifikasi**
   - Filter by Location: "Test"
   - Filter by Status: "Active" → Lihat 13 user (10 lama + 3 baru)
   - Filter by Status: "Inactive" → Lihat 2 user yang dinonaktifkan

### Expected Result
✅ 3 user baru ditambahkan dengan status "Active"  
✅ 2 user lama status berubah jadi "Inactive"  
✅ 10 user lama tetap "Active"  

---

## Skenario 3: Sync Multiple Servers dengan Email yang Sama

### Situasi
Email `vendor1@dkm` digunakan di 3 server berbeda: DEV, TEST, dan PROD.

### Langkah-langkah

1. **Sync Server DEV**
   - Preview menampilkan `vendor1@dkm` di "New Users"
   - Execute sync
   - User ditambahkan: `vendor1@dkm` dengan location "Dev"

2. **Sync Server TEST**
   - Preview menampilkan `vendor1@dkm` di "New Users" (tidak conflict!)
   - Execute sync
   - User ditambahkan: `vendor1@dkm` dengan location "Test"

3. **Sync Server PROD**
   - Preview menampilkan `vendor1@dkm` di "New Users"
   - Execute sync
   - User ditambahkan: `vendor1@dkm` dengan location "Prod"

4. **Verifikasi Database**
   ```sql
   SELECT * FROM users WHERE email = 'vendor1@dkm';
   ```
   
   Result:
   ```
   | id | email          | location_id | status |
   |----|----------------|-------------|--------|
   | 50 | vendor1@dkm    | 8 (Dev)     | active |
   | 51 | vendor1@dkm    | 7 (Test)    | active |
   | 52 | vendor1@dkm    | 1 (Prod)    | active |
   ```

### Expected Result
✅ 3 user dengan email sama tapi location berbeda  
✅ Tidak ada error "Email already exists"  
✅ Constraint unique: `email + location_id` bekerja  

---

## Skenario 4: Handle Error - API Login Failed

### Situasi
Kredensial API di `.env` salah atau expired.

### Langkah-langkah

1. **Pilih Sync Server**
   - Klik "Sync from Server" → "Sync Dev"

2. **Error Muncul**
   - Halaman redirect kembali ke User Management
   - Notifikasi error (merah): "Failed to login to external API: Unauthorized"

3. **Troubleshooting**
   - Cek `.env`:
     ```env
     EXTERNAL_API_EMAIL=admin@example.com
     EXTERNAL_API_PASSWORD=wrong_password
     ```
   
   - Perbaiki password:
     ```env
     EXTERNAL_API_PASSWORD=correct_password
     ```
   
   - Clear config cache:
     ```bash
     php artisan config:clear
     ```

4. **Retry Sync**
   - Klik "Sync from Server" → "Sync Dev" lagi
   - Sekarang berhasil

### Expected Result
✅ Error message user-friendly  
✅ Tidak ada 500 error  
✅ User bisa retry setelah fix  

---

## Skenario 5: Handle Error - Server Down

### Situasi
Server eksternal sedang maintenance atau down.

### Langkah-langkah

1. **Pilih Sync Server**
   - Klik "Sync from Server" → "Sync Test"

2. **Loading Lama**
   - Loading indicator muncul
   - Tunggu 30 detik (timeout)

3. **Error Muncul**
   - Notifikasi error: "Failed to login to external API: Connection timeout"

4. **Action**
   - Coba lagi nanti
   - Atau hubungi admin server eksternal

### Expected Result
✅ Timeout setelah 30 detik (tidak hang forever)  
✅ Error message jelas  
✅ Aplikasi tetap stabil  

---

## Skenario 6: Sync dengan Dataset Besar (>500 Users)

### Situasi
Server PROD punya 800 user.

### Langkah-langkah

1. **Pilih Sync Server PROD**
   - Klik "Sync from Server" → "Sync Prod"

2. **Loading Lebih Lama**
   - Tunggu 30-60 detik
   - API fetch data dengan pagination (100 per page)

3. **Preview Muncul**
   - Summary: 800 total users
   - Tabel menampilkan semua dengan scroll

4. **Execute Sync**
   - Klik "Confirm & Execute Sync"
   - Proses insert 800 user (bisa 1-2 menit)

5. **Success**
   - Notifikasi: "Sync completed successfully! 800 users added, ..."

### Expected Result
✅ Pagination API bekerja  
✅ Tidak ada timeout  
✅ Semua 800 user berhasil ditambahkan  

---

## Skenario 7: Rollback Sync yang Salah

### Situasi
Anda tidak sengaja sync server yang salah dan ingin rollback.

### Langkah-langkah

1. **Identifikasi User yang Salah**
   - Filter by Location: "Dev"
   - Filter by Created Date: Today
   - Catat ID user yang baru ditambahkan

2. **Option 1: Soft Delete (Deactivate)**
   ```sql
   UPDATE users 
   SET status = 'inactive' 
   WHERE location_id = 8 
   AND DATE(created_at) = CURDATE();
   ```

3. **Option 2: Hard Delete**
   ```sql
   DELETE FROM users 
   WHERE location_id = 8 
   AND DATE(created_at) = CURDATE();
   ```

4. **Option 3: Restore dari Backup**
   ```bash
   mysql -u username -p database_name < backup_before_sync.sql
   ```

### Expected Result
✅ User yang salah dihapus/dinonaktifkan  
✅ User lain tidak terpengaruh  

---

## Skenario 8: Scheduled Sync (Future Enhancement)

### Situasi
Anda ingin sync otomatis setiap hari jam 2 pagi.

### Implementasi (Manual)

1. **Buat Command**
   ```bash
   php artisan make:command SyncUsersCommand
   ```

2. **Edit Command**
   ```php
   // app/Console/Commands/SyncUsersCommand.php
   public function handle()
   {
       $locations = Location::all();
       
       foreach ($locations as $location) {
           $result = $this->userSyncService->previewSync($location);
           
           if ($result['success']) {
               $this->userSyncService->executeSync($location, $result);
               $this->info("Synced {$location->location_name}");
           }
       }
   }
   ```

3. **Register di Kernel**
   ```php
   // app/Console/Kernel.php
   protected function schedule(Schedule $schedule)
   {
       $schedule->command('sync:users')->dailyAt('02:00');
   }
   ```

4. **Setup Cron**
   ```bash
   * * * * * cd /path-to-project && php artisan schedule:run >> /dev/null 2>&1
   ```

### Note
⚠️ Fitur ini belum diimplementasi. Gunakan dengan hati-hati!

---

## Tips & Best Practices

### 1. Sync Secara Berkala
- Sync minimal 1x seminggu untuk keep data up-to-date
- Pilih waktu low-traffic (malam/weekend)

### 2. Review Preview dengan Teliti
- Jangan langsung execute tanpa review
- Perhatikan jumlah "Deleted Users" - pastikan memang benar

### 3. Backup Sebelum Sync
- Backup database sebelum sync pertama kali
- Atau sebelum sync dengan perubahan besar

### 4. Monitor Logs
- Cek log setelah sync: `storage/logs/laravel.log`
- Perhatikan error atau warning

### 5. Test di Staging Dulu
- Jangan langsung sync di production
- Test di staging untuk memastikan tidak ada masalah

### 6. Handle Inactive Users
- Review inactive users secara berkala
- Hapus permanent jika sudah tidak diperlukan

### 7. Komunikasi dengan Tim
- Inform tim sebelum sync besar
- Dokumentasikan perubahan

---

## FAQ

**Q: Apakah sync akan menghapus user yang sudah ada?**  
A: Tidak. Sync hanya akan set status jadi "inactive", tidak hard delete.

**Q: Berapa lama waktu sync?**  
A: Tergantung jumlah user. Biasanya 5-30 detik untuk <100 user, 30-60 detik untuk >100 user.

**Q: Apakah bisa sync semua server sekaligus?**  
A: Saat ini belum. Harus sync satu per satu. Fitur bulk sync bisa ditambahkan nanti.

**Q: Apakah sync realtime?**  
A: Tidak. Sync hanya terjadi ketika tombol diklik. Tidak ada auto-sync.

**Q: Bagaimana jika API server down?**  
A: Akan muncul error message. Coba lagi nanti atau hubungi admin server.

**Q: Apakah bisa undo sync?**  
A: Tidak ada fitur undo otomatis. Harus manual via database atau restore backup.

**Q: Apakah sync mempengaruhi performa aplikasi?**  
A: Tidak. Sync berjalan di background dan tidak mempengaruhi user lain.

---

## Kontak Support

Jika ada pertanyaan atau masalah:
- Email: support@company.com
- Slack: #dev-support
- Dokumentasi: `docs/USER_SYNC_FEATURE.md`
