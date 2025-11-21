<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'location_code',
        'location_name',
        'online_url',
        'logo',
        'photo_settings',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'photo_settings' => 'array',
    ];

    /**
     * Get the users for the location.
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }
}
