# 🚀 Quick Fix: CORS Error di Swagger

## Error yang Kamu Alami

```
Access to fetch at 'http://localhost/api/...' from origin 'http://127.0.0.1:8000' 
has been blocked by CORS policy
```

## Solusi Cepat (2 Langkah)

### 1️⃣ Pilih Server yang Benar di Swagger UI

Ketika kamu buka Swagger UI, di bagian atas ada dropdown **"Servers"**.

**Jika kamu akses Swagger via:**
- `http://localhost:8000/api/documentation` → Pilih server: **"http://localhost"**
- `http://127.0.0.1:8000/api/documentation` → Pilih server: **"http://127.0.0.1:8000"**

### 2️⃣ Gunakan URL yang Konsisten

**Cara Terbaik:**
Akses Swagger dan API dengan URL yang sama:

```
# Option 1: Gunakan localhost
http://localhost:8000/api/documentation

# Option 2: Gunakan 127.0.0.1
http://127.0.0.1:8000/api/documentation
```

## Testing

1. Refresh browser (atau buka Incognito)
2. Akses Swagger UI
3. Pilih server yang sesuai di dropdown
4. Klik endpoint → "Try it out" → "Execute"
5. ✅ Response harus muncul tanpa error!

## Masih Error?

Lihat panduan lengkap: [docs/SWAGGER_CORS_FIX.md](docs/SWAGGER_CORS_FIX.md)

## Alternative: Gunakan Postman

File Postman collection sudah tersedia:
- **File:** `Location Server API.postman_collection.json`
- **Import ke Postman:** Import → Upload Files → Select file
- **Update variables:**
  - `base_url`: `http://localhost:8000`
  - `api_key`: API key kamu dari .env

Postman tidak terpengaruh CORS, jadi bisa digunakan untuk testing tanpa masalah!

---

**Quick Commands:**
```bash
# Regenerate Swagger docs
php artisan l5-swagger:generate

# Clear cache
php artisan config:clear
php artisan cache:clear
```
