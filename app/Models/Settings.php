<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Settings extends Model 
{

    protected $table = 'settings';
    public $timestamps = true;
    protected $fillable = array('email', 'phone', 'whatsapp', 'fb_link', 'insta_link', 'tw_link', 'about', 'commission', 'commission_text');

}