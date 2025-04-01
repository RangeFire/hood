<?php

namespace App\Http\Controllers;

use App\Helpers\Auth;
use App\Helpers\Subscription;
use Illuminate\Http\Request;
use App\Services\ProjectService;
use App\Services\MonitoringService;
use App\Services\EventActionService;
use Illuminate\Support\Facades\Session;
use App\Models\Project;
use App\Models\CommunityCenterConfig;
use Illuminate\Support\Facades\Http;

class MonitoringController extends Controller
{
    public function getServices(MonitoringService $monitoringService) {

        if(!Auth::hasPermission('monitoring')) return redirect('/');
        if(!Subscription::hasActiveSubscription('monitoring')) return redirect()->to('/products')->with('error', 'Du hast Hood One nicht freigeschaltet.');

        $services = $monitoringService->getServices(Session::get('activeProject'));
        $services = $monitoringService->formatServicesForInternalMonitoringPage($services);

        $eventActions = (new EventActionService)->getEventActions();

        $globalUptime = $monitoringService->getGlobalUptime();
        $globalOffline = $monitoringService->getGlobalOffline();
        $todayChecks = $monitoringService->getTodayChecks();

        $project = Project::find(Session::get('activeProject'));
        if($project->guild_id) {
            $discord_channels = (string) Http::get(env('DISCORD_BOT_HOST').'/getChannels/'.$project->guild_id);
            $discord_channels = json_decode($discord_channels, false);
        }


        return view('pages/monitoringView', [
            'services' => $services,
            'alerts' => $eventActions,
            'globalUptime' => $globalUptime,
            'globalOffline' => $globalOffline,
            'todayChecks' => $todayChecks,
            'maintenance' => $monitoringService->checkMaintenanceStatus(Session::get('activeProject')),
            'discord_channels' => $discord_channels ?? null,
        ]);   
    }

    public function addService(MonitoringService $monitoringService) {

        if(!Auth::hasPermission('monitoring')) return redirect('/');

        $isSaved = $monitoringService->addService();

        if($isSaved) return redirect()->back()->with('success', 'Der Monitoring Service wurde erstellt. Es dauert bis zu 10 Minuten damit er aktiv ist.');
        return redirect()->back()->with('error', 'Es ist ein Fehler aufgetreten'); 
    }

    public function editService(MonitoringService $monitoringService, $service_id) {

        if(!Auth::hasPermission('monitoring')) return redirect('/');

        $isSaved = $monitoringService->editService($service_id);

        if($isSaved) return redirect()->back()->with('success', 'Der Monitoring Service wurde bearbeitet. Es dauert bis zu 10 Minuten damit die Änderungen aktiv werden.');
        return redirect()->back()->with('error', 'Es ist ein Fehler aufgetreten'); 
    }

    public function addAlert(EventActionService $eventActionService) {

        if(!Auth::hasPermission('monitoring')) return redirect('/');
        
        $isSaved = $eventActionService->addAlert();
        
        if($isSaved) return redirect()->back()->with('success', 'Der Alert wurde erfolgreich erstellt');
        return redirect()->back()->with('error', 'Es ist ein Fehler aufgetreten'); 
    }

    public function editAlert(EventActionService $eventActionService, $alert_id) {

        if(!Auth::hasPermission('monitoring')) return redirect('/');
        
        $isSaved = $eventActionService->editAlert($alert_id);
        
        if($isSaved) return redirect()->back()->with('success', 'Der Alert wurde erfolgreich gespeichert');
        return redirect()->back()->with('error', 'Es ist ein Fehler aufgetreten'); 
    }

    public function deleteAlert(EventActionService $eventActionService, $alert_id) {

        if(!Auth::hasPermission('monitoring')) return redirect('/');
        
        $isSaved = $eventActionService->deleteAlert($alert_id);
        
        if($isSaved) return redirect()->back()->with('success', 'Der Alert wurde erfolgreich gelöscht');
        return redirect()->back()->with('error', 'Es ist ein Fehler aufgetreten'); 
    }

    public function deleteService(MonitoringService $monitoringService, $service_id) {
        
        if(!Auth::hasPermission('monitoring')) return redirect('/');

        $isSaved = $monitoringService->deleteService($service_id);

        if($isSaved) return redirect()->back()->with('success', 'Dein Monitoring Service wurde gelöscht.');
        return redirect()->back()->with('error', 'Es ist ein Fehler aufgetreten'); 
    }

    public function checkServiceStatusAutomatic(MonitoringService $monitoringService) {
        $services = $monitoringService->checkServiceStatusAutomatic();

        return true;
    }

    public function monitoringExtern(ProjectService $projectService, MonitoringService $monitoringService, $projectHash) {
        $getProjectData = $projectService->getCommunityCenterHome($projectHash);
        $communityCenterSettings = (new CommunityCenterConfig)::where('project_id', $getProjectData->id)->first();

        $services = $monitoringService->getServices($getProjectData->id, 60);
        if(!Subscription::hasActiveSubscription('monitoring', $getProjectData->id,)) return redirect()->back()->with('error', 'Du hast Hood One wurde nicht aktiviert.');
                
        return view('pages/communityCenter/monitoring', [
            'project' => $getProjectData,
            'services' => $services,
            'statistics' => $monitoringService->getLastStatistics($getProjectData->id),
            'maintenance' => $monitoringService->checkMaintenanceStatus($getProjectData->id),
            'communityCenterSettings' => $communityCenterSettings,
        ]);   
    }

    public function changeMaintenanceMessage(MonitoringService $monitoringService) {
        $isSaved = $monitoringService->changeMaintenanceMessage();

        if($isSaved) return redirect()->back()->with('success', 'Der Wartungsmodus ist aktiv');
        return redirect()->back()->with('error', 'Es ist ein Fehler aufgetreten'); 
    }

    public function stopMaintenanceMode(MonitoringService $monitoringService) {
        $isSaved = $monitoringService->stopMaintenanceMode();

        if($isSaved) return redirect()->back()->with('success', 'Der Wartungsmodus wurde beendet');
        return redirect()->back()->with('error', 'Es ist ein Fehler aufgetreten'); 
    }

}
