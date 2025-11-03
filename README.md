# Location Server

Location Server adalah sebuah proyek aplikasi backend yang dibangun menggunakan Laravel 10. Aplikasi ini menyediakan API endpoint untuk memeriksa lokasi pengguna berdasarkan email, serta panel admin untuk mengelola data pengguna dan lokasi.

## Fitur Utama

Berdasarkan file-file proyek, berikut adalah fitur-fitur utamanya:

* **API Pengecekan Lokasi**: Menyediakan endpoint API (`/api/check-location`) yang menerima parameter `email` untuk memvalidasi dan mengembalikan data lokasi pengguna.
* **Perlindungan API**: Endpoint API dilindungi dengan *throttling* (pembatasan permintaan) 60 permintaan per menit dan logging kustom (`log.api`).
* **Panel Admin**:
    * Sistem autentikasi terpisah untuk admin (`/admin/login`, `/admin/logout`).
    * Dashboard admin yang dilindungi (`/admin/dashboard`).
    * Manajemen (CRUD) untuk Pengguna (`admin/users`).
    * Manajemen (CRUD) untuk Lokasi (`admin/locations`).
* **Layanan (Services)**: Menggunakan pola *Service Pattern* untuk memisahkan logika bisnis (misalnya `LocationService` yang digunakan oleh `LocationController`).

---

## Teknologi yang Digunakan

Proyek ini dibangun menggunakan tumpukan teknologi berikut, berdasarkan `composer.json`:

* **PHP 8.1+**
* **Laravel Framework 10.0+**
* **Laravel Sanctum** (untuk autentikasi API)
* **Guzzle HTTP Client** (untuk membuat permintaan HTTP)
* **PHPUnit** (untuk pengujian)
* **MySQL 8.0+** (direkomendasikan di `SETUP.md`)

---

## Prasyarat dan Instalasi

Berikut adalah prasyarat dan langkah-langkah untuk menjalankan proyek ini secara lokal, berdasarkan `SETUP.md`.

### Prasyarat

* PHP 8.1 atau lebih tinggi
* Composer
* MySQL 8.0 atau lebih tinggi
* Node.js dan NPM

### Langkah-langkah Instalasi

1.  **Clone repositori:**
    ```bash
    git clone [https://github.com/USERNAME/REPO_NAME.git](https://github.com/USERNAME/REPO_NAME.git)
    cd REPO_NAME
    ```

2.  **Install dependensi PHP:**
    ```bash
    composer install
    ```

3.  **Konfigurasi Lingkungan (.env):**
    Proyek ini memiliki skrip untuk menyalin `.env.example` ke `.env` secara otomatis. Pastikan Anda membuat database MySQL terlebih dahulu.
    ```sql
    CREATE DATABASE location_server CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
    ```
    Kemudian, perbarui file `.env` Anda dengan kredensial database yang benar. Pengaturan default yang ada di `SETUP.md` adalah:
    ```
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=location_server
    DB_USERNAME=root
    DB_PASSWORD=
    ```
    *Catatan: Sesuaikan `DB_PASSWORD` jika root MySQL Anda memiliki password.*

4.  **Hasilkan Kunci Aplikasi:**
    ```bash
    php artisan key:generate
    ```

5.  **Jalankan Migrasi Database:**
    Perintah ini akan membuat semua tabel yang diperlukan di database Anda.
    ```bash
    php artisan migrate
    ```

6.  **Jalankan Seeder (Opsional tapi Direkomendasikan):**
    Perintah ini akan mengisi database dengan data awal (misalnya, data admin, pengguna, dan lokasi).
    ```bash
    php artisan db:seed
    ```

7.  **Install Dependensi Frontend:**
    ```bash
    npm install
    npm run dev
    ```

8.  **Jalankan Server Pengembangan:**
    ```bash
    php artisan serve
    ```
    Aplikasi sekarang akan berjalan di `http://127.0.0.1:8000`.

---

## Susunan Project

Berikut adalah gambaran singkat tentang struktur file dan direktori penting dalam proyek Laravel ini:

* `app/Http/Controllers/Api/LocationController.php`: Mengendalikan logika untuk endpoint API `/api/check-location`.
* `app/Http/Controllers/AdminAuthController.php`: Mengelola logika login dan logout untuk admin.
* `app/Http/Controllers/UserController.php`: Mengelola CRUD untuk pengguna di panel admin.
* `app/Http/Controllers/LocationManagementController.php`: Mengelola CRUD untuk lokasi di panel admin.
* `app/Services/LocationService.php`: Berisi logika bisnis inti untuk memeriksa lokasi pengguna.
* `app/Models/`: Berisi model Eloquent (misalnya `User.php`, `Location.php`, `Admin.php`).
* `routes/api.php`: Mendefinisikan semua rute yang diawali dengan `/api`, termasuk `/check-location`.
* `routes/web.php`: Mendefinisikan rute untuk aplikasi web, termasuk panel admin (`/admin/*`).
* `database/migrations/`: Berisi file-file skema database.
* `database/seeders/`: Berisi file-file untuk mengisi data awal database.
* `SETUP.md`: Catatan panduan setup yang spesifik untuk proyek ini.
* `composer.json`: Mendefinisikan dependensi PHP proyek.

---

## Contoh Penggunaan

### 1. API (Check Location)

Anda dapat menguji endpoint API menggunakan `curl` atau alat seperti Postman. Endpoint ini menerima metode `GET` atau `POST`.

**Permintaan (Request):**
```bash
# Menggunakan GET
curl "[http://127.0.0.1:8000/api/check-location?email=user@example.com](http://127.0.0.1:8000/api/check-location?email=user@example.com)"

# Menggunakan POST
curl -X POST [http://127.0.0.1:8000/api/check-location](http://127.0.0.1:8000/api/check-location) \
     -H "Content-Type: application/json" \
     -d '{"email": "user@example.com"}'
````

**Respon Sukses (Success Response):**

```json
{
    "success": true,
    "data": {
        "user_email": "user@example.com",
        "location_name": "Nama Lokasi",
        "status": "allowed"
    }
}
```

**Respon Gagal (Error Response - Validasi):**

```json
{
    "success": false,
    "message": "Validation error",
    "errors": {
        "email": [
            "The email field is required."
        ]
    }
}
```

### 2\. Panel Admin

1.  Akses halaman login admin di browser Anda: `http://127.0.0.1:8000/admin/login`.
2.  Gunakan kredensial admin (yang mungkin telah dibuat melalui *seeder*) untuk masuk.
3.  Setelah berhasil login, Anda akan diarahkan ke dashboard (`/admin/dashboard`) di mana Anda dapat mengelola pengguna dan lokasi.

-----

## Kontribusi

Kami menyambut baik kontribusi\! Jika Anda ingin berkontribusi pada proyek ini, silakan ikuti langkah-langkah berikut:

1.  **Fork** repositori ini.
2.  Buat *branch* fitur baru (`git checkout -b fitur/nama-fitur`).
3.  *Commit* perubahan Anda (`git commit -m 'Menambahkan fitur A'`).
4.  *Push* ke *branch* Anda (`git push origin fitur/nama-fitur`).
5.  Buka **Pull Request**.

-----

## Lisensi

Proyek ini dilisensikan di bawah **Lisensi MIT**. Lihat file `LICENSE` untuk detail lebih lanjut.

```
```
