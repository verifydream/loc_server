# Panduan Swagger API Documentation

## Akses Dokumentasi API

Setelah L5-Swagger terinstall, kamu bisa mengakses dokumentasi API interaktif di:

```
http://localhost/api/documentation
```

atau jika menggunakan port tertentu:

```
http://localhost:8000/api/documentation
```

## Fitur Swagger UI

### 1. Testing API Langsung
- Klik endpoint yang ingin di-test
- Klik tombol **"Try it out"**
- Isi parameter atau body yang diperlukan
- Klik **"Execute"** untuk menjalankan request
- Lihat response langsung di bawahnya

### 2. Authentication
Karena API menggunakan API Key authentication:
- Klik tombol **"Authorize"** di bagian atas
- Masukkan API Key kamu di field `X-API-KEY`
- Klik **"Authorize"**
- Sekarang semua request akan include API key tersebut

### 3. Customize Request
Kamu bisa edit:
- **Headers**: Tambah custom headers
- **Query Parameters**: Edit parameter di URL
- **Request Body**: Edit JSON body untuk POST/PUT requests
- **Response**: Lihat status code, headers, dan body response

## Regenerate Documentation

Setiap kali kamu update anotasi Swagger di controller, jalankan:

```bash
php artisan l5-swagger:generate
```

## Contoh Penggunaan

### Test Check Location API
1. Buka http://localhost/api/documentation
2. Klik endpoint **POST /api/check-location**
3. Klik **"Try it out"**
4. Edit request body:
```json
{
  "email": "user@example.com"
}
```
5. Klik **"Execute"**
6. Lihat response

### Test Latest Version API
1. Klik endpoint **GET /api/latest-version**
2. Klik **"Try it out"**
3. Klik **"Execute"**
4. Lihat response dengan version info

## Tips

- Swagger UI menyimpan API key di browser, jadi kamu tidak perlu input ulang setiap kali
- Kamu bisa download OpenAPI spec dalam format JSON/YAML
- Gunakan "Models" section untuk melihat struktur data
- Response examples membantu memahami format data yang diharapkan

## Troubleshooting

### Dokumentasi tidak muncul
```bash
php artisan l5-swagger:generate
php artisan cache:clear
```

### Error 404
Pastikan route sudah terdaftar:
```bash
php artisan route:list | findstr swagger
```

### Update tidak muncul
Clear cache dan regenerate:
```bash
php artisan config:clear
php artisan l5-swagger:generate
```
