# API Changelog - Check Location Endpoint

## Version 2.0 - November 8, 2025

### Breaking Changes

#### POST/GET `/api/check-location`

**What Changed**: Endpoint sekarang mengembalikan array of locations jika email ditemukan di multiple locations.

---

### Response Format

#### Case 1: Email di Satu Location (Backward Compatible)

**Request**:
```json
POST /api/check-location
{
  "email": "user_single@dkm"
}
```

**Response** (200 OK):
```json
{
  "success": true,
  "data": {
    "email": "user_single@dkm",
    "online_url": "https://dev.mydeposys.com",
    "location_name": "Dev",
    "location_code": "dev",
    "location_logo": "http://localhost:8000/public/storage/location-logos/dev.png"
  }
}
```

**Note**: Format ini sama dengan versi sebelumnya (backward compatible).

---

#### Case 2: Email di Multiple Locations (NEW)

**Request**:
```json
POST /api/check-location
{
  "email": "user1@dkm"
}
```

**Response** (200 OK):
```json
{
  "success": true,
  "data": [
    {
      "email": "user1@dkm",
      "online_url": "https://dev.mydeposys.com",
      "location_name": "Dev",
      "location_code": "dev",
      "location_logo": "http://localhost:8000/public/storage/location-logos/dev.png"
    },
    {
      "email": "user1@dkm",
      "online_url": "https://apidepotest.dwipakharismamitra.com",
      "location_name": "Test",
      "location_code": "test",
      "location_logo": "http://localhost:8000/public/storage/location-logos/test.png"
    }
  ]
}
```

**Note**: `data` sekarang berupa array of objects.

---

#### Case 3: Email Not Found

**Request**:
```json
POST /api/check-location
{
  "email": "notfound@dkm"
}
```

**Response** (404 Not Found):
```json
{
  "success": false,
  "message": "Email not found"
}
```

---

#### Case 4: Validation Error

**Request**:
```json
POST /api/check-location
{
  "email": "invalid-email"
}
```

**Response** (422 Unprocessable Entity):
```json
{
  "success": false,
  "message": "Validation error",
  "errors": {
    "email": [
      "The email must be a valid email address."
    ]
  }
}
```

---

### Migration Guide for Client Apps

#### Flutter/Mobile App

**Before** (v1.0):
```dart
// Assume data is always single object
final response = await http.post(
  Uri.parse('$baseUrl/api/check-location'),
  body: {'email': email},
);

final data = jsonDecode(response.body)['data'];
final onlineUrl = data['online_url']; // String
```

**After** (v2.0):
```dart
final response = await http.post(
  Uri.parse('$baseUrl/api/check-location'),
  body: {'email': email},
);

final responseData = jsonDecode(response.body)['data'];

// Check if data is array or single object
if (responseData is List) {
  // Multiple locations
  final locations = responseData as List;
  
  // Option 1: Show selection dialog
  final selectedLocation = await showLocationDialog(locations);
  final onlineUrl = selectedLocation['online_url'];
  
  // Option 2: Use first location
  final onlineUrl = locations[0]['online_url'];
  
  // Option 3: Use all locations
  for (var location in locations) {
    print('Available: ${location['location_name']}');
  }
} else {
  // Single location (backward compatible)
  final onlineUrl = responseData['online_url'];
}
```

---

### Implementation Details

#### Files Changed

1. **app/Repositories/UserRepository.php**
   - Added `findAllByEmail()` method
   - Returns all active users with same email

2. **app/Services/LocationService.php**
   - Updated `checkUserLocation()` method
   - Returns single object if 1 location (backward compatible)
   - Returns array if multiple locations

3. **app/Http/Controllers/Api/LocationController.php**
   - No changes (controller remains the same)

---

### Testing

#### Test Case 1: Single Location
```bash
curl -X POST http://localhost:8000/api/check-location \
  -H "Content-Type: application/json" \
  -H "X-API-Key: your_api_key" \
  -d '{"email": "user_single@dkm"}'
```

Expected: Single object in `data`.

---

#### Test Case 2: Multiple Locations
```bash
curl -X POST http://localhost:8000/api/check-location \
  -H "Content-Type: application/json" \
  -H "X-API-Key: your_api_key" \
  -d '{"email": "user1@dkm"}'
```

Expected: Array of objects in `data`.

---

#### Test Case 3: Not Found
```bash
curl -X POST http://localhost:8000/api/check-location \
  -H "Content-Type: application/json" \
  -H "X-API-Key: your_api_key" \
  -d '{"email": "notfound@dkm"}'
```

Expected: 404 error.

---

### Backward Compatibility

✅ **Fully Backward Compatible** for single location users.

⚠️ **Breaking Change** for users with multiple locations:
- Old clients will only see first location
- New clients can handle multiple locations

**Recommendation**: Update client apps to handle both cases (single object or array).

---

### Performance Impact

- **Before**: 1 database query (first())
- **After**: 1 database query (get())
- **Impact**: Minimal (same query, just returns all results)

---

### Security

- ✅ Only returns active users
- ✅ API key authentication required
- ✅ Rate limiting applied (60 requests/minute)
- ✅ Email validation

---

### Rollback Plan

If issues occur, rollback by reverting:

```bash
git revert <commit-hash>
php artisan config:clear
php artisan cache:clear
```

Or manually revert changes in:
- `app/Repositories/UserRepository.php`
- `app/Services/LocationService.php`

---

### Future Enhancements

1. **Add location_id to response** - For easier identification
2. **Add sorting** - Sort by location_name or location_code
3. **Add filtering** - Filter by location_code in request
4. **Add pagination** - If user has many locations (unlikely)

---

### Support

For questions or issues:
- Check logs: `storage/logs/laravel.log`
- Contact: development team
- Documentation: `docs/API_DOCUMENTATION.md`

---

**Version**: 2.0  
**Date**: November 8, 2025  
**Status**: ✅ Deployed
