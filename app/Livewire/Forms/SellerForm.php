<?php

namespace App\Livewire\Forms;

use Livewire\Form;
use App\Models\User;
use App\Models\Seller;
use App\Enums\RoleEnum;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class SellerForm extends Form
{
    public Seller $seller;
    public $name = "";
    public $email = "";
    public $role_id = RoleEnum::MANAGER;
    public $password = "";
    public $password_confirmation = "";

    public function setSeller(Seller $seller)
    {
        $this->seller = $seller;

        if (!empty($this->seller->user)) {
            $this->name = $seller->user->name;
            $this->email = $seller->user->email;
            $this->role_id = $seller->user->role_id->value;
        }
    }

    public function save()
    {

        $emailValidation = ['required', 'email', 'unique:users'];

        $passwordValidation = ['required', 'confirmed', Password::defaults()];

        if (!empty($this->seller->user)) {
            $emailValidation = ['required', 'email', Rule::unique('users')->ignore($this->seller->user_id)];
            $passwordValidation = ['nullable', 'confirmed', Password::defaults()];
        }

        $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => $emailValidation,
            'password' => $passwordValidation,
            'role_id' => ['required']
        ]);

        try {
            if (!empty($this->seller->user)) {

                if (empty($this->password)) {
                    $this->seller->user->update($this->only(['name', 'email', 'role_id']));
                } else {
                    $this->password = bcrypt($this->password);
                    $this->seller->user->update($this->only(['name', 'email', 'password', 'role_id']));
                }
            } else {
                $this->password = bcrypt($this->password);
                User::create($this->only(['name', 'email', 'password', 'role_id']))
                    ->seller()->create();
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Problem creating/updating seller');
        }

        return redirect()->route('sellers.index');
    }
}
