<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class MimeTypeServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Register APK MIME type
        $mime = \Illuminate\Http\Testing\MimeType::from('apk');
        if (!$mime) {
            \Illuminate\Http\Testing\MimeType::$mimes['apk'] = 'application/vnd.android.package-archive';
        }
    }
}
