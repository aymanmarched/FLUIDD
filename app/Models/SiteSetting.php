<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    protected $fillable = [
        'company_name',
        'logo',

        'footer_address_line1',
        'footer_address_line2',
        'footer_city',
        'footer_country',
        'footer_map_embed_url',
        'footer_email',
        'footer_phone',
    ];

    public function socialLinks()
    {
        return $this->hasMany(\App\Models\SiteSocialLink::class, 'site_setting_id')
            ->orderBy('sort_order');
    }
}