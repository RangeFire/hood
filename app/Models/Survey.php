<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Survey extends Model
{
    use HasFactory;
    
    protected $guarded = [];

    protected $casts = [
        'stop_at' => 'datetime'
    ];

    public function answers() {
        return $this->hasMany(SurveyAnswere::class);
    }

}
