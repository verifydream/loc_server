# Design Document - Location Server

## Overview

Location Server adalah aplikasi Laravel yang berfungsi sebagai centralized routing service untuk aplikasi mobile Flutter. Sistem ini menyelesaikan masalah routing dinamis dengan menyediakan lookup service yang menentukan URL API yang tepat berdasarkan email user saat login.

**Masalah yang diselesaikan:**
- Eliminasi kebutuhan rebuild aplikasi mobile untuk setiap perubahan user atau cabang baru
- Centralized management untuk mapping user ke lokasi/cabang
- Skalabilitas untuk menambah cabang baru tanpa modifikasi aplikasi mobile

**Teknologi Stack:**
- Backend: Laravel 10.x (PHP 8.1+)
- Database: MySQL 8.0+
- Frontend Dashboard: Laravel Blade + Bootstrap 5
- Authentication: Laravel Sanctum/Session

## Architecture

### System Architecture

```
┌─────────────────┐
│  Flutter Mobile │
│   Application   │
└────────┬────────┘
         │ POST /api/check-location
         │ {email: "user@example.com"}
         ▼
┌─────────────────────────────────┐
│   Location Server (Laravel)     │
│  ┌──────────────────────────┐  │
│  │   API Layer              │  │
│  │   - LocationController   │  │
│  └──────────┬───────────────┘  │
│             │                   │
│  ┌──────────▼───────────────┐  │
│  │   Service Layer          │  │
│  │   - LocationService      │  │
│  └──────────┬───────────────┘  │
│             │                   │
│  ┌──────────▼───────────────┐  │
│  │   Repository Layer       │  │
│  │   - UserRepository       │  │
│  │   - LocationRepository   │  │
│  └──────────┬───────────────┘  │
│             │                   │
│  ┌──────────▼───────────────┐  │
│  │   Database (MySQL)       │  │
│  │   - users                │  │
│  │   - locations            │  │
│  │   - admins               │  │
│  └──────────────────────────┘  │
└─────────────────────────────────┘
         ▲
         │ HTTPS
         │
┌────────┴────────┐
│  Admin Dashboard│
│  (Web Browser)  │
└─────────────────┘
```

### Layered Architecture

**Presentation Layer:**
- API Routes: Public endpoint untuk mobile app
- Web Routes: Protected routes untuk admin dashboard
- Controllers: Handle HTTP requests/responses

**Business Logic Layer:**
- Services: Business logic dan orchestration
- Validation: Input validation rules
- Authorization: Access control logic

**Data Access Layer:**
- Repositories: Data access abstraction
- Models: Eloquent ORM models
- Migrations: Database schema definitions

**Design Rationale:**
- Repository pattern untuk memisahkan business logic dari data access, memudahkan testing dan maintenance
- Service layer untuk complex business logic, menjaga controller tetap slim
- Separation of concerns antara API dan Web routes untuk security dan maintainability

## Components and Interfaces

### 1. API Component

**LocationController (API)**
```php
POST /api/check-location
Request: {
  "email": "user@example.com"
}

Success Response (200): {
  "success": true,
  "data": {
    "email": "user@example.com",
    "online_url": "sby.web.com",
    "location_name": "Surabaya",
    "location_code": "sby"
  }
}

Error Responses:
- 404: {"success": false, "message": "Email not found"}
- 403: {"success": false, "message": "User is inactive"}
- 422: {"success": false, "message": "Validation error", "errors": {...}}
```

**Design Decision:** Public endpoint tanpa authentication untuk memudahkan integrasi mobile app. Rate limiting akan diterapkan untuk mencegah abuse.

### 2. Dashboard Component

**Admin Authentication**
- Login page: `/admin/login`
- Dashboard: `/admin/dashboard`
- Logout: `/admin/logout`

**User Management Interface**
- List: `/admin/users` - Tabel dengan search, filter, pagination
- Create: `/admin/users/create` - Form input user baru
- Edit: `/admin/users/{id}/edit` - Form edit user
- Delete: `/admin/users/{id}` - Soft delete dengan konfirmasi

**Location Management Interface**
- List: `/admin/locations` - Tabel lokasi/cabang
- Create: `/admin/locations/create` - Form tambah lokasi
- Edit: `/admin/locations/{id}/edit` - Form edit lokasi
- Delete: `/admin/locations/{id}` - Delete dengan validasi usage

**Design Decision:** Menggunakan Laravel Blade untuk dashboard karena:
- Simple CRUD operations tidak memerlukan SPA complexity
- Server-side rendering lebih cepat untuk admin interface
- Built-in CSRF protection dan session management

### 3. Service Layer

**LocationService**
```php
class LocationService
{
    public function checkUserLocation(string $email): array
    public function validateUserAccess(User $user): bool
}
```

**UserService**
```php
class UserService
{
    public function createUser(array $data): User
    public function updateUser(User $user, array $data): User
    public function deleteUser(User $user): bool
    public function searchUsers(string $query, ?int $locationId, ?string $status): Collection
}
```

**LocationManagementService**
```php
class LocationManagementService
{
    public function createLocation(array $data): Location
    public function updateLocation(Location $location, array $data): Location
    public function deleteLocation(Location $location): bool
    public function canDeleteLocation(Location $location): bool
}
```

## Data Models

### Database Schema

**locations table**
```sql
CREATE TABLE locations (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    location_code VARCHAR(10) UNIQUE NOT NULL,
    location_name VARCHAR(100) NOT NULL,
    online_url VARCHAR(255) NOT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    INDEX idx_location_code (location_code)
);
```

**users table**
```sql
CREATE TABLE users (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) UNIQUE NOT NULL,
    location_id BIGINT UNSIGNED NOT NULL,
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (location_id) REFERENCES locations(id) ON DELETE RESTRICT,
    INDEX idx_email (email),
    INDEX idx_location_id (location_id),
    INDEX idx_status (status)
);
```

**admins table**
```sql
CREATE TABLE admins (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    remember_token VARCHAR(100) NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    INDEX idx_email (email)
);
```

**Design Rationale:**
- `location_code` sebagai unique identifier untuk kemudahan reference
- Foreign key dengan `ON DELETE RESTRICT` untuk mencegah data inconsistency
- Indexes pada email dan location_id untuk optimasi query performance
- ENUM untuk status field untuk data integrity
- Email disimpan lowercase untuk konsistensi (handled di model)

### Eloquent Models

**Location Model**
```php
class Location extends Model
{
    protected $fillable = ['location_code', 'location_name', 'online_url'];
    
    public function users()
    {
        return $this->hasMany(User::class);
    }
}
```

**User Model**
```php
class User extends Model
{
    protected $fillable = ['email', 'location_id', 'status'];
    
    protected $casts = [
        'status' => 'string',
    ];
    
    public function location()
    {
        return $this->belongsTo(Location::class);
    }
    
    public function setEmailAttribute($value)
    {
        $this->attributes['email'] = strtolower($value);
    }
    
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
```

**Admin Model**
```php
class Admin extends Authenticatable
{
    protected $fillable = ['name', 'email', 'password'];
    
    protected $hidden = ['password', 'remember_token'];
    
    protected $casts = [
        'password' => 'hashed',
    ];
}
```

## Error Handling

### API Error Responses

**Standard Error Format:**
```json
{
    "success": false,
    "message": "Human readable error message",
    "errors": {
        "field_name": ["Specific validation error"]
    }
}
```

**HTTP Status Codes:**
- 200: Success
- 400: Bad Request (invalid data format)
- 403: Forbidden (user inactive)
- 404: Not Found (email not found)
- 422: Unprocessable Entity (validation errors)
- 500: Internal Server Error

### Dashboard Error Handling

**User Feedback:**
- Flash messages untuk success/error operations
- Inline validation errors pada forms
- Confirmation modals untuk destructive actions

**Logging Strategy:**
- API requests logged dengan email dan response time
- Database errors logged dengan full stack trace
- Authentication attempts logged untuk security audit

**Design Decision:** Separate error handling untuk API vs Dashboard:
- API: JSON responses untuk machine consumption
- Dashboard: Flash messages dan inline errors untuk human readability

## Testing Strategy

### Unit Tests

**Service Layer Tests:**
- LocationService: Test business logic untuk user location lookup
- UserService: Test CRUD operations dan search functionality
- LocationManagementService: Test location management dan deletion validation

**Model Tests:**
- Test relationships (User-Location)
- Test scopes (active users)
- Test mutators (email lowercase)

### Integration Tests

**API Tests:**
- Test `/api/check-location` dengan berbagai scenarios:
  - Valid email, active user
  - Valid email, inactive user
  - Email not found
  - Missing email parameter
  - Invalid email format

**Feature Tests:**
- Admin authentication flow
- User CRUD operations via dashboard
- Location CRUD operations via dashboard
- Search and filter functionality

### Performance Tests

**Response Time Requirements:**
- API endpoint harus respond < 500ms
- Dashboard pages harus load < 2s

**Load Testing:**
- API endpoint harus handle 100 concurrent requests
- Database queries harus optimized dengan proper indexing

**Design Rationale:**
- Focus pada integration tests untuk API karena critical untuk mobile app
- Unit tests untuk complex business logic di service layer
- Performance tests untuk memastikan SLA terpenuhi

## Security Considerations

### API Security

**Rate Limiting:**
- 60 requests per minute per IP untuk `/api/check-location`
- Prevent brute force email enumeration

**Input Validation:**
- Email format validation
- SQL injection prevention via Eloquent ORM
- XSS prevention via output escaping

### Dashboard Security

**Authentication:**
- Session-based authentication untuk admin
- Password hashing dengan bcrypt
- CSRF protection pada semua forms

**Authorization:**
- Middleware untuk protect admin routes
- Session timeout setelah 2 jam inactivity

**Design Decision:** 
- API public tanpa auth untuk simplicity, protected dengan rate limiting
- Dashboard fully authenticated untuk data protection

## Deployment Considerations

### Environment Configuration

**Required Environment Variables:**
```
APP_ENV=production
APP_DEBUG=false
DB_HOST=localhost
DB_DATABASE=location_server
DB_USERNAME=
DB_PASSWORD=
```

### Database Seeding

**Initial Data:**
- 8 lokasi default (Surabaya, Jakarta, Belawan, Semarang, BNS, Java, Test, Dev)
- 1 admin default untuk initial access
- Seeder idempotent (check existing data)

### Migration Strategy

**Deployment Steps:**
1. Run migrations: `php artisan migrate`
2. Run seeders: `php artisan db:seed`
3. Clear cache: `php artisan config:cache`
4. Optimize: `php artisan optimize`

**Design Rationale:**
- Seeders untuk initial data memastikan sistem ready to use setelah deployment
- Idempotent seeders untuk safe re-running

## Future Enhancements

**Potential Improvements:**
- API versioning untuk backward compatibility
- Audit log untuk track semua perubahan data
- Bulk import users via CSV
- API documentation dengan Swagger/OpenAPI
- Multi-tenancy support untuk isolasi data per organization
- Caching layer (Redis) untuk frequently accessed data
