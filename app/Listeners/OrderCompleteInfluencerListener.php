<?php

namespace App\Listeners;

use App\Events\OrderCompleteEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class OrderCompleteInfluencerListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(OrderCompleteEvent $event)
    {
        $order = $event->order;
        Mail::send('emails.influencer', ['order' => $order], function($message) use ($order){
            $message->to($order->influencer_email);
            $message->subject('New earn');
        });
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        //
    }
}
