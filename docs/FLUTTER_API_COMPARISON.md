# Perbandingan API Guide v1 vs v2

## 📊 Quick Comparison

| Feature | FLUTTER_API_GUIDE1.md | FLUTTER_API_GUIDE2.md |
|---------|----------------------|----------------------|
| **Authentication** | ❌ Tidak perlu | ✅ Wajib (API Key) |
| **Base URL** | Production only | Dev + Production |
| **Endpoints** | 1 endpoint | 3 endpoints |
| **Headers** | Content-Type, Accept | X-Api-Key, Content-Type, Accept |
| **Use Case** | Pre-login location check | Location check + Auto update |
| **Response Format** | Simple (email, url, location) | Detailed (user, location objects) |

---

## 🔐 Authentication

### Guide 1 (No Auth)
```
Headers:
  Content-Type: application/json
  Accept: application/json
```
- Tidak perlu API Key
- Public endpoint
- Untuk pre-login check

### Guide 2 (With Auth)
```
Headers:
  X-Api-Key: your-secret-api-key-123
  Content-Type: application/json
  Accept: application/json
```
- Wajib API Key
- Protected endpoint
- Untuk authenticated requests

---

## 📡 Endpoints

### Guide 1
```
POST /api/check-location
```
**Response:**
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

### Guide 2
```
POST /api/check-location
GET  /api/check-location?email=user@example.com
GET  /api/latest-version
```
**Response Check Location:**
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
      "latitude": -6.2088,
      "longitude": 106.8456,
      "radius": 100
    }
  }
}
```

**Response Latest Version:**
```json
{
  "status": "success",
  "data": {
    "version_name": "1.2.5",
    "version_code": 125,
    "release_notes": "Bug fixes",
    "download_url": "/storage/updates/app-v1.2.5.apk"
  }
}
```

---

## 🎯 Use Cases

### Guide 1: Pre-Login Location Check
```
1. User input email
2. Check location (no auth)
3. Get online_url
4. Use online_url for login
```

**Cocok untuk:**
- Multi-tenant system
- Dynamic server routing
- Pre-login validation

### Guide 2: Authenticated Operations
```
1. Setup API Key
2. Check location (with auth)
3. Get user & location details
4. Check for app updates
5. Download & install updates
```

**Cocok untuk:**
- Single server system
- Detailed user info needed
- Auto-update feature
- Secure API access

---

## 🔄 Migration Guide

### Dari Guide 1 ke Guide 2

**Step 1: Tambah API Key**
```dart
// Tambah API Key di headers
final headers = {
  'X-Api-Key': 'your-secret-api-key-123',
  'Content-Type': 'application/json',
  'Accept': 'application/json',
};
```

**Step 2: Update Response Parsing**
```dart
// Guide 1
final onlineUrl = response['data']['online_url'];
final locationName = response['data']['location_name'];

// Guide 2
final userId = response['data']['user']['id'];
final userName = response['data']['user']['name'];
final locationName = response['data']['location']['name'];
final latitude = response['data']['location']['latitude'];
```

**Step 3: Tambah Version Check**
```dart
// Tambah endpoint baru
final versionResponse = await http.get(
  Uri.parse('$baseUrl/latest-version'),
  headers: headers,
);
```

---

## 📝 Kapan Pakai Yang Mana?

### Gunakan Guide 1 jika:
- ✅ Tidak butuh authentication
- ✅ Hanya butuh info server URL
- ✅ Pre-login location check
- ✅ Multi-tenant system
- ✅ Public endpoint

### Gunakan Guide 2 jika:
- ✅ Butuh authentication
- ✅ Butuh detail user & location
- ✅ Butuh fitur auto-update
- ✅ Single server system
- ✅ Protected endpoint

---

## 🎉 Summary

**FLUTTER_API_GUIDE1.md:**
- Simple, no auth
- Pre-login check
- Get server URL
- Public endpoint

**FLUTTER_API_GUIDE2.md:**
- With authentication
- Detailed user/location info
- Auto-update feature
- Protected endpoint

Pilih guide yang sesuai dengan kebutuhan aplikasi kamu!

---

**Created:** November 4, 2025
