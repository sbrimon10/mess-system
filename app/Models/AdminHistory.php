<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 
        'admin_start_date', 
        'admin_end_date',
    ];

    // Relationship: an AdminHistory belongs to a User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
