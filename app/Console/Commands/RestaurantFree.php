<?php

namespace App\Console\Commands;

use App\Models\Restaurant;
use Carbon\Carbon;
use Illuminate\Console\Command;

class RestaurantFree extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'restaurant:free';

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
        $restaurants =Restaurant::where('is_busy',1)->whereHas('orders',function($query){
            $query->where('status','!=','accepted')->where('accept_expired_date','<',Carbon::now()->toDateTimeString());
            });
        info($restaurants->toSql());
        if($restaurants->get()->count())
        {
            foreach ($restaurants as $restaurant) {
                $restaurant->is_busy=0;
                $restaurant->save();
                $notification=$restaurant->notifications()->create([
                    'title'=>'الان انت لست مشغول',
                    'body'=>'الان انت لست مشغول',
                    'order_id'=>null]);
                $tokens=$restaurant->tokens->pluck('token');
                if($tokens->count()){
                    $send=notifyByFirebase($notification->title,$notification->body,$tokens->toArray());
                    info("firebase result: " . $send);
                }
            }
        }
        return Command::SUCCESS;
    }
}
