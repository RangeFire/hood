<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class MonitoringService extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'monitoring_services';

    protected $guarded = [];

    /* relationships */

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function monitoringLogs()
    {
        return $this->hasMany(MonitoringLogs::class, 'monitoring_service_id');
    }

    /* relationships */

}
