<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;

class Users extends Component
{
    use WithPagination;

    public $search = '';

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

    public function block($id)
    {
        if ($id == auth()->id()) {
            session()->flash('error', "You cannot block yourself!");
            return;
        }

        $user = User::find($id);
        if ($user) {
            $user->is_blocked = true;
            $user->save();
            session()->flash('success', "User {$user->name} has been blocked.");
        }
    }

    public function unblock($id)
    {
        $user = User::find($id);
        if ($user) {
            $user->is_blocked = false;
            $user->save();
            session()->flash('success', "User {$user->name} has been unblocked.");
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
