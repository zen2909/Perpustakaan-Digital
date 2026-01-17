<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('categories')->truncate();

        $categories = [
            [
                'name' => 'Fiksi',
                'slug' => 'fiksi',
                'description' => 'Buku-buku fiksi termasuk novel, cerpen, dan karya sastra imajinatif lainnya',
            ],
            [
                'name' => 'Non-Fiksi',
                'slug' => 'non-fiksi',
                'description' => 'Buku-buku berdasarkan fakta dan data nyata seperti biografi, sains, dan sejarah',
            ],
            [
                'name' => 'Sains & Teknologi',
                'slug' => 'sains-teknologi',
                'description' => 'Buku tentang ilmu pengetahuan, teknologi, matematika, dan penelitian',
            ],
            [
                'name' => 'Pendidikan',
                'slug' => 'pendidikan',
                'description' => 'Buku pelajaran, referensi pendidikan, dan materi pembelajaran',
            ],
            [
                'name' => 'Anak & Remaja',
                'slug' => 'anak-remaja',
                'description' => 'Buku cerita anak, dongeng, dan literatur untuk remaja',
            ],
            [
                'name' => 'Agama & Spiritual',
                'slug' => 'agama-spiritual',
                'description' => 'Buku tentang agama, spiritualitas, dan kepercayaan',
            ],
            [
                'name' => 'Bisnis & Ekonomi',
                'slug' => 'bisnis-ekonomi',
                'description' => 'Buku tentang manajemen, keuangan, kewirausahaan, dan ekonomi',
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }

        $this->command->info('Category seeder berhasil: 7 categories created!');
    }
}