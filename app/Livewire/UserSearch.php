<?php 
namespace App\Livewire;

use Livewire\Component;
use App\Models\User;

class UserSearch extends Component
{
    public $searchuser = '';
    public $users = [];
    public $selectedUserId = null;  // The selected user's ID


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
    // Set the search input field to the selected user's name
    $this->searchuser = $userName;

    // Set the selected user's ID to the hidden input field
    $this->selectedUserId = $userId;

    // Optionally clear the user list after selection
    $this->users = [];
}
    public function render()
    {
        return view('livewire.user-search');
    }
    
}
