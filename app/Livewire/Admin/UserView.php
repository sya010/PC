<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\User;
use App\Models\ActivityLog;

class UserView extends Component
{
    public User $user;
    public $logs = [];

    public function mount($id)
    {
        $this->user = User::findOrFail($id);
        
        // Fetch logs where this user is the ACTOR (user_id)
        $this->logs = ActivityLog::where('user_id', $id)
            ->latest()
            ->take(20)
            ->get();
    }

    public function render()
    {
        return view('livewire.admin.user-view')
            ->layout('components.layouts.admin', ['title' => 'User Details']);
    }
}
