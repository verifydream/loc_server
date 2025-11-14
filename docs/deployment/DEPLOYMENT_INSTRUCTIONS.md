# Instruksi Deployment - Fitur User Sync Multi-Server

## Checklist Pre-Deployment

- [ ] Backup database production
- [ ] Test di environment staging terlebih dahulu
- [ ] Siapkan kredensial API eksternal yang valid
- [ ] Pastikan semua server eksternal online dan accessible

## Langkah Deployment

### 1. Pull Code dari Repository

```bash
git pull origin main
```

### 2. Install Dependencies (jika ada perubahan)

```bash
composer install --no-dev --optimize-autoloader
```

### 3. Update Environment Variables

Edit file `.env` dan tambahkan:

```env
# External API Configuration
EXTERNAL_API_EMAIL=admin@example.com
EXTERNAL_API_PASSWORD=your_actual_password_here
```

**PENTING**: Ganti dengan kredensial yang benar!

### 4. Backup Database

```bash
# Untuk MySQL
mysqldump -u username -p database_name > backup_before_sync_$(date +%Y%m%d_%H%M%S).sql

# Atau via phpMyAdmin: Export database
```

### 5. Jalankan Migration

```bash
php artisan migrate
```

Output yang diharapkan:
```
Migrating: 2025_11_08_000000_modify_users_email_unique_constraint
Migrated:  2025_11_08_000000_modify_users_email_unique_constraint (XX.XXms)
```

### 6. Clear Cache

```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
```

### 7. Optimize untuk Production

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 8. Set Permissions (jika perlu)

```bash
# Linux/Mac
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

# Windows: Pastikan folder storage dan bootstrap/cache writable
```

## Testing Post-Deployment

### 1. Test Login Admin
- Buka `/admin/login`
- Login dengan kredensial admin
- Pastikan bisa akses dashboard

### 2. Test User Management Page
- Buka menu "User Management"
- Pastikan tombol "Sync from Server" muncul
- Pastikan dropdown menampilkan semua location

### 3. Test Sync Preview (Dry Run)
- Klik "Sync from Server" → Pilih salah satu server (misal: Dev)
- Tunggu loading (bisa 5-30 detik tergantung jumlah user)
- Pastikan preview muncul dengan summary yang benar
- **JANGAN klik "Confirm & Execute" dulu!**
- Klik "Back" atau "Cancel"

### 4. Test Sync Execution (Real Run)
- Pilih server dengan data sedikit untuk test pertama
- Review preview dengan teliti
- Klik "Confirm & Execute Sync"
- Pastikan muncul notifikasi success
- Cek di tabel users apakah data sudah bertambah/berubah

### 5. Verify Database
```sql
-- Cek apakah constraint sudah berubah
SHOW CREATE TABLE users;
-- Harus ada: UNIQUE KEY `users_email_location_unique` (`email`,`location_id`)

-- Cek apakah ada duplicate email di location berbeda
SELECT email, COUNT(*) as count, GROUP_CONCAT(location_id) as locations
FROM users
GROUP BY email
HAVING count > 1;
-- Harusnya ada hasil jika sync berhasil
```

## Rollback Plan

Jika terjadi masalah serius:

### Option 1: Rollback Migration

```bash
php artisan migrate:rollback --step=1
```

Ini akan:
- Drop constraint `users_email_location_unique`
- Restore constraint `users_email_unique`

**WARNING**: Jika sudah ada duplicate email, rollback akan GAGAL!

### Option 2: Restore Database

```bash
# Restore dari backup
mysql -u username -p database_name < backup_before_sync_YYYYMMDD_HHMMSS.sql
```

### Option 3: Manual Fix

Jika hanya beberapa data yang bermasalah:

```sql
-- Hapus user yang salah
DELETE FROM users WHERE id = XXX;

-- Atau ubah status
UPDATE users SET status = 'inactive' WHERE id = XXX;
```

## Monitoring Post-Deployment

### 1. Check Logs
```bash
tail -f storage/logs/laravel.log
```

Perhatikan error atau warning terkait sync.

### 2. Monitor Performance
- Cek waktu response halaman sync preview
- Jika lambat (>30 detik), pertimbangkan:
  - Increase timeout di `ExternalApiService`
  - Optimize API eksternal
  - Add caching mechanism

### 3. User Feedback
- Minta admin untuk test sync di semua server
- Catat feedback atau bug yang ditemukan

## Troubleshooting Common Issues

### Issue 1: Migration Failed - Duplicate Entry

**Error:**
```
SQLSTATE[23000]: Integrity constraint violation: 1062 Duplicate entry 'email@example.com' for key 'users_email_unique'
```

**Solusi:**
Ada data duplicate di database. Cek dan bersihkan:

```sql
-- Cari duplicate
SELECT email, COUNT(*) FROM users GROUP BY email HAVING COUNT(*) > 1;

-- Hapus duplicate (keep yang terbaru)
DELETE u1 FROM users u1
INNER JOIN users u2 
WHERE u1.id < u2.id AND u1.email = u2.email;
```

Lalu jalankan migration lagi.

### Issue 2: API Login Failed

**Error:**
```
Failed to login to external API: Connection timeout
```

**Solusi:**
1. Cek koneksi internet server
2. Cek URL di tabel `locations` (harus https://)
3. Cek kredensial di `.env`
4. Test manual via Postman/curl

### Issue 3: Sync Button Not Showing

**Solusi:**
1. Clear cache: `php artisan view:clear`
2. Cek apakah ada location di database
3. Cek permission file view

### Issue 4: 500 Internal Server Error

**Solusi:**
1. Cek log: `storage/logs/laravel.log`
2. Enable debug: `APP_DEBUG=true` di `.env` (temporary)
3. Cek permission folder storage

## Performance Optimization

### Untuk Server dengan Banyak User (>1000)

Edit `app/Services/ExternalApiService.php`:

```php
protected $timeout = 60; // Increase dari 30 ke 60 detik
```

### Untuk API yang Lambat

Tambahkan loading indicator di view (sudah ada di template).

### Untuk Reduce API Calls

Pertimbangkan caching:

```php
// Di UserSyncService::previewSync()
$cacheKey = "sync_preview_{$location->id}";
$result = Cache::remember($cacheKey, 300, function() use ($location) {
    // ... existing code
});
```

## Security Checklist

- [ ] `.env` tidak ter-commit ke git
- [ ] `APP_DEBUG=false` di production
- [ ] Password API cukup kuat (min 12 karakter)
- [ ] HTTPS enabled untuk semua URL
- [ ] Middleware `admin.auth` aktif untuk route sync
- [ ] Log file tidak accessible dari web

## Contact & Support

Jika ada masalah saat deployment:
1. Cek dokumentasi di `docs/USER_SYNC_FEATURE.md`
2. Cek log di `storage/logs/laravel.log`
3. Hubungi tim development

---

**Deployment Date**: _____________  
**Deployed By**: _____________  
**Status**: [ ] Success [ ] Failed [ ] Rolled Back  
**Notes**: _____________________________________________
