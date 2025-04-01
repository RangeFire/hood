<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LivechatConfig extends Model
{
    protected $table = 'settings_livechat';
    protected $guarded = [];
    use HasFactory;
}
