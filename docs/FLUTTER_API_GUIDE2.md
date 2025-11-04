# API Documentation - Location Server v2 (With Authentication)

## A. General Rules (Ketentuan Umum)

Berikut adalah aturan umum yang berlaku untuk semua API endpoint:

### Base Configuration

1. **Base URL**
   ```
   https://depoverse.ppzaidbintsabit.com/api
   ```

2. **Authentication**
   - API ini **MEMERLUKAN API KEY** untuk authentication
   - API Key dikirim melalui header `X-Api-Key`
   - API Key bisa di-hardcode di source code Flutter

3. **Required Headers**
   ```
   X-Api-Key: 2yVrUCjQLo4S81l4cZkAaC5UaHbKLTaZ8fJ5p4eh0HLDckP19DNgAg3NHW6YC6Kt
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

**Headers**
```
X-Api-Key: 2yVrUCjQLo4S81l4cZkAaC5UaHbKLTaZ8fJ5p4eh0HLDckP19DNgAg3NHW6YC6Kt
Content-Type: application/json
Accept: application/json
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
    "online_url": "https://dev.mydeposys.com",
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

**Response 401 Unauthorized**
```json
{
  "message": "Unauthorized"
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
  "message": "Too Many Attempts."
}
```

**Response 500 Internal Server Error**
```json
{
  "success": false,
  "message": "An error occurred"
}
```

---

## C. App Version Module

### 1. Get Latest Version

Endpoint untuk mendapatkan informasi versi APK terbaru untuk fitur auto-update.

**Endpoint**
```
GET {{base_url}}/latest-version
```

**Headers**
```
X-Api-Key: 2yVrUCjQLo4S81l4cZkAaC5UaHbKLTaZ8fJ5p4eh0HLDckP19DNgAg3NHW6YC6Kt
Accept: application/json
```

**Request Body**
Tidak ada (GET request)

**Response 200 OK - Version Available**
```json
{
  "status": "success",
  "data": {
    "version_name": "1.0.2",
    "version_code": 3,
    "release_notes": "update menu homepage",
    "download_url": "/storage/updates/app-v1.0.2-1762239609.apk"
  }
}
```

**Response Fields:**
- `status` (string): Status response ("success" atau "error")
- `data` (object): Data versi APK
- `data.version_name` (string): Nama versi (contoh: "1.0.2")
- `data.version_code` (integer): Kode versi (contoh: 3)
- `data.release_notes` (string|null): Catatan rilis (bisa null)
- `data.download_url` (string): URL relatif untuk download APK

**Response 404 Not Found - No Version Available**
```json
{
  "status": "error",
  "message": "No version available"
}
```

**Response 401 Unauthorized**
```json
{
  "message": "Unauthorized"
}
```

**Response 429 Too Many Requests**
```json
{
  "message": "Too Many Attempts."
}
```

**Response 500 Internal Server Error**
```json
{
  "status": "error",
  "message": "Internal server error"
}
```

---

## D. Usage Flow

### Flow 1: Login Flow dengan Location Check

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

### Flow 2: Auto Update dengan Version Check

**Step 1: App Startup**
```
Aplikasi dibuka
Current version_code: 2
```

**Step 2: Check Latest Version**
```
GET {{base_url}}/latest-version
Headers: 
  X-Api-Key: 2yVrUCjQLo4S81l4cZkAaC5UaHbKLTaZ8fJ5p4eh0HLDckP19DNgAg3NHW6YC6Kt
```

**Step 3: Receive Version Data**
```json
{
  "status": "success",
  "data": {
    "version_name": "1.0.2",
    "version_code": 3,
    "release_notes": "update menu homepage",
    "download_url": "/storage/updates/app-v1.0.2-1762239609.apk"
  }
}
```

**Step 4: Compare Version**
```
Latest version_code: 3
Current version_code: 2
3 > 2 = Update available!
```

**Step 5: Show Update Dialog**
```
Title: "Update Available"
Message: "Version 1.0.2 is available"
Release Notes: "update menu homepage"
Buttons: [Update Now] [Later]
```

**Step 6: Download & Install (if user clicks Update Now)**
```
Download URL: https://depoverse.ppzaidbintsabit.com/storage/updates/app-v1.0.2-1762239609.apk
Download APK menggunakan download manager
Install APK setelah download selesai
```

---

## E. HTTP Status Codes

| Status Code | Description |
|-------------|-------------|
| 200 | Success - Request berhasil |
| 401 | Unauthorized - API Key salah atau tidak ada |
| 404 | Not Found - Resource tidak ditemukan |
| 422 | Validation Error - Data tidak valid |
| 429 | Too Many Requests - Rate limit exceeded (60 req/min) |
| 500 | Server Error - Terjadi kesalahan di server |

---

## F. Error Handling

### 1. Handle 401 Unauthorized
- API Key salah atau tidak dikirim di header
- Tampilkan error: "Authentication failed"

### 2. Handle 422 Validation Error
- Email tidak valid atau kosong
- Tampilkan error di form field

### 3. Handle 429 Rate Limit
- Terlalu banyak request (> 60 per menit)
- Tampilkan pesan: "Too many requests. Please try again later."

### 4. Handle 500 Server Error
- Error di server
- Tampilkan pesan: "Server error. Please try again later."

---

**Last Updated:** November 4, 2025  
**API Version:** 2.0  
**Authentication:** Required (API Key)
