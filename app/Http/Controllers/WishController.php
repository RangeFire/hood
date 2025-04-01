<?php

namespace App\Http\Controllers;

use App\Models;
use App\Helpers\Auth;
use Illuminate\Http\Request;
use App\Helpers\Subscription;
use App\Services\WishesService;


class WishController extends Controller
{
    public function wishesExtern(WishesService $wishesService, $projectHash) {
        $project = (new Models\Project)->findProjectByHash($projectHash);
        $wishes = $wishesService->getWishesExtern($project->id);
        $communityCenterSettings = (new Models\CommunityCenterConfig)::where('project_id', $project->id)->first();
        if(!Subscription::hasActiveSubscription('one', $project->id)) return redirect()->back()->with('error', 'Diese Funktion ist gesperrt.');
                    
            return view('pages/communityCenter/wishes', [
                'wishes' => $wishes,
                'project' => $project,
                'count' => 0,
                'communityCenterSettings' => $communityCenterSettings,
        ]);   
    }

    public function getWishes(WishesService $wishesService) {

        if(!Auth::hasPermission('wishes')) return redirect('/');
        if(!Subscription::hasActiveSubscription('one')) return redirect()->to('/products')->with('error', 'Du hast Hood One nicht freigeschaltet.');

        $wishes = $wishesService->getWishes(session('activeProject'));

        return view('pages/wishesView', [
            'wishes' => $wishes,
            'count' => 0
        ]);   
    }

    public function addWish(WishesService $wishesService) {

        $addWish = $wishesService->addWish();

        if($addWish === 'too_many_attempts') {
            return redirect()->back()->with('error', 'Du hast in den letzten 120min zu oft Wünsche geäußert.'); 
        } 
        if(!$addWish) {
            return redirect()->back()->with('error', 'Es ist ein Fehler aufgetreten'); 
        }
        return redirect()->back()->with('success', 'Dein Wunsch/Idee wurde veröffentlicht.');
    }

    public function deleteWish(WishesService $wishesService) {

        if(!Auth::hasPermission('wishes')) return redirect('/');
        
        $deleteWish = $wishesService->deleteWish();

        if($deleteWish === 'not_inside_team') {
            return redirect()->back()->with('error', 'Du hast keine Berechtigung diesen Wunsch zu löschen.'); 
        } 
        if(!$deleteWish) {
            return redirect()->back()->with('error', 'Es ist ein Fehler aufgetreten'); 
        }
        return redirect()->back()->with('success', 'Der Wunsch wurde gelöscht');
    }

    public function vote(WishesService $wishesService) {
        
        $vote = $wishesService->vote();

        if($vote === 'too_many_attempts') {
            return redirect()->back()->with('error', 'Du hast bereits für diesen Vorschlag gestimmt'); 
        } 
        if(!$vote) {
            return redirect()->back()->with('error', 'Es ist ein Fehler aufgetreten'); 
        }
        return redirect()->back()->with('success', 'Dein Vote wurde abgegeben. Vielen Dank');

    }

    public function changeWishTag(WishesService $wishesService) {

        $changeWishTag = $wishesService->changeWishTag();

        if(!$changeWishTag) {
            return redirect()->back()->with('error', 'Es ist ein Fehler aufgetreten'); 
        }
        return redirect()->back()->with('success', 'Du hast den Tag erfolgreich gespeichert.');
    }

    public function changeAdminAsnwer(WishesService $wishesService) {

        $changeAdminAnswer = $wishesService->changeAdminAsnwer();

        if(!$changeAdminAnswer) {
            return redirect()->back()->with('error', 'Es ist ein Fehler aufgetreten'); 
        }
        return redirect()->back()->with('success', 'Du hast deine Antwort erfolgreich gespeichert.');
    }
}
