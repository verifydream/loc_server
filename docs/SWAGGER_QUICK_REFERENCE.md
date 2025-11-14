# 📋 Swagger Quick Reference Card

## 🚀 Quick Access

```
Swagger UI: http://localhost:8000/api/documentation
```

## 🔑 Setup API Key

1. Klik **"Authorize"** 🔒
2. Input API Key dari `.env` → `FLUTTER_API_KEY`
3. Klik **"Authorize"** → **"Close"**

## 🧪 Test Endpoint (3 Steps)

1. **Expand** endpoint yang ingin di-test
2. **"Try it out"** → Edit params/body
3. **"Execute"** → Lihat response

## ⚠️ CORS Error Fix

**Error:** `blocked by CORS policy`

**Fix:** Pilih server yang sesuai di dropdown Swagger UI
- Akses via `127.0.0.1:8000` → Pilih `http://127.0.0.1:8000`
- Akses via `localhost:8000` → Pilih `http://localhost`

**Detail:** `SWAGGER_CORS_QUICK_FIX.md`

## 🔄 Update Documentation

```bash
# Setelah edit anotasi di controller
php artisan l5-swagger:generate

# Clear cache
php artisan config:clear
php artisan cache:clear
```

## 📦 Postman Collection

**File:** `Location Server API.postman_collection.json`

**Import:**
1. Postman → Import → Upload Files
2. Update variables: `base_url` dan `api_key`

## 📚 Documentation Files

| File | Purpose |
|------|---------|
| `SWAGGER_QUICKSTART.md` | Panduan cepat 5 langkah |
| `SWAGGER_CORS_QUICK_FIX.md` | Fix CORS error |
| `docs/SWAGGER_GUIDE.md` | Panduan lengkap |
| `docs/SWAGGER_COMMANDS.md` | Command reference |
| `docs/SWAGGER_CUSTOMIZATION.md` | Cara customize |
| `docs/SWAGGER_CORS_FIX.md` | CORS fix detail |
| `docs/SWAGGER_INDEX.md` | Index semua docs |

## 🎯 Endpoints

### Check Location
- **POST** `/api/check-location` - Check by email (JSON body)
- **GET** `/api/check-location?email=...` - Check by email (query)

### App Version
- **GET** `/api/latest-version` - Get latest APK version

## 🛠️ Common Commands

```bash
# Generate docs
php artisan l5-swagger:generate

# View routes
php artisan route:list --path=api

# Clear cache
php artisan config:clear && php artisan cache:clear

# Check generated files
dir storage\api-docs
```

## 💡 Tips

- ✅ Gunakan URL konsisten (localhost atau 127.0.0.1)
- ✅ Pilih server yang sesuai di Swagger UI
- ✅ API key disimpan di browser
- ✅ Copy curl command untuk testing di terminal
- ✅ Gunakan Postman jika ada CORS issue

## 🆘 Troubleshooting

| Problem | Solution |
|---------|----------|
| CORS error | Pilih server yang sesuai atau lihat `SWAGGER_CORS_QUICK_FIX.md` |
| Docs tidak update | `php artisan l5-swagger:generate` |
| 404 error | `php artisan config:clear` |
| Changes tidak muncul | Clear browser cache atau Incognito |

## 📞 Need Help?

1. **CORS Error:** `SWAGGER_CORS_QUICK_FIX.md`
2. **General Help:** `docs/SWAGGER_GUIDE.md`
3. **Commands:** `docs/SWAGGER_COMMANDS.md`
4. **All Docs:** `docs/SWAGGER_INDEX.md`

---

**Print this card and keep it handy!** 📌
