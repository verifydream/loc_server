# Requirements Document

## Introduction

Location Server adalah sistem manajemen lokasi API yang berfungsi sebagai routing layer untuk aplikasi mobile Flutter. Sistem ini akan menentukan URL API yang tepat berdasarkan email user saat login, sehingga satu aplikasi mobile dapat melayani semua cabang (Surabaya, Jakarta, Belawan, Semarang, BNS, Java, Test, Dev) tanpa perlu rebuild aplikasi setiap kali ada perubahan atau penambahan user baru.

Sistem ini terdiri dari:
- Backend API Laravel untuk lookup email dan URL mapping
- Dashboard web untuk manajemen user (email, lokasi/cabang, URL API)
- Database MySQL untuk menyimpan mapping data

## Requirements

### Requirement 1: User Location Lookup API

**User Story:** Sebagai aplikasi mobile Flutter, saya ingin dapat mengecek lokasi/URL API yang sesuai berdasarkan email user, sehingga user dapat diarahkan ke server yang tepat tanpa hardcode di aplikasi.

#### Acceptance Criteria

1. WHEN aplikasi mobile mengirim request POST ke `/api/check-location` dengan parameter email THEN sistem SHALL mengembalikan response JSON berisi online_url, location_name, dan status success
2. WHEN email yang dikirim tidak terdaftar di database THEN sistem SHALL mengembalikan response error dengan status 404 dan pesan "Email not found"
3. WHEN email yang dikirim terdaftar tapi statusnya inactive THEN sistem SHALL mengembalikan response error dengan status 403 dan pesan "User is inactive"
4. WHEN request tidak menyertakan parameter email THEN sistem SHALL mengembalikan response error dengan status 422 dan pesan validasi
5. IF email valid dan user active THEN sistem SHALL mengembalikan data: email, online_url, location_name, location_code dalam waktu < 500ms

### Requirement 2: User Management Dashboard

**User Story:** Sebagai administrator, saya ingin dapat mengelola daftar user dan mapping lokasi mereka melalui dashboard web, sehingga saya dapat menambah, mengubah, atau menonaktifkan user tanpa perlu rebuild aplikasi mobile.

#### Acceptance Criteria

1. WHEN administrator mengakses dashboard THEN sistem SHALL menampilkan halaman login dengan form email dan password
2. WHEN administrator login dengan kredensial valid THEN sistem SHALL memberikan akses ke dashboard utama
3. WHEN administrator berada di dashboard THEN sistem SHALL menampilkan tabel daftar user dengan kolom: email, location_name, online_url, status, dan action buttons
4. WHEN administrator klik tombol "Add User" THEN sistem SHALL menampilkan form untuk input: email, location/cabang (dropdown), online_url (auto-filled based on location), dan status (active/inactive)
5. WHEN administrator submit form add user dengan data valid THEN sistem SHALL menyimpan data ke database dan menampilkan notifikasi success
6. WHEN administrator klik tombol "Edit" pada user THEN sistem SHALL menampilkan form edit dengan data user yang sudah terisi
7. WHEN administrator update data user THEN sistem SHALL menyimpan perubahan dan menampilkan notifikasi success
8. WHEN administrator klik tombol "Delete" pada user THEN sistem SHALL menampilkan konfirmasi dan menghapus data jika dikonfirmasi
9. IF ada duplikasi email THEN sistem SHALL menampilkan error validasi "Email already exists"

### Requirement 3: Location/Branch Management

**User Story:** Sebagai administrator, saya ingin dapat mengelola daftar lokasi/cabang dan URL API mereka, sehingga ketika ada cabang baru atau perubahan URL, saya dapat mengupdate dengan mudah.

#### Acceptance Criteria

1. WHEN administrator mengakses menu "Locations" THEN sistem SHALL menampilkan tabel daftar lokasi dengan kolom: location_code, location_name, online_url, dan action buttons
2. WHEN administrator klik "Add Location" THEN sistem SHALL menampilkan form untuk input: location_code (e.g., "sby", "jkt"), location_name (e.g., "Surabaya", "Jakarta"), dan online_url (e.g., "sby.web.com")
3. WHEN administrator submit form add location dengan data valid THEN sistem SHALL menyimpan data dan menampilkan notifikasi success
4. WHEN administrator edit atau delete location THEN sistem SHALL melakukan operasi yang sesuai dengan validasi
5. IF location_code sudah ada THEN sistem SHALL menampilkan error "Location code already exists"
6. WHEN location digunakan oleh user THEN sistem SHALL mencegah penghapusan dan menampilkan pesan "Cannot delete location that is assigned to users"

### Requirement 4: Authentication & Authorization

**User Story:** Sebagai sistem, saya ingin memastikan hanya administrator yang terautentikasi dapat mengakses dashboard, sehingga data user dan lokasi terlindungi dari akses tidak sah.

#### Acceptance Criteria

1. WHEN user belum login dan mengakses halaman dashboard THEN sistem SHALL redirect ke halaman login
2. WHEN administrator login dengan kredensial valid THEN sistem SHALL membuat session dan menyimpan authentication token
3. WHEN administrator logout THEN sistem SHALL menghapus session dan redirect ke halaman login
4. IF session expired THEN sistem SHALL redirect ke halaman login dengan pesan "Session expired"
5. WHEN API `/api/check-location` dipanggil THEN sistem SHALL tidak memerlukan authentication (public endpoint)

### Requirement 5: Data Validation & Error Handling

**User Story:** Sebagai sistem, saya ingin memvalidasi semua input data dan memberikan error message yang jelas, sehingga data yang tersimpan selalu valid dan user mendapat feedback yang informatif.

#### Acceptance Criteria

1. WHEN form disubmit dengan field kosong yang required THEN sistem SHALL menampilkan error "Field [name] is required"
2. WHEN email format tidak valid THEN sistem SHALL menampilkan error "Invalid email format"
3. WHEN URL format tidak valid THEN sistem SHALL menampilkan error "Invalid URL format"
4. IF terjadi database error THEN sistem SHALL menampilkan error message generic dan log detail error ke file
5. WHEN API error terjadi THEN sistem SHALL mengembalikan response JSON dengan format: {success: false, message: "error message", errors: {}}

### Requirement 6: Search & Filter Functionality

**User Story:** Sebagai administrator, saya ingin dapat mencari dan memfilter user berdasarkan email atau lokasi, sehingga saya dapat menemukan data dengan cepat ketika ada banyak user.

#### Acceptance Criteria

1. WHEN administrator mengetik di search box THEN sistem SHALL memfilter tabel user secara real-time berdasarkan email
2. WHEN administrator memilih filter lokasi THEN sistem SHALL menampilkan hanya user dari lokasi tersebut
3. WHEN administrator memilih filter status THEN sistem SHALL menampilkan hanya user dengan status active atau inactive sesuai pilihan
4. IF tidak ada hasil pencarian THEN sistem SHALL menampilkan pesan "No users found"

### Requirement 7: Database Schema & Data Integrity

**User Story:** Sebagai sistem, saya ingin memiliki struktur database yang terorganisir dengan baik dan menjaga integritas data, sehingga data konsisten dan relasi antar tabel terjaga.

#### Acceptance Criteria

1. WHEN sistem diinstall THEN database SHALL memiliki tabel: users, locations, admins
2. WHEN data user disimpan THEN sistem SHALL memastikan foreign key location_id valid dan merujuk ke tabel locations
3. IF location dihapus yang masih digunakan THEN sistem SHALL mencegah penghapusan (foreign key constraint)
4. WHEN data disimpan THEN sistem SHALL menyimpan timestamp created_at dan updated_at secara otomatis
5. WHEN email disimpan THEN sistem SHALL menyimpan dalam format lowercase untuk konsistensi

### Requirement 8: Initial Data Seeding

**User Story:** Sebagai developer, saya ingin sistem memiliki data awal untuk lokasi dan admin, sehingga setelah instalasi sistem langsung dapat digunakan tanpa setup manual.

#### Acceptance Criteria

1. WHEN migration dijalankan THEN sistem SHALL membuat seeder untuk 8 lokasi: Surabaya (sby.web.com), Jakarta (jkt.web.com), Belawan (blw.web.com), Semarang (smr.web.com), BNS (bns.web.com), Java (java.web.com), Test (test.web.com), Dev (dev.web.com)
2. WHEN seeder dijalankan THEN sistem SHALL membuat admin default dengan email dan password yang sudah ditentukan
3. IF seeder dijalankan ulang THEN sistem SHALL tidak membuat duplikasi data (check existing data first)
