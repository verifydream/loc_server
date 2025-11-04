# API Response Examples

Contoh lengkap response dari setiap endpoint untuk referensi.

---

## 1. Check Location API

### 1.1 Success - User Found with Location

**Request:**
```http
POST /api/check-location HTTP/1.1
Host: localhost:8000
X-Api-Key: your-secret-api-key-123
Content-Type: application/json

{
  "email": "john.doe@example.com"
}
```

**Response (200 OK):**
```json
{
  "success": true,
  "data": {
    "user": {
      "id": 1,
      "name": "John Doe",
      "email": "john.doe@example.com",
      "phone": "081234567890",
      "status": "active",
      "created_at": "2025-11-01T10:30:00.000000Z",
      "updated_at": "2025-11-04T06:35:00.000000Z"
    },
    "location": {
      "id": 5,
      "name": "Jakarta Office",
      "address": "Jl. Sudirman No. 123, Jakarta Pusat",
      "latitude": -6.2088,
      "longitude": 106.8456,
      "radius": 100,
      "created_at": "2025-10-15T08:00:00.000000Z",
      "updated_at": "2025-10-15T08:00:00.000000Z"
    }
  }
}
```

---

### 1.2 Success - User Not Found

**Request:**
```http
POST /api/check-location HTTP/1.1
Host: localhost:8000
X-Api-Key: your-secret-api-key-123
Content-Type: application/json

{
  "email": "notfound@example.com"
}
```

**Response (200 OK):**
```json
{
  "success": true,
  "data": {
    "user": null,
    "location": null
  }
}
```

---

### 1.3 Validation Error - Invalid Email Format

**Request:**
```http
POST /api/check-location HTTP/1.1
Host: localhost:8000
X-Api-Key: your-secret-api-key-123
Content-Type: application/json

{
  "email": "invalid-email"
}
```

**Response (422 Unprocessable Entity):**
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

---

### 1.4 Validation Error - Missing Email

**Request:**
```http
POST /api/check-location HTTP/1.1
Host: localhost:8000
X-Api-Key: your-secret-api-key-123
Content-Type: application/json

{}
```

**Response (422 Unprocessable Entity):**
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

---

### 1.5 Unauthorized - Wrong API Key

**Request:**
```http
POST /api/check-location HTTP/1.1
Host: localhost:8000
X-Api-Key: wrong-api-key
Content-Type: application/json

{
  "email": "user@example.com"
}
```

**Response (401 Unauthorized):**
```json
{
  "message": "Unauthorized"
}
```

---

### 1.6 Unauthorized - Missing API Key

**Request:**
```http
POST /api/check-location HTTP/1.1
Host: localhost:8000
Content-Type: application/json

{
  "email": "user@example.com"
}
```

**Response (401 Unauthorized):**
```json
{
  "message": "Unauthorized"
}
```

---

### 1.7 GET Method with Query Parameter

**Request:**
```http
GET /api/check-location?email=john.doe@example.com HTTP/1.1
Host: localhost:8000
X-Api-Key: your-secret-api-key-123
Accept: application/json
```

**Response (200 OK):**
```json
{
  "success": true,
  "data": {
    "user": {
      "id": 1,
      "name": "John Doe",
      "email": "john.doe@example.com",
      "phone": "081234567890",
      "status": "active"
    },
    "location": {
      "id": 5,
      "name": "Jakarta Office",
      "address": "Jl. Sudirman No. 123, Jakarta Pusat",
      "latitude": -6.2088,
      "longitude": 106.8456,
      "radius": 100
    }
  }
}
```

---

## 2. Latest Version API

### 2.1 Success - Version Available

**Request:**
```http
GET /api/latest-version HTTP/1.1
Host: localhost:8000
X-Api-Key: your-secret-api-key-123
Accept: application/json
```

**Response (200 OK):**
```json
{
  "status": "success",
  "data": {
    "version_name": "1.2.5",
    "version_code": 125,
    "release_notes": "What's New in v1.2.5:\n\n- Fixed login issue\n- Improved location accuracy\n- Added dark mode support\n- Performance improvements\n- Bug fixes",
    "download_url": "/storage/updates/app-v1.2.5.apk"
  }
}
```

---

### 2.2 Success - Version with Minimal Release Notes

**Request:**
```http
GET /api/latest-version HTTP/1.1
Host: localhost:8000
X-Api-Key: your-secret-api-key-123
Accept: application/json
```

**Response (200 OK):**
```json
{
  "status": "success",
  "data": {
    "version_name": "1.0.0",
    "version_code": 1,
    "release_notes": "Initial release",
    "download_url": "/storage/updates/app-v1.0.0.apk"
  }
}
```

---

### 2.3 Success - Version without Release Notes

**Request:**
```http
GET /api/latest-version HTTP/1.1
Host: localhost:8000
X-Api-Key: your-secret-api-key-123
Accept: application/json
```

**Response (200 OK):**
```json
{
  "status": "success",
  "data": {
    "version_name": "2.0.0",
    "version_code": 200,
    "release_notes": null,
    "download_url": "/storage/updates/app-v2.0.0.apk"
  }
}
```

---

### 2.4 Error - No Version Available

**Request:**
```http
GET /api/latest-version HTTP/1.1
Host: localhost:8000
X-Api-Key: your-secret-api-key-123
Accept: application/json
```

**Response (404 Not Found):**
```json
{
  "status": "error",
  "message": "No version available"
}
```

---

### 2.5 Unauthorized - Wrong API Key

**Request:**
```http
GET /api/latest-version HTTP/1.1
Host: localhost:8000
X-Api-Key: wrong-api-key
Accept: application/json
```

**Response (401 Unauthorized):**
```json
{
  "message": "Unauthorized"
}
```

---

### 2.6 Unauthorized - Missing API Key

**Request:**
```http
GET /api/latest-version HTTP/1.1
Host: localhost:8000
Accept: application/json
```

**Response (401 Unauthorized):**
```json
{
  "message": "Unauthorized"
}
```

---

### 2.7 Internal Server Error

**Request:**
```http
GET /api/latest-version HTTP/1.1
Host: localhost:8000
X-Api-Key: your-secret-api-key-123
Accept: application/json
```

**Response (500 Internal Server Error):**
```json
{
  "status": "error",
  "message": "Internal server error"
}
```

---

## 3. Rate Limiting

### 3.1 Too Many Requests

**Condition:** Setelah 60 requests dalam 1 menit

**Request:**
```http
GET /api/latest-version HTTP/1.1
Host: localhost:8000
X-Api-Key: your-secret-api-key-123
Accept: application/json
```

**Response (429 Too Many Requests):**
```json
{
  "message": "Too Many Attempts."
}
```

**Headers:**
```
X-RateLimit-Limit: 60
X-RateLimit-Remaining: 0
Retry-After: 60
```

---

## 4. Full Download URL Example

Ketika client mendapat response dari `/api/latest-version`, download URL adalah relative path.

**Response:**
```json
{
  "status": "success",
  "data": {
    "version_name": "1.2.5",
    "version_code": 125,
    "release_notes": "Bug fixes and improvements",
    "download_url": "/storage/updates/app-v1.2.5.apk"
  }
}
```

**Full Download URL:**
```
http://localhost:8000/storage/updates/app-v1.2.5.apk
```

Atau di production:
```
https://your-domain.com/storage/updates/app-v1.2.5.apk
```

---

## 5. Response Headers

### Successful Request Headers

```
HTTP/1.1 200 OK
Content-Type: application/json
X-RateLimit-Limit: 60
X-RateLimit-Remaining: 59
Cache-Control: no-cache, private
Date: Tue, 04 Nov 2025 06:35:29 GMT
```

### Error Request Headers

```
HTTP/1.1 401 Unauthorized
Content-Type: application/json
Cache-Control: no-cache, private
Date: Tue, 04 Nov 2025 06:35:29 GMT
```

---

## 6. cURL Examples

### Check Location (POST)

```bash
curl -X POST http://localhost:8000/api/check-location \
  -H "X-Api-Key: your-secret-api-key-123" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{"email":"user@example.com"}'
```

### Check Location (GET)

```bash
curl -X GET "http://localhost:8000/api/check-location?email=user@example.com" \
  -H "X-Api-Key: your-secret-api-key-123" \
  -H "Accept: application/json"
```

### Latest Version

```bash
curl -X GET http://localhost:8000/api/latest-version \
  -H "X-Api-Key: your-secret-api-key-123" \
  -H "Accept: application/json"
```

---

## 7. JavaScript/Fetch Examples

### Check Location

```javascript
fetch('http://localhost:8000/api/check-location', {
  method: 'POST',
  headers: {
    'X-Api-Key': 'your-secret-api-key-123',
    'Content-Type': 'application/json',
    'Accept': 'application/json'
  },
  body: JSON.stringify({
    email: 'user@example.com'
  })
})
.then(response => response.json())
.then(data => console.log(data))
.catch(error => console.error('Error:', error));
```

### Latest Version

```javascript
fetch('http://localhost:8000/api/latest-version', {
  method: 'GET',
  headers: {
    'X-Api-Key': 'your-secret-api-key-123',
    'Accept': 'application/json'
  }
})
.then(response => response.json())
.then(data => console.log(data))
.catch(error => console.error('Error:', error));
```

---

## 8. Flutter/Dart Examples

### Check Location

```dart
import 'package:http/http.dart' as http;
import 'dart:convert';

Future<Map<String, dynamic>> checkLocation(String email) async {
  final response = await http.post(
    Uri.parse('http://localhost:8000/api/check-location'),
    headers: {
      'X-Api-Key': 'your-secret-api-key-123',
      'Content-Type': 'application/json',
      'Accept': 'application/json',
    },
    body: jsonEncode({
      'email': email,
    }),
  );

  if (response.statusCode == 200) {
    return jsonDecode(response.body);
  } else {
    throw Exception('Failed to check location');
  }
}
```

### Latest Version

```dart
import 'package:http/http.dart' as http;
import 'dart:convert';

Future<Map<String, dynamic>> getLatestVersion() async {
  final response = await http.get(
    Uri.parse('http://localhost:8000/api/latest-version'),
    headers: {
      'X-Api-Key': 'your-secret-api-key-123',
      'Accept': 'application/json',
    },
  );

  if (response.statusCode == 200) {
    return jsonDecode(response.body);
  } else {
    throw Exception('Failed to get latest version');
  }
}
```

---

## Notes

1. **Timestamps:** Semua timestamp dalam format ISO 8601 (UTC)
2. **Timezone:** Server menggunakan timezone Asia/Jakarta (WIB)
3. **Encoding:** Semua response dalam UTF-8
4. **Content-Type:** Selalu `application/json`
5. **Rate Limit:** 60 requests per minute per IP

---

**Last Updated:** November 4, 2025
