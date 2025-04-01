<?php
namespace App\Services;

use App\Models;
use App\Helpers\Auth;
use App\Services;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\RateLimiter;

class WishesService extends Service {

    public function getWishes($projectId) {

        $wishes =  Models\Wish::where('project_id', $projectId)->orderBy('votes', 'DESC')->get();

        return $wishes;
    }

    public function getWishesExtern($wishID) {
        
        $wishes =  Models\Wish::where('project_id', $wishID)->orderBy('votes', 'DESC')->get();

        // if(isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        //     $user_unique_identifier = $_SERVER['HTTP_X_FORWARDED_FOR'];
        // } else {
        //     $user_unique_identifier = $_SERVER['REMOTE_ADDR'];
        // }

        $user_unique_identifier = Request::header('do-connecting-ip');

        foreach ($wishes as $i => $wish) {
        
            if (RateLimiter::tooManyAttempts('wish-'.$wish->id.$user_unique_identifier, 1)) {
                $wishes[$i]->canVote = false;
            } else {
                $wishes[$i]->canVote = true;
            }
        }

        return $wishes;
    }

    public function vote() {
        extract(Request::post());
        
        $user_unique_identifier = Request::header('do-connecting-ip');
        $wish = (new Models\Wish)->find($wishID);

        // if(isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        //     $user_unique_identifier = $_SERVER['HTTP_X_FORWARDED_FOR'];
        // } else {
        //     $user_unique_identifier = $_SERVER['REMOTE_ADDR'];
        // }

        if (RateLimiter::tooManyAttempts('wish-'.$wish->id.$user_unique_identifier, 1)) {
            return 'too_many_attempts';
        }
        $operation = $this->setModelFromArray($wish, [
            'votes' => $wish->votes + 1,
        ]);

        $isSaved = $operation->save();

        // if(isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        //     $user_unique_identifier = $_SERVER['HTTP_X_FORWARDED_FOR'];
        // } else {
        //     $user_unique_identifier = $_SERVER['REMOTE_ADDR'];
        // }

        $executed = RateLimiter::attempt('wish-'.$wish->id.$user_unique_identifier, 1, function() {}, 99999999);

        return true;
    }

    public function addWish() {
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

        $addSurvey = $this->createModelFromArray(new Models\Wish, [
            'title' => $wishTitle, 
            'content' => $wishContent, 
            'status' => 'open', 
            'creator' => 'autor', 
            'project_id' => $project_id, 
        ]);

        $sendEventAction = (new Services\EventActionService)->doEventAction('new_user_wish', null, $project_id);

        return $addSurvey;
    }

    public function deleteWish() {
        extract(Request::post());

        $checkTeam = Models\UserProject::where([
            ['user_id', '=', Auth::user('id')],
            ['project_id', '=', session('activeProject')],
        ])->first();


        if(!$checkTeam) {
            return 'not_inside_team';
        }

        $deleteWish = Models\Wish::where([
            ['id', '=', $wishId],
            ['project_id', '=', session('activeProject')],
        ])->first();

        $isSaved = $deleteWish->delete();

        return $isSaved;
    }
    
    public function changeWishTag() {
        extract(Request::post());
        
        $wish = (new Models\Wish)->find($wishId);

        $changeWishTag = $this->setModelFromArray($wish, [
            'tag' => $wishTag,
        ]);

        $isSaved = $changeWishTag->save();
        return $isSaved;
    }

    public function changeAdminAsnwer() {
        extract(Request::post());
        
        $wish = (new Models\Wish)->find($wishId);

        $changeWishTag = $this->setModelFromArray($wish, [
            'answer' => $adminAnswerText,
        ]);

        $isSaved = $changeWishTag->save();
        return $isSaved;
    }
}