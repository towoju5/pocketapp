<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DepositNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $amount;
    protected $currency;
    protected $walletBalance;

    /**
     * Create a new notification instance.
     */
    public function __construct($amount, $currency, $walletBalance)
    {
        $this->amount = $amount;
        $this->currency = strtoupper($currency);
        $this->walletBalance = $walletBalance;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $amount = formatPrice($this->amount);
        $walletBalance = formatPrice($this->walletBalance);
        return (new MailMessage)
            ->subject("New {$this->currency} Deposit Received")
            ->greeting("Hello {$notifiable->last_name},")
            ->line("You have received a new deposit of **{$amount}** into your wallet via {$this->currency}.")
            ->line("Your updated wallet balance is **{$walletBalance}**.")
            ->action('View Wallet', route('dashboard'))
            ->line('Thank you for using our platform!');
    }

    /**
     * Get the array representation of the notification (optional for database).
     */
    public function toArray(object $notifiable): array
    {
        return [
            'amount' => $this->amount,
            'currency' => $this->currency,
            'wallet_balance' => $this->walletBalance,
        ];
    }
}
