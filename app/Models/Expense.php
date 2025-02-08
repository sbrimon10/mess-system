<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Category;
use App\Models\User;
class Expense extends Model
{
    protected $fillable = [
        'type', 'amount', 'description', 'expense_date'
    ];

       // Many-to-many relationship with categories
       public function categories()
       {
        
           return $this->belongsToMany(Category::class, 'expenses_categories')->withTimestamps();
       }
    public function users()
    {
        return $this->belongsToMany(User::class, 'expenses_categories')->withTimestamps();
    }
}
