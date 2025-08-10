<?php

namespace App\Jobs;

use App\Models\Presensi;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class StatusTidakLaporPulangJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Cek jika waktu saat ini sudah lewat jam 18:00
        if (now()->greaterThanOrEqualTo(Carbon::today()->setTime(18, 0, 0))) {
            return; // Stop job jika masih sebelum jam 18:00
        }

        // Ambil semua presensi hari ini dengan jam_masuk TIDAK NULL dan jam_keluar NULL
        $today = Carbon::today();
        $presensis = Presensi::whereDate('tanggal', $today)
            ->whereNotNull('jam_masuk')
            ->whereNull('jam_keluar')
            ->get();

        // Update status menjadi 'hadir-tidak-lapor-pulang'
        foreach ($presensis as $presensi) {
            $presensi->update([
                'status' => 'hadir-tidak-lapor-pulang',
            ]);
        }
    }
}
