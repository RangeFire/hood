<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupportConfig extends Model
{
    use HasFactory;
    protected $table = 'settings_discord';

    protected $guarded = [];

}
