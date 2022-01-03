<?php

namespace App\Console\Commands;

use App\Models\Restaurant;
use Carbon\Carbon;
use Illuminate\Console\Command;

class RestaurantBusy extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'restaurant:late';

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
    public function handle(){
        $restaurants =Restaurant::whereHas('orders',function($query){
            $query->where('status','accepted')->
            where('accept_expired_date','<',Carbon::now()->toDateTimeString());
            });
        info($restaurants->toSql());    
        if ($restaurants->get()->count()) {
            foreach ($restaurants as $restaurant) {
                $restaurant->is_busy=1;
                $restaurant->save();
                $orders=$restaurant->orders->where('status','accepted')->
                where('accept_expired_date','<',Carbon::now()->toDateTimeString());
                foreach ($orders as $order) {
                    $notification=$restaurant->notifications()->create([
                        'title'=>'لا تستطيع استقبال اي طلبات جديده حاليا بدون الانتهاء من الطلبات الحاليه',
                        'body'=>$order->client->name.'لا تستطيع استقبال اي طلب بدون الانتهاء من هذا الطلب ل',
                        'order_id'=>$order->id]);
                    $tokens=$restaurant->tokens->pluck('token');
                    if($tokens->count()){
                        $send=notifyByFirebase($notification->title,$notification->body,$tokens->toArray(),['order_id'=>$order->id]);
                        info("firebase result: " . $send);
                    }
                } 
            }
        }
        return Command::SUCCESS;
    }
}
