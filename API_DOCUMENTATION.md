# Location Server API Documentation

## Base URL
```
http://localhost:8000/api
```

## Endpoints

### 1. Check User Location

Endpoint untuk mengecek lokasi user berdasarkan email.

**Tersedia 2 method (pilih salah satu):**

#### Method 1: POST (Recommended untuk production)

**Endpoint:** `POST /check-location`

**Headers:**
```
Content-Type: application/json
Accept: application/json
```

**Request Body:**
```json
{
    "email": "user@example.com"
}
```

#### Method 2: GET (Recommended untuk testing/development)

**Endpoint:** `GET /check-location?email=user@example.com`

**Headers:**
```
Accept: application/json
```

**Query Parameters:**
- `email` (required): Email address user yang ingin dicek lokasinya

**Success Response (200):**
```json
{
    "success": true,
    "data": {
        "user_id": 1,
        "name": "John Doe",
        "email": "user@example.com",
        "location_id": 5,
        "location_name": "Jakarta Office",
        "address": "Jl. Sudirman No. 123",
        "city": "Jakarta",
        "province": "DKI Jakarta",
        "postal_code": "12345",
        "country": "Indonesia"
    }
}
```

**Error Response - User Not Found (404):**
```json
{
    "success": false,
    "message": "User not found"
}
```

**Error Response - User Has No Location (404):**
```json
{
    "success": false,
    "message": "User has no assigned location"
}
```

**Error Response - Validation Error (422):**
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

**Error Response - Invalid Email Format (422):**
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

**Error Response - Server Error (500):**
```json
{
    "success": false,
    "message": "An error occurred"
}
```

---

## Rate Limiting
- **Limit:** 60 requests per minute
- Jika melebihi limit, akan mendapat response 429 (Too Many Requests)

---

## Testing di Postman

### Setup Collection

1. **Buat Collection Baru:**
   - Nama: "Location Server API"
   - Base URL Variable: `{{base_url}}` = `http://localhost:8000/api`

2. **Buat Request:**
   - Method: `POST`
   - URL: `{{base_url}}/check-location`
   - Headers:
     - `Content-Type`: `application/json`
     - `Accept`: `application/json`
   - Body (raw JSON):
     ```json
     {
         "email": "user@example.com"
     }
     ```

### Test Cases

#### Test 1: Valid Email dengan User yang Ada
**POST:**
```json
{
    "email": "admin@example.com"
}
```
**GET:**
```
?email=admin@example.com
```

#### Test 2: Valid Email tapi User Tidak Ada
**POST:**
```json
{
    "email": "notfound@example.com"
}
```
**GET:**
```
?email=notfound@example.com
```

#### Test 3: Invalid Email Format
**POST:**
```json
{
    "email": "invalid-email"
}
```
**GET:**
```
?email=invalid-email
```

#### Test 4: Missing Email Parameter
**POST:**
```json
{}
```
**GET:**
```
(tanpa query parameter)
```

---

## Contoh cURL

**POST Method:**
```bash
curl -X POST http://localhost:8000/api/check-location \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{"email":"user@example.com"}'
```

**GET Method:**
```bash
curl -X GET "http://localhost:8000/api/check-location?email=user@example.com" \
  -H "Accept: application/json"
```

---

## Notes

- Pastikan server Laravel sudah running di `http://localhost:8000`
- Pastikan database sudah di-migrate dan ada data user & location
- API ini menggunakan rate limiting 60 request/menit
- Semua response menggunakan format JSON
