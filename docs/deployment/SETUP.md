# Location Server - Setup Guide

## Prerequisites

- PHP 8.1 or higher
- Composer
- MySQL 8.0 or higher
- Node.js and NPM (for frontend assets)

## Installation Steps

### 1. Database Setup

Create a MySQL database for the application:

```sql
CREATE DATABASE location_server CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

You can create this database using:
- **MySQL Workbench**: Connect to your MySQL server and run the SQL command above
- **phpMyAdmin**: Use the "New" button to create a database named `location_server`
- **Command Line**: Run `mysql -u root -p` and execute the SQL command

### 2. Environment Configuration

The `.env` file has been configured with the following database settings:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=location_server
DB_USERNAME=root
DB_PASSWORD=
```

**Important**: Update the `DB_PASSWORD` in your `.env` file if your MySQL root user has a password.

### 3. Verify Installation

Test the database connection:

```bash
php artisan migrate:status
```

If the connection is successful, you're ready to proceed with the next tasks (migrations and seeders).

## Next Steps

After completing this setup:
1. Run migrations to create database tables (Task 2)
2. Run seeders to populate initial data (Task 3)
3. Start the development server: `php artisan serve`

## Troubleshooting

### Database Connection Error

If you get a connection error:
1. Verify MySQL is running
2. Check the database name exists: `location_server`
3. Verify username and password in `.env` file
4. Ensure MySQL is listening on port 3306

### Permission Issues

If you encounter permission errors:
```bash
chmod -R 775 storage bootstrap/cache
```

On Windows, ensure the `storage` and `bootstrap/cache` directories are writable.
