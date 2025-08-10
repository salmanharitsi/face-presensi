<?php

namespace App\Livewire;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Livewire\WithPagination;

class DataPengajar extends Component
{
    use WithPagination;

    public $search = "";
    public $roleFilter = '';
    public $selectedUser = null;
    public $showModal = false;
    public $showAddModal = false;
    public $showEditModal = false;
    public $showDeleteModal = false;
    public $userToDelete = null;
    public $userToEdit = null;

    // Properties for adding new teacher
    public $name = '';
    public $nip = '';
    public $email = '';
    public $password = '';
    public $role = 'guru'; // Default role

    // Properties for editing teacher
    public $editName = '';
    public $editNip = '';
    public $editEmail = '';
    public $editNoTelepon = '';
    public $editTanggalLahir = '';
    public $editAlamat = '';
    public $editPassword = '';

    protected $listeners = ['closeModal', 'closeAddModal', 'showAddModal', 'closeDeleteModal', 'showDeleteModal', 'closeEditModal', 'showEditModal'];

    protected $rules = [
        'name' => 'required|string|max:255',
        'nip' => 'required|string|unique:users,nip|max:20',
        'email' => 'required|email|unique:users,email|max:255',
        'password' => 'required|string|min:8',
    ];

    public function getEditValidationRules()
    {
        return [
            'editName' => 'required|string|max:255',
            'editNip' => 'required|string|max:20|unique:users,nip,' . $this->userToEdit->id,
            'editEmail' => 'required|email|max:255|unique:users,email,' . $this->userToEdit->id,
            'editNoTelepon' => 'nullable|string|max:20',
            'editTanggalLahir' => 'nullable|date',
            'editAlamat' => 'nullable|string|max:500',
            'editPassword' => 'nullable|string|min:8',
        ];
    }

    public function updating($key): void
    {
        if (in_array($key, ['search', 'roleFilter'])) {
            $this->resetPage();
        }
    }

    public function showDetail($userId)
    {
        $this->selectedUser = User::find($userId);
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->selectedUser = null;
    }

    public function openAddModal()
    {
        $this->resetAddForm();
        $this->resetErrorBag();
        $this->showAddModal = true;
    }

    public function showAddModal()
    {
        $this->openAddModal();
    }

    public function closeAddModal()
    {
        $this->showAddModal = false;
        $this->resetAddForm();
        $this->resetErrorBag();
    }

    public function resetAddForm()
    {
        $this->name = '';
        $this->nip = '';
        $this->email = '';
        $this->password = '';
        $this->role = 'guru';
    }

    public function openEditModal($userId)
    {
        $this->userToEdit = User::find($userId);
        if ($this->userToEdit) {
            $this->editName = $this->userToEdit->name;
            $this->editNip = $this->userToEdit->nip;
            $this->editEmail = $this->userToEdit->email;
            $this->editNoTelepon = $this->userToEdit->no_telepon;
            $this->editTanggalLahir = $this->userToEdit->tanggal_lahir;
            $this->editAlamat = $this->userToEdit->alamat;
            $this->editPassword = '';
        }
        $this->resetErrorBag();
        $this->showEditModal = true;
    }

    public function showEditModal($userId)
    {
        $this->openEditModal($userId);
    }

    public function closeEditModal()
    {
        $this->showEditModal = false;
        $this->userToEdit = null;
        $this->resetEditForm();
        $this->resetErrorBag();
    }

    public function resetEditForm()
    {
        $this->editName = '';
        $this->editNip = '';
        $this->editEmail = '';
        $this->editNoTelepon = '';
        $this->editTanggalLahir = '';
        $this->editAlamat = '';
        $this->editPassword = '';
    }

    public function openDeleteModal($userId)
    {
        $this->userToDelete = User::find($userId);
        $this->showDeleteModal = true;
    }

    public function closeDeleteModal()
    {
        $this->showDeleteModal = false;
        $this->userToDelete = null;
    }

    public function confirmDelete()
    {
        try {
            if ($this->userToDelete) {
                $this->userToDelete->delete();
                $this->closeDeleteModal();
                $this->resetPage();

                return redirect(url('/data-pengajar'))->with([
                    'success' => [
                        'title' => 'Pengajar berhasil dihapus'
                    ]
                ]);
            }
        } catch (\Exception $e) {
            return redirect(url('/data-pengajar'))->with([
                'error' => [
                    'title' => 'Terjadi kesalahan saat menghapus pengajar: ' . $e->getMessage()
                ]
            ]);
        }
    }

    public function savePengajar()
    {
        $this->validate();

        try {
            $user = new User();
            $user->name = $this->name;
            $user->nip = $this->nip;
            $user->email = $this->email;
            $user->password = Hash::make($this->password);
            $user->role = "guru";
            $user->email_verified_at = now();
            $user->save();

            $this->closeAddModal();
            $this->resetPage();

            return redirect(url('/data-pengajar'))->with([
                'success' => [
                    'title' => 'Pengajar berhasil ditambahkan'
                ]
            ]);
        } catch (\Exception $e) {
            return redirect(url('/data-pengajar'))->with([
                'error' => [
                    'title' => 'Terjadi kesalahan saat menambah pengajar: ' . $e->getMessage()
                ]
            ]);
        }
    }

    public function updatePengajar()
    {
        $this->validate($this->getEditValidationRules());

        try {
            if ($this->userToEdit) {
                $this->userToEdit->name = $this->editName;
                $this->userToEdit->nip = $this->editNip;
                $this->userToEdit->email = $this->editEmail;
                $this->userToEdit->no_telepon = $this->editNoTelepon;
                $this->userToEdit->tanggal_lahir = $this->editTanggalLahir;
                $this->userToEdit->alamat = $this->editAlamat;
                
                // Only update password if provided
                if (!empty($this->editPassword)) {
                    $this->userToEdit->password = Hash::make($this->editPassword);
                }
                
                $this->userToEdit->save();

                $this->closeEditModal();
                $this->resetPage();

                return redirect(url('/data-pengajar'))->with([
                    'success' => [
                        'title' => 'Data pengajar berhasil diperbarui'
                    ]
                ]);
            }
        } catch (\Exception $e) {
            return redirect(url('/data-pengajar'))->with([
                'error' => [
                    'title' => 'Terjadi kesalahan saat memperbarui data pengajar: ' . $e->getMessage()
                ]
            ]);
        }
    }

    public function render()
    {
        $query = User::whereIn('role', ['guru', 'kepsek'])
                    ->orderByRaw("CASE WHEN role = 'kepsek' THEN 0 ELSE 1 END")
                    ->orderBy('name', 'asc');

        if ($this->search) {
            $query->where(function (Builder $builder) {
                $builder->where('name', 'like', "%{$this->search}%")
                       ->orWhere('nip', 'like', "%{$this->search}%")
                       ->orWhere('email', 'like', "%{$this->search}%");
            });
        }

        if ($this->roleFilter) {
            $query->where('role', $this->roleFilter);
        }

        $pengajar = $query->paginate(10);

        return view('livewire.data-pengajar', [
            'pengajar' => $pengajar
        ]);
    }
}