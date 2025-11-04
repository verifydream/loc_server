# 🚀 Panduan Cepat - Testing API di Postman

Panduan singkat dalam Bahasa Indonesia untuk testing API Location Server.

---

## 📋 Yang Kamu Butuhkan

- ✅ Postman (download di postman.com)
- ✅ Server Laravel running
- ✅ API Key dari file `.env`

---

## ⚡ Quick Start (5 Menit)

### Step 1: Cek API Key (1 menit)

Buka file `.env` di project, cari baris ini:
```env
FLUTTER_API_KEY=your-secret-api-key-123
```

Copy API Key-nya (contoh: `your-secret-api-key-123`)

---

### Step 2: Import Collection ke Postman (2 menit)

1. Buka Postman
2. Click tombol **Import** (pojok kiri atas)
3. Pilih file `postman_collection.json` dari project
4. Collection "Location Server API" akan muncul di sidebar

---

### Step 3: Setup Environment (1 menit)

1. Click ⚙️ icon (Settings) di Postman
2. Pilih **Environments**
3. Click **Create Environment**
4. Nama: `Local Development`
5. Tambah 2 variables:

| Variable | Value |
|----------|-------|
| base_url | http://localhost:8000 |
| api_key | your-secret-api-key-123 |

6. Click **Save**
7. Pilih environment "Local Development" dari dropdown

---

### Step 4: Test API! (1 menit)

1. Di sidebar, expand collection "Location Server API"
2. Expand folder "Check Location"
3. Click "POST - Valid Email"
4. Click tombol **Send** (biru)
5. ✅ Lihat response di bawah!

---

## 🎯 Test Scenarios

### ✅ Test 1: Check Location (Success)

**Request:**
- Method: POST
- URL: `{{base_url}}/api/check-location`
- Body:
  ```json
  {
    "email": "user@example.com"
  }
  ```

**Expected Response (200 OK):**
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

### ✅ Test 2: Latest Version (Success)

**Request:**
- Method: GET
- URL: `{{base_url}}/api/latest-version`

**Expected Response (200 OK):**
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

### ❌ Test 3: Wrong API Key (Error)

**Request:**
- Ganti header `X-Api-Key` dengan value salah: `wrong-key`
- Send request

**Expected Response (401 Unauthorized):**
```json
{
  "message": "Unauthorized"
}
```

---

### ❌ Test 4: Invalid Email (Error)

**Request:**
- Body:
  ```json
  {
    "email": "invalid-email"
  }
  ```

**Expected Response (422 Validation Error):**
```json
{
  "success": false,
  "message": "Validation error",
  "errors": {
    "email": ["The email must be a valid email address."]
  }
}
```

---

## 📡 Available Endpoints

### 1. Check Location
```
POST /api/check-location
GET  /api/check-location?email=user@example.com
```
**Fungsi:** Cek lokasi user berdasarkan email

---

### 2. Latest Version
```
GET /api/latest-version
```
**Fungsi:** Ambil info versi APK terbaru

---

## 🔑 Authentication

Semua request butuh header ini:
```
X-Api-Key: your-api-key-here
```

Sudah otomatis di-set kalau pakai Postman collection!

---

## 🐛 Troubleshooting

### ❌ Error: "Unauthorized"
**Masalah:** API Key salah atau tidak ada

**Solusi:**
1. Cek file `.env` → `FLUTTER_API_KEY`
2. Update variable `api_key` di Postman environment
3. Pastikan environment "Local Development" sudah dipilih

---

### ❌ Error: "Connection Refused"
**Masalah:** Server tidak running

**Solusi:**
```bash
php artisan serve
```

---

### ❌ Error: "404 Not Found"
**Masalah:** URL salah

**Solusi:**
1. Cek URL: `http://localhost:8000/api/check-location`
2. Pastikan ada `/api/` di URL
3. Clear route cache:
   ```bash
   php artisan route:clear
   ```

---

### ❌ Error: "Validation Error"
**Masalah:** Data yang dikirim tidak valid

**Solusi:**
1. Cek format email sudah benar
2. Pastikan field required tidak kosong
3. Lihat error message di response

---

## 📚 Dokumentasi Lengkap

Kalau butuh info lebih detail, baca file ini:

| File | Untuk Apa |
|------|-----------|
| `API_TESTING_GUIDE.md` | Panduan testing lengkap (English) |
| `API_DOCUMENTATION.md` | Dokumentasi full semua endpoint |
| `API_RESPONSE_EXAMPLES.md` | Contoh response & code |
| `README_API.md` | Overview & troubleshooting |
| `API_QUICK_REFERENCE.md` | Quick reference card |

---

## ✅ Checklist Testing

### Setup
- [ ] Server running (`php artisan serve`)
- [ ] API Key sudah dicopy dari `.env`
- [ ] Postman sudah installed
- [ ] Collection sudah di-import
- [ ] Environment sudah di-setup

### Test Check Location
- [ ] POST - Email valid (expect 200)
- [ ] POST - Email invalid (expect 422)
- [ ] POST - Tanpa email (expect 422)
- [ ] GET - Dengan query param (expect 200)
- [ ] POST - API Key salah (expect 401)

### Test Latest Version
- [ ] GET - Success (expect 200)
- [ ] GET - API Key salah (expect 401)

---

## 💡 Tips

✅ **Gunakan Environment Variables** - Lebih mudah ganti base URL atau API Key

✅ **Save Response Examples** - Klik "Save Response" untuk dokumentasi

✅ **Test Error Scenarios** - Jangan cuma test yang success

✅ **Check Status Code** - Selalu cek status code dulu (200, 401, 422, dll)

✅ **Read Error Messages** - Error message kasih tau masalahnya apa

---

## 🎯 Next Steps

Setelah berhasil test di Postman:

1. ✅ Implement API di aplikasi Flutter/JavaScript
2. ✅ Handle semua error scenarios
3. ✅ Test di device/emulator
4. ✅ Deploy ke production

---

## 📞 Butuh Bantuan?

### Dokumentasi Kurang Jelas?
Baca file dokumentasi lain yang lebih lengkap:
- `API_DOCUMENTATION.md` - Full documentation
- `API_RESPONSE_EXAMPLES.md` - Contoh lengkap

### API Error?
Cek log error:
```bash
tail -f storage/logs/laravel.log
```

### Masih Bingung?
Baca `README_API.md` untuk troubleshooting lengkap

---

## 🎉 Selesai!

Kalau sudah berhasil test semua endpoint, kamu siap implement API di aplikasi!

**Happy Testing! 🚀**

---

**Dibuat:** 4 November 2025  
**Bahasa:** Indonesia  
**Level:** Beginner Friendly
