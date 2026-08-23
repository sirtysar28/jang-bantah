<?php

namespace Database\Seeders;

use App\Models\Desa;
use App\Models\Kecamatan;
use Illuminate\Database\Seeder;

/**
 * Seeder data Kecamatan & Desa Kabupaten Halmahera Barat.
 * Sumber: https://id.wikipedia.org/wiki/Daftar_kecamatan_dan_kelurahan_di_Kabupaten_Halmahera_Barat
 * 9 Kecamatan, 169 Desa (sesuai data resmi).
 */
class KecamatanDesaSeeder extends Seeder
{
    public function run(): void
    {
        // [kode_wilayah, nama_kecamatan, ibukota, latitude, longitude, [desa,...]]
        $data = [
            ['kode' => '82.01.01', 'nama' => 'Jailolo', 'ibukota' => 'Gufasa', 'latitude' => 1.0514, 'longitude' => 127.5306, 'desas' => ['Acango', 'Akediri', 'Bobanehena', 'Bobo', 'Bobo Jiko', 'Buku Bualawa', 'Buku Maadu', 'Buku Matiti', 'Galala', 'Gam Lamo', 'Gamtala', 'Guaimaadu', 'Guaria', 'Gufasa', 'Hate Bicara', 'Huku-Huku Kie', 'Idam Dehe', 'Idamdehe Gamsungi', 'Jalan Baru', 'Kuripasai', 'Lolory', 'Marmabati', 'Matui', 'Pateng', 'Payo', 'Pornity', 'Saria', 'Soakonora', 'Tauro', 'Tedeng', 'Toboso', 'Todowongi', 'Tuada', 'Ulo']],
            ['kode' => '82.01.02', 'nama' => 'Loloda', 'ibukota' => 'Kedi', 'latitude' => 1.3833, 'longitude' => 127.3833, 'desas' => ['Bakun', 'Bantoli', 'Bosala', 'Buo', 'Kahatola', 'Kedi', 'Laba Besar', 'Laba Kecil', 'Linggua', 'Salu', 'Soasio', 'Tasye', 'Tolofuo', 'Tomodo', 'Totala', 'Totala Jaya', 'Tuguis']],
            ['kode' => '82.01.03', 'nama' => 'Ibu', 'ibukota' => 'Tungute Sungi', 'latitude' => 1.2167, 'longitude' => 127.6500, 'desas' => ['Ake Boso', 'Akesibu', 'Gam Ici', 'Gamlamo', 'Kampung Baru', 'Kie Ici', 'Maritango', 'Naga', 'Soana Masungi', 'Tahafo', 'Tobaol', 'Togola Sangir', 'Togola Wayoli', 'Tongute Goin', 'Tongute Ternate', 'Tongute Ternate Selatan', 'Tungute Sungi']],
            ['kode' => '82.01.04', 'nama' => 'Sahu', 'ibukota' => 'Susupu', 'latitude' => 1.1000, 'longitude' => 127.3833, 'desas' => ['Balisoan', 'Balisoan Utara', 'Dere', 'Golo', 'Goro Goro', 'Jarakore', 'Lako Akediri', 'Lako Akelamo', 'Peot', 'Ropu Tengah Balu', 'Sasur', 'Sasur Pantai', 'Susupu', 'Tacici', 'Tacim', 'Taraudu', 'Taruba', 'Todahe', 'Worat Worat']],
            ['kode' => '82.01.05', 'nama' => 'Jailolo Selatan', 'ibukota' => 'Sidangoli', 'latitude' => 0.9667, 'longitude' => 127.5333, 'desas' => ['Akeara', 'Ake Jailolo', 'Akelaha', 'Bangkit Rahmat', 'Biamaahi', 'Bobane Dano', 'Braha', 'Dodinga', 'Domato', 'Gamlenge', 'Hijrah', 'Moiso', 'Ratem', 'Rioribati', 'Sidangoli Gam', 'Sidongoli Dehe', 'Suka Damai', 'Taba Damai (DB)', 'Tataleka', 'Tewe', 'Toniku', 'Tuguraci']],
            ['kode' => '82.01.07', 'nama' => 'Tabaru', 'ibukota' => 'Duono', 'latitude' => 1.2500, 'longitude' => 127.7333, 'desas' => ['Aru Jaya', 'Barona', 'Duono', 'Goin', 'Pasalulu', 'Podol', 'Sangaji Nyeku', 'Soasangaji', 'Tengowango', 'Todoke', 'Togoreba Sungi', 'Togoreba Tua', 'Togowo', 'Tolisaor', 'Tuguis', 'Tokuoko']],
            ['kode' => '82.01.08', 'nama' => 'Ibu Selatan', 'ibukota' => 'Talaga', 'latitude' => 1.1167, 'longitude' => 127.7167, 'desas' => ['Adu', 'Baru', 'Bataka', 'Gamkonora', 'Gamsida', 'Gamsungi', 'Jere', 'Nanas', 'Ngalo Ngalo', 'Ngawet', 'Sarau', 'Talaga', 'Tobelos', 'Tobobol', 'Tosoa', 'Tuguaer']],
            ['kode' => '82.01.09', 'nama' => 'Sahu Timur', 'ibukota' => 'Akelamo', 'latitude' => 1.0667, 'longitude' => 127.4333, 'desas' => ['Air Panas', 'Akelamo', 'Aketola', 'Awer', 'Campaka', 'Gamiyel', 'Gamomeng', 'Gamsugi', 'Goal', 'Golago Kusuma', 'Hoku-hoku Gam', 'Idam Gamlamo', 'Loce', 'Ngaon', 'Sidodadi', 'Taba Campaka', 'Taraudu Kusu', 'Tibobo']],
            ['kode' => '82.01.10', 'nama' => 'Loloda Tengah', 'ibukota' => 'Barataku', 'latitude' => 1.3167, 'longitude' => 127.4000, 'desas' => ['Aruku', 'Baja', 'Bakun Pantai', 'Barataku', 'Bilote', 'Gam Kahe', 'Jange Lili', 'Jano', 'Puma Dada', 'Tosomolo']],
        ];

        foreach ($data as $k) {
            $kec = Kecamatan::firstOrCreate(
                ['kode' => $k['kode']],
                [
                    'nama'      => $k['nama'],
                    'deskripsi' => 'Ibu kota kecamatan: ' . $k['ibukota'],
                    'latitude'  => $k['latitude'],
                    'longitude' => $k['longitude'],
                    'is_active' => true,
                ]
            );

            foreach ($k['desas'] as $i => $namaDesa) {
                Desa::firstOrCreate(
                    ['kode' => $k['kode'] . '.' . str_pad((string) ($i + 1), 3, '0', STR_PAD_LEFT)],
                    [
                        'nama'          => $namaDesa,
                        'kecamatan_id'  => $kec->id,
                        'is_active'     => true,
                    ]
                );
            }
        }

        $this->command->info(sprintf(
            'KecamatanDesaSeeder: %d kecamatan & %d desa Kab. Halmahera Barat berhasil dibuat.',
            Kecamatan::count(),
            Desa::count()
        ));
    }
}
