<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination; // Import the pagination trait
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class CategoryCrud extends Component
{
    use WithPagination; // This enables pagination for this component

    // Specify the layout file
    protected $layout = 'layouts.app';

    public  $name,$description, $categoryId, $search = '', $filter = '';
    public $isUpdateMode = false;
    public $isDeleteModalOpen = false; // Modal state
    public $categoryToDelete = null; // The category to delete
    public $isFormModalOpen = false;
// The number of items per page
protected $paginationTheme = 'tailwind'; 

    public function render()
    {

        $user = Auth::user();

        // Check user's role and adjust the query accordingly
        $categoriesQuery = Category::query();

        // Super Admin can see all categories
        if ($user->hasRole('super-admin')) {
            $categoriesQuery = $categoriesQuery->with('users');
        }
        
        // Admin can see all categories but might apply additional filters, if needed
        elseif ($user->hasRole('admin')) {
            // Example: Admin can see categories created by any user but might filter by status or other criteria
            $categoriesQuery = $categoriesQuery->with('users');
            if ($this->filter) {
                $categoriesQuery->where('status', $this->filter);
            }
        }

        // User can only see their own categories
        elseif ($user->hasRole('user')) {
            $categoriesQuery = $categoriesQuery->whereHas('users', function ($query) {
                $query->where('user_id', auth()->id());
            })->with('users');;
        }

        // Apply the search filter if present
        if ($this->search) {
            $categoriesQuery->where('name', 'like', '%' . $this->search . '%');
        }

        // Paginate the result
        $categories = $categoriesQuery->orderBy('created_at', 'desc')->paginate(10);


        // this are working
        // $categories = Category::query()
        //     ->when($this->search, function ($query) {
        //         $query->where('name', 'like', '%' . $this->search . '%');
        //     })
        //     ->when($this->filter, function ($query) {
        //         $query->where('status', $this->filter);
        //     })
        //     ->with('users') // Assuming 'user' is the relationship for the created user
        //     ->orderBy('created_at', 'desc')
        //     ->paginate(10);
        // $categories = Category::paginate(5);
        return view('livewire.category-crud',compact('categories'))->layout('layouts.app');
    }

    public function createCategory()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $category=Category::create([
            'name' => $this->name,
            'description' => $this->description
        ]);
        $category->users()->attach(auth()->id());
        session()->flash('success', 'Category created successfully.');
        $this->closeFormModal();
    }

    public function editCategory($categoryId)
    {
        $user = Auth::user();
        $category = Category::find($categoryId);
        if (!$category) {
            session()->flash('error', 'Category not found.');
            return;
        }
        // Check if the category belongs to the logged-in user through the pivot table or if the user is an admin
        if (!$category->users->contains($user) && !$user->hasRole('admin')) {
            session()->flash('error', 'You do not have permission to edit this category.');
            return;
        }
        $this->categoryId = $category->id;
        $this->name = $category->name;
        $this->description = $category->description;
        $this->isUpdateMode = true;
        $this->isFormModalOpen = true; // Open the modal in Edit mode
    }

    public function updateCategory()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);
        $user = Auth::user();
        $category = Category::find($this->categoryId);
        if (!$category) {
            session()->flash('error', 'Category not found.');
            return;
        }
        // Check if the category belongs to the logged-in user through the pivot table or if the user is an admin
        if (!$category->users->contains($user) && !$user->hasRole('admin')) {
            session()->flash('error', 'You do not have permission to edit this category.');
            return;
        }
        $category->update([
            'name' => $this->name,
            'description' => $this->description
        ]);

        session()->flash('success', 'Category updated successfully.');
         $this->closeFormModal();
    }
    public function openFormModal()
    {
        $this->isUpdateMode = false; // Reset to create mode
        $this->name = ''; // Reset name field
        $this->description = ''; // Reset description field
        $this->isFormModalOpen = true; // Open the modal
    }
    public function closeFormModal()
    {
        $this->isFormModalOpen = false; // Close the modal
        $this->isUpdateMode = false; // Reset mode to create
        $this->categoryId = null; // Reset category ID
        $this->resetInputFields();
    }
    public function deleteCategory($categoryId)
    {
        $this->categoryToDelete = $categoryId;
        $this->isDeleteModalOpen = true; // Open the delete modal
    }

    public function confirmDelete()
    {$user = Auth::user();
        $category = Category::find($this->categoryToDelete);
        if (!$category) {
            session()->flash('error', 'Category not found.');
            return;
        }
        // Check if the category belongs to the logged-in user through the pivot table or if the user is an admin
        if (!$category->users->contains($user) && !$user->hasRole('admin')) {
            session()->flash('error', 'You do not have permission to edit this category.');
            return;
        }
       $category->delete();
        session()->flash('success', 'Category deleted successfully.');
        $this->isDeleteModalOpen = false; // Close the modal
        $this->categoryToDelete = null; // Reset the category ID
    }

    public function closeDeleteModal()
    {
        $this->isDeleteModalOpen = false; // Close the modal without deleting
    }

    private function resetInputFields()
    {
        $this->name = '';
        $this->description = '';
        $this->categoryId = null;
        $this->isUpdateMode = false;
    }
}