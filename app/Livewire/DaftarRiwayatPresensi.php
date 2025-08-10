<?php

namespace App\Livewire;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class DaftarRiwayatPresensi extends Component
{

    use WithPagination;

    public $search = "";
    public $statusFilter = '';

    public function updating($key): void
    {
        if (in_array($key, ['search', 'statusFilter'])) {
            $this->resetPage();
        }
    }

    public function render()
    {
        $user = Auth::user();
        
        $query = $user->presensi()->orderBy('tanggal', 'desc');

        if ($this->search) {
            $query->where(function (Builder $builder){
                $builder->where('tanggal', 'like', "%{$this->search}%");
            });
        }

        if ($this->statusFilter) {
            $query->where('status', $this->statusFilter);
        }

        // Apply status filter if selected
        if ($this->statusFilter) {
            $query->where('status', $this->statusFilter);
        }

        $presensi = $query->paginate(10);

        return view('livewire.daftar-riwayat-presensi', [
            'presensi' => $presensi
        ]);
    }
}
