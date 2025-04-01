<?php

namespace App\Helpers;

use App\Models;
use Illuminate\Support\Facades\Session;

class Subscription {

    public static $project;

    public static function showRuntime($productType, $project = null) {

        $project_id = $project ? $project->id : Session::get('activeProject');
        $dateToday = date("Y-m-d h:i");

        // Check if Hood One is Active
        $hoodOne = Models\ProjectSubscription::where('project_id', $project_id)->first()->one;
        if($hoodOne != null && $hoodOne >= $dateToday) {
            return Models\ProjectSubscription::where('project_id', $project_id)->first()->one;
        }
        
        $trialRuntime = Models\Project::where('id', $project_id)->first()->trial_end;
        $productRuntime = Models\ProjectSubscription::where('project_id', $project_id)->first()->$productType;
        
        if($trialRuntime >= $dateToday) return $trialRuntime;
        if($productRuntime >= $dateToday) return $productRuntime;
        return false;

    }
    

    public static function hasFreeTrial($project = null) {
        info("Projekt in Trial ". $project);
        $project_id = $project ? $project : Session::get('activeProject');
        if(!$project_id) return false;
        
        $projectTrial = Models\Project::where('id', $project_id)->first();
        $dateToday = date("Y-m-d");

        $diff = strtotime($projectTrial->trial_end) - strtotime($dateToday);
        if($projectTrial->trial_end >= $dateToday) return round($diff / 86400);
        return false;

    }

    
    public static function hasActiveSubscription($type, $project = null) {
        if(Subscription::hasFreeTrial($project)) return true;
        $project_id = $project ? $project : Session::get('activeProject');

        $projectSubscriptions = Models\ProjectSubscription::where('project_id', $project_id)->first();

        if(!$projectSubscriptions) {
            // fallback - create a new subscription if not set
            $projectSubscriptions = new Models\ProjectSubscription;
            $projectSubscriptions->project_id = $project_id;
            $projectSubscriptions->save();
        }

        $dateToday = date("Y-m-d h:i");
        
        if(($projectSubscriptions->one >= $dateToday)) return true;
        if($type == 'monitoring' && ($projectSubscriptions->monitoring >= $dateToday)) return true;
        if($type == 'support' && ($projectSubscriptions->support >= $dateToday)) return true;
        if($type == 'branding' && ($projectSubscriptions->branding >= $dateToday)) return true;


        return false;

    }



}

?>