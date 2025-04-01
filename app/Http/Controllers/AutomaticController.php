<?php

namespace App\Http\Controllers;

use App\Services\MailService;
use App\Services\MonitoringService;
use Illuminate\Http\Request;

class AutomaticController extends Controller
{
    
    public function productRenewal24hours(MailService $mailService) {
        $mailService->sendProductRenewal24hours();
    }

    public function productRenewalToday(MailService $mailService) {
        $mailService->sendProductRenewalToday();
    }

    public function monitoringLogsRemoval10Days(MonitoringService $monitoringService) {
        $monitoringService->monitoringLogsRemoval10Days();
    }

}
