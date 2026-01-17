<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Author;

class AuthorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('authors')->truncate();

        $authors = [
            [
                'name' => 'Andrea Hirata',
                'biography' => 'Penulis Indonesia terkenal dengan serial novel Laskar Pelangi yang telah diterjemahkan ke berbagai bahasa.',
                'photo' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/0/0d/Andrea_Hirata.jpg/220px-Andrea_Hirata.jpg',
                'nationality' => 'Indonesia',
                'birth_year' => 1967,
            ],
            [
                'name' => 'Tere Liye',
                'biography' => 'Penulis produktif Indonesia dengan karya-karya bestseller seperti Hafalan Shalat Delisa, Bumi, dan Rindu.',
                'photo' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/6/6d/Tere_Liye.jpg/220px-Tere_Liye.jpg',
                'nationality' => 'Indonesia',
                'birth_year' => 1979,
            ],
            [
                'name' => 'Pramoedya Ananta Toer',
                'biography' => 'Sastrawan Indonesia terkenal dengan karya Tetralogi Buru yang diakui secara internasional.',
                'photo' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/1/1f/Pramudya_Ananta_Toer_cropped.jpg/220px-Pramudya_Ananta_Toer_cropped.jpg',
                'nationality' => 'Indonesia',
                'birth_year' => 1925,
            ],
            [
                'name' => 'J.K. Rowling',
                'biography' => 'Penulis Inggris terkenal dengan seri Harry Potter yang menjadi fenomena dunia.',
                'photo' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/5/5d/J._K._Rowling_2010.jpg/220px-J._K._Rowling_2010.jpg',
                'nationality' => 'Inggris',
                'birth_year' => 1965,
            ],
            [
                'name' => 'Stephen King',
                'biography' => 'Penulis horor, fiksi supernatural, fantasi, dan fiksi ilmiah Amerika yang sangat produktif.',
                'photo' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/e/e3/Stephen_King%2C_Comicon.jpg/220px-Stephen_King%2C_Comicon.jpg',
                'nationality' => 'Amerika Serikat',
                'birth_year' => 1947,
            ],
        ];

        foreach ($authors as $author) {
            Author::create($author);
        }

        $this->command->info('Author seeder berhasil: 5 authors created!');
    }
}