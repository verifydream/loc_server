# 📚 API Documentation Index

Panduan lengkap untuk menggunakan dokumentasi API Location Server.

## 🎯 Mulai Dari Mana?

### 👶 Saya Baru Pertama Kali Testing API
**Mulai dari:** [`API_TESTING_GUIDE.md`](API_TESTING_GUIDE.md)
- ⏱️ Waktu: 5-10 menit
- 📖 Isi: Quick start, setup Postman, test pertama
- 🎓 Level: Beginner

---

### 👨‍💻 Saya Mau Implement API di Aplikasi
**Mulai dari:** [`API_DOCUMENTATION.md`](API_DOCUMENTATION.md)
- ⏱️ Waktu: 20-30 menit
- 📖 Isi: Dokumentasi lengkap, endpoint details, authentication
- 🎓 Level: Intermediate

---

### 🔍 Saya Butuh Contoh Response & Code
**Mulai dari:** [`API_RESPONSE_EXAMPLES.md`](API_RESPONSE_EXAMPLES.md)
- ⏱️ Waktu: 15-20 menit
- 📖 Isi: Contoh response lengkap, cURL, JavaScript, Flutter
- 🎓 Level: Intermediate to Advanced

---

### 🚀 Saya Mau Overview Semua Dokumentasi
**Mulai dari:** [`README_API.md`](README_API.md)
- ⏱️ Waktu: 10 menit
- 📖 Isi: Overview, checklist, troubleshooting, best practices
- 🎓 Level: All Levels

---

## 📁 File Structure

```
.
├── README_API.md                    # 📘 Overview & Getting Started
├── API_TESTING_GUIDE.md             # 🚀 Quick Start (5 menit)
├── API_DOCUMENTATION.md             # 📖 Complete Documentation
├── API_RESPONSE_EXAMPLES.md         # 💡 Response Examples & Code
├── postman_collection.json          # 📦 Postman Collection
└── API_DOCS_INDEX.md               # 📚 This file
```

## 🗂️ Dokumentasi Berdasarkan Kebutuhan

### Untuk Testing
| File | Deskripsi |
|------|-----------|
| [`API_TESTING_GUIDE.md`](API_TESTING_GUIDE.md) | Quick start guide untuk testing |
| [`postman_collection.json`](postman_collection.json) | Import ke Postman |

### Untuk Development
| File | Deskripsi |
|------|-----------|
| [`API_DOCUMENTATION.md`](API_DOCUMENTATION.md) | Dokumentasi lengkap semua endpoint |
| [`API_RESPONSE_EXAMPLES.md`](API_RESPONSE_EXAMPLES.md) | Contoh code & response |

### Untuk Reference
| File | Deskripsi |
|------|-----------|
| [`README_API.md`](README_API.md) | Overview & best practices |
| [`API_DOCS_INDEX.md`](API_DOCS_INDEX.md) | Navigation guide (this file) |

## 📋 Dokumentasi Berdasarkan Endpoint

### Check Location API
- **Quick Start:** [`API_TESTING_GUIDE.md`](API_TESTING_GUIDE.md#-test-1-check-location-success)
- **Full Docs:** [`API_DOCUMENTATION.md`](API_DOCUMENTATION.md#1-check-location)
- **Examples:** [`API_RESPONSE_EXAMPLES.md`](API_RESPONSE_EXAMPLES.md#1-check-location-api)

### Latest Version API
- **Quick Start:** [`API_TESTING_GUIDE.md`](API_TESTING_GUIDE.md#-test-2-latest-version-success)
- **Full Docs:** [`API_DOCUMENTATION.md`](API_DOCUMENTATION.md#2-get-latest-version)
- **Examples:** [`API_RESPONSE_EXAMPLES.md`](API_RESPONSE_EXAMPLES.md#2-latest-version-api)

## 🎓 Learning Path

### Path 1: Quick Testing (15 menit)
1. ✅ Baca [`API_TESTING_GUIDE.md`](API_TESTING_GUIDE.md) - 5 min
2. ✅ Import [`postman_collection.json`](postman_collection.json) - 2 min
3. ✅ Test 2-3 requests - 5 min
4. ✅ Done! Kamu sudah bisa test API

### Path 2: Complete Understanding (45 menit)
1. ✅ Baca [`README_API.md`](README_API.md) - 10 min
2. ✅ Baca [`API_DOCUMENTATION.md`](API_DOCUMENTATION.md) - 20 min
3. ✅ Test semua scenarios - 15 min
4. ✅ Done! Kamu paham semua endpoint

### Path 3: Implementation (1-2 jam)
1. ✅ Baca [`API_DOCUMENTATION.md`](API_DOCUMENTATION.md) - 20 min
2. ✅ Baca [`API_RESPONSE_EXAMPLES.md`](API_RESPONSE_EXAMPLES.md) - 20 min
3. ✅ Implement di aplikasi - 30-60 min
4. ✅ Test & debug - 20 min
5. ✅ Done! API sudah terintegrasi

## 🔍 Cari Informasi Spesifik

### Authentication & Security
- **API Key Setup:** [`API_DOCUMENTATION.md`](API_DOCUMENTATION.md#authentication)
- **Security Best Practices:** [`README_API.md`](README_API.md#-best-practices)

### Request & Response Format
- **Request Format:** [`API_DOCUMENTATION.md`](API_DOCUMENTATION.md#endpoints)
- **Response Examples:** [`API_RESPONSE_EXAMPLES.md`](API_RESPONSE_EXAMPLES.md)

### Error Handling
- **Error Responses:** [`API_DOCUMENTATION.md`](API_DOCUMENTATION.md#error-responses)
- **Troubleshooting:** [`README_API.md`](README_API.md#-common-issues)

### Code Examples
- **cURL:** [`API_RESPONSE_EXAMPLES.md`](API_RESPONSE_EXAMPLES.md#6-curl-examples)
- **JavaScript:** [`API_RESPONSE_EXAMPLES.md`](API_RESPONSE_EXAMPLES.md#7-javascriptfetch-examples)
- **Flutter/Dart:** [`API_RESPONSE_EXAMPLES.md`](API_RESPONSE_EXAMPLES.md#8-flutterdart-examples)

### Testing
- **Quick Test:** [`API_TESTING_GUIDE.md`](API_TESTING_GUIDE.md#-test-scenarios)
- **Test Checklist:** [`README_API.md`](README_API.md#-testing-checklist)
- **Postman Collection:** [`postman_collection.json`](postman_collection.json)

## 🛠️ Tools & Resources

### Postman
- **Collection:** [`postman_collection.json`](postman_collection.json)
- **Setup Guide:** [`API_TESTING_GUIDE.md`](API_TESTING_GUIDE.md#step-2-import-postman-collection)

### cURL
- **Examples:** [`API_RESPONSE_EXAMPLES.md`](API_RESPONSE_EXAMPLES.md#6-curl-examples)

### Code Integration
- **JavaScript:** [`API_RESPONSE_EXAMPLES.md`](API_RESPONSE_EXAMPLES.md#7-javascriptfetch-examples)
- **Flutter:** [`API_RESPONSE_EXAMPLES.md`](API_RESPONSE_EXAMPLES.md#8-flutterdart-examples)

## 📊 Quick Reference

### Endpoints
```
POST/GET  /api/check-location     - Check user location
GET       /api/latest-version     - Get latest APK version
```

### Authentication
```
Header: X-Api-Key: your-api-key-here
```

### Base URL
```
Development: http://localhost:8000/api
Production:  https://your-domain.com/api
```

### Status Codes
```
200 - Success
401 - Unauthorized (wrong API key)
404 - Not Found
422 - Validation Error
429 - Rate Limit Exceeded
500 - Server Error
```

## 🎯 Quick Actions

### Saya Mau...

#### ...Test API Sekarang
1. Import [`postman_collection.json`](postman_collection.json)
2. Set API Key di environment
3. Click Send!

#### ...Baca Dokumentasi Lengkap
👉 Buka [`API_DOCUMENTATION.md`](API_DOCUMENTATION.md)

#### ...Lihat Contoh Response
👉 Buka [`API_RESPONSE_EXAMPLES.md`](API_RESPONSE_EXAMPLES.md)

#### ...Implement di Flutter
👉 Buka [`API_RESPONSE_EXAMPLES.md`](API_RESPONSE_EXAMPLES.md#8-flutterdart-examples)

#### ...Troubleshoot Error
👉 Buka [`README_API.md`](README_API.md#-common-issues)

#### ...Setup dari Awal
👉 Buka [`API_TESTING_GUIDE.md`](API_TESTING_GUIDE.md#-quick-setup-5-menit)

## 📞 Need Help?

### Dokumentasi Tidak Jelas?
- Cek file lain yang relevan di index ini
- Lihat contoh di [`API_RESPONSE_EXAMPLES.md`](API_RESPONSE_EXAMPLES.md)

### API Error?
- Cek [`README_API.md`](README_API.md#-common-issues)
- Lihat log: `storage/logs/laravel.log`

### Testing Issues?
- Gunakan [`postman_collection.json`](postman_collection.json)
- Ikuti [`API_TESTING_GUIDE.md`](API_TESTING_GUIDE.md)

## ✅ Checklist

### Setup
- [ ] Server Laravel running
- [ ] API Key di-set di `.env`
- [ ] Database ada data
- [ ] Postman installed

### Documentation
- [ ] Baca overview di [`README_API.md`](README_API.md)
- [ ] Baca quick start di [`API_TESTING_GUIDE.md`](API_TESTING_GUIDE.md)
- [ ] Baca full docs di [`API_DOCUMENTATION.md`](API_DOCUMENTATION.md)
- [ ] Lihat examples di [`API_RESPONSE_EXAMPLES.md`](API_RESPONSE_EXAMPLES.md)

### Testing
- [ ] Import [`postman_collection.json`](postman_collection.json)
- [ ] Test Check Location API
- [ ] Test Latest Version API
- [ ] Test error scenarios

### Implementation
- [ ] Implement authentication
- [ ] Implement Check Location
- [ ] Implement Latest Version
- [ ] Handle errors
- [ ] Test integration

## 🎉 Summary

Dokumentasi API Location Server terdiri dari:

1. **README_API.md** - Overview & getting started
2. **API_TESTING_GUIDE.md** - Quick start untuk testing
3. **API_DOCUMENTATION.md** - Dokumentasi lengkap
4. **API_RESPONSE_EXAMPLES.md** - Contoh response & code
5. **postman_collection.json** - Postman collection
6. **API_DOCS_INDEX.md** - Navigation guide (this file)

Pilih file yang sesuai dengan kebutuhanmu dan mulai explore! 🚀

---

**Last Updated:** November 4, 2025
