<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Expense;
use App\Models\User;
class Category extends Model
{
    protected $fillable = ['name','description'];

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }
    public function users()
    {
        return $this->belongsToMany(User::class, 'categories_users', 'category_id', 'user_id')->withTimestamps();
        //return $this->belongsToMany(User::class, 'categories_users')->withTimestamps();
    }
 
}
