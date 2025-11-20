# Swagger Customization Guide

## Mengubah Informasi Dasar API

Edit file `app/Http/Controllers/Controller.php`:

```php
/**
 * @OA\Info(
 *     title="Nama API Kamu",
 *     version="2.0.0",
 *     description="Deskripsi lengkap API kamu",
 *     @OA\Contact(
 *         email="your-email@company.com",
 *         name="Your Name"
 *     ),
 *     @OA\License(
 *         name="MIT",
 *         url="https://opensource.org/licenses/MIT"
 *     )
 * )
 */
```

## Mengubah Server URL

### Option 1: Via Environment Variable
Tambahkan di `.env`:
```env
L5_SWAGGER_CONST_HOST=https://your-production-domain.com
```

### Option 2: Multiple Servers
Edit `Controller.php`:
```php
/**
 * @OA\Server(
 *     url="http://localhost",
 *     description="Local Development"
 * )
 * @OA\Server(
 *     url="https://staging.example.com",
 *     description="Staging Server"
 * )
 * @OA\Server(
 *     url="https://api.example.com",
 *     description="Production Server"
 * )
 */
```

## Menambah Security Schemes

### Bearer Token
```php
/**
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 *     description="Enter JWT token"
 * )
 */
```

Gunakan di endpoint:
```php
/**
 * @OA\Get(
 *     path="/api/endpoint",
 *     security={{"bearerAuth": {}}},
 *     ...
 * )
 */
```

### Basic Auth
```php
/**
 * @OA\SecurityScheme(
 *     securityScheme="basicAuth",
 *     type="http",
 *     scheme="basic"
 * )
 */
```

### OAuth2
```php
/**
 * @OA\SecurityScheme(
 *     securityScheme="oauth2",
 *     type="oauth2",
 *     @OA\Flow(
 *         flow="authorizationCode",
 *         authorizationUrl="https://example.com/oauth/authorize",
 *         tokenUrl="https://example.com/oauth/token",
 *         scopes={
 *             "read": "Read access",
 *             "write": "Write access"
 *         }
 *     )
 * )
 */
```

## Menambah Tags untuk Grouping

Di setiap endpoint, gunakan tags:
```php
/**
 * @OA\Get(
 *     path="/api/users",
 *     tags={"User Management"},
 *     ...
 * )
 */
```

Untuk menambah deskripsi tag, tambahkan di `Controller.php`:
```php
/**
 * @OA\Tag(
 *     name="User Management",
 *     description="Operations related to user management"
 * )
 * @OA\Tag(
 *     name="Location",
 *     description="Location checking and management"
 * )
 */
```

## Response Schema yang Reusable

### Define Schema
```php
/**
 * @OA\Schema(
 *     schema="User",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="John Doe"),
 *     @OA\Property(property="email", type="string", format="email", example="john@example.com")
 * )
 */
```

### Use Schema
```php
/**
 * @OA\Response(
 *     response=200,
 *     description="Success",
 *     @OA\JsonContent(ref="#/components/schemas/User")
 * )
 */
```

## Request Body yang Reusable

### Define Request Body
```php
/**
 * @OA\RequestBody(
 *     request="UserRequest",
 *     required=true,
 *     @OA\JsonContent(
 *         required={"name", "email"},
 *         @OA\Property(property="name", type="string", example="John Doe"),
 *         @OA\Property(property="email", type="string", format="email", example="john@example.com")
 *     )
 * )
 */
```

### Use Request Body
```php
/**
 * @OA\Post(
 *     path="/api/users",
 *     requestBody={"$ref": "#/components/requestBodies/UserRequest"},
 *     ...
 * )
 */
```

## Pagination Response

```php
/**
 * @OA\Response(
 *     response=200,
 *     description="Paginated list",
 *     @OA\JsonContent(
 *         @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/User")),
 *         @OA\Property(property="current_page", type="integer", example=1),
 *         @OA\Property(property="per_page", type="integer", example=15),
 *         @OA\Property(property="total", type="integer", example=100),
 *         @OA\Property(property="last_page", type="integer", example=7)
 *     )
 * )
 */
```

## File Upload Endpoint

```php
/**
 * @OA\Post(
 *     path="/api/upload",
 *     summary="Upload file",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\MediaType(
 *             mediaType="multipart/form-data",
 *             @OA\Schema(
 *                 @OA\Property(
 *                     property="file",
 *                     type="string",
 *                     format="binary",
 *                     description="File to upload"
 *                 ),
 *                 @OA\Property(
 *                     property="description",
 *                     type="string",
 *                     description="File description"
 *                 )
 *             )
 *         )
 *     ),
 *     @OA\Response(response=200, description="File uploaded successfully")
 * )
 */
```

## Path Parameters

```php
/**
 * @OA\Get(
 *     path="/api/users/{id}",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="User ID",
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\Response(response=200, description="Success")
 * )
 */
```

## Query Parameters dengan Multiple Values

```php
/**
 * @OA\Get(
 *     path="/api/users",
 *     @OA\Parameter(
 *         name="roles[]",
 *         in="query",
 *         description="Filter by roles",
 *         required=false,
 *         @OA\Schema(
 *             type="array",
 *             @OA\Items(type="string", enum={"admin", "user", "moderator"})
 *         )
 *     ),
 *     @OA\Response(response=200, description="Success")
 * )
 */
```

## Enum Values

```php
/**
 * @OA\Property(
 *     property="status",
 *     type="string",
 *     enum={"active", "inactive", "pending"},
 *     example="active"
 * )
 */
```

## Nested Objects

```php
/**
 * @OA\JsonContent(
 *     @OA\Property(property="user", type="object",
 *         @OA\Property(property="name", type="string", example="John"),
 *         @OA\Property(property="address", type="object",
 *             @OA\Property(property="street", type="string", example="123 Main St"),
 *             @OA\Property(property="city", type="string", example="Jakarta")
 *         )
 *     )
 * )
 */
```

## Array of Objects

```php
/**
 * @OA\JsonContent(
 *     @OA\Property(
 *         property="users",
 *         type="array",
 *         @OA\Items(
 *             @OA\Property(property="id", type="integer", example=1),
 *             @OA\Property(property="name", type="string", example="John")
 *         )
 *     )
 * )
 */
```

## Deprecated Endpoint

```php
/**
 * @OA\Get(
 *     path="/api/old-endpoint",
 *     deprecated=true,
 *     summary="This endpoint is deprecated",
 *     description="Use /api/new-endpoint instead",
 *     ...
 * )
 */
```

## External Documentation

```php
/**
 * @OA\Get(
 *     path="/api/endpoint",
 *     @OA\ExternalDocumentation(
 *         description="Find more info here",
 *         url="https://docs.example.com/api"
 *     ),
 *     ...
 * )
 */
```

## Customize Swagger UI

Edit `config/l5-swagger.php`:

```php
'defaults' => [
    'routes' => [
        'docs' => 'docs',
        'oauth2_callback' => 'api/oauth2-callback',
        'middleware' => [
            'api' => [],
            'asset' => [],
            'docs' => [],
            'oauth2_callback' => [],
        ],
    ],
    'paths' => [
        'docs' => storage_path('api-docs'),
        'views' => base_path('resources/views/vendor/l5-swagger'),
        'base' => env('L5_SWAGGER_BASE_PATH', null),
        'swagger_ui_assets_path' => env('L5_SWAGGER_UI_ASSETS_PATH', 'vendor/swagger-api/swagger-ui/dist/'),
        'excludes' => [],
    ],
],
```

## Tips

1. **Gunakan Schema untuk reusability** - Define sekali, pakai berkali-kali
2. **Group dengan Tags** - Mudah navigasi di Swagger UI
3. **Tambahkan Examples** - Memudahkan testing
4. **Dokumentasikan Error Responses** - User tahu apa yang expect
5. **Keep it Updated** - Regenerate setiap ada perubahan

## Regenerate After Changes

Setelah melakukan perubahan apapun:
```bash
php artisan l5-swagger:generate
```
