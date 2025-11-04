# Requirements Document

## Introduction

Sistem manajemen versi APK adalah fitur yang memungkinkan administrator untuk mengelola versi aplikasi Flutter melalui dashboard admin, dan menyediakan API endpoint yang aman untuk aplikasi Flutter mengecek dan mengunduh versi terbaru. Sistem ini menggunakan autentikasi API Key untuk melindungi endpoint publik dan memanfaatkan struktur admin yang sudah ada di proyek Laravel.

## Glossary

- **Admin Dashboard**: Interface web yang dilindungi middleware admin untuk mengelola data aplikasi
- **AppVersion Model**: Model Eloquent yang merepresentasikan satu versi aplikasi APK
- **Flutter App**: Aplikasi mobile client yang mengkonsumsi API untuk update
- **API Key Middleware**: Middleware yang memvalidasi X-Api-Key header untuk autentikasi API
- **Version Controller**: Controller API yang menangani request dari Flutter App
- **AppVersion Controller**: Controller web yang menangani CRUD operations di Admin Dashboard
- **Storage System**: Laravel storage untuk menyimpan file APK

## Requirements

### Requirement 1

**User Story:** Sebagai administrator, saya ingin menyimpan informasi versi APK di database, sehingga sistem dapat melacak semua versi yang pernah dirilis

#### Acceptance Criteria

1. THE AppVersion Model SHALL memiliki atribut version_name, version_code, file_path, dan release_notes yang dapat diisi secara massal
2. THE Storage System SHALL menyimpan file APK di direktori public/updates
3. WHEN administrator menghapus versi APK, THEN THE Storage System SHALL menghapus file fisik dari storage
4. THE AppVersion Model SHALL memiliki kolom version_code yang bersifat unik
5. THE AppVersion Model SHALL menyimpan timestamps untuk created_at dan updated_at

### Requirement 2

**User Story:** Sebagai administrator, saya ingin mengupload versi APK baru melalui dashboard admin, sehingga Flutter App dapat mengunduh versi terbaru

#### Acceptance Criteria

1. WHEN administrator mengakses halaman upload, THEN THE Admin Dashboard SHALL menampilkan form dengan field version_name, version_code, release_notes, dan apk_file
2. WHEN administrator submit form upload, THEN THE AppVersion Controller SHALL memvalidasi bahwa version_code adalah integer unik
3. WHEN administrator submit form upload, THEN THE AppVersion Controller SHALL memvalidasi bahwa apk_file adalah file dengan mime type apk
4. WHEN file APK berhasil diupload, THEN THE AppVersion Controller SHALL menyimpan file ke storage dengan path yang tercatat di database
5. WHEN upload berhasil, THEN THE AppVersion Controller SHALL redirect ke halaman index dengan pesan sukses

### Requirement 3

**User Story:** Sebagai administrator, saya ingin melihat daftar semua versi APK yang telah diupload, sehingga saya dapat mengelola versi-versi tersebut

#### Acceptance Criteria

1. THE Admin Dashboard SHALL menampilkan tabel berisi version_name, version_code, dan created_at untuk setiap versi
2. THE Admin Dashboard SHALL menampilkan versi terbaru terlebih dahulu dengan pagination 10 item per halaman
3. THE Admin Dashboard SHALL menyediakan tombol "Upload Versi Baru" yang mengarah ke halaman create
4. THE Admin Dashboard SHALL menyediakan tombol "Hapus" untuk setiap versi di tabel
5. THE Admin Dashboard SHALL dapat diakses melalui menu sidebar dengan label "App Updates" atau "Manajemen Versi"

### Requirement 4

**User Story:** Sebagai administrator, saya ingin menghapus versi APK yang sudah tidak diperlukan, sehingga storage tidak penuh dengan file lama

#### Acceptance Criteria

1. WHEN administrator klik tombol hapus, THEN THE AppVersion Controller SHALL menghapus file APK dari storage
2. WHEN file berhasil dihapus dari storage, THEN THE AppVersion Controller SHALL menghapus record dari database
3. WHEN penghapusan berhasil, THEN THE AppVersion Controller SHALL redirect ke halaman index dengan pesan sukses
4. THE AppVersion Controller SHALL menangani kasus dimana file tidak ditemukan di storage tanpa error fatal

### Requirement 5

**User Story:** Sebagai Flutter App, saya ingin mengecek versi terbaru yang tersedia melalui API, sehingga saya dapat memberitahu user untuk update

#### Acceptance Criteria

1. WHEN Flutter App mengirim GET request ke /api/latest-version dengan valid API Key, THEN THE Version Controller SHALL mengembalikan data versi terbaru dengan status 200
2. WHEN tidak ada versi yang tersedia di database, THEN THE Version Controller SHALL mengembalikan error JSON dengan status 404
3. THE Version Controller SHALL mengembalikan response JSON dengan format yang berisi version_name, version_code, release_notes, dan download_url
4. THE Version Controller SHALL menghasilkan download_url menggunakan Storage::url() untuk file_path yang tersimpan
5. THE Version Controller SHALL menentukan versi terbaru berdasarkan created_at timestamp

### Requirement 6

**User Story:** Sebagai sistem, saya ingin melindungi API endpoint dengan API Key, sehingga hanya Flutter App yang authorized yang dapat mengakses

#### Acceptance Criteria

1. WHEN request tidak memiliki header X-Api-Key, THEN THE API Key Middleware SHALL mengembalikan response JSON dengan message "Unauthorized" dan status 401
2. WHEN request memiliki header X-Api-Key yang tidak valid, THEN THE API Key Middleware SHALL mengembalikan response JSON dengan message "Unauthorized" dan status 401
3. WHEN request memiliki header X-Api-Key yang valid, THEN THE API Key Middleware SHALL mengizinkan request untuk dilanjutkan
4. THE API Key Middleware SHALL memvalidasi X-Api-Key terhadap nilai FLUTTER_API_KEY dari environment configuration
5. THE API Key Middleware SHALL terdaftar di route middleware dengan key 'auth.apikey'

### Requirement 7

**User Story:** Sebagai developer, saya ingin endpoint /check-location yang sudah ada juga terproteksi dengan API Key, sehingga konsisten dengan security policy

#### Acceptance Criteria

1. THE API routing configuration SHALL mengelompokkan /latest-version dan /check-location dalam satu middleware group
2. THE API routing configuration SHALL menerapkan middleware auth.apikey pada group tersebut
3. WHEN Flutter App mengakses /check-location tanpa valid API Key, THEN THE API Key Middleware SHALL menolak request dengan status 401

### Requirement 8

**User Story:** Sebagai developer, saya ingin konfigurasi API Key tersimpan di environment file, sehingga mudah diubah tanpa mengubah code

#### Acceptance Criteria

1. THE environment configuration SHALL memiliki variable FLUTTER_API_KEY di file .env
2. THE environment configuration SHALL memiliki contoh FLUTTER_API_KEY di file .env.example
3. THE API Key Middleware SHALL membaca nilai API Key dari env('FLUTTER_API_KEY')
