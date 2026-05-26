<?php

namespace App\Livewire\Auth;

use Livewire\Component;

class Login extends Component
{
    public $email = '';
    public $password = '';
    public $remember = false;

    protected $rules = [
        'email' => 'required|email',
        'password' => 'required',
    ];

    public function messages()
    {
        return [
            'email.required' => __('messages.validation.login.email_required'),
            'email.email' => __('messages.validation.login.email_email'),
            'password.required' => __('messages.validation.login.password_required'),
        ];
    }

    public function login()
    {
        $this->validate();

        if (auth()->attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            if (auth()->user()->is_blocked) {
                auth()->logout();
                $this->addError('email', __('messages.admin.users.account_blocked'));
                return;
            }
            session()->regenerate();
            return $this->redirectIntended(route('home'), navigate: true);
        }

        $this->addError('email', __('messages.validation.login.failed'));
    }

    public function render()
    {
        return view('livewire.auth.login');
    }
}
