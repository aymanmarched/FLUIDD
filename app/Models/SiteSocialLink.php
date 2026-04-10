<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteSocialLink extends Model
{
    protected $fillable = [
        'site_setting_id',
        'name',
        'url',
        'color',
        'icon_svg',
        'sort_order',
        'is_enabled',
    ];

    protected $casts = [
        'is_enabled' => 'boolean',
    ];

    public function settings()
    {
        return $this->belongsTo(SiteSetting::class, 'site_setting_id');
    }
}