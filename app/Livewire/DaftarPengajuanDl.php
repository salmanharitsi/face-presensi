<?php

namespace App\Livewire;

use App\Models\DinasLuar;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;
use Livewire\WithPagination;

class DaftarPengajuanDl extends Component
{
    use WithPagination;

    public $search = "";
    public $statusFilter = "";
    public $showModal = false;
    public $selectedPengajuan = null;
    public $actionType = null; // 'setujui' atau 'tolak'

    public function updating($key): void
    {
        if (in_array($key, ['search', 'statusFilter'])) {
            $this->resetPage();
        }
    }

    public function openModal($pengajuanId, $action)
    {
        $this->selectedPengajuan = DinasLuar::with('user')->find($pengajuanId);
        $this->actionType = $action;
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->selectedPengajuan = null;
        $this->actionType = null;
    }

    public function confirmAction()
    {
        if ($this->selectedPengajuan && $this->actionType) {
            // Tentukan status dan pesan secara eksplisit
            if ($this->actionType === 'setujui') {
                $status = 'disetujui';
                $message = 'disetujui';
            } else {
                $status = 'ditolak';
                $message = 'ditolak';
            }

            $this->selectedPengajuan->update([
                'status' => $status
            ]);

            $this->closeModal();

            return redirect(url('/pengajuan-dinas-luar'))->with([
                'success' => [
                    "title" => "Pengajuan berhasil " . $message . ".",
                ],
            ]);
        }
    }

    public function render()
    {
        $query = DinasLuar::orderBy('created_at', 'desc');

        if ($this->search) {
            $query->where(function (Builder $builder) {
                $builder->whereHas('user', function (Builder $builder) {
                    $builder->where('name', 'like', "%{$this->search}%");
                });
            });
        }

        if ($this->statusFilter) {
            $query->where('status', $this->statusFilter);
        }

        $pengajuan = $query->paginate(10);

        return view('livewire.daftar-pengajuan-dl', [
            'pengajuan' => $pengajuan
        ]);
    }
}