<?php
namespace App\Services;

use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Request;
use App\Models;
use App\Helpers\Auth;
use Illuminate\Support\Facades\RateLimiter;
use App\Services;


class BugreportService extends Service {

    public function getBugreports($projectId) {

        $wishes =  Models\Bugreport::where('project_id', $projectId)->get();

        return $wishes;
    }

    public function getBugreportsExtern($project_id) {
        
        $bugreports =  Models\Bugreport::where('project_id', $project_id)->get();

        // if(isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        //     $user_unique_identifier = $_SERVER['HTTP_X_FORWARDED_FOR'];
        // } else {
        //     $user_unique_identifier = $_SERVER['REMOTE_ADDR'];
        // }

        $user_unique_identifier = Request::header('do-connecting-ip');

        foreach ($bugreports as $i => $bugreport) {
        
            if (RateLimiter::tooManyAttempts('bugreport-'.$bugreport->id.$user_unique_identifier, 1)) {
                $bugreports[$i]->canVote = false;
            } else {
                $bugreports[$i]->canVote = true;
            }
        }

        return $bugreports;
    }

    public function addBugreport() {
        extract(Request::post());

        // if(isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        //     $user_unique_identifier = $_SERVER['HTTP_X_FORWARDED_FOR'];
        // } else {
        //     $user_unique_identifier = $_SERVER['REMOTE_ADDR'];
        // }
        
        // if (RateLimiter::tooManyAttempts('addWish'.$project_id.$user_unique_identifier, 3)) {
        //     return 'too_many_attempts';
        // }

        // $executed = RateLimiter::attempt('addWish'.$project_id.$user_unique_identifier, 3, function() {}, 7200);

        $addSurvey = $this->createModelFromArray(new Models\Bugreport(), [
            'title' => $bugreportTitle, 
            'content' => $bugreportDescription, 
            'status' => 'open', 
            'attachment' => $attachmentURL, 
            'project_id' => $project_id, 
        ]);

        $sendEventAction = (new Services\EventActionService)->doEventAction('new_bugreport', null, $project_id);

        return $addSurvey;
    }

    public function deleteBugreport() {
        extract(Request::post());

        $checkTeam = Models\UserProject::where([
            ['user_id', '=', Auth::user('id')],
            ['project_id', '=', session('activeProject')],
        ])->first();


        if(!$checkTeam) {
            return 'not_inside_team';
        }

        $deleteBugreport = Models\Bugreport::where([
            ['id', '=', $bugreportId],
            ['project_id', '=', session('activeProject')],
        ])->first();

        $isSaved = $deleteBugreport->delete();

        return $isSaved;
    }
    
    public function changeBugreportTag() {
        extract(Request::post());
        
        $bugreport = (new Models\Bugreport())->find($bugreportId);

        $changeBugreportTag = $this->setModelFromArray($bugreport, [
            'tag' => $bugreportTag,
        ]);

        $isSaved = $changeBugreportTag->save();
        return $isSaved;
    }

    public function changeAdminAsnwer() {
        extract(Request::post());
        
        $bugreport = (new Models\Bugreport())->find($bugreportId);

        $changeBugreportAnswer = $this->setModelFromArray($bugreport, [
            'answer' => $adminAnswerText,
        ]);

        $isSaved = $changeBugreportAnswer->save();
        return $isSaved;
    }
}