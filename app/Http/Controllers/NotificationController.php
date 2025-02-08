<?php
namespace App\Http\Controllers;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
class NotificationController extends Controller
{
    // Mark notification as read
    public function markAsRead($notificationId)
    {
       // Find the notification from the authenticated user's notifications
    $notification = auth()->user()->notifications()->findOrFail($notificationId);
    
    // Mark the notification as read
    $notification->markAsRead();

    return back()->with('success', 'Notification marked as read.');
    }
    public function index(){
        $notifications = Auth::user()->notifications;

        return view('common_pages.notification', compact('notifications'));
    }
}
