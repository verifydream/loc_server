# Implementation Plan

- [x] 1. Setup database dan model





  - Buat migration file untuk tabel app_versions dengan kolom id, version_name, version_code (unique), file_path, release_notes (nullable), dan timestamps
  - Buat Model AppVersion di app/Models/AppVersion.php dengan fillable attributes: version_name, version_code, file_path, release_notes
  - Tambahkan cast untuk version_code sebagai integer di Model
  - _Requirements: 1.1, 1.2, 1.4, 1.5_

- [x] 2. Implementasi API Key Middleware





  - Buat middleware EnsureApiKeyIsValid di app/Http/Middleware/EnsureApiKeyIsValid.php
  - Implementasi logic untuk mengecek header X-Api-Key terhadap env('FLUTTER_API_KEY')
  - Return JSON response {"message": "Unauthorized"} dengan status 401 jika key tidak valid atau tidak ada
  - Daftarkan middleware di app/Http/Kernel.php dengan alias 'auth.apikey'
  - _Requirements: 6.1, 6.2, 6.3, 6.4, 6.5_

- [x] 3. Setup environment configuration





  - Tambahkan FLUTTER_API_KEY ke file .env.example dengan placeholder value
  - Tambahkan FLUTTER_API_KEY ke file .env dengan secure random key
  - _Requirements: 8.1, 8.2, 8.3_

- [x] 4. Implementasi API endpoint untuk Flutter





  - Buat controller Api\VersionController di app/Http/Controllers/Api/VersionController.php
  - Implementasi method getLatestVersion() yang query AppVersion::latest()->first()
  - Return JSON error 404 jika tidak ada versi tersedia
  - Return JSON success 200 dengan format yang berisi version_name, version_code, release_notes, dan download_url
  - Generate download_url menggunakan Storage::url($version->file_path)
  - _Requirements: 5.1, 5.2, 5.3, 5.4, 5.5_

- [x] 5. Update API routes dengan middleware protection





  - Edit routes/api.php untuk menambahkan middleware 'auth.apikey' ke group yang ada
  - Pindahkan route /check-location ke dalam group middleware auth.apikey
  - Tambahkan route GET /latest-version yang mengarah ke Api\VersionController@getLatestVersion
  - _Requirements: 5.1, 6.5, 7.1, 7.2, 7.3_

- [x] 6. Implementasi AppVersion Controller untuk admin





  - Buat controller AppVersionController di app/Http/Controllers/AppVersionController.php
  - Implementasi method index() yang return view dengan AppVersion::latest()->paginate(10)
  - Implementasi method create() yang return view form upload
  - Implementasi method store() dengan validasi version_name, version_code (unique), release_notes (nullable), apk_file (required, mimes:apk, max:102400)
  - Dalam store(), simpan file dengan $request->file('apk_file')->store('public/updates')
  - Dalam store(), buat record AppVersion dengan data yang divalidasi
  - Implementasi method destroy() yang hapus file dari storage dan record dari database
  - Tambahkan try-catch untuk error handling dengan flash messages
  - _Requirements: 2.2, 2.3, 2.4, 2.5, 4.1, 4.2, 4.3, 4.4_

- [x] 7. Setup web routes untuk admin CRUD





  - Edit routes/web.php di dalam group admin middleware yang sudah ada
  - Tambahkan Route::resource('app-versions', AppVersionController::class)->except(['show', 'edit', 'update'])
  - _Requirements: 2.1_

- [x] 8. Buat view index untuk daftar versi





  - Buat file resources/views/admin/app-versions/index.blade.php yang extend layouts.admin
  - Buat page header dengan title "Manajemen Versi APK" dan tombol "Upload Versi Baru"
  - Buat tabel dengan kolom Version Name, Version Code, Upload Date, dan Actions
  - Implementasi pagination dengan Bootstrap styling
  - Tambahkan tombol Delete untuk setiap row dengan confirmation
  - Tambahkan empty state message jika tidak ada data
  - _Requirements: 3.1, 3.2, 3.3, 3.4_

- [x] 9. Buat view create untuk upload form





  - Buat file resources/views/admin/app-versions/create.blade.php yang extend layouts.admin
  - Buat form dengan method POST, action ke route('admin.app-versions.store'), dan enctype="multipart/form-data"
  - Tambahkan CSRF token
  - Buat input field untuk version_name (text, required)
  - Buat input field untuk version_code (number, required)
  - Buat textarea untuk release_notes (optional)
  - Buat input file untuk apk_file (required, accept=".apk")
  - Tambahkan tombol Submit dan Cancel
  - Implementasi error display dengan Bootstrap validation classes
  - _Requirements: 2.1, 2.2_

- [x] 10. Update sidebar admin layout





  - Edit file resources/views/layouts/admin.blade.php
  - Tambahkan nav-item baru di sidebar dengan icon bi-phone-fill atau bi-download
  - Link ke route('admin.app-versions.index') dengan label "App Updates" atau "Manajemen Versi"
  - Tambahkan active class conditional berdasarkan request()->routeIs('admin.app-versions.*')
  - _Requirements: 3.5_

- [x] 11. Run migrations dan setup storage





  - Jalankan php artisan migrate untuk membuat tabel app_versions
  - Jalankan php artisan storage:link untuk membuat symbolic link
  - Verifikasi storage directory permissions
  - _Requirements: 1.1, 1.3_

- [ ]* 12. Testing dan validasi
  - Test API endpoint /latest-version dengan valid API key
  - Test API endpoint tanpa API key (harus return 401)
  - Test upload APK melalui admin dashboard
  - Test validasi form (duplicate version_code, invalid file type)
  - Test delete functionality (file dan record terhapus)
  - Test pagination di index page
  - Test sidebar navigation dan active state
  - Test download URL dari API response
  - _Requirements: All_
