<?php

namespace App\Listeners;

use App\Events\odcastrocessed;
use App\Mail\test;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class endodcastotification implements ShouldQueue
{
    use InteractsWithQueue;
    use Queueable;
    /**
     
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\odcastrocessed  $event
     * @return void
     */
    public function handle(odcastrocessed $event)
    {
        Mail::to($event->email)->bcc('mi0530838@gmail.com')->send(new test());
    }
}
