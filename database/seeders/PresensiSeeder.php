<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\User;
use Carbon\Carbon;

class PresensiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil semua user yang rolenya 'guru' atau 'kepsek'
        $users = User::whereIn('role', ['guru', 'kepsek'])->get();

        // Tanggal rentang 4 s/d 8 Agustus 2025 (inklusif)
        $startDate = Carbon::create(2025, 8, 4);
        $endDate   = Carbon::create(2025, 8, 9);

        $statuses = ['hadir', 'hadir-dl', 'tidak-hadir', 'hadir-tidak-lapor-pulang'];

        foreach ($users as $user) {
            $date = $startDate->copy();

            while ($date->lte($endDate)) {
                $status = $statuses[array_rand($statuses)];

                // Default semua null
                $jamMasuk = null;
                $jamKeluar = null;
                $latMasuk = null;
                $longMasuk = null;
                $latKeluar = null;
                $longKeluar = null;

                if ($status !== 'tidak-hadir') {
                    // Set data masuk
                    $jamMasuk  = $this->getRandomTime('07:00', '08:30');
                    $latMasuk  = '-0.5315' . rand(100, 999);
                    $longMasuk = '101.450' . rand(100, 999);

                    // Untuk 'hadir' atau 'hadir-dl', isi data pulang
                    if (in_array($status, ['hadir', 'hadir-dl'], true)) {
                        $jamKeluar  = $this->getRandomTime('15:00', '17:00');
                        $latKeluar  = '-0.5315' . rand(100, 999);
                        $longKeluar = '101.450' . rand(100, 999);
                    }
                    // Untuk 'hadir-tidak-lapor-pulang', biarkan keluar = null (sesuai permintaan)
                }
                // Jika 'tidak-hadir', semua tetap null (sesuai permintaan)

                DB::table('presensi')->insert([
                    'id'               => (string) Str::uuid(),
                    'user_id'          => $user->id,
                    'tanggal'          => $date->toDateString(),
                    'jam_masuk'        => $jamMasuk,
                    'jam_keluar'       => $jamKeluar,
                    'status'           => $status,
                    'latitude_masuk'   => $latMasuk,
                    'longitude_masuk'  => $longMasuk,
                    'latitude_keluar'  => $latKeluar,
                    'longitude_keluar' => $longKeluar,
                    'created_at'       => now(),
                    'updated_at'       => now(),
                ]);

                $date->addDay();
            }
        }
    }

    /**
     * Get random time between two hours.
     */
    private function getRandomTime(string $start, string $end): string
    {
        $startTime = strtotime($start);
        $endTime   = strtotime($end);
        return date('H:i:s', rand($startTime, $endTime));
    }
}
