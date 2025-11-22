# 📍 Location Resolver & App Update Server (`loc_server`)

[![PHP](https://img.shields.io/badge/PHP-8.2%2B-777BB4?style=for-the-badge&logo=php)](https://php.net)
[![Laravel](https://img.shields.io/badge/Laravel-10.x-FF2D20?style=for-the-badge&logo=laravel)](https://laravel.com)
[![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?style=for-the-badge&logo=mysql)](https://mysql.com)

Proyek backend Laravel ini berfungsi sebagai *server* pendukung untuk aplikasi mobile Flutter internal. Proyek ini memiliki dua tanggung jawab utama:
1.  **Resolver Lokasi:** Memberi tahu aplikasi Flutter URL API cabang (tenant) yang benar berdasarkan email user.
2.  **Server In-App Update:** Menyediakan API untuk *auto-update* aplikasi Android (`.apk`) tanpa melalui Google Play Store.

## ✨ Fitur Utama

* **Admin Dashboard:** Antarmuka web untuk me-manage:
    * **Manajemen Lokasi:** CRUD untuk semua cabang perusahaan (Surabaya, Jakarta, Belawan, dll) beserta URL API unik mereka.
    * **Manajemen User:** CRUD untuk user aplikasi (tim Survey & Crani), menautkan mereka ke lokasi cabang yang sesuai.
    * **Manajemen Versi Aplikasi:** Halaman untuk meng-upload file `.apk` baru, mencatat *version code*, dan *release notes*.
* **API Resolver Lokasi:** Satu API publik (`/api/check-location`) untuk memvalidasi email dan mengembalikan URL API yang benar.
* **API In-App Update:** Satu API aman (`/api/latest-version`) yang mengembalikan data versi `.apk` terbaru dan URL unduhan yang dinamis.
* **Keamanan API:** Endpoint *In-App Update* dilindungi menggunakan *middleware* `X-Api-Key` kustom.

## ⚙️ Instalasi & Setup (Lokal)

1.  **Clone Repository**
    ```bash
    git clone [https://github.com/verifydream/loc_server.git](https://github.com/verifydream/loc_server.git)
    cd loc_server
    ```

2.  **Install Dependensi**
    ```bash
    composer install
    npm install
    npm run build
    ```

3.  **Konfigurasi Environment**
    ```bash
    cp .env.example .env
    ```
    Buka file `.env` dan atur koneksi database Anda (`DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`).

4.  **Atur Kunci Rahasia**
    Di file `.env`, atur juga variabel kustom ini untuk API Key:
    ```env
    FLUTTER_API_KEY="kunci_rahasia_super_aman_anda"
    ```

5.  **Generate Kunci & Migrasi**
    ```bash
    php artisan key:generate
    php artisan migrate --seed
    ```
    *(Perintah `--seed` akan membuat Admin default, data Lokasi, dan User contoh).*

6.  **Buat Storage Link**
    Meskipun *download* APK utama menggunakan *route* dinamis, `storage:link` tetap penting untuk file lain (jika ada).
    ```bash
    php artisan storage:link
    ```

7.  **Jalankan Server**
    ```bash
    php artisan serve
    ```

## 🗃️ Struktur Database

* `admins`: Menyimpan data login untuk admin *dashboard* (username, password).
* `locations`: Menyimpan daftar semua cabang/lokasi (nama lokasi, URL API).
* `users`: Menyimpan data user aplikasi (email, nama) dan terhubung ke `locations` via `location_id`.
* `app_versions`: Menyimpan riwayat versi aplikasi `.apk` (version code, version name, path file, release notes).

## 🖥️ Admin Dashboard

* **URL:** `/admin/login`
* **Admin Default:**
    * **Email:** `admin@example.com`
    * **Password:** `password`
    *(Lihat `database/seeders/AdminSeeder.php` untuk detail)*

Dari *dashboard* ini, Anda bisa mengelola semua data user, lokasi, dan meng-upload versi `.apk` baru melalui menu **"App Updates"**.

## 📚 API Documentation (Swagger)

Proyek ini sudah dilengkapi dengan **Swagger UI** untuk dokumentasi API interaktif!

* **URL Swagger:** `/api/documentation`
* **Contoh:** `http://localhost/api/documentation` atau `https://your-domain.com/api/documentation`

### Fitur Swagger UI:
- ✅ **Testing API langsung** dari browser tanpa Postman
- ✅ **Dokumentasi lengkap** untuk semua endpoints
- ✅ **Try it out** - Test API dengan custom parameters dan body
- ✅ **Authentication** - Input API Key sekali untuk semua requests
- ✅ **Response examples** - Lihat contoh response untuk setiap endpoint

### Cara Menggunakan:
1. Buka `http://your-domain.com/api/documentation`
2. Klik tombol **"Authorize"** dan masukkan API Key kamu
3. Pilih endpoint yang ingin di-test
4. Klik **"Try it out"**, edit parameters/body
5. Klik **"Execute"** dan lihat response

📖 **Dokumentasi lengkap:** Lihat [docs/API_DOCUMENTATION.md](docs/API_DOCUMENTATION.md) dan [docs/SWAGGER_GUIDE.md](docs/SWAGGER_GUIDE.md)

---

## 📡 Spesifikasi API (untuk Klien Flutter)

### 1. Endpoint: Check Lokasi (Publik)

Memberi tahu URL API yang benar berdasarkan email user.

* **URL:** `POST /api/check-location`
* **Body (JSON):**
    ```json
    {
        "email": "surveyor.jkt@perusahaan.com"
    }
    ```
* **Respons Sukses (200 OK):**
    ```json
    {
        "status": "success",
        "location": {
            "id": 1,
            "location_name": "Jakarta",
            "online_url": "[https://jkt.web.com](https://jkt.web.com)",
            "created_at": "...",
            "updated_at": "..."
        }
    }
    ```
* **Respons Gagal (404 Not Found):**
    ```json
    {
        "status": "error",
        "message": "User not found or location not set"
    }
    ```

---

### 2. Endpoint: Check Versi Terbaru (Aman)

Mendapatkan informasi versi `.apk` terbaru untuk *in-app update*.

* **URL:** `GET /api/latest-version`
* **Header Wajib:**
    | Key | Value |
    | :--- | :--- |
    | `X-Api-Key` | `nilai_dari_FLUTTER_API_KEY_anda` |
* **Respons Sukses (200 OK):**
    ```json
    {
        "status": "success",
        "data": {
            "id": 5,
            "version_name": "1.0.2",
            "version_code": 3,
            "release_notes": "Perbaikan bug minor.",
            "file_name": "app-v1.0.2.apk",
            "download_url": "[https://depoverse.ppzaidbintsabit.com/download/apk/5](https://depoverse.ppzaidbintsabit.com/download/apk/5)"
        }
    }
    ```
* **Respons Gagal (401 Unauthorized):** (API Key salah/tidak ada)
    ```json
    {
        "message": "Unauthorized"
    }
    ```
* **Respons Gagal (404 Not Found):** (Belum ada versi yang di-upload)
    ```json
    {
        "status": "error",
        "message": "Belum ada versi aplikasi yang tersedia."
    }
    ```

---

### 3. Endpoint: Download APK (Publik)

*Route* publik yang ditunjuk oleh `download_url` dari API di atas. *Route* ini akan memicu *download* file `.apk` secara paksa.

* **URL:** `GET /download/apk/{id}`
* **Contoh Panggilan:** `httpsE://depoverse.ppzaidbintsabit.com/download/apk/5`
* **Respons Sukses:** Server akan mengirimkan file `app-v1.0.2.apk` (atau nama file yang sesuai) sebagai *attachment* untuk di-download.

## 🚀 Catatan Deployment (Hostinger)

1.  **Struktur Folder:** Karena *shared hosting* (Hostinger) mengarahkan domain ke *root* (bukan folder `/public`), pastikan Anda memindahkan `index.php`, `.htaccess`, dll. ke *root* dan sesuaikan *path* di `index.php` (hapus `../`).
2.  **File Permissions:** Pastikan folder `storage` *writable* oleh server agar *upload* `.apk` berhasil.
