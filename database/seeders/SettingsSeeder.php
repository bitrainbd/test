<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            // General
            ['key' => 'site_title',       'value' => 'BitRain',              'type' => 'text',    'group' => 'general'],
            ['key' => 'site_description', 'value' => "BitRain is a comprehensive learning and knowledge-sharing platform founded by Barat Ahmed, designed to empower students, educators, and creators. Explore interactive MCQ exams, engaging quizzes, insightful blogs, free and premium courses, and project-based learning opportunities—all in one place. Whether you're preparing for exams, building skills, or launching your next project, BitRain provides the tools and resources to help you succeed.",                     'type' => 'text',    'group' => 'general'],
            ['key' => 'site_url',         'value' => config('app.url'),      'type' => 'text',    'group' => 'general'],
            ['key' => 'site_language',    'value' => 'en',                   'type' => 'text',    'group' => 'general'],
            ['key' => 'theme_color',      'value' => '#1a1a1a',              'type' => 'text',    'group' => 'general'],
 
            // Appearance / files
            ['key' => 'favicon',          'value' => null,                   'type' => 'file',    'group' => 'appearance'],
            ['key' => 'logo',             'value' => null,                   'type' => 'file',    'group' => 'appearance'],
            ['key' => 'logo_dark',        'value' => null,                   'type' => 'file',    'group' => 'appearance'],
            ['key' => 'og_image',         'value' => null,                   'type' => 'file',    'group' => 'appearance'],
 
            // SEO
            ['key' => 'meta_title',       'value' => 'BitRain - Dive into Technology', 'type' => 'text',    'group' => 'seo'],
            ['key' => 'meta_description', 'value' => "BitRain is a comprehensive learning and knowledge-sharing platform founded by Barat Ahmed. Explore MCQ exams, interactive quizzes, educational blogs, free and premium courses, and project-based learning opportunities to enhance your skills and achieve your goals.",  
                                                                                       'type' => 'text',    'group' => 'seo'],
            ['key' => 'meta_keywords',    'value' => '["BitRain","online learning","education platform","MCQ exams","online quizzes","free courses","paid courses","educational blogs","e-learning","skill development","project-based learning","online education","exam preparation","student resources","Barat Ahmed","learning management system","online tests","practice exams","digital learning","knowledge sharing"]', 
                                                                                        'type' => 'json',    'group' => 'seo'],
            ['key' => 'canonical_url',    'value' => 'https://bitrainbd.com',           'type' => 'text',    'group' => 'seo'],
            ['key' => 'meta_author',      'value' => 'Barat Ahmed',                     'type' => 'text',    'group' => 'seo'],
            ['key' => 'robots',           'value' => 'index,follow',         'type' => 'text',    'group' => 'seo'],
            ['key' => 'google_verify',    'value' => '',                     'type' => 'text',    'group' => 'seo'],
            ['key' => 'bing_verify',      'value' => '',                     'type' => 'text',    'group' => 'seo'],
            ['key' => 'enable_sitemap',   'value' => '1',                    'type' => 'boolean', 'group' => 'seo'],
            ['key' => 'enable_jsonld',    'value' => '0',                    'type' => 'boolean', 'group' => 'seo'],
 
            // Social
            ['key' => 'og_title',         'value' => 'BitRain - Dive into Technology',  'type' => 'text',    'group' => 'social'],
            ['key' => 'og_description',   'value' => "BitRain is a comprehensive learning and knowledge-sharing platform founded by Barat Ahmed. Explore MCQ exams, quizzes, educational blogs, free and premium courses, and project-based learning opportunities—all in one place.", 
                                                                            'type' => 'text',    'group' => 'social'],

            ['key' => 'og_type',          'value' => 'website',              'type' => 'text',    'group' => 'social'],
            ['key' => 'twitter_card',     'value' => 'summary_large_image',  'type' => 'text',    'group' => 'social'],
            ['key' => 'twitter_handle',   'value' => '@baratahmed',          'type' => 'text',    'group' => 'social'],
            ['key' => 'social_links',     'value' => json_encode([
                'facebook'  => 'https://www.facebook.com/bitrainbd',
                'twitter'   => 'https://x.com/baratahmed',
                'instagram' => 'https://www.instagram.com/baratbitrain/',
                'linkedin'  => 'https://www.linkedin.com/in/barat-ahmed-037466379/',
                'youtube'   => 'https://www.youtube.com/@BitRainBD',
                'github'    => 'https://www.github.com/baratahmed',
            ]),                                                              'type' => 'json',    'group' => 'social'],
        ];
 
        foreach ($settings as $setting) {
            DB::table('settings')->updateOrInsert(
                ['key' => $setting['key']],
                array_merge($setting, [
                    'created_at' => now(),
                    'updated_at' => now(),
                ])
            );
        }
    }
}