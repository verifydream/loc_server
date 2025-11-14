# 🌐 Setup Hosting & URL Configuration

## Pertanyaan Umum

### 1. Apakah URL download akan berubah saat hosting?

**Ya, URL akan otomatis menyesuaikan dengan domain hosting Anda.**

#### Contoh:

**Development (localhost):**
```
http://localhost:8000/download/apk/5
```

**Production (Hostinger):**
```
https://depoverse.ppzaidbintsabit.com/download/apk/5
```

Laravel akan otomatis generate URL yang benar berdasarkan `APP_URL` di file `.env`.

---

## Setup di Hostinger

### Step 1: Update File `.env` di Production

Setelah upload ke Hostinger, edit file `.env`:

```env
APP_NAME="Location Server"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://depoverse.ppzaidbintsabit.com

# Database production
DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=your_production_db
DB_USERNAME=your_db_user
DB_PASSWORD=your_db_password

# API Key (sama seperti development atau generate baru)
FLUTTER_API_KEY=2yVrUCjQLo4S81l4cZkAaC5UaHbKLTaZ8fJ5p4eh0HLDckP19DNgAg3NHW6YC6Kt
```

**Penting:**
- `APP_URL` harus sesuai dengan domain Anda
- `APP_ENV=production` untuk production
- `APP_DEBUG=false` untuk keamanan

### Step 2: Clear Cache di Production

Setelah update `.env`, jalankan:

```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
```

### Step 3: Setup Storage Link

Pastikan symbolic link storage sudah dibuat:

```bash
php artisan storage:link
```

### Step 4: Set Permissions

Set permission yang benar untuk folder storage:

```bash
chmod -R 775 storage
chmod -R 775 bootstrap/cache
```

---

## Testing di Production

### Test 1: API Response

```bash
curl -H "X-Api-Key: YOUR_API_KEY" https://depoverse.ppzaidbintsabit.com/api/latest-version
```

Response akan seperti ini:
```json
{
  "status": "success",
  "data": {
    "version_name": "1.0.2",
    "version_code": 2,
    "release_notes": "Update terbaru",
    "download_url": "https://depoverse.ppzaidbintsabit.com/download/apk/5"
  }
}
```

**Perhatikan:** `download_url` sudah otomatis menggunakan domain production!

### Test 2: Direct Download

Buka di browser (tanpa login):
```
https://depoverse.ppzaidbintsabit.com/download/apk/5
```

File APK harus langsung terdownload.

---

## Implementasi di Flutter

### Development (localhost):

```dart
class ApiConfig {
  static const String baseUrl = 'http://10.0.2.2:8000'; // Android emulator
  // static const String baseUrl = 'http://localhost:8000'; // iOS simulator
  static const String apiKey = 'YOUR_API_KEY';
}
```

### Production (Hostinger):

```dart
class ApiConfig {
  static const String baseUrl = 'https://depoverse.ppzaidbintsabit.com';
  static const String apiKey = 'YOUR_API_KEY';
}
```

### Code untuk Check Update & Download:

```dart
import 'package:http/http.dart' as http;
import 'dart:convert';
import 'package:url_launcher/url_launcher.dart';

class UpdateChecker {
  static Future<void> checkForUpdate() async {
    try {
      final response = await http.get(
        Uri.parse('${ApiConfig.baseUrl}/api/latest-version'),
        headers: {
          'X-Api-Key': ApiConfig.apiKey,
          'Accept': 'application/json',
        },
      );

      if (response.statusCode == 200) {
        final data = jsonDecode(response.body);
        
        if (data['status'] == 'success') {
          final latestVersion = data['data']['version_code'];
          final currentVersion = 1; // Get from package_info
          
          if (latestVersion > currentVersion) {
            // Ada update baru
            final downloadUrl = data['data']['download_url'];
            final releaseNotes = data['data']['release_notes'];
            
            // Show dialog
            showUpdateDialog(
              versionName: data['data']['version_name'],
              releaseNotes: releaseNotes,
              downloadUrl: downloadUrl,
            );
          }
        }
      }
    } catch (e) {
      print('Error checking update: $e');
    }
  }

  static Future<void> downloadUpdate(String downloadUrl) async {
    // downloadUrl sudah full URL dari API
    // Contoh: https://depoverse.ppzaidbintsabit.com/download/apk/5
    
    final uri = Uri.parse(downloadUrl);
    
    if (await canLaunchUrl(uri)) {
      await launchUrl(
        uri,
        mode: LaunchMode.externalApplication,
      );
    } else {
      throw 'Could not launch $downloadUrl';
    }
  }
}
```

---

## Troubleshooting

### Masalah: URL masih menggunakan localhost di production

**Solusi:**
1. Cek file `.env` di production, pastikan `APP_URL` sudah benar
2. Clear config cache: `php artisan config:clear`
3. Restart web server

### Masalah: Download redirect ke login

**Solusi:**
1. Pastikan route publik ada di luar middleware admin
2. Cek file `routes/web.php`:
   ```php
   // Route ini HARUS di luar middleware admin
   Route::get('/download/apk/{appVersion}', [AppVersionController::class, 'publicDownload'])
       ->name('apk.download');
   ```
3. Clear route cache: `php artisan route:clear`

### Masalah: File tidak ditemukan (404)

**Solusi:**
1. Pastikan symbolic link storage sudah dibuat: `php artisan storage:link`
2. Cek permission folder storage: `chmod -R 775 storage`
3. Cek apakah file APK ada di `storage/app/public/updates/`

### Masalah: CORS error di Flutter

**Solusi:**
Tambahkan CORS middleware di Laravel (sudah ada by default).

Jika masih error, tambahkan di `.htaccess`:
```apache
<IfModule mod_headers.c>
    Header set Access-Control-Allow-Origin "*"
    Header set Access-Control-Allow-Methods "GET, POST, OPTIONS"
    Header set Access-Control-Allow-Headers "X-Api-Key, Content-Type, Accept"
</IfModule>
```

---

## Keamanan

### 1. Protect API Key

**Jangan hardcode API key di Flutter!**

Gunakan environment variables atau secure storage:

```dart
import 'package:flutter_dotenv/flutter_dotenv.dart';

// Load dari .env file
final apiKey = dotenv.env['API_KEY'];
```

### 2. Rate Limiting

API sudah dilindungi rate limiting (60 req/min). Jika perlu adjust:

```php
// routes/api.php
Route::middleware(['throttle:100,1']) // 100 requests per minute
```

### 3. HTTPS Only di Production

Pastikan semua request menggunakan HTTPS di production:

```dart
// Jangan gunakan http:// di production!
static const String baseUrl = 'https://depoverse.ppzaidbintsabit.com';
```

---

## Checklist Deployment

### Pre-Deployment:
- [ ] Update `.env` dengan APP_URL production
- [ ] Set `APP_ENV=production`
- [ ] Set `APP_DEBUG=false`
- [ ] Generate APP_KEY baru: `php artisan key:generate`
- [ ] Update database credentials

### Post-Deployment:
- [ ] Run migrations: `php artisan migrate --force`
- [ ] Create storage link: `php artisan storage:link`
- [ ] Clear all cache
- [ ] Set folder permissions (775)
- [ ] Test API endpoint
- [ ] Test download APK
- [ ] Test dari Flutter app

### Testing:
- [ ] API latest-version berfungsi
- [ ] Download URL benar (menggunakan domain production)
- [ ] Download APK tanpa login berhasil
- [ ] Flutter app bisa check update
- [ ] Flutter app bisa download APK

---

## URL Structure

### Development:
```
Base URL: http://localhost:8000
API: http://localhost:8000/api/latest-version
Download: http://localhost:8000/download/apk/{id}
Admin: http://localhost:8000/admin/dashboard
```

### Production:
```
Base URL: https://depoverse.ppzaidbintsabit.com
API: https://depoverse.ppzaidbintsabit.com/api/latest-version
Download: https://depoverse.ppzaidbintsabit.com/download/apk/{id}
Admin: https://depoverse.ppzaidbintsabit.com/admin/dashboard
```

---

## Support

Jika ada masalah saat deployment:
1. Cek log Laravel: `storage/logs/laravel.log`
2. Cek error log server (cPanel/Hostinger)
3. Test dengan curl untuk isolate masalah
4. Verify route list: `php artisan route:list`

---

**Last Updated:** 4 November 2025  
**Status:** ✅ Ready for Production
