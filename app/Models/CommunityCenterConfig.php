<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommunityCenterConfig extends Model
{
    use HasFactory;
    protected $table = 'settings_community_center';
    protected $guarded = [];
}
