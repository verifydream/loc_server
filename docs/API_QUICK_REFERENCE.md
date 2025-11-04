# 🚀 API Quick Reference Card

Referensi cepat untuk testing API Location Server.

---

## 🔗 Base URL
```
http://localhost:8000/api
```

---

## 🔑 Authentication
```
Header: X-Api-Key: your-secret-api-key-123
```

**Get API Key from:** `.env` file → `FLUTTER_API_KEY`

---

## 📡 Endpoints

### 1️⃣ Check Location
```
POST /api/check-location
GET  /api/check-location?email=user@example.com
```

**Request Body (POST):**
```json
{
  "email": "user@example.com"
}
```

**Response (200):**
```json
{
  "success": true,
  "data": {
    "user": { ... },
    "location": { ... }
  }
}
```

---

### 2️⃣ Latest Version
```
GET /api/latest-version
```

**Response (200):**
```json
{
  "status": "success",
  "data": {
    "version_name": "1.0.0",
    "version_code": 1,
    "release_notes": "...",
    "download_url": "/storage/updates/..."
  }
}
```

---

## 📋 Required Headers

```
X-Api-Key: your-api-key-here
Content-Type: application/json
Accept: application/json
```

---

## 🎯 Status Codes

| Code | Meaning |
|------|---------|
| 200 | ✅ Success |
| 401 | ❌ Unauthorized (wrong API key) |
| 404 | ❌ Not Found |
| 422 | ❌ Validation Error |
| 429 | ❌ Rate Limit (60/min) |
| 500 | ❌ Server Error |

---

## 🧪 Quick Test (cURL)

### Check Location
```bash
curl -X POST http://localhost:8000/api/check-location \
  -H "X-Api-Key: your-api-key" \
  -H "Content-Type: application/json" \
  -d '{"email":"user@example.com"}'
```

### Latest Version
```bash
curl -X GET http://localhost:8000/api/latest-version \
  -H "X-Api-Key: your-api-key"
```

---

## 🐛 Common Errors

### 401 Unauthorized
**Cause:** Wrong/missing API key  
**Fix:** Check `.env` → `FLUTTER_API_KEY`

### 422 Validation Error
**Cause:** Invalid data  
**Fix:** Check email format

### 404 Not Found
**Cause:** Wrong endpoint  
**Fix:** Check URL path

### 500 Server Error
**Cause:** Server issue  
**Fix:** Check `storage/logs/laravel.log`

---

## ⚡ Quick Setup

1. **Start Server**
   ```bash
   php artisan serve
   ```

2. **Check API Key**
   ```bash
   cat .env | grep FLUTTER_API_KEY
   ```

3. **Import Postman**
   - Import `postman_collection.json`
   - Set environment variables

4. **Test!**
   - Click Send in Postman

---

## 📚 Full Documentation

- **Quick Start:** `API_TESTING_GUIDE.md`
- **Full Docs:** `API_DOCUMENTATION.md`
- **Examples:** `API_RESPONSE_EXAMPLES.md`
- **Overview:** `README_API.md`

---

## 💡 Tips

✅ Use Postman collection for testing  
✅ Save API key in environment variables  
✅ Check status code first  
✅ Read error messages  
✅ Monitor rate limit (60/min)

---

**Print this card for quick reference! 📄**
