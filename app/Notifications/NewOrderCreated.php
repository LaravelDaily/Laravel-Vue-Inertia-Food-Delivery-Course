<?php

namespace App\Notifications;

use App\Models\Order;
use App\Models\Restaurant;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewOrderCreated extends Notification
{
    use Queueable;

    protected Order $order;

    protected Restaurant $restaurant;

    protected Collection $products;

    protected User $customer;

    /**
     * Create a new notification instance.
     */
    public function __construct(Order $order)
    {
        $this->order      = $order;
        $this->restaurant = $order->restaurant;
        $this->products   = $order->products;
        $this->customer   = $order->customer;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
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
        return (new MailMessage)
            ->subject(__('[:restaurant_name] New Order', [
                'restaurant_name' => $this->restaurant->name,
            ]))
            ->markdown('mail.order.new-order-created', [
                'order'      => $this->order,
                'restaurant' => $this->restaurant,
                'products'   => $this->products,
                'customer'   => $this->customer,
            ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
