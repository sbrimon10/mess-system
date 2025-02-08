<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use App\Models\FoodPreference;
use App\Models\UserInfo;
use App\Models\Expense;
class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name','username',
        'email','phone',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the food preferences associated with the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */

    public function foodPreferences()
    {
        return $this->hasMany(FoodPreference::class);
    }

// Define the relationship with UserInfo (One-to-One)
public function userInfo()
{
    return $this->hasOne(UserInfo::class);
}
public function categories(){
    return $this->belongsToMany(Category::class, 'categories_users', 'user_id', 'category_id')->withTimestamps();
}
// public function expenses()
// {
//     return $this->belongsToMany(Expense::class)->withPivot('amount')->withTimestamps();
// }
}
