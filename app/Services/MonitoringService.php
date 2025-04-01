<?php
namespace App\Services;

use DateTime;
use App\Models;
use DatePeriod;
use DateInterval;
use Carbon\Carbon;
use App\Helpers\Auth;
use Carbon\CarbonPeriod;
use App\Helpers\Subscription;
use App\Models\MonitoringLogs;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;

class MonitoringService extends Service {

    public function getServices($projectId, $subDays = 30) {
        $monitoringServices =  Models\MonitoringService::where('project_id', $projectId)->get();

        foreach ($monitoringServices as $i => $service) {
                // $monitoringServices[$i]->liveStatus = $serviceStatus;
                // $this->showLastMonthUptime($service->id);
                $monitoringServices[$i]->status = $this->showLastMonthUptime($service->id, $subDays);
        }

        return $monitoringServices; 
    }

    public function formatServicesForInternalMonitoringPage($services) {

        foreach ($services as $i => $service) {

            $services[$i]->uptimePercentage = $this->formatServiceCounts($service, true);
            $services[$i]->averageResponseTime = $this->averageResponseTime($service, true);

            $services[$i]->onlineSince = $this->onlineSince($service);
            $services[$i]->last7DaysUptime = $this->last7DaysUptime($service);
        }

        return $services;

    }

    private function last7DaysUptime($service) {

        /* make dateperiod for the last 7 days */
        $date = (new DateTime())->modify('+1 day');
        $datePeriod = new DatePeriod($date->modify('-7 days'), new DateInterval('P1D'), (new DateTime())->modify('+1 day'));

        $return_data = [];

        foreach($datePeriod as $date) {
            $uptimeByDay = $this->formatServiceCountsByDay($service, $date->format('Y-m-d'), true);
            $return_data[$date->format('d.m.')] = $uptimeByDay['uptime'] / 100;
        }

        return $return_data;
        
    }

    private function onlineSince($service) {

        $logs = $service->monitoringLogs()->orderBy('id', 'desc')->get();

        /* false if no logs could be determined */
        if(!$logs || count($logs) == 0) return 'Nicht erfasst';

        /* false if currently offline */
        if($logs[0]->status == 'offline') return 'Nicht erfasst';
        
        foreach($logs as $i => $log) {
            /* return last online date ($i-1) if last offline period was found */
            if($log->status == 'offline') {
                return (new DateTime($logs[$i-1]->created_at))->format('d.m.Y H:i');
            }
        }

        /* fallback if only 1 online and 0 offline entries */
        return (new DateTime($logs[0]->created_at))->format('d.m.Y H:i');

    }

    private function averageResponseTime($service) {
        
        $logs = $service->monitoringLogs;

        $responseTimes = 0;
        $countResponseTimes = 0;

        foreach($logs as $log) {
            if($this->isInValid(['response_time'], $log)) continue;

            $responseTimes += $log->response_time;
            $countResponseTimes++;

        }

        if($responseTimes != 0 && $countResponseTimes != 0) {
            $averageResponseTime = round($responseTimes / $countResponseTimes, 0);
        }

        return $averageResponseTime ?? 0;

    }

    public function getGlobalUptime() {

        $allServices = Models\MonitoringService::where('project_id', Session::get('activeProject'))->get();

        $allUptime = 0;

        foreach ($allServices as $service) {
            $globalServiceUptime = $this->formatServiceCounts($service, true)['uptime'];
            $allUptime += $globalServiceUptime;
        }

        if(intVal($allUptime) != 0 && count($allServices) != 0) {
            $globalUptime = intVal($allUptime) / count($allServices);
            $globalUptime = round($globalUptime, 1);
        }

        return $globalUptime ?? 0;

    }

    public function getGlobalOffline() {
        $allServices = Models\MonitoringService::where('project_id', Session::get('activeProject'))->get();
        $allOffline = 0;
        foreach ($allServices as $service) {
            $allOffline += $service->count_offline;
            // $globalServiceOffline = $this->formatServiceCounts($service, false)['downtime'];
            // $allOffline += $globalServiceOffline;
        }
        
        return $allOffline ?? 0;
    }

    public function getTodayChecks() { 

        return $this->fakeTodayChecks();

        $allServices = Models\MonitoringService::where('project_id', Session::get('activeProject'))->get();

        $allChecks = 0;

        foreach ($allServices as $service) {
            $checks = $this->formatServiceCounts($service, false, Carbon::today());

            $allChecks += $checks['uptime'] + $checks['downtime'];
        }

        return $allChecks ?? 0;

    }

    private function fakeTodayChecks() {

        // get minute count of now
        $now = Carbon::now();

        $allMinutesElapsed = $now->hour * 60 + $now->minute;

        return $allMinutesElapsed;
    }

    private function formatServiceCountsByDay($service, $date, $returnInPercent = true) {
        $serviceOnline = 0;
        $serviceOffline = 0;

        $serviceChecks = Models\MonitoringLogs::where('monitoring_service_id', $service->id)
        ->whereDate('created_at', Carbon::parse($date))->get();

        foreach ($serviceChecks as $check) {
            if($check->status == 'online') {
                $serviceOnline++;
            } else if($check->status == 'offline'){
                $serviceOffline++;
            }
        }

        if(intVal($serviceOnline) != 0 && count($serviceChecks) != 0) {
            $serviceUptime = $serviceOnline / count($serviceChecks);
            $serviceUptime = $serviceUptime * 100;
            $serviceUptime = round($serviceUptime, 1);
        }
        
        if(intVal($serviceOffline) != 0 && count($serviceChecks) != 0) {
            $serviceDowntime = $serviceOffline / count($serviceChecks);
            $serviceDowntime = $serviceDowntime * 100;
            $serviceDowntime = round($serviceDowntime, 1);
        }

        if($returnInPercent === false) {
            return [
                'uptime' => $serviceOnline ?? 0,
                'downtime' => $serviceOffline ?? 0,
            ];
        }

        return [
            'uptime' => $serviceUptime ?? 0,
            'downtime' => $serviceDowntime ?? 0,
        ];

    }

    private function formatServiceCounts($service, $returnInPercent = true, Carbon $specificDate = null) {
        $serviceOnline = 0;
        $serviceOffline = 0;

        $serviceChecks = Models\MonitoringLogs::where('monitoring_service_id', $service->id);

        if($specificDate) {
            $serviceChecks = $serviceChecks->whereDate('created_at', $specificDate);
        }

        $serviceChecks = $serviceChecks->get();

        foreach ($serviceChecks as $check) {
            if($check->status == 'online') {
                $serviceOnline++;
            } else if($check->status == 'offline'){
                $serviceOffline++;
            }
        }

        if(intVal($serviceOnline) != 0 && count($serviceChecks) != 0) {
            $serviceUptime = $serviceOnline / count($serviceChecks);
            $serviceUptime = $serviceUptime * 100;
            $serviceUptime = round($serviceUptime, 1);
        }
        
        if(intVal($serviceOffline) != 0 && count($serviceChecks) != 0) {
            $serviceDowntime = $serviceOffline / count($serviceChecks);
            $serviceDowntime = $serviceDowntime * 100;
            $serviceDowntime = round($serviceDowntime, 1);
        }

        if($returnInPercent === false) {
            return [
                'uptime' => $serviceOnline ?? 0,
                'downtime' => $serviceOffline ?? 0,
            ];
        }

        return [
            'uptime' => $serviceUptime ?? 0,
            'downtime' => $serviceDowntime ?? 0,
        ];
    }

    public function getLastStatistics($project_id) {

        $project = Models\Project::find($project_id);

        $status24 = $this->getGlobalUptimeByDays($project, 1)['uptime'];
        $status7 = $this->getGlobalUptimeByDays($project, 7)['uptime'];
        $status30 = $this->getGlobalUptimeByDays($project, 30)['uptime'];
        $status60 = $this->getGlobalUptimeByDays($project, 60)['uptime'];
        
        return [
            '1day' => $status24,
            '7days' => $status7,
            '30days' => $status30,
            '60days' => $status60,
        ];
    }

    private function getGlobalUptimeByDays($project, $days) {

        $allServices = Models\MonitoringService::where('project_id', $project->id)->get();

        $logsCount = 0;
        $uptime = 0;
        $downtime = 0;

        foreach($allServices as $service) {

            $logs = $service->monitoringLogs()->whereDate('created_at', '>=', Carbon::now()->subDays($days)->format('Y-m-d'))->get();
        
            foreach($logs as $log) {
                if($log->status == 'online') {
                    $uptime++;
                } else if($log->status == 'offline') {
                    $downtime++;
                }
                $logsCount++;
            }
        
        }

        if(intVal($uptime) != 0 && $logsCount != 0) {
            $uptime = $uptime / $logsCount;
            $uptime = $uptime * 100;
            $uptime = round($uptime, 1);
        }

        if(intVal($downtime) != 0 && $logsCount != 0) {
            $downtime = $downtime / $logsCount;
            $downtime = $downtime * 100;
            $downtime = round($downtime, 1);
        }

        return [
            'uptime' => $uptime ?? 0,
            'downtime' => $downtime ?? 0,
        ];

    }

    public function addService () {

        $addService = $this->manageService();

        return $addService;
    }

    public function editService ($service_id) {

        $addService = $this->manageService($service_id);

        return $addService;
    }

    private function manageService($service_id = null) {

        $projectID = Session::get('activeProject');

        extract(Request::post());

        if($service_id) {
            $model = Models\MonitoringService::find($service_id);
        }else {
            $model = new Models\MonitoringService;
        }

        $model->name = $serviceTitle;
        $model->type = $serviceType;
        $model->url = $serviceURL;
        $model->ip = $serviceIP;
        $model->port = $servicePort;
        $model->project_id = Session::get('activeProject');
        $model->last_status = 'online';
        $model->save();

        return $model->save();

    }

    public function deleteService($service_id) {

        $deleteService = Models\MonitoringService::find($service_id);

        $checkIfUserIsSameProject = Models\UserProject::where([
            ['user_id', '=', Auth::$user->id],
            ['project_id', '=', $deleteService->project_id],
        ])->first();

        if($checkIfUserIsSameProject) {
            $isSaved = $deleteService->delete();
            return $isSaved;
        } else {
            return false;
        }

    }

    private function check_game ($host, $gamePort) {

        $fp = @fsockopen($host, $gamePort, $errCode, $errStr, 1);

        if ($fp) {
            return true;
            fclose($fp);
        } else {
            return false;
            fclose($fp);
        }
        
    }

    private function check_website($url) {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $data = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);    
        curl_close($ch);

        return $status;

    }
    
    public function checkServiceStatus($serviceId) {

        $serviceData =  Models\MonitoringService::find($serviceId);

        $host = $serviceData->url != null ? $serviceData->url : $serviceData->ip;

        $startMeasureTime = microtime(true);

        // if($serviceData->type == 'teamspeak') {
        //     try {
        //         $status = $this->checkTeamspeak($host, $serviceData->port ?: 9987);
        //     } catch (\Throwable $th) {}
        // }

        if($serviceData->port != null) {

            if($serviceData->type == 'gameserver') {
                $status = $this->check_game($serviceData->ip, $serviceData->port);
            }

        } else {
            $status = $this->check_website($host);
        }
        
        $time_elapsed_secs = (microtime(true) - $startMeasureTime) * 1000;

        if (isset($status) && $status) {
            return ['online', $time_elapsed_secs];
        } else {
            return ['offline', $time_elapsed_secs];
        }
    }

    public function checkTeamspeak($ip, $port) {

        $connectString = "https://api.cleanvoice.ru/ts3/?address=$ip:$port";

        info($connectString);

        $json = file_get_contents($connectString);
        $obj = json_decode($json, true);

        info($obj);

        if(!isset($obj['error'])) return true;

        if($obj['can_connect'] === true) {
            return true;
        } else {
            return false;
        }

        if($obj['error'] === false) return true;

        return false;

    }

    public function showLastMonthUptime($serviceId, $subDays = 30) {

        $uptimeStatusByDay = [];
        $uptimeStatus = [];

        $serviceLogs = Models\MonitoringLogs::where('monitoring_service_id', $serviceId)
        ->where('created_at', '>=', now()->subDays($subDays)->endOfDay())->orderBy('created_at', 'ASC')
        ->get();

        foreach ($serviceLogs as $serviceLog) {
            $date = (new DateTime($serviceLog->created_at))->format('Y-m-d');
            $uptimeStatusByDay [$date][] = $serviceLog; 
        }

        foreach($uptimeStatusByDay as $date => $uptimeStatusByDaySingle) {
            $date = (new DateTime($date))->format('Y-m-d');
            $status = $this->filterDayUptimeStatus($uptimeStatusByDaySingle);
            $courses = $this->getDayUptimeCourses($date, $serviceId);

            $uptimeStatus[] = [
                'date' => $date,
                'status' => $status,
                'courses' => $courses,
                'status_counts' => $this->getStatusCounts($date, $serviceId)
            ];
        }

        $uptimeStatus = $this->fillEmptyDates($uptimeStatus, $subDays);

        return $uptimeStatus;
    }

    private function fillEmptyDates($currentSetDates, $subDays) {

        $interval = DateInterval::createFromDateString('1 day');
        $period = new DatePeriod((new DateTime())->sub(new DateInterval('P'.($subDays - 1).'D')), $interval, new DateTime());

        foreach ($period as $dt) {

            $isAlreadyFilled = false;

            foreach($currentSetDates as $date) {
                if($date['date'] == $dt->format('Y-m-d')) {
                    $isAlreadyFilled = true;
                }
            }

            if(!$isAlreadyFilled) {
                $currentSetDates[] = [
                    'date' => $dt->format('Y-m-d'),
                    'status' => 'no_data'
                ];
            }
        }

        $keys = array_column($currentSetDates, 'date');
        array_multisort($keys, SORT_ASC, $currentSetDates);

        foreach ($currentSetDates as $i => $date) {
            $currentSetDates[$i]['date'] = (new DateTime($date['date']))->format('d.m.Y');
        }
        
        return $currentSetDates;

    }

    private function filterDayUptimeStatus($serviceLogs) {

        $countOnline = 0;
        $countOffline = 0;

        foreach ($serviceLogs as $i => $serviceLog) {
            if ($serviceLogs[$i]->status == 'online') {
                $countOnline++;
            } else {
                $countOffline++;
            }
        }

        $percentOffline = intVal(($countOffline / count($serviceLogs)) * 100);

        /* red status at 30% downtime */
        if($percentOffline >= 30) {
            return 'statusOffline';
        } else if($percentOffline >= 15) {
            return 'statusMaintenance';
        }else {
            return 'statusOnline';
        }

    }

    private function getDayUptimeCourses($date, $service_id) {
        $results = [];

        $monitoringLogsGroups = \App\Models\MonitoringLogs::whereDate('created_at', $date)->where('monitoring_service_id', $service_id)->get()->groupBy(function ($item, $key) {
            return \Carbon\Carbon::parse($item['created_at'])->hour;
        });

        foreach ($monitoringLogsGroups as $monitoringLogsGroup) {
            $countOnline = 0;
            $countOffline = 0;
            $hour = '';

            foreach ($monitoringLogsGroup as $monitoringLogsItem) {
                if(!$hour) {
                    $hour = $monitoringLogsItem->created_at->hour;
                }

                $monitoringLogsItem->status === 'online' ? $countOnline++ : $countOffline++;
            }

            $percentOffline = intVal(($countOffline / count($monitoringLogsGroup)) * 100);

            if($percentOffline >= 30) {
                $results[$hour] = 'danger';
            } else if($percentOffline >= 15) {
                $results[$hour] = 'warning';
            }else {
                $results[$hour] = 'success';
            }
        }

        if(count($results) < 24) {
            for ($i = 0; $i < 24; $i++) {
                if(!isset($results[$i])) {
                    $results[$i] = 'empty';
                }
            }
        }

        return $results;
    }

    private function getStatusCounts($date, $service_id) {
    {
        $countOnline = 0;
        $countOffline = 0;

        $monitoringLogsGroups = \App\Models\MonitoringLogs::whereDate('created_at', $date)->where('monitoring_service_id', $service_id)->get()->groupBy(function ($item, $key) {
            return \Carbon\Carbon::parse($item['created_at'])->hour;
        });

        foreach ($monitoringLogsGroups as $monitoringLogsGroup) {
            $hour = '';

            foreach ($monitoringLogsGroup as $monitoringLogsItem) {
                if (!$hour) {
                    $hour = $monitoringLogsItem->created_at->hour;
                }

                $monitoringLogsItem->status === 'online' ? $countOnline++ : $countOffline++;
            }
        }

        return [
            'online' => $countOnline,
            'offline' => $countOffline,
            'empty' => (144 - ($countOnline + $countOffline))
        ];
    }

    }

    private function checkServicesByAgent($services) {

        $formattedServices = [];

        foreach ($services as $service) {
            $formattedServices[] = [
                'id' => $service->id,
                'name' => $service->name,
                'type' => $service->type,
                'host' => $service->url,
                'ip' => $service->ip,
                'port' => $service->port,
            ];
        }

        $data = Http::post(env('MONITORING_AGENT_HOST').'/checkServices/aosigh74h2oug', ['data' => $formattedServices]);
        $data = $data->getBody()->getContents();

        $returnData = [];

        foreach(json_decode($data, true) as $i => $e) {
            $returnData[] = [
                'id' => $e['id'],
                'status' => $e['reachable'] ? 'online' : 'offline',
                'response_time' => $e['avg']  
            ];
        }

        return $returnData;

    }

    public function checkServiceStatusAutomatic() {

        $services =  Models\MonitoringService::get();

        $servicesStatus = $this->checkServicesByAgent($services);

        foreach ($services as $service) {
            // info('Checking service status: '.$service->name);

            $project = $service->project;

            // if project has maintenance, skip
            if($project->maintenance) {
                continue;
            }

            // get servicestatus with id = $service->id
            $status = array_filter($servicesStatus, function($e) use ($service) {
                return $e['id'] == $service->id;
            });

            $status = $status[array_key_last($status)];

            $lastMonitoringStatus = $service->last_status;

            if ($status['status'] === 'online') {

                if($lastMonitoringStatus == 'offline') {
                    $service->last_status = 'online_1';
                }else if($lastMonitoringStatus == 'online_1') {
                    $service->last_status = 'online_2';
                }else if($lastMonitoringStatus == 'online_2') {
                    $service->last_status = 'online_3';
                }else if($lastMonitoringStatus == 'online_3') {
                    $service->count_online += 1;
                    (new EventActionService)->doEventAction('monitoring_online_again', $service, null);
                    $service->last_status = 'online';
                }

                $service->save();

                

            } else {

                if($lastMonitoringStatus == 'online') {
                    $service->last_status = 'offline_1';
                }else if($lastMonitoringStatus == 'offline_1') {
                    $service->last_status = 'offline_2';
                }else if($lastMonitoringStatus == 'offline_2') {
                    $service->last_status = 'offline_3';
                }else if($lastMonitoringStatus == 'offline_3') {
                    $service->count_offline += 1;
                    (new EventActionService)->doEventAction('monitoring_down', $service, null);
                    $service->last_status = 'offline';
                }

                $service->save();

            }

            $hourlyLog = $this->hourlyLogAlreadySet($service);

            /* check for active hour log */
            if($hourlyLog === false) {

                $addMonitoringLog = $this->createModelFromArray(new Models\MonitoringLogs, [
                    'status' => $status['status'], 
                    'response_time' =>  $status['response_time'] ?: null,
                    'monitoring_service_id' => $service->id 
                ]);
                
            }else {

                $hourlyLog->status = $status['status'];
                $hourlyLog->response_time = $status['response_time'] ?: null;
                $hourlyLog->save();

            }

        }

        return true;
    }

    private function hourlyLogAlreadySet(\App\Models\MonitoringService $service) : MonitoringLogs|bool {

        $todayLogs = Models\MonitoringLogs::where('monitoring_service_id', $service->id)
        ->whereDate('created_at' , '=', Carbon::today())->get();

        foreach($todayLogs as $todayLog) {

            $logHour = Carbon::parse($todayLog->created_at)->format('H');
            $nowHour = Carbon::now()->format('H');

            if($logHour == $nowHour) {
                return $todayLog;
            }
        }

        return false;

    }

    public function checkMaintenanceStatus($projectID) {
        $maintenanceStatus = Models\Maintenance::where('project_id', $projectID)->first();

        return $maintenanceStatus;

    }

    public function changeMaintenanceMessage() {
        extract(Request::post());
        $projectID = Session::get('activeProject');

        $projectMaintenance = Models\Maintenance::where('project_id', $projectID)->first();

        if(!$projectMaintenance) {
            $projectMaintenance = new Models\Maintenance;
        }

        $projectMaintenance->text = $maintenanceMessage;

        $projectMaintenance->project_id = $projectID;
        $projectMaintenance->save();

        if(isset($discordMaintenanceAlert)) {
            $projectMaintenance->discord_channel = $discordMaintenanceAlert;
            $projectMaintenance->save();

            $response = Http::post(env('DISCORD_BOT_HOST').'/maintenanceMessage/'.$projectMaintenance->project->guild_id.'/'.$discordMaintenanceAlert, [
                'message' => $maintenanceMessage,
            ]);
        }

        return $projectMaintenance;

    }

    public function stopMaintenanceMode() {
        $projectID = Session::get('activeProject');
        $projectMaintenance = Models\Maintenance::where('project_id', $projectID)->first();

        if(!$projectMaintenance) return false;

        // $projectMaintenance->text = null;
        $projectMaintenance->delete();

        return $projectMaintenance;

    }

    public function monitoringLogsRemoval10Days() {

        $services = Models\MonitoringService::get();

        foreach($services as $service) {

            $project = $service->project;

            if(empty($project)) continue;

            // check if project subscription is 10 days expired
            if(Carbon::parse($project->subscription->monitoring)->toDateString() <= Carbon::now()->subDays(10)->toDateString()) {
                $service->monitoringLogs()->delete();
                continue;
            }

            // check if project trial is 10 days expired
            if(empty($project->subscription->monitoring) && Carbon::parse($project->trial->monitoring)->toDateString() <= Carbon::now()->subDays(10)->toDateString()) {
                $service->monitoringLogs()->delete();
            }
        }

    }

}