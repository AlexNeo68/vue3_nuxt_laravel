<?php

namespace App\Listeners;

use App\Events\OrderCompleteEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class OrderCompleteAdminListener
{

    public function handle(OrderCompleteEvent $event)
    {
        $order = $event->order;
        Mail::send('emails.admin', ['order' => $order], function($message) use ($order) {
            $message->to($order->email);
            $message->subject('New order');
        });
    }
}
