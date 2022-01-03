<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model 
{

    protected $table = 'notifications';
    public $timestamps = true;
    protected $fillable = array('title', 'body', 'notificationable', 'is_read','order_id');

    public function notificationable()
    {
        return $this->morphTo();
    }

}