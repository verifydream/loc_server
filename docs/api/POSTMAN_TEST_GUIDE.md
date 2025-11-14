# Postman Test Guide - Check Location API

## Setup

### 1. Import Environment Variables

Create new environment in Postman:

```
base_url: http://localhost:8000
api_key: 2yVrUCjQLo4S81l4cZkAaC5UaHbKLTaZ8fJ5p4eh0HLDckP19DNgAg3NHW6YC6Kt
```

---

## Test Cases

### Test 1: Multiple Locations (user1@dkm)

**Method**: POST  
**URL**: `{{base_url}}/api/check-location`

**Headers**:
```
Content-Type: application/json
X-API-Key: {{api_key}}
```

**Body** (raw JSON):
```json
{
  "email": "user1@dkm"
}
```

**Expected Response** (200 OK):
```json
{
  "success": true,
  "data": [
    {
      "email": "user1@dkm",
      "online_url": "https://apidepotest.dwipakharismamitra.com",
      "location_name": "Test",
      "location_code": "test",
      "location_logo": "http://localhost:8000/public/storage/location-logos/1762480426_test.png"
    },
    {
      "email": "user1@dkm",
      "online_url": "https://dev.mydeposys.com",
      "location_name": "Dev",
      "location_code": "dev",
      "location_logo": "http://localhost:8000/public/storage/location-logos/1762480426_dev.png"
    }
  ]
}
```

**Verification**:
- ✅ Status code: 200
- ✅ `success`: true
- ✅ `data` is array
- ✅ Array has 2 elements
- ✅ Both have same email
- ✅ Different location_code

---

### Test 2: Single Location

**Method**: POST  
**URL**: `{{base_url}}/api/check-location`

**Headers**:
```
Content-Type: application/json
X-API-Key: {{api_key}}
```

**Body** (raw JSON):
```json
{
  "email": "sby_survey@web.com"
}
```

**Expected Response** (200 OK):
```json
{
  "success": true,
  "data": {
    "email": "sby_survey@web.com",
    "online_url": "https://sby.mydeposys.com",
    "location_name": "Surabaya",
    "location_code": "sby",
    "location_logo": "http://localhost:8000/public/storage/location-logos/sby.png"
  }
}
```

**Verification**:
- ✅ Status code: 200
- ✅ `success`: true
- ✅ `data` is object (not array)
- ✅ Has all required fields

---

### Test 3: Email Not Found

**Method**: POST  
**URL**: `{{base_url}}/api/check-location`

**Headers**:
```
Content-Type: application/json
X-API-Key: {{api_key}}
```

**Body** (raw JSON):
```json
{
  "email": "notfound@dkm"
}
```

**Expected Response** (404 Not Found):
```json
{
  "success": false,
  "message": "Email not found"
}
```

**Verification**:
- ✅ Status code: 404
- ✅ `success`: false
- ✅ Has error message

---

### Test 4: Invalid Email Format

**Method**: POST  
**URL**: `{{base_url}}/api/check-location`

**Headers**:
```
Content-Type: application/json
X-API-Key: {{api_key}}
```

**Body** (raw JSON):
```json
{
  "email": "invalid-email"
}
```

**Expected Response** (422 Unprocessable Entity):
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

**Verification**:
- ✅ Status code: 422
- ✅ `success`: false
- ✅ Has validation errors

---

### Test 5: Missing Email

**Method**: POST  
**URL**: `{{base_url}}/api/check-location`

**Headers**:
```
Content-Type: application/json
X-API-Key: {{api_key}}
```

**Body** (raw JSON):
```json
{}
```

**Expected Response** (422 Unprocessable Entity):
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

**Verification**:
- ✅ Status code: 422
- ✅ `success`: false
- ✅ Has validation errors

---

### Test 6: Missing API Key

**Method**: POST  
**URL**: `{{base_url}}/api/check-location`

**Headers**:
```
Content-Type: application/json
(No X-API-Key header)
```

**Body** (raw JSON):
```json
{
  "email": "user1@dkm"
}
```

**Expected Response** (401 Unauthorized):
```json
{
  "success": false,
  "message": "Unauthorized"
}
```

**Verification**:
- ✅ Status code: 401
- ✅ `success`: false

---

### Test 7: Invalid API Key

**Method**: POST  
**URL**: `{{base_url}}/api/check-location`

**Headers**:
```
Content-Type: application/json
X-API-Key: invalid_key_12345
```

**Body** (raw JSON):
```json
{
  "email": "user1@dkm"
}
```

**Expected Response** (401 Unauthorized):
```json
{
  "success": false,
  "message": "Unauthorized"
}
```

**Verification**:
- ✅ Status code: 401
- ✅ `success`: false

---

### Test 8: GET Method (Alternative)

**Method**: GET  
**URL**: `{{base_url}}/api/check-location?email=user1@dkm`

**Headers**:
```
X-API-Key: {{api_key}}
```

**Expected Response** (200 OK):
Same as Test 1 (array of locations)

**Verification**:
- ✅ GET method also works
- ✅ Same response as POST

---

## Additional Test Cases

### Test 9: All Users with Multiple Locations

Test these emails (all should return array):
- acc@gmail.com
- adi@dkm.com
- admin_surabaya@gmail.com
- agung@gmail.com
- agus@gmail.com
- user1@dkm
- ... (see test-check-location.php output)

---

### Test 10: Inactive User

**Setup**: Set user status to 'inactive' in database

**Expected**: 404 Not Found (inactive users not returned)

---

## Postman Collection

### Import this JSON:

```json
{
  "info": {
    "name": "Check Location API Tests",
    "schema": "https://schema.getpostman.com/json/collection/v2.1.0/collection.json"
  },
  "item": [
    {
      "name": "Multiple Locations",
      "request": {
        "method": "POST",
        "header": [
          {
            "key": "Content-Type",
            "value": "application/json"
          },
          {
            "key": "X-API-Key",
            "value": "{{api_key}}"
          }
        ],
        "body": {
          "mode": "raw",
          "raw": "{\"email\": \"user1@dkm\"}"
        },
        "url": {
          "raw": "{{base_url}}/api/check-location",
          "host": ["{{base_url}}"],
          "path": ["api", "check-location"]
        }
      }
    },
    {
      "name": "Single Location",
      "request": {
        "method": "POST",
        "header": [
          {
            "key": "Content-Type",
            "value": "application/json"
          },
          {
            "key": "X-API-Key",
            "value": "{{api_key}}"
          }
        ],
        "body": {
          "mode": "raw",
          "raw": "{\"email\": \"sby_survey@web.com\"}"
        },
        "url": {
          "raw": "{{base_url}}/api/check-location",
          "host": ["{{base_url}}"],
          "path": ["api", "check-location"]
        }
      }
    },
    {
      "name": "Email Not Found",
      "request": {
        "method": "POST",
        "header": [
          {
            "key": "Content-Type",
            "value": "application/json"
          },
          {
            "key": "X-API-Key",
            "value": "{{api_key}}"
          }
        ],
        "body": {
          "mode": "raw",
          "raw": "{\"email\": \"notfound@dkm\"}"
        },
        "url": {
          "raw": "{{base_url}}/api/check-location",
          "host": ["{{base_url}}"],
          "path": ["api", "check-location"]
        }
      }
    }
  ]
}
```

---

## Quick Test Commands (curl)

### Test 1: Multiple Locations
```bash
curl -X POST http://localhost:8000/api/check-location \
  -H "Content-Type: application/json" \
  -H "X-API-Key: 2yVrUCjQLo4S81l4cZkAaC5UaHbKLTaZ8fJ5p4eh0HLDckP19DNgAg3NHW6YC6Kt" \
  -d '{"email": "user1@dkm"}'
```

### Test 2: Single Location
```bash
curl -X POST http://localhost:8000/api/check-location \
  -H "Content-Type: application/json" \
  -H "X-API-Key: 2yVrUCjQLo4S81l4cZkAaC5UaHbKLTaZ8fJ5p4eh0HLDckP19DNgAg3NHW6YC6Kt" \
  -d '{"email": "sby_survey@web.com"}'
```

### Test 3: Not Found
```bash
curl -X POST http://localhost:8000/api/check-location \
  -H "Content-Type: application/json" \
  -H "X-API-Key: 2yVrUCjQLo4S81l4cZkAaC5UaHbKLTaZ8fJ5p4eh0HLDckP19DNgAg3NHW6YC6Kt" \
  -d '{"email": "notfound@dkm"}'
```

---

## Verification Checklist

After running all tests:

- [ ] Test 1: Multiple locations returns array ✅
- [ ] Test 2: Single location returns object ✅
- [ ] Test 3: Not found returns 404 ✅
- [ ] Test 4: Invalid email returns 422 ✅
- [ ] Test 5: Missing email returns 422 ✅
- [ ] Test 6: Missing API key returns 401 ✅
- [ ] Test 7: Invalid API key returns 401 ✅
- [ ] Test 8: GET method works ✅

**All tests passed**: [ ] Yes [ ] No

---

**Last Updated**: November 8, 2025
