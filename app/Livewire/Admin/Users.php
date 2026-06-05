<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;

class Users extends Component
{
    use WithPagination;

    public $search = '';
    public $confirmingUserId = null;
    public $confirmingAction = null; // 'promote', 'demote', 'delete'
    public $confirmingUserName = '';

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

    public function startConfirmation($id, $action)
    {
        if ($id == auth()->id() && in_array($action, ['demote', 'delete'])) {
            session()->flash('error', "You cannot perform this action on yourself!");
            return;
        }

        $user = User::find($id);
        if ($user) {
            $this->confirmingUserId = $id;
            $this->confirmingAction = $action;
            $this->confirmingUserName = $user->name;
        }
    }

    public function cancelConfirmation()
    {
        $this->confirmingUserId = null;
        $this->confirmingAction = null;
        $this->confirmingUserName = '';
    }

    public function executeAction()
    {
        if (!$this->confirmingUserId || !$this->confirmingAction) {
            return;
        }

        $id = $this->confirmingUserId;
        $action = $this->confirmingAction;

        if ($id == auth()->id() && in_array($action, ['demote', 'delete'])) {
            session()->flash('error', "You cannot perform this action on yourself!");
            $this->cancelConfirmation();
            return;
        }

        $user = User::find($id);
        if (!$user && $action !== 'delete') {
            $this->cancelConfirmation();
            return;
        }

        switch ($action) {
            case 'promote':
                $user->is_admin = true;
                $user->save();
                session()->flash('success', "User {$user->name} promoted to Admin.");
                break;

            case 'demote':
                $user->is_admin = false;
                $user->save();
                session()->flash('success', "User {$user->name} demoted to User.");
                break;



            case 'delete':
                $name = $user ? $user->name : 'User';
                if ($user) {
                    $user->delete();
                }
                session()->flash('success', "User {$name} deleted successfully.");
                break;
        }

        $this->cancelConfirmation();
    }
}
