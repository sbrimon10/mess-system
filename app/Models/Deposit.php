<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Notifications\DepositStatusNotification;

class Deposit extends Model
{
    use HasFactory;
    protected $table = 'deposits';
    protected $fillable = [
        'user_id',
        'amount',
        'payment_method',
        'status',
        'deposited_at',
        'rejection_comment',
        'admin_approved_by',
        'approved_at' // Add this to fillable
    ];

    /**
     * Approve the deposit and update the user's balance.
     *
     * @param  \App\Models\User  $admin
     * @return void
     */
    public function approve(User $admin)
    {
        // Update deposit status
        $this->status = 'approved';
        $this->approved_at = now();
        $this->admin_approved_by = $admin->id;
        $this->save();
 // Notify the user

        // Update the user's balance
        $user = $this->user; 
        $user->notify(new DepositStatusNotification($this,  $this->status,$admin->name));
        $user->userInfo->balance += $this->amount;
        $user->userInfo->save();
    }

    /**
     * Reject the deposit and add a rejection comment.
     *
     * @param string $comment
     * @return void
     */
    public function reject($id,$comment = null)
    {
        // Update deposit status
        $this->status = 'rejected';
        $this->approved_at = now();
        $this->admin_approved_by = $id;
        $this->rejection_comment = $comment;
        $this->save();
        
        $this->user->notify(new DepositStatusNotification($this, 'rejected',auth()->user()->name));
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'admin_approved_by');
    }
}
