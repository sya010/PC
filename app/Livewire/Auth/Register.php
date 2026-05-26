<?php

namespace App\Livewire\Auth;

use Livewire\Component;

class Register extends Component
{
    public $name = '';
    public $email = '';
    public $password = '';
    public $password_confirmation = '';
    public $terms = false;

    protected $rules = [
        'name' => 'required|min:3',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|confirmed|min:8',
        'terms' => 'accepted',
    ];

    public function messages()
    {
        return [
            'name.required' => __('messages.validation.register.name_required'),
            'name.min' => __('messages.validation.register.name_min'),
            'email.required' => __('messages.validation.register.email_required'),
            'email.email' => __('messages.validation.register.email_email'),
            'email.unique' => __('messages.validation.register.email_unique'),
            'password.required' => __('messages.validation.register.password_required'),
            'password.confirmed' => __('messages.validation.register.password_confirmed'),
            'password.min' => __('messages.validation.register.password_min'),
            'terms.accepted' => __('messages.validation.register.terms_accepted'),
        ];
    }

    public function register()
    {
        $this->validate();

        $user = \App\Models\User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => \Illuminate\Support\Facades\Hash::make($this->password),
        ]);

        auth()->login($user);

        session()->regenerate();
        
        return $this->redirectIntended(route('home'), navigate: true);
    }

    public function render()
    {
        return view('livewire.auth.register');
    }
}
