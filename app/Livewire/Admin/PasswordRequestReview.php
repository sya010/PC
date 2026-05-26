<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\PasswordRequest;
use App\Models\ActivityLog;

class PasswordRequestReview extends Component
{
    public $requestId;
    public $request;
    public $adminNote = '';

    public function mount($id)
    {
        $this->requestId = $id;
        $this->request = PasswordRequest::findOrFail($id);
        $this->adminNote = $this->request->admin_note ?? '';
    }

    public function updateStatus($status)
    {
        $this->request->update([
            'status' => $status,
            'admin_note' => $this->adminNote,
        ]);

        ActivityLog::log('password_request_status', 'Updated password request status for ' . $this->request->email . ' to ' . $status, $this->request);

        session()->flash('success', __('messages.admin.password_requests.status_updated_successfully') ?? 'Request status updated successfully.');
        
        return $this->redirect(route('admin.password-requests'), navigate: true);
    }

    public function render()
    {
        return view('livewire.admin.password-request-review')
            ->layout('components.layouts.admin', ['title' => 'Review Password Request']);
    }
}
