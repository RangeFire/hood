<?php
namespace App\Services;

use App\Models;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\RateLimiter;


class SurveyService extends Service {

    public function getAll() {
        $surveys =  Models\Survey::where('project_id', session('activeProject'))->get();

        foreach($surveys as $survey) {

            $answers = [
                'one' => 0,
                'two' => 0,
                'three' => 0,
            ];

            foreach($survey->answers as $answer) {
                if($answer->answere == 'answere_one')
                    $answers['one']++;
                else if($answer->answere == 'answere_two')
                    $answers['two']++;
                else if($answer->answere == 'answere_three')
                    $answers['three']++;
            }

            $answers['count'] = $answers['one'] + $answers['two'] + $answers['three'];

            if($answers['count']) {
                $answers['one_percent'] = round($answers['one'] / $answers['count'] * 100);
                $answers['two_percent'] = round($answers['two'] / $answers['count'] * 100);
                $answers['three_percent'] = round($answers['three'] / $answers['count'] * 100);
            }else {
                $answers['one_percent'] = 0;
                $answers['two_percent'] = 0;
                $answers['three_percent'] = 0;
            }


            $survey->formattedAnswers = $answers;

        }

        return $surveys;
    }

    public function getSingle($surveyID) {
        $surveys =  Models\Survey::find($surveyID);

        return $surveys;
    }

    public function addSurvey() {
        extract(Request::post());

        $emoji = json_decode($custom_emojis, TRUE);

        $addSurvey = $this->createModelFromArray(new Models\Survey, [
            'title' => $surveyTitle, 
            'content' => $surveyContent, 
            'answere_one' => $answere1, 
            'answere_two' => $answere2, 
            'answere_three' => $answere3, 
            'icon_one' => $emoji[0], 
            'icon_two' => $emoji[1], 
            'icon_three' => $emoji[2], 
            'project_id' => session('activeProject'),
            'stop_At' => $stopAt,
        ]);

        return $addSurvey;
    }

    public function stopSurvey ($surveyID) {
        $survey = (new Models\Survey)->find($surveyID);
        $operation = $this->setModelFromArray($survey, [
            'status' => 'stop',
        ]);

        $isSaved = $operation->save();

        return $isSaved;
    }

    public function startSurvey ($surveyID) {
        $survey = (new Models\Survey)->find($surveyID);
        $operation = $this->setModelFromArray($survey, [
            'status' => 'active',
        ]);

        $isSaved = $operation->save();

        return $isSaved;
    }

    public function addAnswere($survey_id) {
        extract(Request::post());

        $user_unique_identifier = Request::header('do-connecting-ip');
        
        if (RateLimiter::tooManyAttempts('survey'.$survey_id.$user_unique_identifier, 1)) {
            return 'too_many_attempts';
        }

        $addSurvey = $this->createModelFromArray(new Models\SurveyAnswere, [
            'answere' => $surveyAnswere, 
            'survey_id' => $survey_id, 
        ]);

        $executed = RateLimiter::attempt('survey'.$survey_id.$user_unique_identifier, 1, function() {}, 99999999);

        return $addSurvey;
    }

    public function getSurveyStatistics($surveyID) {

        $surveysAnswers = Models\SurveyAnswere::where('survey_id', $surveyID)->count();
        $answerOne = Models\SurveyAnswere::where([['survey_id', '=', $surveyID],['answere', '=', 'answere_one']])->count();
        $answerTwo = Models\SurveyAnswere::where([['survey_id', '=', $surveyID],['answere', '=', 'answere_two']])->count();
        $answerThree= Models\SurveyAnswere::where([['survey_id', '=', $surveyID],['answere', '=', 'answere_three']])->count();
        $countSurveysAnswers = (new Models\SurveyAnswere)->countSurveyAnswer($surveyID);

        $answer_one_percent = null;
        $answer_two_percent= null;
        $answer_three_percent = null;

        if($countSurveysAnswers) {
            $answer_one_percent = round($answerOne / $countSurveysAnswers * 100);
        }
        if($countSurveysAnswers) {   
            $answer_two_percent = round($answerTwo / $countSurveysAnswers * 100);
        }
        if($countSurveysAnswers) {
            $answer_three_percent = round($answerThree / $countSurveysAnswers * 100);
        }

        $surveyStatistics = [
            'countAnswers' => $countSurveysAnswers, 
            'answer_one' => $answer_one_percent,
            'answer_two' => $answer_two_percent,
            'answer_three' => $answer_three_percent, 
        ];

        return $surveyStatistics;
    }


    

}