# Troubleshooting Guide - User Sync Feature

## Common Issues & Solutions

### Issue 1: "Failed to login to external API: Access token not found in response"

**Cause**: Format response API berbeda dari yang diharapkan.

**Solution**: 
Sudah diperbaiki di `ExternalApiService.php`. Service sekarang support berbagai format response:
- `{"access_token": "..."}`
- `{"token": "..."}`
- `{"object": {"access_token": "..."}}`
- `{"data": {"access_token": "..."}}`

**Verification**:
```bash
php test-api-login.php
```

Jika masih error, cek log:
```bash
tail -f storage/logs/laravel.log
```

---

### Issue 2: "Failed to create user: Email already exists"

**Cause**: Validation masih menggunakan constraint lama (email only).

**Solution**: 
Sudah diperbaiki di `UserService.php`. Validation sekarang menggunakan composite unique (email + location_id).

**Verification**:
```bash
php check-constraint.php
```

Expected output:
```
✓ NEW CONSTRAINT FOUND: users_email_location_unique (email + location_id)
✓✓✓ SUCCESS! Same email can be used in different locations!
```

**Manual Fix** (jika masih error):
```bash
# Clear cache
php artisan config:clear
php artisan cache:clear

# Re-run migration
php artisan migrate:rollback --step=1
php artisan migrate
```

---

### Issue 3: Sync Button Not Showing

**Cause**: View cache belum di-clear.

**Solution**:
```bash
php artisan view:clear
php artisan cache:clear
```

Refresh browser dengan Ctrl+F5 (hard refresh).

---

### Issue 4: "Connection timeout" atau "Failed to connect"

**Cause**: 
- Server eksternal down
- URL salah
- Firewall blocking

**Solution**:
1. Cek URL di database:
```sql
SELECT id, location_code, location_name, online_url FROM locations;
```

2. Test koneksi manual:
```bash
curl -I https://dev.mydeposys.com
```

3. Cek kredensial di `.env`:
```env
EXTERNAL_API_EMAIL=admin_surabaya@gmail.com
EXTERNAL_API_PASSWORD=123456
```

4. Test API login:
```bash
php test-api-login.php
```

---

### Issue 5: "Invalid response format: users data not found"

**Cause**: Format response API untuk fetch users berbeda.

**Solution**:
Service sudah support berbagai format:
- `{"object": [...]}`
- `{"data": [...]}`
- `{"users": [...]}`

Jika masih error, cek log untuk melihat format response sebenarnya:
```bash
tail -f storage/logs/laravel.log | grep "Fetch users response"
```

---

### Issue 6: Sync Berhasil tapi Data Tidak Muncul

**Cause**: Filter atau pagination issue.

**Solution**:
1. Clear filter di halaman User Management
2. Cek database langsung:
```sql
SELECT * FROM users 
WHERE location_id = 8 
ORDER BY created_at DESC 
LIMIT 10;
```

3. Refresh halaman dengan Ctrl+F5

---

### Issue 7: "SQLSTATE[23000]: Integrity constraint violation"

**Cause**: Constraint lama masih ada atau migration belum jalan.

**Solution**:
```bash
# Check constraint
php check-constraint.php

# If old constraint still exists, rollback and re-run
php artisan migrate:rollback --step=1
php artisan migrate

# Verify
php check-constraint.php
```

---

### Issue 8: Sync Lambat (>60 detik)

**Cause**: 
- Banyak user (>1000)
- API server lambat
- Timeout terlalu kecil

**Solution**:
1. Increase timeout di `app/Services/ExternalApiService.php`:
```php
protected $timeout = 60; // Dari 30 ke 60 detik
```

2. Atau sync di background (future enhancement)

---

### Issue 9: "Unauthorized" atau "401 Error"

**Cause**: 
- Kredensial salah
- Token expired
- User tidak punya akses

**Solution**:
1. Verify kredensial:
```bash
php test-api-login.php
```

2. Update kredensial di `.env`:
```env
EXTERNAL_API_EMAIL=correct_email@example.com
EXTERNAL_API_PASSWORD=correct_password
```

3. Clear config cache:
```bash
php artisan config:clear
```

---

### Issue 10: Preview Muncul tapi Execute Gagal

**Cause**: 
- Database error
- Transaction rollback
- Validation error

**Solution**:
1. Cek log:
```bash
tail -f storage/logs/laravel.log
```

2. Cek database connection:
```bash
php artisan tinker --execute="DB::connection()->getPdo();"
```

3. Test manual insert:
```sql
INSERT INTO users (email, location_id, status, created_at, updated_at)
VALUES ('test@example.com', 8, 'active', NOW(), NOW());
```

---

## Debugging Tools

### 1. Test API Login
```bash
php test-api-login.php
```

### 2. Check Database Constraint
```bash
php check-constraint.php
```

### 3. Check Logs
```bash
# Real-time log
tail -f storage/logs/laravel.log

# Last 50 lines
tail -n 50 storage/logs/laravel.log

# Search for errors
grep "ERROR" storage/logs/laravel.log

# Search for sync activity
grep "Sync" storage/logs/laravel.log
```

### 4. Check Routes
```bash
php artisan route:list | grep sync
```

### 5. Check Config
```bash
php artisan tinker --execute="config('services.external_api')"
```

---

## Quick Fixes

### Clear All Cache
```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
```

### Re-run Migration
```bash
php artisan migrate:rollback --step=1
php artisan migrate
```

### Reset Database (DANGER!)
```bash
# Backup first!
php artisan migrate:fresh --seed
```

---

## Getting Help

### 1. Check Documentation
- [USER_SYNC_FEATURE.md](docs/USER_SYNC_FEATURE.md)
- [QUICK_REFERENCE.md](QUICK_REFERENCE.md)
- [DEPLOYMENT_INSTRUCTIONS.md](DEPLOYMENT_INSTRUCTIONS.md)

### 2. Check Logs
```bash
tail -f storage/logs/laravel.log
```

### 3. Enable Debug Mode (Temporary)
Edit `.env`:
```env
APP_DEBUG=true
```

**IMPORTANT**: Set back to `false` in production!

### 4. Contact Support
- Email: support@company.com
- Slack: #dev-support

---

## Prevention Tips

1. **Always backup** before sync
2. **Test in staging** first
3. **Review preview** carefully
4. **Monitor logs** after sync
5. **Keep credentials** secure
6. **Update documentation** when API changes

---

**Last Updated**: November 8, 2025  
**Version**: 1.1
