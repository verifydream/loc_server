# Swagger Commands - Quick Reference

## Generate/Regenerate Documentation

Setiap kali kamu update anotasi Swagger di controller:

```bash
php artisan l5-swagger:generate
```

## Clear Cache

Jika perubahan tidak muncul:

```bash
php artisan config:clear
php artisan cache:clear
php artisan l5-swagger:generate
```

## View Routes

Lihat route Swagger yang tersedia:

```bash
php artisan route:list --path=api/documentation
```

## Publish Config (jika perlu customize)

Jika kamu sudah pernah publish dan ingin update config:

```bash
php artisan vendor:publish --provider "L5Swagger\L5SwaggerServiceProvider" --force
```

## Check Generated Files

File dokumentasi yang di-generate:

```bash
# Windows
dir storage\api-docs

# Linux/Mac
ls -la storage/api-docs/
```

File yang di-generate:
- `api-docs.json` - OpenAPI spec dalam format JSON
- `api-docs.yaml` - OpenAPI spec dalam format YAML

## Environment Variables

Tambahkan di `.env` untuk kustomisasi:

```env
# Set base URL untuk API
L5_SWAGGER_CONST_HOST=http://localhost

# Use absolute path for assets
L5_SWAGGER_USE_ABSOLUTE_PATH=true

# Format documentation (json or yaml)
L5_FORMAT_TO_USE_FOR_DOCS=json
```

## Anotasi Swagger Cheat Sheet

### Basic Endpoint
```php
/**
 * @OA\Get(
 *     path="/api/endpoint",
 *     summary="Short description",
 *     tags={"Category"},
 *     @OA\Response(response=200, description="Success")
 * )
 */
```

### With Request Body
```php
/**
 * @OA\Post(
 *     path="/api/endpoint",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             @OA\Property(property="field", type="string", example="value")
 *         )
 *     ),
 *     @OA\Response(response=200, description="Success")
 * )
 */
```

### With Query Parameters
```php
/**
 * @OA\Get(
 *     path="/api/endpoint",
 *     @OA\Parameter(
 *         name="param",
 *         in="query",
 *         required=true,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Response(response=200, description="Success")
 * )
 */
```

### With Security
```php
/**
 * @OA\Get(
 *     path="/api/endpoint",
 *     security={{"apikey": {}}},
 *     @OA\Response(response=200, description="Success")
 * )
 */
```

## Troubleshooting

### Error: "Failed to load API definition"
```bash
php artisan l5-swagger:generate
php artisan config:clear
```

### Error: "Swagger UI assets not found"
```bash
composer require swagger-api/swagger-ui
php artisan vendor:publish --provider "L5Swagger\L5SwaggerServiceProvider" --force
```

### Permission denied on storage/api-docs
```bash
# Windows (run as Administrator)
icacls storage\api-docs /grant Users:F /T

# Linux/Mac
chmod -R 775 storage/api-docs
chown -R www-data:www-data storage/api-docs
```

## Import to Postman

1. Generate documentation: `php artisan l5-swagger:generate`
2. Download file: `storage/api-docs/api-docs.json`
3. Di Postman: Import → Upload Files → Select `api-docs.json`
4. Semua endpoints akan ter-import otomatis!

## Useful Links

- Swagger UI: `http://your-domain.com/api/documentation`
- JSON Spec: `http://your-domain.com/docs/api-docs.json`
- YAML Spec: `http://your-domain.com/docs/api-docs.yaml`
