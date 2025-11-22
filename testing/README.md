# Testing Scripts

Folder ini berisi berbagai script untuk testing aplikasi Location Server.

## 📁 Available Test Scripts

### PHP Scripts

#### 1. test-api-login.php
**Purpose**: Test API login dan melihat format response token.

**Usage**:
```bash
php testing/test-api-login.php
```

**What it tests**:
- Login ke external API
- Format response token
- JWT token location

---

#### 2. test-check-location.php
**Purpose**: Test API `/api/check-location` untuk multiple locations.

**Usage**:
```bash
php testing/test-check-location.php
```

**What it tests**:
- Users dengan single location
- Users dengan multiple locations
- Database query results

---

#### 3. check-constraint.php
**Purpose**: Verify database constraint untuk email unique.

**Usage**:
```bash
php testing/check-constraint.php
```

**What it tests**:
- Constraint `users_email_location_unique` exists
- Duplicate email di location berbeda allowed
- Database schema correctness

---

#### 4. test-route.php
**Purpose**: Test routing dan basic functionality.

**Usage**:
```bash
php testing/test-route.php
```

---

### HTML Test Pages

#### 1. test-upload-progress.html
**Purpose**: Test upload progress bar functionality.

**Usage**:
1. Buka di browser: `http://localhost:8000/test-upload-progress.html`
2. Select file APK
3. Click upload
4. Observe progress bar

**What it tests**:
- File upload dengan progress
- Real-time progress updates
- Upload completion

---

#### 2. test-public-download.html
**Purpose**: Test public APK download functionality.

**Usage**:
1. Buka di browser: `http://localhost:8000/test-public-download.html`
2. Click download button
3. Verify file downloads

**What it tests**:
- Public download route
- File download without auth
- Download button functionality

---

#### 3. debug-upload-form.html
**Purpose**: Debug upload form issues.

**Usage**:
1. Buka di browser: `http://localhost:8000/debug-upload-form.html`
2. Test various upload scenarios

---

### HTTP Test Files

#### 1. test-api-download.http
**Purpose**: Test API download endpoints dengan REST client.

**Usage**:
- Open in VS Code with REST Client extension
- Click "Send Request"

**What it tests**:
- API download endpoints
- Authentication headers
- Response format

---

## 🚀 Quick Test Commands

### Test All PHP Scripts
```bash
# Test API login
php testing/test-api-login.php

# Test check location
php testing/test-check-location.php

# Test database constraint
php testing/check-constraint.php
```

### Test HTML Pages
```bash
# Start Laravel server
php artisan serve

# Then open in browser:
# http://localhost:8000/test-upload-progress.html
# http://localhost:8000/test-public-download.html
# http://localhost:8000/debug-upload-form.html
```

---

## 📋 Test Checklist

### Before Deployment

- [ ] Run `test-api-login.php` - API login works
- [ ] Run `test-check-location.php` - Multiple locations work
- [ ] Run `check-constraint.php` - Database constraint correct
- [ ] Test `test-upload-progress.html` - Upload works
- [ ] Test `test-public-download.html` - Download works

### After Deployment

- [ ] Test API endpoints in production
- [ ] Verify database constraints
- [ ] Test file uploads
- [ ] Test file downloads
- [ ] Check logs for errors

---

## 🔧 Configuration

### Environment Variables

Make sure `.env` is configured:

```env
# API Credentials
EXTERNAL_API_EMAIL=admin@example.com
EXTERNAL_API_PASSWORD=your_password

# App URL
APP_URL=http://localhost:8000

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=location_server
```

---

## 📊 Test Results

### Expected Results

#### test-api-login.php
```
HTTP Code: 200
✓ Found: object.access_token
```

#### test-check-location.php
```
Users with multiple locations: 30
Users with single location: 26
✓✓✓ SUCCESS!
```

#### check-constraint.php
```
✓ NEW CONSTRAINT FOUND: users_email_location_unique
✓✓✓ SUCCESS! Same email can be used in different locations!
```

---

## 🐛 Troubleshooting

### Issue: "Class not found"
**Solution**: Run `composer install`

### Issue: "Database connection failed"
**Solution**: Check `.env` database configuration

### Issue: "API login failed"
**Solution**: Check `EXTERNAL_API_EMAIL` and `EXTERNAL_API_PASSWORD` in `.env`

### Issue: "File not found"
**Solution**: Make sure you're running from project root

---

## 📚 Related Documentation

- [Testing Guide](../docs/testing/TESTING_GUIDE.md) - Complete testing guide
- [API Documentation](../docs/api/API_DOCUMENTATION.md) - API reference
- [Troubleshooting](../docs/guides/TROUBLESHOOTING.md) - Common issues

---

## 🆘 Need Help?

1. Check logs: `storage/logs/laravel.log`
2. Run with verbose: `php -d display_errors=1 testing/test-script.php`
3. Contact development team

---

**Last Updated**: November 8, 2025
