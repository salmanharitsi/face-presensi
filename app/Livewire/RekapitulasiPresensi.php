<?php

namespace App\Livewire;

use App\Models\User;
use App\Models\Presensi;
use App\Exports\RekapitulasiPresensiExport;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;

class RekapitulasiPresensi extends Component
{
    public $selectedYear = '';
    public $availableYears = [];
    public $currentUserName = '';

    public function mount()
    {
        $this->currentUserName = Auth::user()->name;
        $this->loadAvailableYears();
        $this->selectedYear = $this->availableYears[0] ?? date('Y');
    }

    public function loadAvailableYears()
    {
        $this->availableYears = Presensi::selectRaw('YEAR(tanggal) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year')
            ->toArray();

        // Jika tidak ada data, tambahkan tahun sekarang
        if (empty($this->availableYears)) {
            $this->availableYears = [date('Y')];
        }
    }

    public function updatedSelectedYear()
    {
        // Refresh data when year changes - otomatis ter-handle oleh Livewire
    }

    public function downloadExcel()
    {
        try {
            $data = $this->getRekapitulasiData();
            
            return Excel::download(
                new RekapitulasiPresensiExport($data, $this->selectedYear), 
                'rekapitulasi-presensi-' . $this->selectedYear . '.xlsx'
            );
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal mengunduh file Excel: ' . $e->getMessage());
        }
    }

    public function downloadCsv()
    {
        try {
            $data = $this->getRekapitulasiData();
            
            return Excel::download(
                new RekapitulasiPresensiExport($data, $this->selectedYear), 
                'rekapitulasi-presensi-' . $this->selectedYear . '.csv',
                \Maatwebsite\Excel\Excel::CSV
            );
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal mengunduh file CSV: ' . $e->getMessage());
        }
    }

    private function getRekapitulasiData()
    {
        // Get users with guru or kepsek role
        $users = User::whereIn('role', ['guru', 'kepsek'])
                    ->orderBy('name')
                    ->get();

        $rekapitulasi = [];
        
        foreach ($users as $user) {
            $userData = [
                'user_id' => $user->id,
                'name' => $user->name,
                'months' => []
            ];

            // Initialize months data
            for ($month = 1; $month <= 12; $month++) {
                $startDate = Carbon::create($this->selectedYear, $month, 1)->startOfMonth();
                $endDate = Carbon::create($this->selectedYear, $month, 1)->endOfMonth();

                // Get presensi data for this month
                $presensiData = Presensi::where('user_id', $user->id)
                    ->whereBetween('tanggal', [$startDate, $endDate])
                    ->get();

                // Count each status separately
                $hadir = $presensiData->where('status', 'hadir')->count();
                $hadirDl = $presensiData->where('status', 'hadir-dl')->count();
                $hadirTidakLaporPulang = $presensiData->where('status', 'hadir-tidak-lapor-pulang')->count();
                $tidakHadir = $presensiData->where('status', 'tidak-hadir')->count();

                $userData['months'][$month] = [
                    'name' => $this->getMonthName($month),
                    'hadir' => $hadir,
                    'hadir_dl' => $hadirDl,
                    'hadir_tidak_lapor_pulang' => $hadirTidakLaporPulang,
                    'tidak_hadir' => $tidakHadir,
                    'total' => $hadir + $hadirDl + $hadirTidakLaporPulang + $tidakHadir
                ];
            }

            // Calculate yearly totals
            $userData['yearly_hadir'] = collect($userData['months'])->sum('hadir');
            $userData['yearly_hadir_dl'] = collect($userData['months'])->sum('hadir_dl');
            $userData['yearly_hadir_tidak_lapor_pulang'] = collect($userData['months'])->sum('hadir_tidak_lapor_pulang');
            $userData['yearly_tidak_hadir'] = collect($userData['months'])->sum('tidak_hadir');
            $userData['yearly_total'] = $userData['yearly_hadir'] + $userData['yearly_hadir_dl'] + 
                                      $userData['yearly_hadir_tidak_lapor_pulang'] + $userData['yearly_tidak_hadir'];

            $rekapitulasi[] = $userData;
        }

        // Sort to put current user first
        $currentUserId = Auth::id();
        usort($rekapitulasi, function($a, $b) use ($currentUserId) {
            if ($a['user_id'] == $currentUserId) return -1;
            if ($b['user_id'] == $currentUserId) return 1;
            return strcmp($a['name'], $b['name']);
        });

        return $rekapitulasi;
    }

    private function getMonthName($monthNumber)
    {
        $months = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        return $months[$monthNumber] ?? '';
    }

    public function render()
    {
        $rekapitulasi = $this->getRekapitulasiData();

        return view('livewire.rekapitulasi-presensi', [
            'rekapitulasi' => $rekapitulasi
        ]);
    }
}