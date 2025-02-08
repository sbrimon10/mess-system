<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
class UserInfo extends Model
{
    // The table name (optional if your table name is plural and matches Laravel's convention)
    protected $table = 'users_info';

    // Specify the foreign key for the relationship (optional)
    protected $primaryKey = 'id'; // or 'user_id', depending on your table structure

    protected $fillable = [
        'room_number', 'balance',
    ];
    // Define the inverse relationship (User -> UserInfo)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
