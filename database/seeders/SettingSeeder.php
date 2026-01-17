<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Setting;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('settings')->truncate();

        $settings = [
            [
                'key' => 'library_name',
                'value' => 'Perpustakaan Digital Nusantara',
            ],
            [
                'key' => 'library_address',
                'value' => 'Jl. Pendidikan No. 123, Kota Buku, Indonesia',
            ],
            [
                'key' => 'library_phone',
                'value' => '(021) 1234-5678',
            ],
            [
                'key' => 'library_email',
                'value' => 'info@perpustakaandigital.com',
            ],
            [
                'key' => 'max_borrow_days',
                'value' => '14',
            ],
            [
                'key' => 'late_fee_per_day',
                'value' => '1000',
            ],
        ];

        foreach ($settings as $setting) {
            Setting::create($setting);
        }

        $this->command->info('Setting seeder berhasil: 6 settings created!');
    }
}