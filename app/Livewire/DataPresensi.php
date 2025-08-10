<?php

namespace App\Livewire;

use App\Models\User;
use App\Models\Presensi;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;
use Livewire\WithPagination;

class DataPresensi extends Component
{
    use WithPagination;

    public $search = "";
    public $statusFilter = '';
    public $userFilter = '';
    public $showDeleteModal = false;
    public $selectedPresensi = null;

    // Edit modal properties
    public $showEditModal = false;
    public $presensiToEdit = null;
    public $editJamMasuk = '';
    public $editJamKeluar = '';
    public $editStatus = '';

    public function updating($key): void
    {
        if (in_array($key, ['search', 'statusFilter', 'userFilter'])) {
            $this->resetPage();
        }
    }

    public function openDeleteModal($presensiId)
    {
        $this->selectedPresensi = Presensi::with('user')->find($presensiId);
        
        if ($this->selectedPresensi) {
            $this->showDeleteModal = true;
        } else {
            return redirect(url('/data-presensi'))->with([
                'error' => [
                    'title' => 'Data presensi tidak ditemukan'
                ]
            ]);
        }
    }

    public function closeDeleteModal()
    {
        $this->showDeleteModal = false;
        $this->selectedPresensi = null;
    }

    public function confirmDelete()
    {
        if ($this->selectedPresensi) {
            try {
                $userName = $this->selectedPresensi->user->name;
                $tanggal = $this->selectedPresensi->tanggal;
                
                $this->selectedPresensi->delete();
                
                $this->closeDeleteModal();
                $this->resetPage();

                return redirect(url('/data-presensi'))->with([
                    'success' => [
                        'title' => 'Data presensi berhasil dihapus'
                    ]
                ]);
            } catch (\Exception $e) {
                return redirect(url('/data-presensi'))->with([
                    'error' => [
                        'title' => 'Terjadi kesalahan saat menghapus data presensi: ' . $e->getMessage()
                    ]
                ]);
            }
        }
    }

    public function openEditModal($presensiId)
    {
        $this->presensiToEdit = Presensi::with('user')->find($presensiId);
        
        if ($this->presensiToEdit) {
            // Set form values
            $this->editJamMasuk = $this->presensiToEdit->jam_masuk ?? '';
            $this->editJamKeluar = $this->presensiToEdit->jam_keluar ?? '';
            $this->editStatus = $this->presensiToEdit->status;
            
            $this->showEditModal = true;
        } else {
            return redirect(url('/data-presensi'))->with([
                'error' => [
                    'title' => 'Data presensi tidak ditemukan'
                ]
            ]);
        }
    }

    public function closeEditModal()
    {
        $this->showEditModal = false;
        $this->presensiToEdit = null;
        $this->editJamMasuk = '';
        $this->editJamKeluar = '';
        $this->editStatus = '';
        
        // Clear validation errors
        $this->resetValidation();
    }

    public function updatePresensi()
    {
        $this->validate([
            'editJamMasuk' => 'nullable|date_format:H:i',
            'editJamKeluar' => 'nullable|date_format:H:i',
            'editStatus' => 'required|in:hadir,hadir-dl,tidak-hadir,hadir-tidak-lapor-pulang'
        ], [
            'editJamMasuk.date_format' => 'Format jam masuk tidak valid (HH:MM)',
            'editJamKeluar.date_format' => 'Format jam keluar tidak valid (HH:MM)',
            'editStatus.required' => 'Status harus dipilih',
            'editStatus.in' => 'Status yang dipilih tidak valid'
        ]);

        // Additional validation: jam keluar should be after jam masuk if both are provided
        if ($this->editJamMasuk && $this->editJamKeluar) {
            if (strtotime($this->editJamKeluar) <= strtotime($this->editJamMasuk)) {
                $this->addError('editJamKeluar', 'Jam keluar harus lebih besar dari jam masuk');
                return;
            }
        }

        try {
            $this->presensiToEdit->update([
                'jam_masuk' => $this->editJamMasuk ?: null,
                'jam_keluar' => $this->editJamKeluar ?: null,
                'status' => $this->editStatus
            ]);

            $this->closeEditModal();

            return redirect(url('/data-presensi'))->with([
                'success' => [
                    'title' => 'Data presensi berhasil diperbarui'
                ]
            ]);
        } catch (\Exception $e) {
            return redirect(url('/data-presensi'))->with([
                'error' => [
                    'title' => 'Terjadi kesalahan saat memperbarui data presensi: ' . $e->getMessage()
                ]
            ]);
        }
    }

    public function render()
    {
        // Get all users for filter dropdown
        $users = User::orderBy('name')->get();
        
        // Build query for presensi
        $query = Presensi::with('user')->orderBy('tanggal', 'desc');

        // Filter by user if selected
        if ($this->userFilter) {
            $query->where('user_id', $this->userFilter);
        }

        // Search by date
        if ($this->search) {
            $query->where(function (Builder $builder) {
                $builder->where('tanggal', 'like', "%{$this->search}%")
                        ->orWhereHas('user', function (Builder $builder) {
                    $builder->where('name', 'like', "%{$this->search}%");
                });
            });
        }

        // Filter by status
        if ($this->statusFilter) {
            $query->where('status', $this->statusFilter);
        }

        $presensi = $query->paginate(10);

        return view('livewire.data-presensi', [
            'presensi' => $presensi,
            'users' => $users
        ]);
    }
}