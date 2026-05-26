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
    public $is_admin = true;

    public function mount($id = null)
    {
        if ($id) {
            $this->user = User::findOrFail($id);
            $this->name = $this->user->name;
            $this->email = $this->user->email;
            $this->is_admin = $this->user->is_admin;
        }
    }

    public function messages()
    {
        return [
            'name.required' => __('messages.validation.admin_user.name_required'),
            'name.min' => __('messages.validation.admin_user.name_min'),
            'name.max' => __('messages.validation.admin_user.name_max'),
            'email.required' => __('messages.validation.admin_user.email_required'),
            'email.email' => __('messages.validation.admin_user.email_email'),
            'email.max' => __('messages.validation.admin_user.email_max'),
            'email.unique' => __('messages.validation.admin_user.email_unique'),
            'password.required' => __('messages.validation.admin_user.password_required'),
            'password.min' => __('messages.validation.admin_user.password_min'),
            'password.max' => __('messages.validation.admin_user.password_max'),
        ];
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
            $data['password'] = $this->password;
        }

        if ($this->user) {
            $this->user->update($data);

             // Log Activity
             ActivityLog::log('updated_user', 'Updated user details: ' . $this->name, $this->user);

            session()->flash('success', __('messages.admin.user_form.updated_success'));
        } else {
            $user = User::create([
                'name' => $this->name,
                'email' => $this->email,
                'password' => $this->password,
                'is_admin' => $this->is_admin,
            ]);

            // Log Activity
            ActivityLog::log('created_user', 'Created new user: ' . $this->name, $user);

            session()->flash('success', __('messages.admin.user_form.created_success'));
        }

        return $this->redirect(route('admin.users'), navigate: true);
    }

    public function render()
    {
        return view('livewire.admin.user-form')
            ->layout('components.layouts.admin', ['title' => 'Edit User']);
    }
}
