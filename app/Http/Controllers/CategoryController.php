<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
class CategoryController extends Controller
{
    public function index(){

    // Check if the user is a super admin
    if(auth()->user()->hasRole('super-admin')) {
        // Super admins can see all categories
        $categories = Category::with('users')->paginate(15);
    } elseif (auth()->user()->hasRole('admin')) {
        // Admins can see their own categories and other admin's categories
        $categories = Category::whereHas('users', function ($query) {
            $query->where('user_id', auth()->id()) // The current user's categories
                  ->orWhereHas('roles', function ($roleQuery) {
                      $roleQuery->where('name', 'admin'); // Admin's categories
                  });
        })->with('users')->paginate(15);
    } else {
        // Regular users can only see their own categories
        $categories = Category::whereHas('users', function ($query) {
            $query->where('user_id', auth()->id());
        })->with('users')->paginate(15);
    }
        return view('admin.categories.index', compact('categories'));
    }

    public function create(){
        return view('admin.categories.create');
    }

    public function store(Request $request){
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);
        // Create the category (no users assigned yet)
        $category = Category::create([
            'name' => $request->name,
            'description' => $request->description
        ]);
        $category->users()->attach(auth()->id());
        return redirect()->route('categories.index')->with('success', 'Category created successfully');
    }
    public function edit(Category $category){
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category){
        $request->validate([
            'name' => 'required'
        ]);
        $category->update($request->all());
        return redirect()->route('categories.index')->with('success', 'Category updated successfully');
    }
    public function destroy(Category $category){
        $category->delete();
        return redirect()->route('categories.index')->with('success', 'Category deleted successfully');
    }
}
