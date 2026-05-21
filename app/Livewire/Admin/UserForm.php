<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Validation\Rule;

class UserForm extends Component
{
    public ?User $user = null;
    
    public $name;
    public $email;
    public $password;
    public $is_admin = false;

    public function mount($id = null)
    {
        if ($id) {
            $this->user = User::findOrFail($id);
            $this->name = $this->user->name;
            $this->email = $this->user->email;
            $this->is_admin = $this->user->is_admin;
        }
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|min:3|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($this->user?->id)],
            'password' => $this->user ? 'nullable|string|min:8|max:255' : 'required|string|min:8|max:255',
            'is_admin' => 'boolean'
        ]);

        $data = [
            'name' => $this->name,
            'email' => $this->email,
            'is_admin' => $this->is_admin,
        ];

        if ($this->password) {
            $data['password'] = bcrypt($this->password);
        }

        if ($this->user) {
            $this->user->update($data);

             // Log Activity
             ActivityLog::log('updated_user', 'Updated user details: ' . $this->name, $this->user);

            session()->flash('success', 'User updated successfully.');
        } else {
            // Optional: Layout doesn't strictly link to create user yet, but good to have.
        }

        return $this->redirect(route('admin.users'), navigate: true);
    }

    public function render()
    {
        return view('livewire.admin.user-form')
            ->layout('components.layouts.admin', ['title' => 'Edit User']);
    }
}
