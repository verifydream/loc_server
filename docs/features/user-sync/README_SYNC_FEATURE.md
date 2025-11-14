# User Sync Multi-Server Feature

## Overview

Fitur ini memungkinkan sinkronisasi akun email user dari server eksternal (dev, test, prod, dll) ke database Laravel secara manual dengan preview konfirmasi.

## Key Features

✅ **Multi-Server Support** - Satu email bisa digunakan di beberapa server berbeda  
✅ **Manual Sync** - Kontrol penuh, tidak ada sync otomatis  
✅ **Preview Changes** - Lihat perubahan sebelum eksekusi  
✅ **Safe Operations** - Soft delete (deactivate) untuk user yang terhapus  
✅ **Error Handling** - User-friendly error messages  
✅ **Audit Logging** - Semua aktivitas tercatat  

## Quick Start

### 1. Setup Environment

Edit `.env`:
```env
EXTERNAL_API_EMAIL=admin@example.com
EXTERNAL_API_PASSWORD=your_password
```

### 2. Run Migration

```bash
php artisan migrate
```

### 3. Clear Cache

```bash
php artisan config:clear
php artisan cache:clear
```

### 4. Test Sync

1. Login sebagai admin
2. Buka "User Management"
3. Klik "Sync from Server" → Pilih server
4. Review preview
5. Klik "Confirm & Execute"

## Documentation

| Document | Description |
|----------|-------------|
| [USER_SYNC_FEATURE.md](docs/USER_SYNC_FEATURE.md) | Dokumentasi lengkap fitur |
| [DEPLOYMENT_INSTRUCTIONS.md](DEPLOYMENT_INSTRUCTIONS.md) | Panduan deployment step-by-step |
| [TESTING_GUIDE.md](docs/TESTING_GUIDE.md) | Test cases dan panduan testing |
| [SYNC_FEATURE_SUMMARY.md](docs/SYNC_FEATURE_SUMMARY.md) | Ringkasan perubahan |
| [QUICK_REFERENCE.md](QUICK_REFERENCE.md) | Command dan query reference |

## Architecture

### Services
- **ExternalApiService** - Handle komunikasi dengan API eksternal (login, fetch users)
- **UserSyncService** - Logic sinkronisasi (preview, execute)

### Database Changes
- Constraint unique: `email + location_id` (bukan hanya `email`)
- Memungkinkan duplicate email di location berbeda

### API Endpoints
- `POST /api/auth/login` - Get JWT token
- `GET /api/conf/users` - Fetch user list

## Files Created/Modified

### New Files
```
app/Services/ExternalApiService.php
app/Services/UserSyncService.php
database/migrations/2025_11_08_000000_modify_users_email_unique_constraint.php
resources/views/admin/users/sync-preview.blade.php
docs/USER_SYNC_FEATURE.md
docs/TESTING_GUIDE.md
docs/SYNC_FEATURE_SUMMARY.md
DEPLOYMENT_INSTRUCTIONS.md
QUICK_REFERENCE.md
README_SYNC_FEATURE.md
```

### Modified Files
```
app/Http/Controllers/UserController.php
routes/web.php
config/services.php
.env.example
resources/views/admin/users/index.blade.php
```

## Security

- ✅ Kredensial API di `.env` (tidak di-commit)
- ✅ JWT token authentication
- ✅ Admin middleware protection
- ✅ Input validation
- ✅ Error logging

## Troubleshooting

### Migration Failed
```bash
# Check for duplicates
mysql -u user -p -e "SELECT email, COUNT(*) FROM users GROUP BY email HAVING COUNT(*) > 1;"
```

### API Login Failed
```bash
# Check credentials
cat .env | grep EXTERNAL_API
```

### Sync Button Not Showing
```bash
php artisan view:clear
```

### Check Logs
```bash
tail -f storage/logs/laravel.log
```

## Support

Untuk pertanyaan atau masalah:
1. Cek dokumentasi di folder `docs/`
2. Cek log di `storage/logs/laravel.log`
3. Hubungi tim development

## Version

- **Feature Version**: 1.0
- **Laravel**: 10.x
- **PHP**: 8.1+
- **Database**: MySQL/MariaDB

## License

Internal use only - PT Dwipa Kharisma Mitra
