<?php

namespace App\Http\Controllers;

use App\Models;
use App\Helpers\Auth;
use Illuminate\Http\Request;
use App\Services\BugreportService;

class BugreportController extends Controller
{

        public function getBugreports(BugreportService $bugreportService) {

            if(!Auth::hasPermission('bugreports')) return redirect('/');
    
            $bugreports = $bugreportService->getBugreports(session('activeProject'));
            $countBugreports = count($bugreports);
    
            return view('pages/bugreportsView', [
                'bugreports' => $bugreports,
                'count' => 0,
                'countOpenBugreports' => $countBugreports,
            ]);   
        }

        public function getBugreportsExtern(BugreportService $bugreportService, $projectHash) {
            $project = (new Models\Project)->findProjectByHash($projectHash);
            $bugreports = $bugreportService->getBugreportsExtern($project->id);
            $communityCenterSettings = (new Models\CommunityCenterConfig)::where('project_id', $project->id)->first();
            
            return view('pages/communityCenter/bugreport', [
                'bugreports' => $bugreports,
                'project' => $project,
                'count' => 0,
                'communityCenterSettings' => $communityCenterSettings,
            ]);   
        }
    
        public function addBugreport(BugreportService $bugreportService) {
    
            $addBugreport = $bugreportService->addBugreport();
    
            if($addBugreport === 'too_many_attempts') {
                return redirect()->back()->with('error', 'Du hast in den letzten 120min zu oft Bugs reportet.'); 
            } 
            if(!$addBugreport) {
                return redirect()->back()->with('error', 'Es ist ein Fehler aufgetreten'); 
            }
            return redirect()->back()->with('success', 'Dein Bugreport wurde erfolgreich eingereicht.');
        }
    
        public function deleteBugreport(BugreportService $bugreportService) {
                
            $deleteBugreport = $bugreportService->deleteBugreport();
    
            if($deleteBugreport === 'not_inside_team') {
                return redirect()->back()->with('error', 'Du hast keine Berechtigung diesen Bugreport zu löschen.'); 
            } 
            if(!$deleteBugreport) {
                return redirect()->back()->with('error', 'Es ist ein Fehler aufgetreten'); 
            }
            return redirect()->back()->with('success', 'Der Bugreport wurde gelöscht');
        }
    
        public function changeBugreportTag(BugreportService $bugreportService) {
    
            $changeBugreportTag = $bugreportService->changeBugreportTag();
    
            if(!$changeBugreportTag) {
                return redirect()->back()->with('error', 'Es ist ein Fehler aufgetreten'); 
            }
            return redirect()->back()->with('success', 'Du hast den Tag erfolgreich gespeichert.');
        }
    
        public function changeAdminAsnwer(BugreportService $bugreportService) {

            $changeAdminAnswer = $bugreportService->changeAdminAsnwer();
    
            if(!$changeAdminAnswer) {
                return redirect()->back()->with('error', 'Es ist ein Fehler aufgetreten'); 
            }
            return redirect()->back()->with('success', 'Du hast deine Antwort erfolgreich gespeichert.');
        }
}
