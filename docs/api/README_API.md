# Location Server API - Complete Documentation

Dokumentasi lengkap untuk API Location Server dan App Version Management.

## 📚 Dokumentasi Files

| File | Deskripsi | Untuk Siapa |
|------|-----------|-------------|
| **API_TESTING_GUIDE.md** | Quick start guide (5 menit) | Developer yang baru mulai |
| **API_DOCUMENTATION.md** | Dokumentasi lengkap semua endpoint | Developer & QA |
| **API_RESPONSE_EXAMPLES.md** | Contoh response lengkap | Developer (referensi) |
| **postman_collection.json** | Postman collection siap import | QA & Testing |

## 🚀 Quick Start

### 1. Setup (2 Menit)

```bash
# 1. Pastikan server running
php artisan serve

# 2. Cek API Key di .env
cat .env | grep FLUTTER_API_KEY

# 3. Import postman_collection.json ke Postman

# 4. Set environment variables di Postman:
#    - base_url: http://localhost:8000
#    - api_key: your-secret-api-key-123
```

### 2. Test API (1 Menit)

Di Postman:
1. Pilih collection "Location Server API"
2. Pilih request "Check Location > POST - Valid Email"
3. Click **Send**
4. ✅ Seharusnya dapat response 200 OK

## 📋 Available APIs

### 1. Check Location API

Mengecek lokasi user berdasarkan email.

**Endpoints:**
- `POST /api/check-location`
- `GET /api/check-location?email=user@example.com`

**Use Case:**
- Mobile app check user location saat login
- Validasi user ada di lokasi yang benar
- Get user profile dan location data

**Quick Test:**
```bash
curl -X POST http://localhost:8000/api/check-location \
  -H "X-Api-Key: your-api-key" \
  -H "Content-Type: application/json" \
  -d '{"email":"user@example.com"}'
```

---

### 2. Latest Version API

Mendapatkan informasi versi APK terbaru untuk auto-update.

**Endpoint:**
- `GET /api/latest-version`

**Use Case:**
- Mobile app check for updates saat startup
- Auto-download APK terbaru
- Show release notes ke user

**Quick Test:**
```bash
curl -X GET http://localhost:8000/api/latest-version \
  -H "X-Api-Key: your-api-key"
```

## 🔐 Authentication

Semua API endpoint memerlukan API Key di header:

```
X-Api-Key: your-secret-api-key-123
```

**Setup API Key:**

1. Edit file `.env`:
   ```env
   FLUTTER_API_KEY=your-secret-api-key-123
   ```

2. Restart server:
   ```bash
   php artisan config:clear
   php artisan serve
   ```

3. Gunakan API Key di header request

## 📊 Response Format

### Success Response

```json
{
  "success": true,
  "data": {
    // ... data here
  }
}
```

### Error Response

```json
{
  "success": false,
  "message": "Error message",
  "errors": {
    // ... validation errors
  }
}
```

## 🎯 Testing Checklist

### Pre-Testing
- [ ] Server Laravel running (`php artisan serve`)
- [ ] Database ada data user dan location
- [ ] API Key sudah di-set di `.env`
- [ ] Postman collection sudah di-import
- [ ] Environment variables sudah di-set

### Test Check Location API
- [ ] ✅ POST dengan email valid (expect 200)
- [ ] ✅ GET dengan query param (expect 200)
- [ ] ❌ POST dengan email invalid (expect 422)
- [ ] ❌ POST tanpa email (expect 422)
- [ ] ❌ Request dengan wrong API key (expect 401)
- [ ] ❌ Request tanpa API key (expect 401)

### Test Latest Version API
- [ ] ✅ GET latest version (expect 200)
- [ ] ❌ GET dengan wrong API key (expect 401)
- [ ] ❌ GET tanpa API key (expect 401)
- [ ] ❌ GET saat tidak ada version (expect 404)

### Performance Testing
- [ ] Response time < 1 second
- [ ] Rate limiting works (60 req/min)
- [ ] Concurrent requests handled properly

## 🐛 Common Issues

### ❌ 401 Unauthorized

**Problem:** API Key tidak valid

**Solution:**
```bash
# Cek API Key di .env
cat .env | grep FLUTTER_API_KEY

# Clear config cache
php artisan config:clear

# Restart server
php artisan serve
```

---

### ❌ 404 Not Found

**Problem:** Endpoint tidak ditemukan

**Solution:**
```bash
# Clear route cache
php artisan route:clear

# List semua routes
php artisan route:list | grep api

# Pastikan route ada
```

---

### ❌ 422 Validation Error

**Problem:** Data yang dikirim tidak valid

**Solution:**
- Cek format email sudah benar
- Pastikan required fields tidak kosong
- Lihat error message di response

---

### ❌ 500 Internal Server Error

**Problem:** Error di server

**Solution:**
```bash
# Cek log error
tail -f storage/logs/laravel.log

# Cek database connection
php artisan tinker
>>> DB::connection()->getPdo();

# Clear all cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear
```

---

### ❌ Connection Refused

**Problem:** Server tidak running

**Solution:**
```bash
# Start server
php artisan serve

# Atau dengan custom port
php artisan serve --port=8080
```

## 📖 Detailed Documentation

### Untuk Developer Baru
👉 Baca: **API_TESTING_GUIDE.md**
- Quick start dalam 5 menit
- Step-by-step setup
- Basic testing scenarios

### Untuk Development
👉 Baca: **API_DOCUMENTATION.md**
- Dokumentasi lengkap semua endpoint
- Request/response format
- Error handling
- Rate limiting
- Security best practices

### Untuk Referensi
👉 Baca: **API_RESPONSE_EXAMPLES.md**
- Contoh response lengkap
- cURL examples
- JavaScript/Fetch examples
- Flutter/Dart examples

### Untuk Testing
👉 Import: **postman_collection.json**
- Ready-to-use Postman collection
- Semua test cases sudah ada
- Environment variables template

## 🔧 Development Tools

### Postman Collection

Import `postman_collection.json` untuk:
- ✅ Pre-configured requests
- ✅ Environment variables
- ✅ Test scenarios
- ✅ Easy testing

### cURL Commands

Lihat `API_RESPONSE_EXAMPLES.md` untuk cURL examples.

### Code Examples

Lihat `API_RESPONSE_EXAMPLES.md` untuk:
- JavaScript/Fetch
- Flutter/Dart
- PHP/Laravel

## 📈 API Specifications

| Spec | Value |
|------|-------|
| Base URL | `http://localhost:8000/api` |
| Authentication | API Key (Header) |
| Rate Limit | 60 requests/minute |
| Response Format | JSON |
| Encoding | UTF-8 |
| Timezone | Asia/Jakarta (WIB) |

## 🎓 Learning Path

### Beginner (15 menit)
1. Baca **API_TESTING_GUIDE.md** (5 min)
2. Import Postman collection (2 min)
3. Test 2-3 requests (5 min)
4. ✅ Selesai!

### Intermediate (30 menit)
1. Baca **API_DOCUMENTATION.md** (15 min)
2. Test semua success scenarios (10 min)
3. Test semua error scenarios (5 min)
4. ✅ Paham semua endpoint!

### Advanced (1 jam)
1. Baca **API_RESPONSE_EXAMPLES.md** (20 min)
2. Implement di Flutter/JavaScript (30 min)
3. Test edge cases (10 min)
4. ✅ Siap production!

## 🚦 API Status Codes

| Code | Meaning | When |
|------|---------|------|
| 200 | OK | Request berhasil |
| 401 | Unauthorized | API Key salah/tidak ada |
| 404 | Not Found | Resource tidak ditemukan |
| 422 | Validation Error | Data tidak valid |
| 429 | Too Many Requests | Rate limit exceeded |
| 500 | Server Error | Error di server |

## 💡 Best Practices

### Security
- ✅ Jangan commit API Key ke repository
- ✅ Gunakan HTTPS di production
- ✅ Rotate API Key secara berkala
- ✅ Validate semua input

### Performance
- ✅ Cache response jika memungkinkan
- ✅ Gunakan pagination untuk list data
- ✅ Monitor response time
- ✅ Handle rate limiting dengan graceful

### Error Handling
- ✅ Always check status code
- ✅ Handle semua error scenarios
- ✅ Show user-friendly error messages
- ✅ Log errors untuk debugging

## 📞 Support

### Documentation Issues
Jika ada yang kurang jelas di dokumentasi, silakan update file yang sesuai.

### API Issues
Cek log error di `storage/logs/laravel.log`

### Testing Issues
Gunakan Postman collection untuk consistent testing.

## 📝 Changelog

### Version 1.0.0 (November 4, 2025)
- ✅ Initial API documentation
- ✅ Check Location endpoint
- ✅ Latest Version endpoint
- ✅ Postman collection
- ✅ Complete examples

## 🎯 Next Steps

1. ✅ Setup environment
2. ✅ Import Postman collection
3. ✅ Test all endpoints
4. ✅ Read full documentation
5. ✅ Implement in your app
6. ✅ Deploy to production

---

**Happy Coding! 🚀**

*Last Updated: November 4, 2025*
