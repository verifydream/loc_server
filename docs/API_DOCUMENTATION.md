# API Documentation - Location Server

Dokumentasi lengkap untuk testing API menggunakan Postman atau tools lainnya.

## Base URL

```
http://localhost:8000/api
```

Atau sesuaikan dengan URL server Anda.

---

## Authentication

Semua endpoint API memerlukan API Key untuk autentikasi.

### Headers Required

```
X-Api-Key: your-api-key-here
Content-Type: application/json
Accept: application/json
```

**Cara mendapatkan API Key:**
- API Key disimpan di file `.env` dengan key `FLUTTER_API_KEY`
- Contoh: `FLUTTER_API_KEY=your-secret-api-key-123`

---

## Rate Limiting

- **Limit:** 60 requests per minute
- **Response ketika limit exceeded:**
  ```json
  {
    "message": "Too Many Attempts."
  }
  ```

---

## Endpoints

### 1. Check Location

Mengecek lokasi user berdasarkan email.

#### Endpoint
```
POST /api/check-location
GET  /api/check-location
```

#### Headers
```
X-Api-Key: your-api-key-here
Content-Type: application/json
Accept: application/json
```

#### Request Body (POST)
```json
{
  "email": "user@example.com"
}
```

#### Query Parameters (GET)
```
?email=user@example.com
```

#### Success Response (200 OK)

**User Found:**
```json
{
  "success": true,
  "data": {
    "user": {
      "id": 1,
      "name": "John Doe",
      "email": "user@example.com",
      "status": "active"
    },
    "location": {
      "id": 5,
      "name": "Jakarta Office",
      "address": "Jl. Sudirman No. 123",
      "latitude": -6.2088,
      "longitude": 106.8456,
      "radius": 100
    }
  }
}
```

**User Not Found:**
```json
{
  "success": true,
  "data": {
    "user": null,
    "location": null
  }
}
```

#### Error Responses

**Validation Error (422 Unprocessable Entity):**
```json
{
  "success": false,
  "message": "Validation error",
  "errors": {
    "email": [
      "The email field is required.",
      "The email must be a valid email address."
    ]
  }
}
```

**Unauthorized (401):**
```json
{
  "message": "Unauthorized"
}
```

**Server Error (500):**
```json
{
  "success": false,
  "message": "An error occurred"
}
```

#### Postman Example

**Method:** POST  
**URL:** `http://localhost:8000/api/check-location`

**Headers:**
| Key | Value |
|-----|-------|
| X-Api-Key | your-api-key-here |
| Content-Type | application/json |
| Accept | application/json |

**Body (raw JSON):**
```json
{
  "email": "user@example.com"
}
```

---

### 2. Get Latest Version

Mendapatkan informasi versi APK terbaru untuk auto-update.

#### Endpoint
```
GET /api/latest-version
```

#### Headers
```
X-Api-Key: your-api-key-here
Accept: application/json
```

#### Request Body
Tidak ada body (GET request)

#### Success Response (200 OK)

```json
{
  "status": "success",
  "data": {
    "version_name": "1.0.0",
    "version_code": 1,
    "release_notes": "- Initial release\n- Bug fixes\n- Performance improvements",
    "download_url": "/storage/updates/app-v1.0.0.apk"
  }
}
```

#### Error Responses

**No Version Available (404 Not Found):**
```json
{
  "status": "error",
  "message": "No version available"
}
```

**Unauthorized (401):**
```json
{
  "message": "Unauthorized"
}
```

**Server Error (500):**
```json
{
  "status": "error",
  "message": "Internal server error"
}
```

#### Postman Example

**Method:** GET  
**URL:** `http://localhost:8000/api/latest-version`

**Headers:**
| Key | Value |
|-----|-------|
| X-Api-Key | your-api-key-here |
| Accept | application/json |

**Body:** None

---

## Testing Flow

### 1. Setup Environment

1. Pastikan server Laravel sudah running:
   ```bash
   php artisan serve
   ```

2. Cek API Key di file `.env`:
   ```
   FLUTTER_API_KEY=your-secret-api-key-123
   ```

3. Pastikan sudah ada data user dan location di database

### 2. Test Check Location API

#### Test Case 1: Valid Email (User Exists)
- **Method:** POST
- **URL:** `http://localhost:8000/api/check-location`
- **Headers:** 
  - `X-Api-Key: your-secret-api-key-123`
  - `Content-Type: application/json`
- **Body:**
  ```json
  {
    "email": "existing-user@example.com"
  }
  ```
- **Expected:** Status 200, return user and location data

#### Test Case 2: Invalid Email Format
- **Body:**
  ```json
  {
    "email": "invalid-email"
  }
  ```
- **Expected:** Status 422, validation error

#### Test Case 3: Missing Email
- **Body:**
  ```json
  {}
  ```
- **Expected:** Status 422, validation error

#### Test Case 4: Wrong API Key
- **Headers:** 
  - `X-Api-Key: wrong-key`
- **Expected:** Status 401, Unauthorized

#### Test Case 5: Missing API Key
- **Headers:** (tanpa X-Api-Key)
- **Expected:** Status 401, Unauthorized

#### Test Case 6: Using GET Method
- **Method:** GET
- **URL:** `http://localhost:8000/api/check-location?email=user@example.com`
- **Expected:** Status 200, same response as POST

### 3. Test Latest Version API

#### Test Case 1: Get Latest Version (Version Exists)
- **Method:** GET
- **URL:** `http://localhost:8000/api/latest-version`
- **Headers:** 
  - `X-Api-Key: your-secret-api-key-123`
- **Expected:** Status 200, return latest version data

#### Test Case 2: No Version Available
- **Condition:** Hapus semua data di tabel `app_versions`
- **Expected:** Status 404, "No version available"

#### Test Case 3: Wrong API Key
- **Headers:** 
  - `X-Api-Key: wrong-key`
- **Expected:** Status 401, Unauthorized

#### Test Case 4: Missing API Key
- **Headers:** (tanpa X-Api-Key)
- **Expected:** Status 401, Unauthorized

---

## Postman Collection

### Import ke Postman

Buat collection baru dengan struktur berikut:

```
Location Server API
├── Check Location
│   ├── POST - Valid Email
│   ├── POST - Invalid Email
│   ├── POST - Missing Email
│   ├── GET - With Query Param
│   └── POST - Wrong API Key
└── Latest Version
    ├── GET - Success
    ├── GET - No Version
    └── GET - Wrong API Key
```

### Environment Variables

Buat environment di Postman dengan variables:

| Variable | Initial Value | Current Value |
|----------|--------------|---------------|
| base_url | http://localhost:8000 | http://localhost:8000 |
| api_key | your-secret-api-key-123 | your-secret-api-key-123 |

Gunakan `{{base_url}}` dan `{{api_key}}` di request Anda.

---

## Common Issues & Solutions

### 1. 401 Unauthorized
**Problem:** API Key tidak valid atau tidak ditemukan  
**Solution:** 
- Cek file `.env` untuk `FLUTTER_API_KEY`
- Pastikan header `X-Api-Key` sudah benar
- Restart server setelah update `.env`

### 2. 404 Not Found
**Problem:** Endpoint tidak ditemukan  
**Solution:**
- Cek URL endpoint sudah benar
- Pastikan prefix `/api` ada di URL
- Clear route cache: `php artisan route:clear`

### 3. 422 Validation Error
**Problem:** Data yang dikirim tidak valid  
**Solution:**
- Cek format email sudah benar
- Pastikan field required tidak kosong
- Cek response error untuk detail

### 4. 429 Too Many Requests
**Problem:** Melebihi rate limit (60 req/min)  
**Solution:**
- Tunggu 1 menit sebelum request lagi
- Atau update rate limit di `routes/api.php`

### 5. 500 Internal Server Error
**Problem:** Error di server  
**Solution:**
- Cek log Laravel: `storage/logs/laravel.log`
- Pastikan database connection OK
- Cek semua service dan model sudah benar

---

## Response Status Codes

| Code | Meaning | Description |
|------|---------|-------------|
| 200 | OK | Request berhasil |
| 401 | Unauthorized | API Key tidak valid |
| 404 | Not Found | Resource tidak ditemukan |
| 422 | Unprocessable Entity | Validation error |
| 429 | Too Many Requests | Rate limit exceeded |
| 500 | Internal Server Error | Error di server |

---

## Notes

1. **API Key Security:**
   - Jangan commit API Key ke repository
   - Gunakan `.env` untuk menyimpan API Key
   - Ganti API Key secara berkala

2. **Download URL:**
   - URL download APK adalah relative path
   - Full URL: `http://your-domain.com/storage/updates/filename.apk`
   - Pastikan symbolic link storage sudah dibuat: `php artisan storage:link`

3. **Testing Tips:**
   - Gunakan Postman Collection untuk save semua test cases
   - Setup environment variables untuk mudah switch antara dev/prod
   - Save response examples untuk dokumentasi

4. **Rate Limiting:**
   - Default: 60 requests per minute
   - Bisa diubah di `routes/api.php` pada middleware `throttle:60,1`
   - Format: `throttle:attempts,minutes`

---

## Contact & Support

Untuk pertanyaan atau issue, silakan hubungi tim development.

**Last Updated:** November 4, 2025
