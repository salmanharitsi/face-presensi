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

        // Tanggal rentang 4 s/d 8 Agustus 2025
        $startDate = Carbon::create(2025, 8, 4);
        $endDate = Carbon::create(2025, 8, 8);

        $statuses = ['hadir', 'hadir-dl', 'tidak-hadir', 'hadir-tidak-lapor-pulang'];

        foreach ($users as $user) {
            $date = $startDate->copy();
            while ($date->lte($endDate)) {
                DB::table('presensi')->insert([
                    'id' => Str::uuid(),
                    'user_id' => $user->id,
                    'tanggal' => $date->toDateString(),
                    'jam_masuk' => $this->getRandomTime('07:00', '08:30'),
                    'jam_keluar' => $this->getRandomTime('15:00', '17:00'),
                    'status' => $statuses[array_rand($statuses)],
                    'latitude_masuk' => '-0.5315' . rand(100, 999),  // random lat/long dummy
                    'longitude_masuk' => '101.450' . rand(100, 999),
                    'latitude_keluar' => '-0.5315' . rand(100, 999),
                    'longitude_keluar' => '101.450' . rand(100, 999),
                    'created_at' => now(),
                    'updated_at' => now(),
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
        $endTime = strtotime($end);
        return date('H:i:s', rand($startTime, $endTime));
    }
}
