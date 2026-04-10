<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SiteSetting;
use App\Models\HomeSection;

class HomePageSeeder extends Seeder
{
    public function run(): void
    {
        SiteSetting::firstOrCreate([], [
            'company_name' => 'Climatisation',
            'footer_address' => "123 Anywhere St.\nAny City, State\nAny Country\n(+123) 456 7890",
            'footer_map_embed' => null,
            'footer_phone' => '0612345678',
            'footer_email' => 'hello@reallygreatsite.com',
            'social_links' => [
                ['name' => 'Facebook', 'url' => 'https://facebook.com', 'icon' => 'facebook'],
                ['name' => 'Instagram', 'url' => 'https://instagram.com', 'icon' => 'instagram'],
                ['name' => 'Twitter', 'url' => 'https://twitter.com', 'icon' => 'twitter'],
            ],
        ]);

        // HERO
        HomeSection::firstOrCreate(['key' => 'hero_1'], [
            'type' => 'hero',
            'position' => 1,
            'is_enabled' => true,
            'content' => [
                'title' => 'Le premier service de maintenance thermique structuré,',
                'highlight_blue' => 'transparent et fiable au Maroc,',
                'highlight_yellow' => 'qui sécurise vos installations et réduit vos pannes.',
                'subtitle' => 'Une expertise dédiée à la performance, la sécurité et la longévité de vos installations thermiques, avec un service professionnel et constant.',
                'image' => 'images/homelg.png', // keep as public asset for now
                'work_days' => 'Lundi – Samedi',
                'work_hours' => '08:30 – 18:00',
                'buttons' => [
                    ['label' => 'Entretenir Ma Maison', 'url' => '/service/entretien/entretenir-ma-maison', 'style' => 'primary'],
                    ['label' => 'Activer Ma Garantie', 'url' => '/service/entretien/activer-ma-garantie', 'style' => 'secondary'],
                    ['label' => 'Remplacer mon machine', 'url' => '/remplacer', 'style' => 'danger'],
                ],
            ],
        ]);

        // SECTION 2 (we’ll keep your current layout but editable later)
        HomeSection::firstOrCreate(['key' => 'feature_1'], [
            'type' => 'feature',
            'position' => 2,
            'is_enabled' => true,
            'content' => [
                'badge_title' => 'Garantie 100%',
                'badge_desc' => 'Toutes nos installations et réparations sont couvertes par une garantie complète.',
                'badge_image' => 'images/garantie.png',
            ],
        ]);

        // MARQUES dynamic
        HomeSection::firstOrCreate(['key' => 'marques_1'], [
            'type' => 'marques_dynamic',
            'position' => 3,
            'is_enabled' => true,
            'content' => [
                'title' => 'Nous réparons toutes les marques',
            ],
        ]);

        // AVIS dynamic
        HomeSection::firstOrCreate(['key' => 'avis_1'], [
            'type' => 'avis_dynamic',
            'position' => 4,
            'is_enabled' => true,
            'content' => [
                'title' => 'Avis Clients',
            ],
        ]);
    }
}