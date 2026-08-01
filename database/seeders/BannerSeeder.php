<?php

namespace Database\Seeders;

use App\Models\Banner;
use Illuminate\Database\Seeder;

class BannerSeeder extends Seeder
{
    public function run(): void
    {
        $banners = [
            [
                'title' => 'New Summer Collection 2024',
                'description' => 'Explore our latest arrivals in Fiction and Mystery. Get up to 20% off on your first purchase.',
                'image' => 'banners/banner1.webp',
                'link' => '/books',
                'position' => 'home',
                'order' => 1,
                'status' => true,
            ],
            [
                'title' => 'Master Your Craft',
                'description' => 'A curated selection of technical and self-improvement books for professional growth.',
                'image' => 'banners/banner2.webp',
                'link' => '/categories',
                'position' => 'home',
                'order' => 2,
                'status' => true,
            ],
            [
                'title' => 'Join Our Reading Community',
                'description' => 'Sign up today and get exclusive access to our weekly book club and author interviews.',
                'image' => 'banners/banner3.webp',
                'link' => '/register',
                'position' => 'home',
                'order' => 3,
                'status' => true,
            ],
        ];

        foreach ($banners as $banner) {
            Banner::create($banner);
        }
    }
}
