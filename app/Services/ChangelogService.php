<?php
namespace App\Services;

use App\Models;
use App\Helpers\Auth;
use App\Models\Changelogs;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\RateLimiter;

class ChangelogService extends Service {

    public function saveChangelog() {

        extract(Request::post());

        // dd($changelogFinalContent);
        $saveChangelog = $this->createModelFromArray(new Models\Changelogs(), [
            'title' => $changelogPostTitle, 
            'content' => $changelogContent, 
            'status' => $changelogPostStatus, 
            'creator' => Auth::$user->id, 
            'hash' => (new ProjectService)->generateProjektHash(),
            'project_id' => Session::get('activeProject'), 
        ]);

        return $saveChangelog;

    }

    public function saveEditChangelog($hash) {

        $changelog = Changelogs::where('hash', $hash)->first();
        extract(Request::post());

        $saveChangelog = $this->setModelFromArray($changelog, [
            'title' => $changelogPostTitle, 
            'content' => $changelogContent, 
            'status' => $changelogPostStatus, 
        ]);

        $isSaved = $saveChangelog->save();
        if(!$saveChangelog) return false;

        return true;
        
    }

    public function likeChangelog($hash) {


        $user_unique_identifier = Request::header('do-connecting-ip');

        if (RateLimiter::tooManyAttempts('changelogLike'.$hash.$user_unique_identifier, 1)) {
            return 'too_many_attempts';
        }

        $changelog = Changelogs::where('hash', $hash)->first();

        $saveChangelog = $this->setModelFromArray($changelog, [
            'votes_up' => $changelog->votes_up + 1, 
        ]);

        $isSaved = $saveChangelog->save();
        if(!$isSaved) return false;

        $executed = RateLimiter::attempt('changelogLike'.$hash.$user_unique_identifier, 1, function() {}, 99999999);

        return $isSaved;
        
    }
    
    public function deleteChangelog($hash) {

        $changelog = Changelogs::where('hash', $hash)->first();
        extract(Request::post());

        $checkTeam = Models\UserProject::where([
            ['user_id', '=', Auth::user('id')],
            ['project_id', '=', $changelog->project_id],
        ])->first();


        if(!$checkTeam) {
            return 'no_rights';
        }

        $deleteChangelog = Models\Changelogs::where([
            ['hash', '=', $hash],
            ['project_id', '=', session('activeProject')],
        ])->first();

        $isSaved = $deleteChangelog->delete();

        return $isSaved;
        
    }
}