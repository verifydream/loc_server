# 🔧 Swagger CORS Fix Guide

## Masalah CORS di Swagger UI

Jika kamu melihat error seperti ini di console browser:

```
Access to fetch at 'http://localhost/api/...' from origin 'http://127.0.0.1:8000' 
has been blocked by CORS policy: Response to preflight request doesn't pass access 
control check: No 'Access-Control-Allow-Origin' header is present on the requested resource.
```

## Penyebab

Error ini terjadi karena:
1. Swagger UI diakses dari `http://127.0.0.1:8000`
2. Tapi mencoba request ke `http://localhost`
3. Browser menganggap ini sebagai cross-origin request

## Solusi

### Solusi 1: Gunakan Server yang Sama (Recommended)

Di Swagger UI, pilih server yang sama dengan URL yang kamu gunakan untuk akses Swagger:

**Jika akses Swagger via `http://localhost:8000/api/documentation`:**
- Pilih server: `http://localhost` atau `Current Environment`

**Jika akses Swagger via `http://127.0.0.1:8000/api/documentation`:**
- Pilih server: `http://127.0.0.1:8000`

### Solusi 2: Akses Swagger dengan URL yang Konsisten

Gunakan URL yang sama untuk akses Swagger dan API:

```
# Gunakan localhost untuk keduanya
http://localhost:8000/api/documentation

# ATAU gunakan 127.0.0.1 untuk keduanya
http://127.0.0.1:8000/api/documentation
```

### Solusi 3: Update CORS Config (Jika Masih Error)

Jika masih ada error, pastikan CORS config sudah benar:

**File: `config/cors.php`**
```php
return [
    'paths' => ['api/*', 'sanctum/csrf-cookie'],
    'allowed_methods' => ['*'],
    'allowed_origins' => ['*'],  // Allow all origins
    'allowed_origins_patterns' => [],
    'allowed_headers' => ['*'],
    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => false,
];
```

Kemudian clear config:
```bash
php artisan config:clear
php artisan cache:clear
```

### Solusi 4: Pastikan CORS Middleware Aktif

**File: `app/Http/Kernel.php`**

Pastikan `HandleCors` middleware ada di global middleware:
```php
protected $middleware = [
    // ...
    \Illuminate\Http\Middleware\HandleCors::class,
    // ...
];
```

## Testing Setelah Fix

1. **Clear browser cache** atau buka Incognito/Private window
2. Akses Swagger UI: `http://localhost:8000/api/documentation`
3. Pilih server yang sesuai di dropdown
4. Test endpoint dengan klik "Try it out" → "Execute"
5. Response harus muncul tanpa CORS error

## Tips

### Tip 1: Gunakan Localhost Konsisten
Selalu gunakan `localhost` atau `127.0.0.1`, jangan mix:
- ✅ Good: `http://localhost:8000` untuk semua
- ✅ Good: `http://127.0.0.1:8000` untuk semua
- ❌ Bad: Mix `localhost` dan `127.0.0.1`

### Tip 2: Check Browser Console
Buka Developer Tools (F12) → Console untuk melihat error detail

### Tip 3: Test dengan Curl
Jika Swagger error, test dengan curl untuk memastikan API berfungsi:
```bash
curl -X GET "http://localhost:8000/api/latest-version" ^
  -H "X-Api-Key: your-api-key" ^
  -H "Accept: application/json"
```

### Tip 4: Gunakan Postman
Postman tidak terpengaruh CORS, jadi bisa digunakan untuk testing alternatif

## Production Setup

Untuk production, update CORS config untuk security:

**File: `config/cors.php`**
```php
return [
    'paths' => ['api/*'],
    'allowed_methods' => ['GET', 'POST', 'PUT', 'DELETE'],
    'allowed_origins' => [
        'https://your-domain.com',
        'https://app.your-domain.com',
    ],
    'allowed_headers' => ['Content-Type', 'X-Api-Key', 'Authorization'],
    'exposed_headers' => [],
    'max_age' => 3600,
    'supports_credentials' => false,
];
```

## Troubleshooting

### Error: "No 'Access-Control-Allow-Origin' header"
**Fix:** Pastikan CORS middleware aktif dan config benar

### Error: "CORS policy: Method not allowed"
**Fix:** Tambahkan method yang dibutuhkan di `allowed_methods`

### Error: "CORS policy: Header not allowed"
**Fix:** Tambahkan header yang dibutuhkan di `allowed_headers`

### Swagger UI tidak load sama sekali
**Fix:** 
```bash
php artisan config:clear
php artisan cache:clear
php artisan l5-swagger:generate
```

## Alternative: Disable CORS Check (Development Only)

**⚠️ WARNING: Hanya untuk development, JANGAN di production!**

Buka Chrome dengan CORS disabled:
```bash
# Windows
chrome.exe --user-data-dir="C:/Chrome dev session" --disable-web-security

# Mac
open -n -a /Applications/Google\ Chrome.app/Contents/MacOS/Google\ Chrome --args --user-data-dir="/tmp/chrome_dev_test" --disable-web-security

# Linux
google-chrome --user-data-dir="/tmp/chrome_dev_test" --disable-web-security
```

## Summary

1. ✅ Gunakan URL yang konsisten (localhost atau 127.0.0.1)
2. ✅ Pilih server yang sesuai di Swagger UI
3. ✅ Pastikan CORS config benar
4. ✅ Clear cache jika perlu
5. ✅ Test dengan curl atau Postman jika masih error

## Need Help?

Jika masih ada masalah:
1. Check Laravel logs: `storage/logs/laravel.log`
2. Check browser console untuk error detail
3. Test API dengan curl atau Postman
4. Pastikan server Laravel running: `php artisan serve`
