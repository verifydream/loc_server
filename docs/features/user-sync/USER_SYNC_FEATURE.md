# Fitur Sinkronisasi User Multi-Server

## Deskripsi
Fitur ini memungkinkan sinkronisasi akun email user dari server eksternal (dev, test, prod, dll) ke database Laravel secara manual dengan preview konfirmasi.

## Perubahan Utama

### 1. Database Schema
- **Constraint Unique Email**: Diubah dari global menjadi composite unique per kombinasi `email + location_id`
- Sekarang satu email bisa digunakan di beberapa server/location yang berbeda
- Contoh: `dev_vendor1@dkm` bisa ada di server DEV dan TEST secara bersamaan

### 2. Fitur Sinkronisasi
- **Manual Sync**: Tombol "Sync from Server" di halaman User Management
- **Preview Changes**: Menampilkan ringkasan perubahan sebelum eksekusi:
  - Akun baru (akan ditambahkan)
  - Akun terhapus (akan dinonaktifkan)
  - Akun tidak berubah
- **Konfirmasi User**: Sync hanya dijalankan setelah user menekan "Confirm & Execute"

### 3. Keamanan
- Kredensial API disimpan di `.env`
- JWT token authentication
- Error handling yang aman
- Logging untuk audit trail

## Instalasi & Setup

### 1. Update Database

Jalankan migration untuk mengubah constraint unique:

```bash
php artisan migrate
```

Migration akan:
- Drop constraint `users_email_unique`
- Tambah constraint `users_email_location_unique` (composite)

### 2. Konfigurasi Environment

Tambahkan kredensial API eksternal di file `.env`:

```env
# External API Configuration (for User Sync)
EXTERNAL_API_EMAIL=admin@example.com
EXTERNAL_API_PASSWORD=your_secure_password_here
```

**PENTING**: Ganti dengan kredensial admin yang valid untuk login ke API eksternal.

### 3. Verifikasi URL Server

Pastikan setiap location di database memiliki `online_url` yang valid:

```sql
SELECT id, location_code, location_name, online_url FROM locations;
```

Contoh URL yang benar:
- `https://dev.mydeposys.com`
- `https://sby.mydeposys.com`
- `https://apidepotest.dwipakharismamitra.com`

## Cara Penggunaan

### 1. Akses Halaman User Management
- Login sebagai admin
- Buka menu "User Management"

### 2. Pilih Server untuk Sync
- Klik tombol dropdown "Sync from Server"
- Pilih server yang ingin disinkronkan (misal: "Sync Dev")

### 3. Review Preview
Sistem akan menampilkan:
- **Summary Cards**: Jumlah user baru, terhapus, dan tidak berubah
- **Detail Tables**: Daftar lengkap email per kategori

### 4. Konfirmasi & Execute
- Review perubahan dengan teliti
- Klik "Confirm & Execute Sync" jika sudah yakin
- Sistem akan:
  - Menambahkan user baru dengan status "active"
  - Mengubah status user yang terhapus menjadi "inactive"
  - Tidak mengubah user yang sudah sama

### 5. Hasil Sync
Setelah selesai, akan muncul notifikasi:
```
Sync completed successfully! X users added, Y users deactivated, Z users unchanged.
```

## API Endpoint yang Digunakan

### 1. Login API
```
POST {base_url}/api/auth/login
Headers:
  - X-Requested-With: XMLHttpRequest
  - Accept: application/json
Body:
  - email: {EXTERNAL_API_EMAIL}
  - password: {EXTERNAL_API_PASSWORD}
Response:
  - access_token: JWT token
  - token_type: bearer
```

### 2. Fetch Users API
```
GET {base_url}/api/conf/users
Headers:
  - X-Requested-With: XMLHttpRequest
  - Accept: application/json
  - Authorization: Bearer {token}
Query Parameters:
  - q: (empty string)
  - limit: 100
  - page: 1
  - sort: id_user
  - order: desc
Response:
  - object: array of user objects
```

## Error Handling

### Kemungkinan Error & Solusi

1. **"Failed to login to external API"**
   - Cek kredensial di `.env`
   - Pastikan `EXTERNAL_API_EMAIL` dan `EXTERNAL_API_PASSWORD` benar
   - Cek koneksi internet

2. **"Failed to fetch users from external API"**
   - Cek URL server di database (tabel `locations`)
   - Pastikan server eksternal online
   - Cek token JWT masih valid

3. **"Invalid response format"**
   - API eksternal mungkin berubah format response
   - Cek log di `storage/logs/laravel.log`

4. **"Connection timeout"**
   - Server eksternal lambat atau down
   - Coba lagi beberapa saat kemudian

## Logging

Semua aktivitas sync dicatat di log:
- Login success/failure
- Fetch users success/failure
- Sync execution details

Lokasi log: `storage/logs/laravel.log`

Contoh log entry:
```
[2025-11-08 10:30:45] local.INFO: Sync completed for location 8: 5 inserted, 2 deactivated
```

## Keamanan

### Best Practices
1. **Jangan commit file `.env`** ke repository
2. **Gunakan password yang kuat** untuk `EXTERNAL_API_PASSWORD`
3. **Batasi akses** halaman sync hanya untuk admin
4. **Review log secara berkala** untuk deteksi anomali
5. **Backup database** sebelum sync pertama kali

### Rollback
Jika terjadi kesalahan, Anda bisa:
1. Restore dari backup database
2. Atau manual update status user yang salah:
```sql
UPDATE users SET status = 'active' WHERE email = 'user@example.com' AND location_id = 8;
```

## Troubleshooting

### Sync Tidak Menambahkan User Baru
- Cek apakah email sudah ada di database untuk location tersebut
- Cek format email dari API (harus valid email format)

### User Tidak Muncul di Preview
- Cek response API di log
- Pastikan field `email` ada di response API

### Duplicate Entry Error
- Jalankan migration lagi: `php artisan migrate:fresh` (HATI-HATI: akan hapus semua data)
- Atau manual drop constraint lama dan buat yang baru

## Pengembangan Lebih Lanjut

### Fitur yang Bisa Ditambahkan
1. **Scheduled Sync**: Otomatis sync setiap hari via cron job
2. **Sync History**: Simpan riwayat sync di database
3. **Email Notification**: Kirim email ke admin setelah sync selesai
4. **Selective Sync**: Pilih email tertentu untuk di-sync
5. **Bulk Sync**: Sync semua server sekaligus

### Customization
Jika ingin mengubah behavior:
- **Soft delete vs Hard delete**: Edit `UserSyncService::executeSync()`
- **Timeout API**: Edit `ExternalApiService::$timeout`
- **Retry logic**: Edit `ExternalApiService::$retryTimes`

## Support
Jika ada pertanyaan atau masalah, hubungi tim development.
