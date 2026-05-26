<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\PasswordRequest;
use App\Models\ActivityLog;

class PasswordRequests extends Component
{
    use WithPagination;

    public $search = '';
    public $status = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'status' => ['except' => ''],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatus()
    {
        $this->resetPage();
    }

    public function deleteRequest($id)
    {
        $request = PasswordRequest::findOrFail($id);
        $email = $request->email;
        $request->delete();

        ActivityLog::log('password_request_deleted', 'Deleted password request from ' . $email);

        session()->flash('success', __('messages.admin.password_requests.deleted_success') ?? 'Request deleted successfully.');
    }

    public function render()
    {
        $query = PasswordRequest::latest();

        if (!empty($this->search)) {
            $query->where('email', 'like', '%' . $this->search . '%');
        }

        if (!empty($this->status)) {
            $query->where('status', $this->status);
        }

        return view('livewire.admin.password-requests', [
            'requests' => $query->paginate(10)
        ])->layout('components.layouts.admin', ['title' => 'Password Requests']);
    }
}
