<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DepositStatusNotification extends Notification
{
    use Queueable;

    public $status;
    public $deposit;
    public $approved_admin;

    // Constructor to pass the deposit and status (approved/rejected)
    public function __construct($deposit, $status, $adminname)
    {
        $this->deposit = $deposit;
        $this->status = $status;
        $this->approved_admin = $adminname;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */
 // Store the notification in the database
 public function toDatabase($notifiable)
 {
     return [
         'deposit_id' => $this->deposit->id,
         'status' => $this->status,
         'message' => 'Your deposit of ' . $this->deposit->amount . ' has been ' . $this->status . ' by admin '.$this->approved_admin.'.',
     ];
 }
}
