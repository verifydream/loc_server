# 🚀 Swagger Quick Start Guide

## ✅ Installation Complete!

L5-Swagger sudah berhasil diinstall dan dikonfigurasi di project kamu.

## 🎯 Akses Swagger UI

Buka browser dan kunjungi:

```
http://localhost/api/documentation
```

atau jika menggunakan `php artisan serve`:

```
http://localhost:8000/api/documentation
```

## 📝 5 Langkah Testing API

### 1️⃣ Buka Swagger UI
Akses URL di atas, kamu akan melihat halaman dokumentasi API yang interaktif.

### 2️⃣ Authorize (untuk endpoint yang butuh API Key)
- Klik tombol **"Authorize"** 🔒 di bagian atas
- Masukkan API Key kamu di field `Value`
- Klik **"Authorize"**
- Klik **"Close"**

### 3️⃣ Pilih Endpoint
- Lihat daftar endpoint yang tersedia
- Klik endpoint yang ingin kamu test (contoh: `POST /api/check-location`)

### 4️⃣ Try It Out
- Klik tombol **"Try it out"**
- Edit request body atau parameters sesuai kebutuhan
- Contoh untuk check-location:
  ```json
  {
    "email": "user@example.com"
  }
  ```

### 5️⃣ Execute & Lihat Response
- Klik tombol **"Execute"**
- Scroll ke bawah untuk melihat:
  - **Curl command** (bisa copy-paste ke terminal)
  - **Request URL**
  - **Response status code** (200, 404, 422, dll)
  - **Response body** (JSON)

## 🎨 Fitur Swagger UI

### ✨ Testing Langsung
Tidak perlu Postman! Test API langsung dari browser.

### 📋 Copy Curl Command
Setiap request bisa di-copy sebagai curl command untuk digunakan di terminal atau script.

### 🔄 Multiple Formats
Response bisa dilihat dalam format JSON yang rapi dan mudah dibaca.

### 📚 Documentation
Setiap endpoint punya dokumentasi lengkap:
- Deskripsi endpoint
- Parameters yang dibutuhkan
- Request body format
- Response examples
- Error codes

### 🔐 Authentication
Input API key sekali, semua request otomatis include API key tersebut.

## 📖 Endpoints yang Tersedia

### Location API
- **POST /api/check-location** - Check user location by email
- **GET /api/check-location** - Check user location by email (alternative)

### App Version API
- **GET /api/latest-version** - Get latest APK version

## 🛠️ Commands yang Sering Digunakan

### Regenerate Documentation
Setelah update anotasi di controller:
```bash
php artisan l5-swagger:generate
```

### Clear Cache
Jika perubahan tidak muncul:
```bash
php artisan config:clear
php artisan cache:clear
```

### View Routes
```bash
php artisan route:list --path=api
```

## 💡 Tips

1. **Gunakan "Try it out"** untuk test API tanpa coding
2. **Copy curl command** untuk digunakan di script atau terminal
3. **Lihat response examples** untuk memahami format data
4. **Authorize sekali** untuk semua requests
5. **Regenerate** setiap kali update anotasi

## 📚 Dokumentasi Lengkap

- **API Documentation:** [API_DOCUMENTATION.md](API_DOCUMENTATION.md)
- **Swagger Guide:** [SWAGGER_GUIDE.md](SWAGGER_GUIDE.md)
- **Commands Reference:** [SWAGGER_COMMANDS.md](SWAGGER_COMMANDS.md)
- **Customization:** [SWAGGER_CUSTOMIZATION.md](SWAGGER_CUSTOMIZATION.md)

## 🎉 Selamat!

Kamu sekarang punya dokumentasi API interaktif yang bisa digunakan untuk:
- ✅ Testing API tanpa Postman
- ✅ Dokumentasi untuk team
- ✅ Sharing dengan client/stakeholder
- ✅ Generate client SDK
- ✅ Import ke Postman

Happy Testing! 🚀
