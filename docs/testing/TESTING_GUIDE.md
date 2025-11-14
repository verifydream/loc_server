# Panduan Testing Fitur User Sync

## Persiapan Testing

### 1. Setup Environment Testing
```bash
# Copy .env
cp .env .env.testing

# Edit .env.testing
DB_DATABASE=location_server_test
EXTERNAL_API_EMAIL=admin@example.com
EXTERNAL_API_PASSWORD=test_password
```

### 2. Buat Database Testing
```sql
CREATE DATABASE location_server_test;
```

### 3. Jalankan Migration
```bash
php artisan migrate --env=testing
```

### 4. Seed Data (Optional)
```bash
php artisan db:seed --env=testing
```

## Test Case 1: Preview Sync - Server dengan User Baru

**Tujuan**: Memastikan sistem bisa detect user baru dari API

**Langkah**:
1. Login sebagai admin
2. Buka "User Management"
3. Klik "Sync from Server" → Pilih "Dev"
4. Tunggu loading (5-30 detik)

**Expected Result**:
- Halaman preview muncul
- Summary card "New Users" menampilkan jumlah > 0
- Tabel "New Users" menampilkan daftar email
- Badge "Will be added" berwarna hijau

**Actual Result**: _____________

**Status**: [ ] Pass [ ] Fail

---

## Test Case 2: Preview Sync - Server dengan User Terhapus

**Tujuan**: Memastikan sistem bisa detect user yang sudah tidak ada di API

**Persiapan**:
1. Tambah user manual di database untuk location tertentu
2. Pastikan user tersebut TIDAK ada di server eksternal

**Langkah**:
1. Sync server tersebut
2. Lihat preview

**Expected Result**:
- Summary card "Deleted Users" menampilkan jumlah > 0
- Tabel "Deleted Users" menampilkan email yang ditambah manual
- Badge "Will be deactivated" berwarna merah

**Actual Result**: _____________

**Status**: [ ] Pass [ ] Fail

---

## Test Case 3: Execute Sync - Tambah User Baru

**Tujuan**: Memastikan sync bisa menambah user baru ke database

**Langkah**:
1. Preview sync untuk server dengan user baru
2. Catat jumlah "New Users" (misal: 5)
3. Klik "Confirm & Execute Sync"
4. Tunggu proses selesai

**Expected Result**:
- Notifikasi success muncul: "Sync completed successfully! 5 users added, ..."
- Redirect ke halaman User Management
- User baru muncul di tabel dengan status "Active"

**Verifikasi Database**:
```sql
SELECT * FROM users WHERE location_id = X ORDER BY created_at DESC LIMIT 10;
```

**Actual Result**: _____________

**Status**: [ ] Pass [ ] Fail

---

## Test Case 4: Execute Sync - Deactivate User

**Tujuan**: Memastikan sync bisa nonaktifkan user yang sudah tidak ada di API

**Persiapan**:
1. Tambah user manual: `test_delete@example.com` untuk location_id = 8
2. Pastikan user ini TIDAK ada di server DEV

**Langkah**:
1. Sync server DEV
2. Preview harus menampilkan user ini di "Deleted Users"
3. Execute sync

**Expected Result**:
- User `test_delete@example.com` status berubah jadi "Inactive"
- Notifikasi success: "... X users deactivated ..."

**Verifikasi Database**:
```sql
SELECT * FROM users WHERE email = 'test_delete@example.com' AND location_id = 8;
-- status harus 'inactive'
```

**Actual Result**: _____________

**Status**: [ ] Pass [ ] Fail

---

## Test Case 5: Duplicate Email di Location Berbeda

**Tujuan**: Memastikan satu email bisa digunakan di beberapa location

**Langkah**:
1. Tambah user manual: `duplicate@example.com` untuk location_id = 7 (TEST)
2. Tambah user manual: `duplicate@example.com` untuk location_id = 8 (DEV)

**Expected Result**:
- Kedua user berhasil ditambahkan
- Tidak ada error "Email already exists"
- Di tabel users ada 2 row dengan email sama tapi location_id berbeda

**Verifikasi Database**:
```sql
SELECT * FROM users WHERE email = 'duplicate@example.com';
-- Harus ada 2 row
```

**Actual Result**: _____________

**Status**: [ ] Pass [ ] Fail

---

## Test Case 6: Validation - Email Unique per Location

**Tujuan**: Memastikan email tidak bisa duplicate di location yang sama

**Langkah**:
1. Buka "Add User"
2. Isi email: `existing@example.com`, location: DEV, status: Active
3. Submit
4. Coba tambah lagi dengan email dan location yang sama

**Expected Result**:
- Submit pertama: Success
- Submit kedua: Error "Email already exists for this location"

**Actual Result**: _____________

**Status**: [ ] Pass [ ] Fail

---

## Test Case 7: API Error Handling - Login Failed

**Tujuan**: Memastikan sistem handle error login dengan baik

**Persiapan**:
1. Edit `.env`: Set `EXTERNAL_API_PASSWORD=wrong_password`
2. Clear config cache: `php artisan config:clear`

**Langkah**:
1. Coba sync server DEV

**Expected Result**:
- Error message: "Failed to login to external API: ..."
- Tidak ada 500 error
- User friendly error message

**Actual Result**: _____________

**Status**: [ ] Pass [ ] Fail

**Cleanup**: Restore password yang benar di `.env`

---

## Test Case 8: API Error Handling - Server Down

**Tujuan**: Memastikan sistem handle server down dengan baik

**Persiapan**:
1. Edit database: Ubah `online_url` location DEV jadi `https://invalid-url-12345.com`

**Langkah**:
1. Coba sync server DEV

**Expected Result**:
- Error message: "Failed to login to external API: Connection timeout" atau similar
- Tidak ada 500 error
- User bisa kembali ke halaman sebelumnya

**Actual Result**: _____________

**Status**: [ ] Pass [ ] Fail

**Cleanup**: Restore URL yang benar

---

## Test Case 9: Performance - Large Dataset

**Tujuan**: Memastikan sync bisa handle banyak user

**Persiapan**:
1. Pilih server dengan user > 100

**Langkah**:
1. Sync server tersebut
2. Catat waktu loading

**Expected Result**:
- Preview muncul dalam < 60 detik
- Tidak ada timeout error
- Semua user ditampilkan dengan benar

**Actual Result**: 
- Waktu loading: _____ detik
- Jumlah user: _____

**Status**: [ ] Pass [ ] Fail

---

## Test Case 10: UI/UX - Responsive Design

**Tujuan**: Memastikan UI responsive di berbagai device

**Langkah**:
1. Buka halaman User Management di:
   - Desktop (1920x1080)
   - Tablet (768x1024)
   - Mobile (375x667)

**Expected Result**:
- Tombol "Sync from Server" terlihat jelas
- Dropdown tidak terpotong
- Tabel responsive (scroll horizontal jika perlu)
- Preview page readable di semua device

**Actual Result**: _____________

**Status**: [ ] Pass [ ] Fail

---

## Test Case 11: Security - Unauthorized Access

**Tujuan**: Memastikan route sync hanya bisa diakses admin

**Langkah**:
1. Logout dari admin
2. Coba akses langsung: `/admin/users/sync/8/preview`

**Expected Result**:
- Redirect ke login page
- Tidak bisa akses tanpa login

**Actual Result**: _____________

**Status**: [ ] Pass [ ] Fail

---

## Test Case 12: Logging

**Tujuan**: Memastikan aktivitas sync tercatat di log

**Langkah**:
1. Hapus log lama: `> storage/logs/laravel.log`
2. Lakukan sync (preview + execute)
3. Cek log: `cat storage/logs/laravel.log`

**Expected Result**:
- Ada log entry untuk sync completed
- Format: "Sync completed for location X: Y inserted, Z deactivated"
- Jika ada error, tercatat dengan detail

**Actual Result**: _____________

**Status**: [ ] Pass [ ] Fail

---

## Regression Testing

Setelah semua test case pass, test fitur lama untuk memastikan tidak ada yang rusak:

- [ ] Login admin masih berfungsi
- [ ] Dashboard masih bisa diakses
- [ ] Add user manual masih berfungsi
- [ ] Edit user masih berfungsi
- [ ] Delete user masih berfungsi
- [ ] Location management masih berfungsi
- [ ] App version management masih berfungsi

---

## Test Summary

| Test Case | Status | Notes |
|-----------|--------|-------|
| TC1: Preview - User Baru | [ ] | |
| TC2: Preview - User Terhapus | [ ] | |
| TC3: Execute - Tambah User | [ ] | |
| TC4: Execute - Deactivate User | [ ] | |
| TC5: Duplicate Email | [ ] | |
| TC6: Validation | [ ] | |
| TC7: API Error - Login | [ ] | |
| TC8: API Error - Server Down | [ ] | |
| TC9: Performance | [ ] | |
| TC10: UI/UX | [ ] | |
| TC11: Security | [ ] | |
| TC12: Logging | [ ] | |

**Overall Status**: [ ] All Pass [ ] Some Fail

**Tested By**: _____________  
**Date**: _____________  
**Environment**: [ ] Local [ ] Staging [ ] Production

---

## Bug Report Template

Jika menemukan bug, catat dengan format:

**Bug ID**: BUG-001  
**Severity**: [ ] Critical [ ] High [ ] Medium [ ] Low  
**Test Case**: TC-X  
**Description**: _____________  
**Steps to Reproduce**:
1. _____________
2. _____________

**Expected**: _____________  
**Actual**: _____________  
**Screenshot**: (attach if any)  
**Log**: (paste relevant log)

---

## Notes

- Test di environment staging dulu sebelum production
- Backup database sebelum testing
- Jangan test di production tanpa backup
- Catat semua bug dan issue yang ditemukan
