<?php

namespace App\Http\Controllers;

use App\Models;
use Models\Project;
use App\Models\Changelogs;
use Illuminate\Http\Request;
use App\Helpers\Subscription;
use App\Services\ChangelogService;
use Illuminate\Support\Facades\Session;

class ChangelogController extends Controller
{
    public function getChangelogs(ChangelogService $changelogService) {
        if(!Subscription::hasActiveSubscription('one')) return redirect()->to('/products')->with('error', 'Du hast Hood One nicht freigeschaltet.');

        $changelogs = Changelogs::where('project_id', Session::get('activeProject'))->orderBy('id', 'DESC')->get();
        $changelogLikes = 0;
        foreach ($changelogs as $changelog) {
            $changelogLikes += $changelog->votes_up;
        }

        return view('pages/changelog/changelogsView', [
            'changelogs' => $changelogs,
            'changelogLikes' => $changelogLikes
        ]);    

    }
    
    public function viewChangelogExtern(ChangelogService $changelogService, $projectHash, $changelogHash) {
        
        $changelogData = Changelogs::where('hash', $changelogHash)->first();
        $project = (new Models\Project)::find($changelogData->project_id);
        $communityCenterSettings = (new Models\CommunityCenterConfig)::where('project_id', $project->id)->first();
        if(!Subscription::hasActiveSubscription('one', $project->id)) return redirect()->back()->with('error', 'Diese Funktion ist gesperrt');

        if(!$changelogData) {
            return redirect()->back()->with('error', 'Es konnten keine Daten gefunden werden'); 
        }

        if($changelogData->status == 'Private' || $changelogData->status == 'Draft') {
            return redirect()->back()->with('error', 'Du hast keine Berechtigung diesen Changelog einzusehen'); 
        }

        return view('pages/changelog/changelogDetail', [
            'changelogData' => $changelogData,
            'project' => $project,
            'communityCenterSettings' => $communityCenterSettings
        ]);    

    }

    public function addChangelog(ChangelogService $changelogService) {
        
        return view('pages/changelog/addChangelog', []);   
    
    }
    
    public function saveChangelog(ChangelogService $changelogService) {
        
        $saveChangelog = $changelogService->saveChangelog();
        
        if(!$saveChangelog) {
            return redirect()->to('/changelogs')->with('error', 'Es ist ein Fehler aufgetreten'); 
        }
        return redirect()->to('/changelogs')->with('success', 'Dein Changelog wurde erfolgreich gespeichert.');
        
    }
    
    public function editChangelog(ChangelogService $changelogService, $changelogHash) {
        
        $changelogData = Changelogs::where('hash', $changelogHash)->first();
        
        return view('pages/changelog/editChangelog', [
            'changelogData' => $changelogData
        ]);   
        
    }

    public function saveEditChangelog(ChangelogService $changelogService, $hash) {
        
        $saveChangelog = $changelogService->saveEditChangelog($hash);
        
        if(!$saveChangelog) {
            return redirect()->to('/changelogs')->with('error', 'Es ist ein Fehler aufgetreten'); 
        }
        return redirect()->to('/changelogs')->with('success', 'Dein Changelog wurde erfolgreich gespeichert.');
        
    }

    public function likeChangelog(ChangelogService $changelogService, $hash) {
        
        $likeChangelog = $changelogService->likeChangelog($hash);

        if ($likeChangelog === 'too_many_attempts') {
            return redirect()->back()->with('error', 'Du hast diesen Post bereits geliked');
        }
        
        if(!$likeChangelog) {
            return redirect()->back()->with('error', 'Es ist ein Fehler aufgetreten'); 
        }
        return redirect()->back()->with('success', 'Vielen Dank für dein Feedback.');
        
    }

    public function deleteChangelog(ChangelogService $changelogService, $hash) {
        
        $saveChangelog = $changelogService->deleteChangelog($hash);

        if($saveChangelog === 'no_rights') {
            return redirect()->back()->with('error', 'Sooo nicht du Schlingel!'); 
        }
        
        if(!$saveChangelog) {
            return redirect()->to('/changelogs')->with('error', 'Es ist ein Fehler aufgetreten'); 
        }
        return redirect()->to('/changelogs')->with('success', 'Dein Changelog wurde erfolgreich gelöscht.');
        
    }
}
