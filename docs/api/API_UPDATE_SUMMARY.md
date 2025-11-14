# API Update Summary - Check Location Endpoint

## Date: November 8, 2025

---

## 🎯 Problem

API `/api/check-location` hanya mengembalikan 1 location meskipun email yang sama ada di multiple locations (cabang berbeda).

**Example**:
- Email `user1@dkm` ada di:
  - Location: Dev
  - Location: Test
  
**Before**: API hanya return location "Test" (yang pertama ditemukan)  
**After**: API return kedua location (Dev dan Test)

---

## ✅ Solution

Updated API untuk mengembalikan:
- **Single object** jika email hanya di 1 location (backward compatible)
- **Array of objects** jika email di multiple locations

---

## 📊 Response Format Changes

### Case 1: Single Location (Backward Compatible)

**Request**:
```http
POST /api/check-location
Content-Type: application/json
X-API-Key: your_api_key

{
  "email": "single_user@dkm"
}
```

**Response**:
```json
{
  "success": true,
  "data": {
    "email": "single_user@dkm",
    "online_url": "https://dev.mydeposys.com",
    "location_name": "Dev",
    "location_code": "dev",
    "location_logo": "http://localhost:8000/public/storage/location-logos/dev.png"
  }
}
```

**Note**: Format sama dengan sebelumnya ✅

---

### Case 2: Multiple Locations (NEW)

**Request**:
```http
POST /api/check-location
Content-Type: application/json
X-API-Key: your_api_key

{
  "email": "user1@dkm"
}
```

**Response**:
```json
{
  "success": true,
  "data": [
    {
      "email": "user1@dkm",
      "online_url": "https://apidepotest.dwipakharismamitra.com",
      "location_name": "Test",
      "location_code": "test",
      "location_logo": "http://localhost:8000/public/storage/location-logos/test.png"
    },
    {
      "email": "user1@dkm",
      "online_url": "https://dev.mydeposys.com",
      "location_name": "Dev",
      "location_code": "dev",
      "location_logo": "http://localhost:8000/public/storage/location-logos/dev.png"
    }
  ]
}
```

**Note**: `data` sekarang array ⚠️

---

## 🔧 Files Modified

1. **app/Repositories/UserRepository.php**
   - Added: `findAllByEmail()` method
   - Returns: Collection of all users with same email (active only)

2. **app/Services/LocationService.php**
   - Updated: `checkUserLocation()` method
   - Logic: 
     - If 1 location → return single object
     - If multiple locations → return array

---

## 📈 Impact

### Database
- **Current**: 30 users with multiple locations
- **Current**: 26 users with single location

### Affected Users
Users yang akan mendapat array response:
- acc@gmail.com (2 locations)
- adi@dkm.com (2 locations)
- admin_surabaya@gmail.com (2 locations)
- user1@dkm (2 locations)
- ... dan 26 lainnya

---

## 🧪 Testing

### Test Script
```bash
php test-check-location.php
```

### Manual Test (Postman)

**Test 1: Multiple Locations**
```
POST http://localhost:8000/api/check-location
Headers:
  Content-Type: application/json
  X-API-Key: 2yVrUCjQLo4S81l4cZkAaC5UaHbKLTaZ8fJ5p4eh0HLDckP19DNgAg3NHW6YC6Kt

Body:
{
  "email": "user1@dkm"
}

Expected: Array with 2 objects
```

**Test 2: Single Location**
```
POST http://localhost:8000/api/check-location
Headers:
  Content-Type: application/json
  X-API-Key: 2yVrUCjQLo4S81l4cZkAaC5UaHbKLTaZ8fJ5p4eh0HLDckP19DNgAg3NHW6YC6Kt

Body:
{
  "email": "sby_survey@web.com"
}

Expected: Single object (not array)
```

---

## ⚠️ Breaking Changes

### For Client Apps (Flutter/Mobile)

**Old Code** (will break for multiple locations):
```dart
final data = response['data'];
final url = data['online_url']; // ❌ Error if data is array
```

**New Code** (handles both cases):
```dart
final data = response['data'];

if (data is List) {
  // Multiple locations - show selection
  final locations = data as List;
  // Handle multiple locations
} else {
  // Single location - use directly
  final url = data['online_url'];
}
```

---

## 🔄 Migration Guide

### Option 1: Update Client App (Recommended)

Update Flutter/mobile app untuk handle both cases:

```dart
Future<String> getOnlineUrl(String email) async {
  final response = await checkLocation(email);
  final data = response['data'];
  
  if (data is List) {
    // Multiple locations - let user choose
    return await showLocationPicker(data);
  } else {
    // Single location
    return data['online_url'];
  }
}
```

### Option 2: Always Return Array (Breaking Change)

Jika ingin konsisten selalu return array:

Edit `app/Services/LocationService.php`:
```php
// Always return array
return $locations; // Remove the single object logic
```

**Cons**: Breaking change untuk semua client.

---

## 📊 Statistics

From database:
- **Total active users**: 56
- **Users with multiple locations**: 30 (53.6%)
- **Users with single location**: 26 (46.4%)

**Conclusion**: Banyak user yang affected, update client app sangat disarankan.

---

## 🚀 Deployment

### Steps
1. ✅ Code updated
2. ✅ Tested locally
3. ⏳ Deploy to staging
4. ⏳ Test on staging
5. ⏳ Update client app
6. ⏳ Deploy to production

### Rollback Plan
```bash
git revert <commit-hash>
php artisan config:clear
php artisan cache:clear
```

---

## 📞 Support

### For Backend Issues
- Check logs: `storage/logs/laravel.log`
- Run test: `php test-check-location.php`

### For Client App Issues
- Update to handle both single object and array
- See migration guide above

---

## ✅ Verification Checklist

- [x] Code implemented
- [x] No syntax errors
- [x] Test script created
- [x] Documentation updated
- [ ] Tested in Postman
- [ ] Client app updated
- [ ] Deployed to staging
- [ ] Deployed to production

---

**Status**: ✅ Ready for Testing  
**Version**: 2.0  
**Date**: November 8, 2025
