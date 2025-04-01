<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MonitoringLogs extends Model
{
    use HasFactory;
    protected $table = 'monitoring_logs';

    protected $guarded = [];

    public function monitoringService() {
        return $this->belongsTo(MonitoringService::class, 'monitoring_service_id');
    }

}
