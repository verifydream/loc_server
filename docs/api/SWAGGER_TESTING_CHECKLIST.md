# ✅ Swagger Testing Checklist

Gunakan checklist ini untuk memastikan Swagger berfungsi dengan baik.

## 🔍 Pre-Testing Checklist

- [ ] L5-Swagger package sudah terinstall (`composer show darkaonline/l5-swagger`)
- [ ] Config file sudah di-publish (`config/l5-swagger.php` exists)
- [ ] Documentation sudah di-generate (`php artisan l5-swagger:generate`)
- [ ] Route `/api/documentation` tersedia (`php artisan route:list --path=api/documentation`)
- [ ] File `storage/api-docs/api-docs.json` exists

## 🌐 Access Testing

### Test 1: Akses Swagger UI
- [ ] Buka browser
- [ ] Akses `http://localhost/api/documentation` atau `http://localhost:8000/api/documentation`
- [ ] Swagger UI muncul dengan judul "Location Server API Documentation"
- [ ] Tidak ada error 404 atau 500

### Test 2: Lihat Endpoints
- [ ] Endpoint **POST /api/check-location** muncul
- [ ] Endpoint **GET /api/check-location** muncul
- [ ] Endpoint **GET /api/latest-version** muncul
- [ ] Setiap endpoint punya deskripsi yang jelas
- [ ] Tags "Location" dan "App Version" muncul

### Test 3: Lihat Documentation Details
- [ ] Klik salah satu endpoint untuk expand
- [ ] Muncul deskripsi endpoint
- [ ] Muncul parameters/request body
- [ ] Muncul response examples
- [ ] Muncul response codes (200, 404, 422, 500)

## 🔐 Authentication Testing

### Test 4: Authorize dengan API Key
- [ ] Klik tombol **"Authorize"** (ikon gembok) di atas
- [ ] Field `X-API-KEY` muncul
- [ ] Input API key kamu (dari .env `FLUTTER_API_KEY`)
- [ ] Klik **"Authorize"**
- [ ] Status berubah menjadi "Authorized"
- [ ] Klik **"Close"**

## 🧪 API Testing

### Test 5: Test POST /api/check-location
- [ ] Klik endpoint **POST /api/check-location**
- [ ] Klik **"Try it out"**
- [ ] Request body editor muncul
- [ ] Edit email menjadi email yang valid dari database
- [ ] Klik **"Execute"**
- [ ] Response code 200 muncul
- [ ] Response body berisi data location
- [ ] Curl command muncul di bawah

### Test 6: Test GET /api/check-location
- [ ] Klik endpoint **GET /api/check-location**
- [ ] Klik **"Try it out"**
- [ ] Parameter `email` muncul
- [ ] Input email yang valid
- [ ] Klik **"Execute"**
- [ ] Response code 200 muncul
- [ ] Response body berisi data location

### Test 7: Test GET /api/latest-version
- [ ] Klik endpoint **GET /api/latest-version**
- [ ] Klik **"Try it out"**
- [ ] Klik **"Execute"**
- [ ] Response code 200 atau 404 muncul (tergantung ada data atau tidak)
- [ ] Jika 200: Response body berisi version info
- [ ] Jika 404: Response body berisi error message

## ❌ Error Testing

### Test 8: Test Validation Error
- [ ] Test **POST /api/check-location** dengan email kosong
- [ ] Response code 422 muncul
- [ ] Response body berisi validation errors
- [ ] Error message jelas dan informatif

### Test 9: Test Not Found Error
- [ ] Test **POST /api/check-location** dengan email yang tidak ada di database
- [ ] Response code 404 muncul
- [ ] Response body berisi error message

### Test 10: Test Unauthorized (tanpa API Key)
- [ ] Klik **"Authorize"** dan **"Logout"**
- [ ] Test salah satu endpoint
- [ ] Response code 401 atau 403 muncul (jika middleware aktif)

## 📋 Response Testing

### Test 11: Verify Response Format
- [ ] Response dalam format JSON yang valid
- [ ] Response structure sesuai dengan dokumentasi
- [ ] Property names sesuai dengan yang didokumentasikan
- [ ] Data types sesuai (string, integer, boolean, dll)

### Test 12: Verify Response Headers
- [ ] Response headers muncul
- [ ] Content-Type: application/json
- [ ] Headers lain sesuai kebutuhan

## 🔄 Copy & Export Testing

### Test 13: Copy Curl Command
- [ ] Execute salah satu endpoint
- [ ] Copy curl command yang muncul
- [ ] Paste ke terminal/command prompt
- [ ] Command berjalan dan menghasilkan response yang sama

### Test 14: Download OpenAPI Spec
- [ ] Klik link "Download" atau akses `/docs/api-docs.json`
- [ ] File JSON ter-download
- [ ] File valid dan bisa dibuka
- [ ] Bisa di-import ke Postman

## 🎨 UI/UX Testing

### Test 15: UI Elements
- [ ] Authorize button terlihat jelas
- [ ] Endpoints ter-group dengan baik (by tags)
- [ ] Colors dan styling sesuai
- [ ] Responsive di mobile browser
- [ ] Tidak ada broken images atau CSS

### Test 16: Navigation
- [ ] Bisa scroll dengan smooth
- [ ] Bisa expand/collapse endpoints
- [ ] Bisa expand/collapse sections
- [ ] Search/filter berfungsi (jika ada)

## 🔧 Configuration Testing

### Test 17: Environment Variables
- [ ] `L5_SWAGGER_CONST_HOST` di .env sesuai dengan server URL
- [ ] Server URL di Swagger UI benar
- [ ] Bisa switch server (jika multiple servers)

### Test 18: Regenerate Documentation
- [ ] Edit anotasi di controller
- [ ] Run `php artisan l5-swagger:generate`
- [ ] Refresh Swagger UI
- [ ] Perubahan muncul

## 📱 Integration Testing

### Test 19: Import to Postman
- [ ] Download `storage/api-docs/api-docs.json`
- [ ] Buka Postman
- [ ] Import → Upload Files
- [ ] Select file JSON
- [ ] Collection ter-import dengan semua endpoints
- [ ] Endpoints bisa di-test dari Postman

### Test 20: Share with Team
- [ ] Share URL Swagger dengan team member
- [ ] Team member bisa akses tanpa error
- [ ] Team member bisa test API
- [ ] Documentation jelas dan mudah dipahami

## 🚀 Production Readiness

### Test 21: Production Configuration
- [ ] Update `L5_SWAGGER_CONST_HOST` untuk production URL
- [ ] Regenerate documentation
- [ ] Test di production environment
- [ ] Semua endpoints accessible

### Test 22: Security
- [ ] API Key authentication berfungsi
- [ ] Unauthorized requests ditolak
- [ ] Sensitive data tidak ter-expose di documentation
- [ ] HTTPS enabled di production

## 📊 Final Checklist

- [ ] Semua endpoints terdokumentasi
- [ ] Semua response codes terdokumentasi
- [ ] Examples jelas dan akurat
- [ ] Descriptions informatif
- [ ] Tags ter-organize dengan baik
- [ ] Authentication berfungsi
- [ ] Testing dari Swagger UI berhasil
- [ ] Documentation bisa di-share
- [ ] Ready for production

## 🎉 Success Criteria

Swagger setup dianggap berhasil jika:
- ✅ Swagger UI bisa diakses tanpa error
- ✅ Semua endpoints muncul dan terdokumentasi
- ✅ Testing dari Swagger UI berhasil
- ✅ Response sesuai dengan dokumentasi
- ✅ Authentication berfungsi dengan baik
- ✅ Documentation bisa di-export dan di-share

## 🐛 Troubleshooting

Jika ada test yang gagal, lihat:
- `docs/SWAGGER_GUIDE.md` - Troubleshooting section
- `docs/SWAGGER_COMMANDS.md` - Command reference
- Run `php artisan l5-swagger:generate` dan `php artisan config:clear`

---

**Total Tests:** 22
**Estimated Time:** 15-20 minutes

Happy Testing! 🚀
