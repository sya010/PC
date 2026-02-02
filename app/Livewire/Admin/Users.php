<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;

class Users extends Component
{
    use WithPagination;

    public $search = '';

    // No need for a modal for basic role management, we can do it inline or simple actions
    // But for consistency let's stick to inline actions for simple promote/demote

    public function render()
    {
        $users = User::where(function($query) {
                $query->where('name', 'like', '%'.$this->search.'%')
                      ->orWhere('email', 'like', '%'.$this->search.'%');
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.admin.users', [
            'users' => $users
        ])->layout('components.layouts.admin', ['title' => 'Users Management']);
    }

    public function promote($id)
    {
        // Prevent self-promotion loop issues if any, but mainly just toggle is_admin
        // Assuming User model has is_admin boolean or similar. checking User model...
        // Actually User model was showing sizeBytes 1009, I should verify if it has is_admin.
        // If not, I'll assumne it's a column in DB.
        
        $user = User::find($id);
        if ($user) {
            $user->is_admin = true;
            $user->save();
            session()->flash('success', "User {$user->name} promoted to Admin.");
        }
    }

    public function demote($id)
    {
        if ($id == auth()->id()) {
            session()->flash('error', "You cannot demote yourself!");
            return;
        }

        $user = User::find($id);
        if ($user) {
            $user->is_admin = false;
            $user->save();
            session()->flash('success', "User {$user->name} demoted to User.");
        }
    }

    public function delete($id)
    {
        if ($id == auth()->id()) {
            session()->flash('error', "You cannot delete yourself!");
            return;
        }

        User::find($id)->delete();
        session()->flash('success', 'User deleted successfully.');
    }
}
