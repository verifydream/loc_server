# API Documentation dengan Swagger

## Setup Selesai! ✅

L5-Swagger sudah berhasil diinstall dan dikonfigurasi di project ini.

## Akses Swagger UI

Buka browser dan akses:
```
http://your-domain.com/api/documentation
```

Untuk development lokal:
```
http://localhost/api/documentation
```

## API Endpoints yang Terdokumentasi

### 1. Location API
- **POST** `/api/check-location` - Check user location by email
- **GET** `/api/check-location` - Check user location by email (alternative method)

### 2. App Version API
- **GET** `/api/latest-version` - Get latest APK version information

## Cara Menggunakan Swagger UI

### Step 1: Authorize dengan API Key
1. Klik tombol **"Authorize"** (ikon gembok) di bagian atas halaman
2. Masukkan API Key kamu di field `X-API-KEY`
3. Klik **"Authorize"**
4. Klik **"Close"**

### Step 2: Test Endpoint
1. Pilih endpoint yang ingin di-test (contoh: POST /api/check-location)
2. Klik untuk expand endpoint tersebut
3. Klik tombol **"Try it out"**
4. Edit request body atau parameters sesuai kebutuhan
5. Klik **"Execute"**
6. Lihat response di bagian bawah

### Step 3: Lihat Response
Response akan menampilkan:
- **Status Code** (200, 404, 422, 500, dll)
- **Response Headers**
- **Response Body** dalam format JSON
- **Curl Command** untuk copy-paste ke terminal

## Contoh Testing

### Test Check Location
```json
POST /api/check-location
Headers: X-API-KEY: your-api-key-here

Body:
{
  "email": "user@example.com"
}

Expected Response (200):
{
  "success": true,
  "data": {
    "location": "Jakarta Office",
    "latitude": -6.2088,
    "longitude": 106.8456
  }
}
```

### Test Latest Version
```
GET /api/latest-version
Headers: X-API-KEY: your-api-key-here

Expected Response (200):
{
  "status": "success",
  "data": {
    "version_name": "1.0.0",
    "version_code": 1,
    "release_notes": "Initial release",
    "download_url": "https://example.com/download/app.apk"
  }
}
```

## Menambah Dokumentasi untuk Endpoint Baru

Jika kamu menambah endpoint baru, tambahkan anotasi Swagger di controller:

```php
/**
 * @OA\Post(
 *     path="/api/your-endpoint",
 *     summary="Deskripsi endpoint",
 *     tags={"Category Name"},
 *     security={{"apikey": {}}},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             @OA\Property(property="field", type="string", example="value")
 *         )
 *     ),
 *     @OA\Response(response=200, description="Success")
 * )
 */
public function yourMethod() {
    // your code
}
```

Kemudian regenerate dokumentasi:
```bash
php artisan l5-swagger:generate
```

## Kustomisasi Swagger

### Ubah Judul dan Deskripsi
Edit file `app/Http/Controllers/Controller.php` di bagian anotasi `@OA\Info`

### Ubah Server URL
Edit di `@OA\Server` annotation atau set di `.env`:
```
L5_SWAGGER_CONST_HOST=http://your-domain.com
```

### Tambah Security Scheme Lain
Tambahkan di `Controller.php`:
```php
/**
 * @OA\SecurityScheme(
 *     securityScheme="bearer",
 *     type="http",
 *     scheme="bearer"
 * )
 */
```

## Export Documentation

Swagger juga generate file JSON dan YAML yang bisa kamu gunakan untuk:
- Import ke Postman
- Generate client SDK
- Share dengan team

File tersimpan di:
- `storage/api-docs/api-docs.json`
- `storage/api-docs/api-docs.yaml`

## Tips & Tricks

1. **Gunakan Tags** untuk mengelompokkan endpoints
2. **Tambahkan Examples** untuk memudahkan testing
3. **Dokumentasikan semua Response Codes** (200, 400, 404, 500, dll)
4. **Gunakan Descriptions** yang jelas dan informatif
5. **Test langsung di Swagger UI** sebelum deploy

## Troubleshooting

### Swagger UI tidak muncul
```bash
php artisan l5-swagger:generate
php artisan config:clear
php artisan cache:clear
```

### Changes tidak muncul
```bash
php artisan l5-swagger:generate
```

### Permission Error
```bash
chmod -R 775 storage/api-docs
```

## Resources

- [L5-Swagger Documentation](https://github.com/DarkaOnLine/L5-Swagger)
- [OpenAPI Specification](https://swagger.io/specification/)
- [Swagger UI](https://swagger.io/tools/swagger-ui/)
