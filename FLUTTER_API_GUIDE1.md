# API Documentation - Location Server

## A. General Rules (Ketentuan Umum)

Berikut adalah aturan umum yang berlaku untuk semua API endpoint:

### Base Configuration

1. **Base URL**
   `https://your-ngrok-url.ngrok-free.app/api`

2. **Authentication**
   - API ini **tidak memerlukan authentication**
   - Tidak perlu token atau header authorization
   - Public endpoint untuk pre-login location check

3. **Required Headers**
   ```
   Content-Type: application/json
   Accept: application/json
   ```

4. **Rate Limiting**
   - Limit: 60 requests per minute per IP address
   - Jika melebihi limit: HTTP 429 (Too Many Requests)

---

## B. Location Check Module

### 1. Check User Location

Endpoint untuk mengecek lokasi server yang sesuai untuk user berdasarkan email. API ini dipanggil sebelum user login untuk menentukan server mana yang harus digunakan untuk autentikasi.

**Endpoint**
```
POST {{base_url}}/check-location
```

**Request Body** (JSON)
```json
{
  "email": "dev_survey@dkm"
}
```

**Request Parameters:**
- `email` (string, required): Email address user yang ingin dicek lokasinya

**Response 200 OK**
```json
{
  "success": true,
  "data": {
    "email": "dev_survey@dkm",
    "online_url": "dev.mydeposys.com",
    "location_name": "Dev",
    "location_code": "dev"
  }
}
```

**Response Fields:**
- `success` (boolean): Status keberhasilan request (always true for 200 OK)
- `data` (object): Data lokasi user
- `data.email` (string): Email user yang dicek
- `data.online_url` (string): URL server online yang harus digunakan untuk login/API calls
- `data.location_name` (string): Nama lokasi/kota
- `data.location_code` (string): Kode lokasi (singkatan)

**Response 404 Not Found - User Not Found**
```json
{
  "success": false,
  "message": "User not found"
}
```

**Response 404 Not Found - User Has No Location**
```json
{
  "success": false,
  "message": "User has no assigned location"
}
```

**Response 422 Unprocessable Entity - Email Required**
```json
{
  "success": false,
  "message": "Validation error",
  "errors": {
    "email": [
      "The email field is required."
    ]
  }
}
```

**Response 422 Unprocessable Entity - Invalid Email Format**
```json
{
  "success": false,
  "message": "Validation error",
  "errors": {
    "email": [
      "The email must be a valid email address."
    ]
  }
}
```

**Response 429 Too Many Requests**
```json
{
  "message": "Too Many Attempts.",
  "exception": "Illuminate\\Http\\Exceptions\\ThrottleRequestsException"
}
```

**Response 500 Internal Server Error**
```json
{
  "success": false,
  "message": "An error occurred"
}
```

**Frontend Notes:**
- Panggil endpoint ini SEBELUM user login
- Simpan `online_url` di localStorage/sessionStorage
- Gunakan `online_url` untuk semua request selanjutnya (login, get data, dll)
- Format `online_url` adalah domain saja tanpa protocol (contoh: `dev.mydeposys.com`)
- Tambahkan `https://` saat menggunakan URL tersebut
- Handle error 404 untuk menampilkan pesan "Email tidak terdaftar"
- Handle error 422 untuk validasi email di client side

---

## C. Usage Flow

### Login Flow dengan Location Check

Berikut adalah alur lengkap dari input email hingga berhasil login:

**Step 1: User Input Email**
```
User memasukkan email di form login
Email: dev_survey@dkm
```

**Step 2: Check Location**
```
POST {{base_url}}/check-location
Body: {"email": "dev_survey@dkm"}
```

**Step 3: Receive Location Data**
```json
{
  "success": true,
  "data": {
    "email": "dev_survey@dkm",
    "online_url": "dev.mydeposys.com",
    "location_name": "Dev",
    "location_code": "dev"
  }
}
```

**Step 4: Save & Use Online URL**
```
Simpan online_url: "dev.mydeposys.com"
Base URL untuk login: https://dev.mydeposys.com/api
```

**Step 5: Proceed to Login**
```
POST https://dev.mydeposys.com/api/auth/login
Body: {
  "email": "dev_survey@dkm",
  "password": "user_password"
}
```

**Step 6: Use Same URL for All Requests**
```
Semua request selanjutnya menggunakan: https://dev.mydeposys.com/api
- GET https://dev.mydeposys.com/api/user/profile
- POST https://dev.mydeposys.com/api/data/submit
- dll
```

---

## D. HTTP Status Codes

| Status Code | Description |
|-------------|-------------|
| 200 | Success - User ditemukan dan memiliki lokasi |
| 404 | Not Found - User tidak ditemukan atau tidak memiliki lokasi |
| 422 | Validation Error - Email tidak valid atau kosong |
| 429 | Too Many Requests - Rate limit exceeded (60 req/min) |
| 500 | Server Error - Terjadi kesalahan di server |

---
