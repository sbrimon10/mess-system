<?php
namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Broadcast;

class NotificationComponent extends Component
{
    public $notifications = [];

    protected $listeners = ['newNotification' => 'addNotification'];

    public function mount()
    {
        // Fetch notifications for the current user
        $this->notifications = auth()->user()->notifications->take(5);  // Limit to latest 5 notifications
    }

    public function addNotification($notification)
    {
        $this->notifications->prepend($notification);  // Add to the top of the list
    }

    public function render()
    {
        return view('livewire.notification-component');
    }
}

