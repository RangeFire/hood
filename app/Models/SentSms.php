<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SentSms extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $table = 'sent_sms';

    /* relationships */
        public function project() {
            return $this->belongsTo(Project::class, 'project_id');
        }
    /* relationships */

    // count sent sms for project this month
    public static function countSentSmsForProjectThisMonth($project_id) {
        return SentSms::where('project_id', $project_id)
            ->whereMonth('created_at', date('m'))
            ->count();
    }

}
