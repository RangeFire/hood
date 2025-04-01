<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectSubscription extends Model
{
    use HasFactory;
    protected $table = 'project_subscriptions';
    
    protected $guarded = [];

    public function project() {
        return $this->belongsTo(Project::class);
    }


}
