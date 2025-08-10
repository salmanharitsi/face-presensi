<?php

namespace App\Jobs;

use App\Models\User;
use App\Models\Presensi;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class StatusTidakHadirJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Lewati eksekusi jika hari ini Minggu
        if (Carbon::now()->isSunday()) {
            return;
        }

        $today = Carbon::today();
        $batasWaktu = $today->copy()->setTime(7, 15, 0);

        // Ambil semua user kecuali yang role-nya 'admin'
        $users = User::where('role', '!=', 'admin')->get();

        foreach ($users as $user) {
            // Cek apakah user belum memiliki presensi hari ini
            $sudahPresensi = $user->presensi()
                ->whereDate('created_at', $today)
                ->exists();

            // Jika belum presensi dan waktu sekarang sudah lebih dari jam 07:15
            if (!$sudahPresensi && now()->greaterThanOrEqualTo($batasWaktu)) {
                Presensi::create([
                    'user_id' => $user->id,
                    'tanggal' => $today,
                    'jam_masuk' => null,
                    'jam_keluar' => null,
                    'status' => 'tidak-hadir',
                ]);
            }
        }
    }
}
