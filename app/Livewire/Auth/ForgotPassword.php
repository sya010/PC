<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Mail;
use App\Mail\ForgotPasswordRequest as ForgotPasswordMail;

class ForgotPassword extends Component
{
    public $email = '';
    public $reason = '';
    public $description = '';
    public $success = false;

    protected function rules()
    {
        return [
            'email' => 'required|email|exists:users,email',
            'reason' => 'required|string',
            'description' => 'required|string|min:15|max:1000',
        ];
    }

    public function messages()
    {
        return [
            'email.required' => __('messages.validation.forgot_password.email_required'),
            'email.email' => __('messages.validation.forgot_password.email_email'),
            'email.exists' => __('messages.validation.forgot_password.email_exists'),
            'reason.required' => __('messages.validation.forgot_password.reason_required'),
            'description.required' => __('messages.validation.forgot_password.description_required'),
            'description.min' => __('messages.validation.forgot_password.description_min'),
        ];
    }

    public function submit()
    {
        $this->validate();

        \App\Models\PasswordRequest::create([
            'email' => $this->email,
            'reason' => $this->reason,
            'description' => $this->description,
            'status' => 'pending',
        ]);

        $this->success = true;
    }

    public function render()
    {
        return view('livewire.auth.forgot-password');
    }
}
