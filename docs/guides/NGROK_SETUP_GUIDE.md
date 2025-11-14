# Ngrok Setup Guide - Location Server

## Langkah-langkah Setup Ngrok

### 1. Download & Install Ngrok

1. **Download ngrok:**
   - Buka: https://ngrok.com/download
   - Pilih versi Windows
   - Download file ZIP

2. **Extract ngrok:**
   - Extract file `ngrok.exe` ke folder yang mudah diakses
   - Contoh: `C:\ngrok\ngrok.exe`
   - Atau bisa taruh di folder project kamu

### 2. Sign Up (Optional tapi Recommended)

1. **Buat akun gratis:**
   - https://dashboard.ngrok.com/signup
   - Sign up dengan Google/GitHub atau email

2. **Get authtoken:**
   - Setelah login, copy authtoken dari dashboard
   - Jalankan command ini (sekali aja):
   ```bash
   ngrok config add-authtoken YOUR_AUTHTOKEN_HERE
   ```

**Keuntungan sign up:**
- Session lebih lama (8 jam vs 2 jam)
- Bisa lihat request logs di dashboard
- Lebih stable

### 3. Jalankan Laravel Server

Pastikan Laravel server sudah running:

```bash
php artisan serve
```

Output:
```
INFO  Server running on [http://127.0.0.1:8000]
```

**Jangan close terminal ini!**

### 4. Jalankan Ngrok

**Buka terminal/CMD baru**, lalu jalankan:

```bash
ngrok http 8000
```

Atau jika ngrok ada di folder tertentu:
```bash
C:\ngrok\ngrok.exe http 8000
```

### 5. Copy Public URL

Setelah ngrok running, kamu akan lihat tampilan seperti ini:

```
ngrok

Session Status                online
Account                       your-email@example.com (Plan: Free)
Version                       3.x.x
Region                        Asia Pacific (ap)
Latency                       -
Web Interface                 http://127.0.0.1:4040
Forwarding                    https://abc123def456.ngrok-free.app -> http://localhost:8000

Connections                   ttl     opn     rt1     rt5     p50     p90
                              0       0       0.00    0.00    0.00    0.00
```

**Copy URL yang ada di "Forwarding":**
```
https://abc123def456.ngrok-free.app
```

### 6. Update Laravel Config (Optional)

Edit file `.env`:

```env
APP_URL=https://abc123def456.ngrok-free.app
```

Lalu clear config:
```bash
php artisan config:clear
```

### 7. Test API

**Test di browser:**
```
https://abc123def456.ngrok-free.app/api/check-location?email=admin@example.com
```

**Test di Postman:**
```
POST https://abc123def456.ngrok-free.app/api/check-location
Body: {"email": "admin@example.com"}
```

### 8. Share ke Flutter Developer

Berikan informasi ini ke Flutter dev:

```
Base URL: https://abc123def456.ngrok-free.app/api

Endpoint: POST /check-location
Body: {"email": "user@example.com"}
```

---

## Tips & Tricks

### Lihat Request Logs

Buka di browser:
```
http://localhost:4040
```

Di sini kamu bisa lihat semua request yang masuk, response-nya, dll. Sangat berguna untuk debugging!

### Keep Ngrok Running

- **Jangan close terminal ngrok!**
- Kalau di-close, URL akan berubah
- Free tier: URL berubah setiap restart ngrok

### Multiple Sessions

Kalau mau jalankan beberapa service sekaligus:

```bash
# Terminal 1: Laravel
php artisan serve

# Terminal 2: Ngrok untuk Laravel
ngrok http 8000

# Terminal 3: Vite (jika perlu)
npm run dev

# Terminal 4: Ngrok untuk Vite (jika perlu)
ngrok http 5173
```

### Custom Subdomain (Paid Feature)

Kalau upgrade ke paid plan, bisa pakai custom subdomain:

```bash
ngrok http 8000 --subdomain=location-server
```

URL jadi: `https://location-server.ngrok.io`

---

## Troubleshooting

### Error: "command not found"

**Solusi:**
- Pastikan ngrok.exe sudah di-extract
- Jalankan dengan full path: `C:\ngrok\ngrok.exe http 8000`
- Atau tambahkan ngrok ke PATH environment variable

### Error: "Failed to listen on port 8000"

**Solusi:**
- Pastikan Laravel server sudah running di port 8000
- Cek dengan: `php artisan serve`

### URL Berubah Terus

**Solusi:**
- Sign up dan add authtoken untuk session lebih lama
- Atau upgrade ke paid plan untuk permanent URL

### CORS Error dari Flutter

**Solusi:**
Edit `config/cors.php`:

```php
'allowed_origins' => ['*'],
'allowed_origins_patterns' => ['*.ngrok-free.app', '*.ngrok.io'],
```

Atau install package:
```bash
composer require fruitcake/laravel-cors
```

### Ngrok Warning Page

Ngrok free tier kadang tampilkan warning page sebelum redirect. Flutter dev tinggal klik "Visit Site" sekali aja.

---

## Alternatif Commands

### Jalankan di region tertentu:
```bash
ngrok http 8000 --region=ap  # Asia Pacific
ngrok http 8000 --region=us  # United States
ngrok http 8000 --region=eu  # Europe
```

### Dengan custom hostname (paid):
```bash
ngrok http 8000 --hostname=myapp.ngrok.io
```

### Dengan basic auth (paid):
```bash
ngrok http 8000 --basic-auth="username:password"
```

---

## Monitoring

### Ngrok Dashboard
- Login ke: https://dashboard.ngrok.com
- Lihat active tunnels, request logs, dll

### Local Web Interface
- Buka: http://localhost:4040
- Lihat real-time requests & responses
- Replay requests untuk testing

---

## Setelah Testing Selesai

1. **Stop ngrok:** Tekan `Ctrl+C` di terminal ngrok
2. **Stop Laravel:** Tekan `Ctrl+C` di terminal Laravel
3. **URL akan expired** dan tidak bisa diakses lagi

Kalau mau testing lagi, tinggal jalankan ulang ngrok (URL akan berubah).

---

## Next Steps

Setelah testing selesai dan API sudah OK:
1. Deploy ke hosting (VPS/Cloud)
2. Setup domain & SSL
3. Update base URL di Flutter app
4. Ready for production! 🚀
