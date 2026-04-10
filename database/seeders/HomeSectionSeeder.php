<?php

namespace Database\Seeders;

use App\Models\HomeSection;
use Illuminate\Database\Seeder;

class HomeSectionSeeder extends Seeder
{
    public function run(): void
    {
        // HERO
        HomeSection::firstOrCreate(
            ['key' => 'hero'],
            [
                'position' => 1,
                'is_active' => true,
                'content' => [
                    'title' => 'Le premier service de maintenance thermique structuré,',
                    'highlight_1' => 'transparent et fiable au Maroc,',
                    'highlight_2' => 'qui sécurise vos installations et réduit vos pannes.',
                    'subtitle' => 'Une expertise dédiée à la performance, la sécurité et la longévité de vos installations thermiques.',
                    'work_days' => 'Lundi – Samedi',
                    'work_hours' => '08:30 – 18:00',
                    'buttons' => [
                        [
                            'label' => 'Entretenir Ma Maison',
                            'url' => '/service/entretien/entretenir-ma-maison',
                            'style' => 'primary',
                        ],
                        [
                            'label' => 'Activer Ma Garantie',
                            'url' => '/service/entretien/activer-ma-garantie',
                            'style' => 'secondary',
                        ],
                        [
                            'label' => 'Remplacer mon machine',
                            'url' => '/remplacer',
                            'style' => 'danger',
                        ],
                    ],
                ],
            ]
        );

        // FOOTER
        HomeSection::firstOrCreate(
            ['key' => 'footer'],
            [
                'position' => 99,
                'is_active' => true,
                'content' => [
                    'address' => "123 Anywhere St.\nAny City\nMorocco",
                    'phone' => '0612345678',
                    'email' => 'hello@reallygreatsite.com',
                    'map_iframe' => 'https://www.google.com/maps/embed?pb=...',
                    'socials' => [
                        [
                            'name' => 'Facebook',
                            'url' => 'https://facebook.com',
                            'icon' => 'https://cdn-icons-png.flaticon.com/512/733/733547.png',
                        ],
                        [
                            'name' => 'Instagram',
                            'url' => 'https://instagram.com',
                            'icon' => 'https://cdn-icons-png.flaticon.com/512/2111/2111463.png',
                        ],
                        [
                            'name' => 'Twitter',
                            'url' => 'https://twitter.com',
                            'icon' => 'https://cdn-icons-png.flaticon.com/512/733/733579.png',
                        ],
                    ],
                ],
            ]
        );
    }
}