<?php

namespace App\Console\Commands;

use App\Models\Survey;
use Illuminate\Console\Command;

class StopSurveysCommand extends Command
{
    protected $signature = 'stop:surveys';

    protected $description = 'Stop all surveys, which are overdue.';

    public function handle()
    {
        $overdue_surveys = Survey::where('status', '!=', 'stop')
            ->where('stop_at', '<=', now())
            ->get();

        $overdue_surveys->each(function ($survey) {
            $survey->update([
                'status' => 'stop'
            ]);
        });
    }
}
