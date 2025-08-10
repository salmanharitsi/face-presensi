<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Validate;
use Livewire\Component;

class UbahPassword extends Component
{
    #[Validate]

    public $password,
    $password_confirmation,
    $current_password;

    public function rules()
    {
        return [
            'current_password' => 'required',
            'password' => [
                'required',
                'min:8',
                'different:current_password',
                'regex:/^(?=.*[a-z])(?=.*\d).+$/',
            ],
            'password_confirmation' => 'required_with:password|same:password',
        ];
    }

    public function messages()
    {
        return [
            'password.regex' => 'Password harus mengandung minimal satu huruf kecil dan satu angka.',
            'password_confirmation.required_with' => 'Konfirmasi password harus diisi',
            'password_confirmation.same' => 'Konfirmasi password tidak sama',
        ];
    }

    public function updatePassword()
    {
        $this->validate();

        $user = Auth::user();

        if (!Hash::check($this->current_password, $user->password)) {
            return redirect('/profil?tab=password')->with([
                'error' => [
                    "title" => "Password saat ini salah!",
                ]
            ]);
        }

        $user->password = Hash::make($this->password);
        $user->save();

        return redirect('/profil?tab=password')->with([
            'success' => [
                "title" => "Password berhasil diperbarui",
            ]
        ]);
    }

    public function render()
    {
        return view('livewire.ubah-password');
    }
}
