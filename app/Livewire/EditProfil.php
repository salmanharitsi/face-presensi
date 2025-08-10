<?php

namespace App\Livewire;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;

class EditProfil extends Component
{
    use WithFileUploads;

    #[Validate]

    public $name, 
    $email, 
    $no_telepon,
    $alamat,
    $tanggal_lahir,
    $foto_profil;

    public function mount()
    {
        $user = Auth::user();
        $this->name = $user->name;
        $this->email = $user->email;
        $this->no_telepon = $user->no_telepon;
        $this->alamat = $user->alamat;
        $this->tanggal_lahir = $user->tanggal_lahir;
    }

    public function rules()
    {
        return [
            'name' => 'required|min:5',
            'email' => 'required|email|unique:users,email,' . Auth::user()->id,
            'no_telepon' => 'required|numeric|min:10',
            'alamat' => 'required|min:5',
            'tanggal_lahir' => 'required|date',
            'foto_profil' => 'nullable|image|max:2048',
        ];
    }

    public function update()
    {
        $validated = $this->validate();

        $user = Auth::user();
        $foto_profil = $user->foto_profil;

        $isNewPhoto = false;
        if ($this->foto_profil instanceof UploadedFile) {
            $foto_profil = $this->foto_profil->store('foto_profil', 'public');
            $isNewPhoto = true;
        }

        $isDataChanged =
            $user->name !== ucwords(strtolower(trim($validated['name']))) ||
            $user->email !== $validated['email'] ||
            $user->no_telepon !== $validated['no_telepon'] ||
            $user->alamat !== $validated['alamat'] ||
            $user->tanggal_lahir !== $validated['tanggal_lahir'] ||
            $isNewPhoto;

        if (!$isDataChanged) {
            return redirect('/profil?tab=edit')->with([
                'warning' => [
                    "title" => "Tidak ada perubahan data"
                ]
            ]);
        }

        $user->name = ucwords(strtolower(trim($validated['name'])));
        $user->foto_profil = $foto_profil;
        $user->email = $validated['email'];
        $user->no_telepon = $validated['no_telepon'];
        $user->alamat = $validated['alamat'];
        $user->tanggal_lahir = $validated['tanggal_lahir'];

        $user->save();

        return redirect('/profil?tab=overview')->with([
            'success' => [
                "title" => "Data berhasil perbarui"
            ]
        ]);
    }

    public function render()
    {
        return view('livewire.edit-profil');
    }
}
