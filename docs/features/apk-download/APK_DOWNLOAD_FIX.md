# Fix APK Download - Public Access

## Masalah Sebelumnya

1. API mengembalikan URL storage: `/storage/updates/app-v1.0.2-1762244486.apk`
2. URL download admin memerlukan login: `/admin/app-versions/5/download`
3. Flutter tidak bisa download APK karena URL storage tidak bisa diakses atau perlu login admin

## Solusi yang Diterapkan

### 1. Route Publik Baru untuk Download APK

**File:** `routes/web.php`

Ditambahkan route publik yang bisa diakses tanpa autentikasi:

```php
Route::get('/download/apk/{appVersion}', [App\Http\Controllers\AppVersionController::class, 'publicDownload'])
    ->name('apk.download');
```

### 2. Method Baru di Controller

**File:** `app/Http/Controllers/AppVersionController.php`

Ditambahkan method `publicDownload()` yang tidak memerlukan autentikasi:

```php
public function publicDownload(AppVersion $appVersion)
{
    if (!Storage::exists($appVersion->file_path)) {
        abort(404, 'APK file not found');
    }

    $fileName = "app-v{$appVersion->version_name}.apk";
    return Storage::download($appVersion->file_path, $fileName);
}
```

### 3. Update API Response

**File:** `app/Http/Controllers/Api/VersionController.php`

API sekarang mengembalikan URL download publik yang benar:

```php
// Sebelumnya:
$downloadUrl = Storage::url($version->file_path);
// Hasil: /storage/updates/app-v1.0.2-1762244486.apk

// Sekarang:
$downloadUrl = route('apk.download', ['appVersion' => $version->id]);
// Hasil: http://localhost:8000/download/apk/5
```

## Cara Kerja

1. **Flutter request API** `/api/latest-version`
2. **API response** dengan `download_url`: `http://localhost:8000/download/apk/5`
3. **Flutter buka URL** tersebut di browser atau download langsung
4. **Server langsung download** file APK tanpa perlu login

## Testing

### Test API Response

```bash
curl -H "X-Api-Key: your-api-key" http://localhost:8000/api/latest-version
```

Response:
```json
{
  "status": "success",
  "data": {
    "version_name": "1.0.2",
    "version_code": 2,
    "release_notes": "asd asdas",
    "download_url": "http://localhost:8000/download/apk/5"
  }
}
```

### Test Download Langsung

Buka di browser (tanpa login):
```
http://localhost:8000/download/apk/5
```

File APK akan langsung terdownload dengan nama: `app-v1.0.2.apk`

## Perbedaan URL

| Tipe | URL | Akses | Fungsi |
|------|-----|-------|--------|
| Storage URL | `/storage/updates/app-v1.0.2.apk` | Publik (jika symlink ada) | Akses file langsung |
| Admin Download | `/admin/app-versions/5/download` | Perlu login admin | Download dari admin panel |
| Public Download | `/download/apk/5` | Publik (tanpa login) | Download untuk umum/Flutter |

## Keuntungan

1. ✅ Tidak perlu login untuk download APK
2. ✅ URL konsisten dan mudah digunakan di Flutter
3. ✅ Nama file download otomatis sesuai versi
4. ✅ Tetap aman karena menggunakan ID database
5. ✅ Admin masih bisa download dari panel admin

## Implementasi di Flutter

```dart
// Setelah dapat response dari API
final response = await http.get(
  Uri.parse('http://localhost:8000/api/latest-version'),
  headers: {'X-Api-Key': 'your-api-key'},
);

final data = jsonDecode(response.body);
final downloadUrl = data['data']['download_url'];

// Langsung buka URL untuk download
// downloadUrl sudah full URL: http://localhost:8000/download/apk/5
await launchUrl(Uri.parse(downloadUrl));
```

## Catatan Penting

1. Route publik ini **tidak memerlukan API key** karena untuk download file
2. File tetap aman karena hanya bisa diakses jika ID valid
3. Admin panel tetap bisa menggunakan route lama untuk download
4. Pastikan server Laravel sudah running untuk test

---

**Tanggal:** 4 November 2025  
**Status:** ✅ Selesai dan siap digunakan
