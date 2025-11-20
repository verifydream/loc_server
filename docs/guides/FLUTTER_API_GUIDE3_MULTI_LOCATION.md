# API Documentation - Multi-Location Login (v3)

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

## B. Multi-Location Check Module

### 1. Check User Location (Multi-Location Support)

Endpoint untuk mengecek lokasi server yang tersedia untuk user berdasarkan email. API ini dipanggil sebelum user login untuk menentukan server mana yang harus digunakan untuk autentikasi.

**PERUBAHAN DARI v2:**
- Sekarang satu email bisa terdaftar di **MULTIPLE LOCATIONS**
- Response bisa berupa **single object** (1 lokasi) atau **array of objects** (multiple lokasi)
- User bisa **MEMILIH** server mana yang ingin digunakan untuk login

**Endpoint**
```
POST {{base_url}}/check-location
```

**Alternative Method**
```
GET {{base_url}}/check-location?email=dev_survey@dkm
```

**Headers**
```
X-Api-Key: 2yVrUCjQLo4S81l4cZkAaC5UaHbKLTaZ8fJ5p4eh0HLDckP19DNgAg3NHW6YC6Kt
Content-Type: application/json
Accept: application/json
```

**Request Body** (JSON - untuk POST method)
```json
{
  "email": "dev_survey@dkm"
}
```

**Request Parameters:**
- `email` (string, required): Email address user yang ingin dicek lokasinya

---

### Response Scenarios

#### Scenario 1: Single Location (Backward Compatible)

Jika user hanya terdaftar di **SATU LOKASI**, response akan berupa **single object**.

**Response 200 OK - Single Location**
```json
{
  "success": true,
  "data": {
    "email": "dev_survey@dkm",
    "online_url": "https://dev.mydeposys.com",
    "location_name": "Dev",
    "location_code": "dev",
    "location_logo": "https://depoverse.ppzaidbintsabit.com/public/storage/location-logos/dev-logo.png"
  }
}
```

**Response Fields:**
- `success` (boolean): Status keberhasilan request (always true for 200 OK)
- `data` (object): Data lokasi user (single object)
- `data.email` (string): Email user yang dicek
- `data.online_url` (string): URL server online yang harus digunakan untuk login/API calls
- `data.location_name` (string): Nama lokasi/kota
- `data.location_code` (string): Kode lokasi (singkatan)
- `data.location_logo` (string|null): URL logo lokasi (bisa null jika tidak ada logo)

---

#### Scenario 2: Multiple Locations (NEW!)

Jika user terdaftar di **MULTIPLE LOCATIONS**, response akan berupa **array of objects**.

**Response 200 OK - Multiple Locations**
```json
{
  "success": true,
  "data": [
    {
      "email": "admin@company.com",
      "online_url": "https://jkt.mydeposys.com",
      "location_name": "Jakarta",
      "location_code": "jkt",
      "location_logo": "https://depoverse.ppzaidbintsabit.com/public/storage/location-logos/jakarta.png"
    },
    {
      "email": "admin@company.com",
      "online_url": "https://sby.mydeposys.com",
      "location_name": "Surabaya",
      "location_code": "sby",
      "location_logo": "https://depoverse.ppzaidbintsabit.com/public/storage/location-logos/surabaya.png"
    },
    {
      "email": "admin@company.com",
      "online_url": "https://blw.mydeposys.com",
      "location_name": "Belawan",
      "location_code": "blw",
      "location_logo": null
    }
  ]
}
```

**Response Fields:**
- `success` (boolean): Status keberhasilan request (always true for 200 OK)
- `data` (array): Array of location objects
- `data[].email` (string): Email user yang dicek
- `data[].online_url` (string): URL server online untuk lokasi ini
- `data[].location_name` (string): Nama lokasi/kota
- `data[].location_code` (string): Kode lokasi (singkatan)
- `data[].location_logo` (string|null): URL logo lokasi (bisa null)

---

### Error Responses

**Response 404 Not Found - Email Not Found**
```json
{
  "success": false,
  "message": "Email not found"
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

## C. Usage Flow

### Flow 1: Single Location Login (Backward Compatible)

Alur ini sama dengan v2, untuk user yang hanya terdaftar di satu lokasi.

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

**Step 3: Receive Single Location Data**
```json
{
  "success": true,
  "data": {
    "email": "dev_survey@dkm",
    "online_url": "https://dev.mydeposys.com",
    "location_name": "Dev",
    "location_code": "dev",
    "location_logo": "https://depoverse.ppzaidbintsabit.com/public/storage/location-logos/dev-logo.png"
  }
}
```

**Step 4: Detect Single Location**

**Step 5: Proceed to Login**
```
POST https://dev.mydeposys.com/api/auth/login
Body: {
  "email": "dev_survey@dkm",
  "password": "user_password"
}
```

---

### Flow 2: Multiple Location Login (NEW!)

Alur baru untuk user yang terdaftar di multiple locations.

**Step 1: User Input Email**
```
User memasukkan email di form login
Email: admin@company.com
```

**Step 2: Check Location**
```
POST {{base_url}}/check-location
Body: {"email": "admin@company.com"}
```

**Step 3: Receive Multiple Location Data**
```json
{
  "success": true,
  "data": [
    {
      "email": "admin@company.com",
      "online_url": "https://jkt.mydeposys.com",
      "location_name": "Jakarta",
      "location_code": "jkt",
      "location_logo": "https://depoverse.ppzaidbintsabit.com/public/storage/location-logos/jakarta.png"
    },
    {
      "email": "admin@company.com",
      "online_url": "https://sby.mydeposys.com",
      "location_name": "Surabaya",
      "location_code": "sby",
      "location_logo": "https://depoverse.ppzaidbintsabit.com/public/storage/location-logos/surabaya.png"
    },
    {
      "email": "admin@company.com",
      "online_url": "https://blw.mydeposys.com",
      "location_name": "Belawan",
      "location_code": "blw",
      "location_logo": null
    }
  ]
}
```

**Step 4: Detect Multiple Locations**

**Step 5: Show Location Selection UI**
```
┌─────────────────────────────────────┐
│  Select Your Location               │
├─────────────────────────────────────┤
│  [Logo] Jakarta              │
│        jkt.mydeposys.com        │
├─────────────────────────────────────┤
│  [Logo] Surabaya             │
│        sby.mydeposys.com       │
├─────────────────────────────────────┤
│  [Icon] Belawan              │
│        blw.mydeposys.com        │
└─────────────────────────────────────┘
```

**Step 6: User Selects Location**
```
User clicks: "Jakarta"
Selected online_url: "https://jkt.mydeposys.com"
```

**Step 7: Save Selected Location**


**Step 8: Proceed to Login**
```
POST https://jkt.mydeposys.com/api/auth/login
Body: {
  "email": "admin@company.com",
  "password": "user_password"
}
```

**Step 9: Use Selected URL for All Requests**
```
Semua request selanjutnya menggunakan: https://jkt.mydeposys.com/api
- GET https://jkt.mydeposys.com/api/user/profile
- POST https://jkt.mydeposys.com/api/data/submit
- dll
```


## D. HTTP Status Codes

| Status Code | Description |
|-------------|-------------|
| 200 | Success - Request berhasil (single atau multiple locations) |
| 401 | Unauthorized - API Key salah atau tidak ada |
| 404 | Not Found - Email tidak ditemukan |
| 422 | Validation Error - Data tidak valid |
| 429 | Too Many Requests - Rate limit exceeded (60 req/min) |
| 500 | Server Error - Terjadi kesalahan di server |

---

## H. Error Handling

### 1. Handle 401 Unauthorized
- API Key salah atau tidak dikirim di header
- Tampilkan error: "Authentication failed"

### 2. Handle 404 Email Not Found
- Email tidak terdaftar di sistem
- Tampilkan error: "Email not found. Please check your email address."

### 3. Handle 422 Validation Error
- Email tidak valid atau kosong
- Tampilkan error di form field

### 4. Handle 429 Rate Limit
- Terlalu banyak request (> 60 per menit)
- Tampilkan pesan: "Too many requests. Please try again later."

### 5. Handle 500 Server Error
- Error di server
- Tampilkan pesan: "Server error. Please try again later."

---

---

**Last Updated:** November 17, 2025  
**API Version:** 3.0  
**Authentication:** Required (API Key)  
**Backward Compatible:** Yes (with v2)
