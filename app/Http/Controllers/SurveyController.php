<?php

namespace App\Http\Controllers;

use App\Helpers\Auth;
use Illuminate\Http\Request;
use App\Services\SurveyService;
use App\Services\ProjectService;
use App\Models;


class SurveyController extends Controller
{
    public function surveys(SurveyService $surveyService) {
        
        if(!Auth::hasPermission('surveys')) return redirect('/');

        $surveys = $surveyService->getAll();

        return view('pages/surveysView', [
            'surveys' => $surveys
        ]);    
    }

    public function surveyShowExtern(SurveyService $surveyService, ProjectService $projectService, $projectHash, $surveyID) {

        $getProjectData = $projectService->getCommunityCenterHome($projectHash);
        $survey = $surveyService->getSingle($surveyID);

        return view('pages/surveyExternView', [
            'survey' => $survey,
            'project' => $getProjectData,
            'projectHash' => $projectHash,
        ]);    
    }

    public function surveyShowResultExtern(SurveyService $surveyService, $projectHash) {

        $project = (new Models\Project)->findProjectByHash($projectHash);
        //$wishes = $surveyService->getWishesExtern($project->id);

        return view('pages/communityCenter/surveyResult', [
            //'wishes' => $wishes,
            'project' => $project,
            'count' => 0
        ]);   
    }

    public function addSurvey(SurveyService $surveyService) {

        if(!Auth::hasPermission('surveys')) return redirect('/');

        $isSaved = $surveyService->addSurvey();

        if($isSaved != 'too_many_attempts') return redirect()->back()->with('success', 'Die Umfrage wurde erfolgreich erstellt und veröffentlicht.');
        return redirect()->back()->with('error', 'Es ist ein Fehler aufgetreten'); 
    }

    public function stopSurvey(SurveyService $surveyService, $surveyID) {
        
        if(!Auth::hasPermission('surveys')) return redirect('/');

        $isSaved = $surveyService->stopSurvey($surveyID);

        if($isSaved) return redirect()->back()->with('success', 'Die Umfrage wurde gestopt.');
        return redirect()->back()->with('error', 'Es ist ein Fehler aufgetreten'); 
    }

    public function startSurvey (SurveyService $surveyService, $surveyID) {
        
        if(!Auth::hasPermission('surveys')) return redirect('/');

        $isSaved = $surveyService->startSurvey($surveyID);

        if($isSaved) return redirect()->back()->with('success', 'Abstimmungen sind jetzt wieder möglich.');
        return redirect()->back()->with('error', 'Es ist ein Fehler aufgetreten'); 
    }

    public function addAnswere(SurveyService $surveyService, $projectHash, $survey_id) {

        $operation = $surveyService->addAnswere($survey_id);

        if ($operation == 'too_many_attempts') {
            return redirect()->to('/'.$projectHash.'/survey/result/'.$survey_id)->with('error', 'Du hast bereits abgestimmt');
        }

        if($operation) return redirect()->to('/'.$projectHash.'/survey/result/'.$survey_id);
        return redirect()->back()->with('error', 'Es ist ein Fehler aufgetreten'); 
    }

    public function showStatistics(SurveyService $surveyService, $surveyID) {
        
        $survey = $surveyService->getSingle($surveyID);
        $answerStatistics = $surveyService->getSurveyStatistics($surveyID);

        if($survey && $answerStatistics) return view('pages/communityCenter/surveyResult', [
            'survey' => $survey,
            'answereStatistics' => $answerStatistics
        ]);  
        return redirect()->back()->with('error', 'Es ist ein Fehler aufgetreten'); 
        }
    
}
