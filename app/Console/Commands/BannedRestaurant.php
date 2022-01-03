<?php

namespace App\Console\Commands;

use App\Models\Restaurant;
use Illuminate\Console\Command;

class BannedRestaurant extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'restaurant:panding';

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
        $restaurants=Restaurant::where('is_warned',1);
        info($restaurants->toSql());
        if($restaurants->get()->count()) {
            foreach ($restaurants as $restaurant) {
                $restaurant->is_active=0;
                $restaurant->api_token=null;
                $restaurant->save();
            }
        }
        else
        info('failed');
        return Command::SUCCESS;
    }
}
