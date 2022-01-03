<?php

namespace App\Console\Commands;

use App\Models\Payment;
use App\Models\Restaurant;
use Carbon\Carbon;
use Illuminate\Console\Command;

class DebtorRestaurant extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'restaurant:debtor';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $debtorRestaurants=Restaurant::whereHas('payment',function($query){
            $query->whereRaw('commission - amount >30');
        })->get();
        if($debtorRestaurants->count())
        {
            foreach ($debtorRestaurants as  $restaurant) {
                $restaurant->is_warned=1;
                $restaurant->save();
                $notification=$restaurant->notifications()->create([
                    'title'=>"You Must Pay App Commission",
                    'body'=>'You Must Pay App Commission During this Week',
                    'order_id'=>null
                    ]
                );
                $tokens=$restaurant->tokens()->pluck('token');
                if($tokens->count())
                {
                    $send=notifyByFirebase($notification->title,$notification->body,$tokens->toArray());
                    info("firebase result: " . $send);
                }
            }
        }    
        return Command::SUCCESS;
    }
}
