# Quick Start - API Testing Guide

Panduan cepat untuk testing API menggunakan Postman.

## 📋 Prerequisites

1. ✅ Laravel server running (`php artisan serve`)
2. ✅ Database sudah di-migrate dan ada data
3. ✅ API Key sudah di-set di `.env`
4. ✅ Postman installed

## 🚀 Quick Setup (5 Menit)

### Step 1: Cek API Key

Buka file `.env` dan cari:
```env
FLUTTER_API_KEY=your-secret-api-key-123
```

Jika belum ada, tambahkan dan restart server:
```bash
php artisan config:clear
php artisan serve
```

### Step 2: Import Postman Collection

1. Buka Postman
2. Click **Import** button
3. Pilih file `postman_collection.json`
4. Collection "Location Server API" akan muncul

### Step 3: Setup Environment Variables

Di Postman:
1. Click ⚙️ (Settings) → **Environments**
2. Create new environment: "Local Development"
3. Tambahkan variables:

| Variable | Value |
|----------|-------|
| base_url | http://localhost:8000 |
| api_key | your-secret-api-key-123 |

4. Save dan pilih environment "Local Development"

### Step 4: Test API

Pilih request dari collection dan click **Send**!

## 🧪 Test Scenarios

### ✅ Test 1: Check Location (Success)

**Request:** `POST /api/check-location`

**Body:**
```json
{
  "email": "user@example.com"
}
```

**Expected Response (200):**
```json
{
  "success": true,
  "data": {
    "user": { ... },
    "location": { ... }
  }
}
```

---

### ✅ Test 2: Latest Version (Success)

**Request:** `GET /api/latest-version`

**Expected Response (200):**
```json
{
  "status": "success",
  "data": {
    "version_name": "1.0.0",
    "version_code": 1,
    "release_notes": "...",
    "download_url": "/storage/updates/..."
  }
}
```

---

### ❌ Test 3: Invalid API Key

**Request:** Any endpoint dengan header:
```
X-Api-Key: wrong-key
```

**Expected Response (401):**
```json
{
  "message": "Unauthorized"
}
```

---

### ❌ Test 4: Validation Error

**Request:** `POST /api/check-location`

**Body:**
```json
{
  "email": "invalid-email"
}
```

**Expected Response (422):**
```json
{
  "success": false,
  "message": "Validation error",
  "errors": {
    "email": ["The email must be a valid email address."]
  }
}
```

## 📊 Available Endpoints

| Method | Endpoint | Description |
|--------|----------|-------------|
| POST/GET | `/api/check-location` | Check user location by email |
| GET | `/api/latest-version` | Get latest APK version |

## 🔑 Authentication

Semua endpoint memerlukan header:
```
X-Api-Key: your-api-key-here
```

## 📝 Common Headers

```
X-Api-Key: {{api_key}}
Content-Type: application/json
Accept: application/json
```

## 🐛 Troubleshooting

### Problem: 401 Unauthorized
**Solution:** Cek API Key di `.env` dan header request

### Problem: 404 Not Found
**Solution:** 
```bash
php artisan route:clear
php artisan route:list | grep api
```

### Problem: 500 Internal Server Error
**Solution:** Cek log:
```bash
tail -f storage/logs/laravel.log
```

### Problem: Connection Refused
**Solution:** Pastikan server running:
```bash
php artisan serve
```

## 📚 Full Documentation

Untuk dokumentasi lengkap, lihat file `API_DOCUMENTATION.md`

## 🎯 Testing Checklist

- [ ] API Key valid dan di-set di environment
- [ ] Server Laravel running
- [ ] Database ada data user dan location
- [ ] Postman collection sudah di-import
- [ ] Environment variables sudah di-set
- [ ] Test semua success scenarios
- [ ] Test semua error scenarios
- [ ] Cek response time < 1 second
- [ ] Cek rate limiting (60 req/min)

## 💡 Tips

1. **Save Responses:** Save example responses di Postman untuk dokumentasi
2. **Use Variables:** Gunakan `{{variable}}` untuk dynamic values
3. **Test Scripts:** Tambahkan test scripts di Postman untuk automated testing
4. **Monitor:** Gunakan Postman Monitor untuk scheduled testing

## 📞 Need Help?

Jika ada masalah, cek:
1. `API_DOCUMENTATION.md` - Full documentation
2. `storage/logs/laravel.log` - Error logs
3. `php artisan route:list` - Available routes

---

**Happy Testing! 🚀**
