<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
class MultiUserSearch extends Component
{
    public $searchuser = '';
    public $users = [];
    public $selectedUsers = [];  // Array to store selected users

    public function updatedsearchuser($searchuser)
    {
        if ($searchuser) {
            $this->users = User::where('name', 'like', '%' . $searchuser . '%')
                ->orWhere('email', 'like', '%' . $searchuser . '%')
                ->limit(10)
                ->get();
        } else {
            $this->users = [];
        }
    }

    // This method is triggered when a user is selected from the list
    public function selectUser($userId, $userName)
    {
        // Add selected user to the array if it's not already selected
        if (!in_array($userId, array_column($this->selectedUsers, 'id'))) {
            $this->selectedUsers[] = ['id' => $userId, 'name' => $userName];
        }

        // Optionally clear the user list after selection
        $this->users = [];
    }

    // This method is triggered when a user is removed from the selection
    public function removeUser($userId)
    {
        // Remove the selected user from the array
        $this->selectedUsers = array_filter($this->selectedUsers, function($user) use ($userId) {
            return $user['id'] !== $userId;
        });
        $this->selectedUsers = array_values($this->selectedUsers); // Reindex the array
    }
    public function render()
    {
        return view('livewire.multi-user-search');
    }
}
