<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DbsyncCredential extends Model
{
    use HasFactory;

    protected $guarded = [];
    
    function project() {
        return $this->belongsTo(Project::class, 'project_id');
    }

}
