<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Services\EventActionService;

class EventActionController extends Controller
{
    
    public function index(EventActionService $eventActionService) {

        $eventActions = $eventActionService->getEventActions();
        
        return response()->json([
            'message' => 'EventActionController::index()',
        ]);

    }

    public function APICallEventAction(EventActionService $eventActionService, $livechat_token, $event) {
        $project = Project::where('livechat_token', $livechat_token)->first();

        $eventActions = $eventActionService->doEventAction($event, null, $project->id);
        return response()->json([
            'message' => 'Called',
        ]);

    }

    

}
