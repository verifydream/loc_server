# API Documentation - Rules & Authentication

## A. General Rules (Ketentuan Umum)

Berikut adalah aturan umum yang berlaku untuk semua API endpoint:

### Base Configuration

1. **Base URL**
   - Development/Local: `http://127.0.0.1:5008`
   - Online: Use `{{online_url}}` variable

2. **Authentication Token**
   - Token format: `eyJ0eXAi.........JeEs4`
   - Token bersifat dinamis (berubah-ubah/tidak permanen)

3. **Required Headers**
   ```
   X-Requested-With: XMLHttpRequest
   Authorization: Bearer {{token}}
   Content-Type: application/json
   ```

4. **Common Parameters**
   - `id_survey_in`
   - dll (sesuai kebutuhan endpoint)

---

## B. Authentication Module

### 1. User Login

Login endpoint untuk mendapatkan access token.

**Endpoint**
```
GET {{online_url}}/api/auth/login
```

**Request Body** (JSON)
```json
{
  "email": "admin@gmail.com",
  "password": "testadmin"
}
```

**Response 200 OK**
```json
{
  "object": {
    "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9...",
    "expired_time": "2025-10-17 14:21",
    "expired_token": "2025-10-17 16:06"
  },
  "status": "success"
}
```

**Response 401 Unauthorized**
```json
{
  "message": "Your username or password not valid",
  "status": "error"
}
```

**Response 500 Internal Server Error**
```json
{
  "status": "error",
  "message": "You must input email and password"
}
```

**Frontend Notes:**
- Simpan `access_token` di localStorage/sessionStorage
- Perhatikan `expired_token` untuk refresh token otomatis
- Handle error 401 untuk redirect ke login page

---

### 2. Refresh Token

Endpoint untuk refresh access token yang akan expired.

**Endpoint**
```
POST {{online_url}}/api/auth/refreshtoken
```

**Headers Required**
```
Authorization: Bearer {{current_token}}
X-Requested-With: XMLHttpRequest
```

**Response 200 OK**
```json
{
  "object": {
    "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9...",
    "expired_time": "2025-10-17 14:29",
    "expired_token": "2025-10-17 16:14"
  },
  "status": "success"
}
```

**Response 401 Unauthorized**
```json
{
  "status": "error",
  "message": "Unauthorized"
}
```

**Frontend Notes:**
- Panggil endpoint ini sebelum token expired
- Update token di storage dengan token baru
- Jika 401, logout user dan redirect ke login

---

### 3. User System

Endpoint untuk mendapatkan daftar user dalam sistem dengan pagination.

**Endpoint**
```
GET {{online_url}}/api/conf/users
```

**Request Body** (JSON)
```json
{
  "q": "",
  "limit": 2,
  "page": 1,
  "sort": "",
  "order": "desc"
}
```

**Request Parameters:**
- `q` (string): Search query untuk filter user
- `limit` (integer): Jumlah data per page
- `page` (integer): Nomor halaman (dimulai dari 1)
- `sort` (string): Field untuk sorting
- `order` (string): `asc` atau `desc`

**Response 200 OK**
```json
{
  "object": [
    {
      "id_user": 1,
      "id_company": 1,
      "name": "Admin surabaya",
      "phone": "0857333444555",
      "email": "admin_surabaya@gmail.com",
      "role": "admin",
      "vendor_repair_id": null,
      "status_user": 1,
      "created_at": "2023-05-13T05:10:19.000000Z",
      "updated_at": "2023-09-24T16:58:44.000000Z",
      "company": "PT DWIPA KHARISMA MITRA",
      "location": "SURABAYA"
    },
    {
      "id_user": 6,
      "id_company": 1,
      "name": "Yanto",
      "phone": "123456789",
      "email": "yanto@gmail.com",
      "role": "user",
      "vendor_repair_id": null,
      "status_user": 1,
      "created_at": "2023-05-17T02:40:28.000000Z",
      "updated_at": "2024-11-16T08:26:19.000000Z",
      "company": "PT DWIPA KHARISMA MITRA",
      "location": "SURABAYA"
    }
  ],
  "total": 36,
  "page": 1,
  "limit": 2,
  "status": "success"
}
```

**Response Fields:**
- `object` (array): Array of user objects
- `total` (integer): Total jumlah user (untuk pagination)
- `page` (integer): Current page
- `limit` (integer): Items per page
- `status` (string): Status response

**Frontend Notes:**
- Gunakan `total` untuk menghitung total pages
- `status_user`: 1 = active, 0 = inactive
- Role bisa `admin`, `user`, dll

---

### 4. User Access Rights (Hak Akses)

Endpoint untuk mendapatkan detail user beserta hak akses per modul.

**Endpoint**
```
GET {{online_url}}/api/conf/user-akses/{{id_user}}
```

**URL Parameters:**
- `id_user` (integer): ID user yang ingin diambil hak aksesnya

**Example URL:**
```
GET {{online_url}}/api/conf/user-akses/126
```

**Response 200 OK**
```json
{
  "object": {
    "id_user": 126,
    "id_company": 1,
    "name": "survey-crani",
    "phone": "084499557744",
    "email": "survey-crani@dkm",
    "role": "user",
    "vendor_repair_id": null,
    "status_user": 1,
    "created_at": "2025-10-16T03:48:25.000000Z",
    "updated_at": "2025-10-16T03:48:25.000000Z",
    "operasional": [
      {
        "id_hak_akses": 17646,
        "id_admin": 126,
        "modul": "operasional",
        "name": "surveyIn",
        "label": "Survey In",
        "view": 1,
        "create": 1,
        "update": 1,
        "delete": 1,
        "print": -1,
        "import": -1,
        "id_user": 1,
        "created_at": "2025-10-16T03:49:48.000000Z",
        "updated_at": "2025-10-16T04:16:22.000000Z"
      },
      {
        "id_hak_akses": 17647,
        "id_admin": 126,
        "modul": "operasional",
        "name": "surveyOut",
        "label": "Survey Out",
        "view": 1,
        "create": 1,
        "update": 1,
        "delete": 1,
        "print": -1,
        "import": -1,
        "id_user": 1,
        "created_at": "2025-10-16T03:49:48.000000Z",
        "updated_at": "2025-10-16T04:16:22.000000Z"
      },
      {
        "id_hak_akses": 17648,
        "id_admin": 126,
        "modul": "operasional",
        "name": "craniIn",
        "label": "Crani In",
        "view": 1,
        "create": 1,
        "update": 1,
        "delete": 1,
        "print": -1,
        "import": -1,
        "id_user": 1,
        "created_at": "2025-10-16T03:49:48.000000Z",
        "updated_at": "2025-10-16T03:49:48.000000Z"
      },
      {
        "id_hak_akses": 17649,
        "id_admin": 126,
        "modul": "operasional",
        "name": "craniOut",
        "label": "Crani Out",
        "view": 1,
        "create": 1,
        "update": 1,
        "delete": 1,
        "print": -1,
        "import": -1,
        "id_user": 1,
        "created_at": "2025-10-16T03:49:48.000000Z",
        "updated_at": "2025-10-16T03:49:48.000000Z"
      }
    ],
    "logistic": [],
    "finance": [],
    "setting": []
  },
  "status": "success"
}
```

**Module Categories:**
- `operasional`: Modul operasional (survey, crani, dll)
- `logistic`: Modul logistik
- `finance`: Modul finance/keuangan
- `setting`: Modul pengaturan

**Access Rights Values:**
- `1` = Granted (memiliki akses)
- `-1` = Not Available/Denied (tidak ada akses)
- `0` = Disabled

**Permission Types:**
- `view`: Hak lihat/read
- `create`: Hak tambah data
- `update`: Hak edit data
- `delete`: Hak hapus data
- `print`: Hak print/export
- `import`: Hak import data

**Last Updated:** October 17, 2025
