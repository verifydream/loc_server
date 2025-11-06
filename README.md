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
    * **Upload Progress Bar:** Real-time progress tracking saat upload APK dengan informasi kecepatan dan ETA.
* **API Resolver Lokasi:** Satu API publik (`/api/check-location`) untuk memvalidasi email dan mengembalikan URL API yang benar.
* **API In-App Update:** Satu API aman (`/api/latest-version`) yang mengembalikan data versi `.apk` terbaru dan URL unduhan.
* **Keamanan API:** Endpoint *In-App Update* dilindungi menggunakan *middleware* `X-Api-Key` kustom.

## ⚙️ Instalasi & Setup (Lokal)

1.  **Clone Repository**
    ```bash
    git clone [URL_REPO_ANDA]
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

6.  **Buat Storage Link (SANGAT PENTING)**
    Ini wajib agar file `.apk` yang di-upload bisa di-download.
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
            "version_name": "1.2.0",
            "version_code": 3,
            "release_notes": "Perbaikan bug di halaman survey.\nPenambahan fitur foto crani.",
            "download_url": "[https://locstorage.com/storage/updates/file_apk_acak.apk](https://locstorage.com/storage/updates/file_apk_acak.apk)"
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

## 🚀 Catatan Deployment (Hostinger)

1.  **Struktur Folder:** Karena *shared hosting* (Hostinger) mengarahkan domain ke *root* (bukan folder `/public`), pastikan Anda memindahkan `index.php`, `.htaccess`, dll. ke *root* dan sesuaikan *path* di `index.php` (hapus `../`).
2.  **Storage Link:** Setelah deploy, Anda **wajib** menjalankan `php artisan storage:link` melalui Terminal SSH di Hostinger agar URL unduhan `.apk` berfungsi.
3.  **File Permissions:** Pastikan folder `storage` *writable* oleh server.
