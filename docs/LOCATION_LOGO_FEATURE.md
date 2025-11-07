# Location Logo Feature

## Overview
Fitur ini memungkinkan admin untuk menambahkan logo untuk setiap location. Logo bersifat opsional dan dapat diakses secara publik melalui URL.

## Features
- Upload logo saat membuat location baru
- Upload/update logo saat mengedit location
- Preview logo di halaman edit location
- Tampilkan logo di tabel list locations
- Logo URL tersedia di API response `/api/check-location`

## Technical Details

### Database
- Kolom baru: `logo` (string, nullable, 255 chars) di tabel `locations`
- Migration: `2025_11_07_084504_add_logo_to_locations_table.php`

### Storage
- Lokasi: `public/storage/location-logos/`
- Format yang didukung: JPEG, JPG, PNG, GIF, SVG
- Ukuran maksimal: 2MB
- Naming convention: `{timestamp}_{location_code}.{extension}`

### API Response
Endpoint: `POST /api/check-location`

**Request:**
```json
{
  "email": "dev_multi@dkm"
}
```

**Response (dengan logo):**
```json
{
  "success": true,
  "data": {
    "email": "dev_multi@dkm",
    "online_url": "https://dev.mydeposys.com",
    "location_name": "Dev",
    "location_code": "dev",
    "location_logo": "http://localhost:8000/public/storage/location-logos/1762480440_dev.png"
  }
}
```

**Response (tanpa logo):**
```json
{
  "success": true,
  "data": {
    "email": "dev_multi@dkm",
    "online_url": "https://dev.mydeposys.com",
    "location_name": "Dev",
    "location_code": "dev",
    "location_logo": null
  }
}
```

## Usage

### Admin Panel
1. Login ke admin panel: `http://localhost:8000/admin/login`
2. Navigate ke "Locations" menu
3. Klik "Add Location" atau "Edit" pada location yang ada
4. Upload logo melalui field "Location Logo" (opsional)
5. Save

### Public Access
Logo dapat diakses langsung melalui URL tanpa autentikasi:
```
http://localhost:8000/public/storage/location-logos/{filename}
```

**Note:** 
- URL path: `/public/storage/location-logos/` (sama untuk localhost & hosting)
- File fisik disimpan di: `public/storage/location-logos/`
- Di localhost: Menggunakan Laravel route untuk serve file
- Di hosting: Menggunakan .htaccess rewrite rule untuk serve file
- Konsisten antara localhost dan hosting environment

## Files Modified
1. `database/migrations/2025_11_07_084504_add_logo_to_locations_table.php` - Migration untuk kolom logo
2. `app/Models/Location.php` - Tambah 'logo' ke fillable
3. `app/Services/LocationService.php` - Include logo URL di API response (path: `/public/storage/location-logos/`)
4. `app/Services/LocationManagementService.php` - Handle upload logo
5. `app/Http/Controllers/LocationManagementController.php` - Validasi upload logo
6. `resources/views/admin/locations/create.blade.php` - Form upload logo
7. `resources/views/admin/locations/edit.blade.php` - Form upload logo dengan preview
8. `resources/views/admin/locations/index.blade.php` - Tampilkan logo di tabel
9. `routes/web.php` - Route untuk serve logo di localhost
10. `.htaccess` - Rewrite rule untuk serve logo di hosting

## Notes
- Logo bersifat opsional, location dapat dibuat tanpa logo
- Saat update logo, logo lama akan otomatis dihapus
- Logo disimpan di `public/storage/location-logos/` yang sudah di-gitignore
- URL logo menggunakan helper `url()` untuk generate full URL
