<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SurveyAnswere extends Model
{
    use HasFactory;
    protected $table = 'survey_answere';

    protected $guarded = [];

    public function countSurveyAnswer($surveyID) {

        $answers = $this->where('survey_id', $surveyID)->count();

        return $answers;
    }

}
