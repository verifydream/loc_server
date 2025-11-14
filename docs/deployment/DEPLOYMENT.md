# Location Server - Deployment Guide

This guide provides step-by-step instructions for deploying the Location Server application.

## Prerequisites

Before deploying, ensure you have:

- PHP 8.1 or higher
- Composer
- MySQL 8.0 or higher
- Web server (Apache/Nginx)
- Node.js and NPM (for frontend assets)

## Environment Setup

### 1. Clone and Configure Environment

```bash
# Clone the repository (if applicable)
git clone <repository-url>
cd location-server

# Copy environment file
cp .env.example .env
```

### 2. Configure Environment Variables

Edit the `.env` file and update the following critical settings:

```bash
# Application Settings
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com

# Database Configuration
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=location_server
DB_USERNAME=your_db_username
DB_PASSWORD=your_secure_password

# Session Configuration
SESSION_LIFETIME=120

# Admin Credentials (for initial seeding)
ADMIN_DEFAULT_NAME="Admin"
ADMIN_DEFAULT_EMAIL=admin@yourdomain.com
ADMIN_DEFAULT_PASSWORD=your_secure_password

# API Rate Limiting
API_RATE_LIMIT=60
```

**Important Security Notes:**
- Set `APP_DEBUG=false` in production
- Use strong passwords for database and admin accounts
- Change default admin credentials immediately after first login

## Installation Steps

### 1. Install Dependencies

```bash
# Install PHP dependencies
composer install --optimize-autoloader --no-dev

# Install Node dependencies (if using Vite for assets)
npm install
npm run build
```

### 2. Generate Application Key

```bash
php artisan key:generate
```

This command generates a unique encryption key for your application.

### 3. Create Database

Create the MySQL database specified in your `.env` file:

```sql
CREATE DATABASE location_server CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 4. Run Database Migrations

Execute migrations to create all required database tables:

```bash
php artisan migrate --force
```

The `--force` flag is required in production environment.

**Tables Created:**
- `locations` - Stores location/branch information
- `users` - Stores user email and location mappings
- `admins` - Stores admin user credentials

### 5. Seed Initial Data

Run database seeders to populate initial data:

```bash
php artisan db:seed --force
```

This will create:

**Default Locations:**
- Surabaya (sby, sby.web.com)
- Jakarta (jkt, jkt.web.com)
- Belawan (blw, blw.web.com)
- Semarang (smr, smr.web.com)
- BNS (bns, bns.web.com)
- Java (java, java.web.com)
- Test (test, test.web.com)
- Dev (dev, dev.web.com)

**Default Admin User:**
- Email: As specified in `ADMIN_DEFAULT_EMAIL`
- Password: As specified in `ADMIN_DEFAULT_PASSWORD`

**Note:** Seeders are idempotent - they check for existing data before inserting, so it's safe to run them multiple times.

### 6. Optimize Application

Run optimization commands for better performance:

```bash
# Cache configuration
php artisan config:cache

# Cache routes
php artisan route:cache

# Cache views
php artisan view:cache

# Optimize autoloader
composer dump-autoload --optimize
```

### 7. Set File Permissions

Ensure proper permissions for storage and cache directories:

```bash
# Linux/Unix
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

# Or if using a different user
chown -R your-web-user:your-web-group storage bootstrap/cache
```

## Web Server Configuration

### Apache Configuration

Create a virtual host configuration:

```apache
<VirtualHost *:80>
    ServerName your-domain.com
    DocumentRoot /path/to/location-server/public

    <Directory /path/to/location-server/public>
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/location-server-error.log
    CustomLog ${APACHE_LOG_DIR}/location-server-access.log combined
</VirtualHost>
```

Enable required modules:
```bash
a2enmod rewrite
systemctl restart apache2
```

### Nginx Configuration

```nginx
server {
    listen 80;
    server_name your-domain.com;
    root /path/to/location-server/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

## Post-Deployment Tasks

### 1. Verify Installation

Test the API endpoint:

```bash
curl -X POST https://your-domain.com/api/check-location \
  -H "Content-Type: application/json" \
  -d '{"email":"test@example.com"}'
```

### 2. Access Admin Dashboard

1. Navigate to `https://your-domain.com/admin/login`
2. Login with the admin credentials from your `.env` file
3. **Immediately change the default password** after first login

### 3. Configure SSL/HTTPS

For production, always use HTTPS. Use Let's Encrypt for free SSL certificates:

```bash
# Install Certbot
apt-get install certbot python3-certbot-apache  # For Apache
# or
apt-get install certbot python3-certbot-nginx   # For Nginx

# Obtain certificate
certbot --apache -d your-domain.com  # For Apache
# or
certbot --nginx -d your-domain.com   # For Nginx
```

## Updating the Application

When deploying updates:

```bash
# 1. Enable maintenance mode
php artisan down

# 2. Pull latest changes (if using git)
git pull origin main

# 3. Update dependencies
composer install --optimize-autoloader --no-dev
npm install && npm run build

# 4. Run migrations (if any new migrations)
php artisan migrate --force

# 5. Clear and rebuild caches
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 6. Disable maintenance mode
php artisan up
```

## Troubleshooting

### Clear All Caches

If you encounter issues after deployment:

```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
php artisan optimize:clear
```

### Reset and Reseed Database

**Warning:** This will delete all data!

```bash
php artisan migrate:fresh --seed --force
```

### Check Logs

Application logs are stored in `storage/logs/laravel.log`:

```bash
tail -f storage/logs/laravel.log
```

### Permission Issues

If you encounter permission errors:

```bash
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

## Monitoring and Maintenance

### Regular Tasks

1. **Monitor Logs**: Regularly check `storage/logs/laravel.log` for errors
2. **Database Backups**: Set up automated daily backups of the MySQL database
3. **Update Dependencies**: Regularly update Composer and NPM packages for security patches

### Performance Monitoring

Monitor these metrics:
- API response time (should be < 500ms)
- Database query performance
- Server resource usage (CPU, memory, disk)

### Security Checklist

- [ ] `APP_DEBUG=false` in production
- [ ] Strong database passwords
- [ ] Default admin password changed
- [ ] HTTPS/SSL enabled
- [ ] File permissions properly set
- [ ] Regular security updates applied
- [ ] Database backups configured
- [ ] Rate limiting enabled on API endpoints

## Support

For issues or questions:
1. Check application logs in `storage/logs/`
2. Review Laravel documentation: https://laravel.com/docs
3. Contact your development team

## Quick Reference

### Common Commands

```bash
# View routes
php artisan route:list

# Run specific seeder
php artisan db:seed --class=LocationSeeder
php artisan db:seed --class=AdminSeeder

# Create new admin user manually
php artisan tinker
>>> App\Models\Admin::create(['name' => 'New Admin', 'email' => 'admin@example.com', 'password' => bcrypt('password')]);

# Check application status
php artisan about

# Clear specific cache
php artisan cache:forget key_name
```

### Environment-Specific Commands

**Development:**
```bash
php artisan serve  # Start development server
php artisan migrate:fresh --seed  # Reset database with fresh data
```

**Production:**
```bash
php artisan migrate --force  # Run migrations
php artisan optimize  # Optimize application
php artisan config:cache  # Cache configuration
```
