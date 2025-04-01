<?php

namespace App\Http\Controllers;

use Exception;
use \Firebase\JWT\JWT;
use Illuminate\Http\Request;
use App\Services\ProjectService;
use App\Models\CommunityCenterConfig;
use Illuminate\Support\Facades\Session;
use App\Helpers\Subscription;



class ProjectController extends Controller
{
    public function create(ProjectService $projectService) {
        $createProject = $projectService->create();

        return redirect('/start/success');
    }

    public function join(ProjectService $projectService) {
        $joinProject = $projectService->join();
        
        if($joinProject) {
            return redirect('/start/success');
        }

        return redirect()->back()->with('error', 'Der Einladungcode ist falsch');
    }

    public function change(ProjectService $projectService, $project_id) {
        $createProject = $projectService->change($project_id);

        return redirect('/dashboard');
    }

    public function generateInvite(ProjectService $projectService, $project_id) {
        $createProject = $projectService->generateInviteCode($project_id);

        return $createProject;
    }

    public function setProjectGuildID(ProjectService $projectService) {
        $createProject = $projectService->setProjectGuildID();

        if(!$createProject) return redirect('/settings')->with('error', 'Der Bot konnte nicht auf deinem Server hinzugefügt werden.');

        return redirect('/settings')->with('success', 'Der Discord-Bot wurde erfolgreich verknüpft');
    }

    public function setProjectInitChannel(ProjectService $projectService) {
        $createProject = $projectService->setProjectInitChannel();
        return redirect()->back()->with('success', 'Die Supportnachricht wurde erfolgreich gesendet'); 
    }

    public function deleteProject(ProjectService $projectService) {
        $deleteProject = $projectService->deleteProject();

        if($deleteProject == 'not_owner') {
            return redirect()->back()->with('error', 'Du bist nicht der Projekt-Owner');
        }

        if(!$deleteProject) {
            return redirect()->back()->with('error', 'Das Projekt konnte nicht gelöscht werden');
        }

        return redirect('/start')->with('success', 'Dein Projekt wurde gelöscht'); 
    }

    public function communityCenterHome(ProjectService $projectService, $projectHash) {
        $getProjectData = $projectService->getCommunityCenterHome($projectHash);
        $communityCenterSettings = (new CommunityCenterConfig)::where('project_id', $getProjectData->id)->first();

        return view('pages/communityCenter/home', [
            'project' => $getProjectData,
            'communityCenterSettings' => $communityCenterSettings,
        ]);   
    }

    public function livechat(ProjectService $projectService, $livechat_token) {

        $project = $projectService->getProjectByLivechatID($livechat_token);
        if((Subscription::hasActiveSubscription('support', $project->id))) {
            // return view('pages/comp/livechat', [
            return view('pages/comp/livechat', [
                'project' => $project,
            ]);   
        }

        return false;

    }

    public function livechat_script(ProjectService $projectService, $livechat_token) {

        $project = $projectService->getProjectByLivechatID($livechat_token);

        // return view('pages/comp/livechat', [
        return view('pages/comp/livechat-script', [
            'project' => $project,
            'livechat_token' => $livechat_token,
        ]);   
    }

    public function createCommunityCenterDBEntry(ProjectService $projectService) {
        $createCommunityCenterDBEntry = $projectService->createCommunityCenterDBEntry();

        return redirect('/dashboard');
    }

    public function loadJwt(ProjectService $projectService) {
        
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
        
        $privateKey = <<<EOD
        -----BEGIN PRIVATE KEY-----
        MIIEvwIBADANBgkqhkiG9w0BAQEFAASCBKkwggSlAgEAAoIBAQDbHA84jPYyRzeJ
        WePJsBwOQn6dNPP7guObJNqd2sQuYUz6ZosYb9x90LL4FQ5Oo4SPbOhVNUu3mxWc
        uOniuILVGj6TFg40f8JKTl/2TwoYnZXjMdNeY4DzfJy0nV1MO/4Y+xYgCHNmG8Uf
        BFcAKiV7LJhi/jMLvHItcySMoKJTN3nZtUHyCl/hBjkt1/6JwBwjD/JUb/vZNzsl
        hW168WdO3U+SQ5AzKcZ609myFDbDGHeUbuZ/18h2v/93fG2yJJRjpMLkPWpVF7xM
        TU5sdpR0l97aKpyroxCvgTsAJHZ9xm8+2QhLgFV2VoVYuwuMHscpJHwatXzdEjbF
        32doAKjXAgMBAAECggEACjGs6NBW6stS1qWWsJbS31XE2hL3ucwC3Rniv/UvKz2k
        qeiLPSi3+Fr1Q9yHuca0aQ92/PJJvD7PZRpahPO/rO/kWTSHMA/nneYClsBiDSru
        9jVZvhD6ejMu5hC4oZtC8BRlsmzVbaE9U3sz6J9aKhGblc0TQLVuSUk+08bsQ0HO
        uXA4XBzOd1PzGtOKZgnWgOO4hYN9697ulfAGDe1uSxRe8kiLoldD6LjxeH/lqpKc
        LffVclHZlZ1mqBEdgllFag1C2hGd3TNH04sMH0/oHMms4UW6RnSx4QoJGp3PKz/P
        elPdZHVCGUqx5BwEahENDaWfRTwnuCwEUfUpue7JVQKBgQD5elnFvSq2Y4CkG77w
        RSKm+zPfUxPSSnfaJBPCPCKGCsRvaRbiWhQJGnt4v/A7rnKkvHxTmrDwvYBttEWH
        E8Uxrdt1JTVBwBAnp9Vejq/zQql5GZgM4zaFNm15RbBeB2w5uZzpJJjqAL7v6ses
        y6oo9syYlhEbi0RoWqoH1B5ajQKBgQDg1ndvc0zYKzLQBT1ql2EFMZ0amclshKjb
        OB4SSMAIwnEl7SccctbmhpneERC/o9vjke7Pqy/EZjBO2Yqkzlk54PoeBXN7FmG9
        /68Z399YXoovvL5KwMcgHq97DA+MGCsuZcNrliVK/PXHax7b5xiyn8T3kjl8tGtb
        PuVv6HjJ8wKBgQCwD/vJBE5fd2ty5a3jzTa+V2vtQKktcKaSyYE0Q9ItfO3Snnyq
        891N62WV+wckZ2G7BtZK/lG/JE29nkqvIHG7NLI7Qy4Jn+0Gv3hiihp8d+A4eaqH
        9dYlPxsVSexE/8IOHwMwukY8ZphZovyV4wnMbRhI2ydpo+h8KPf0HGvrhQKBgQCy
        tya2iyKwzR2VpiScXnl7BGXJaCZoQMZrgh69D+C81bnUmL426b2R/bm+fzgd7GRz
        bmMx5POSHFwXwOloeLEJxZ9qT5DsbO0CdoM7gOzqNDOJIHDtwHBHQrzfhMWy6N8i
        ATelg/JeudWvTqF30Cici0yoMS3Kxypym/sJyfKBKwKBgQCHzacuRw5+O6NUBsm6
        Sf8Id9wQyjOIrsz0hyCkrwRdPc31CWqfCdbHyyRz9XqeAKzJI5dl0z6B5juDqBDV
        YeRIuLABqFz4DDhZrczXnA6aTig4EBfRpo7NcgtlUsktWU0abGHQBUOtqNIIhPHn
        1/hOUqusojVv96sMKXXWVKY22A==
        -----END PRIVATE KEY-----
        EOD;
        
        // NOTE: Before you proceed with the TOKEN, verify your users session or access.
        
        $payload = array(
          "sub" => "123", // unique user id string
          "name" => "Timon Lutz", // full name of user
        
          // Optional custom user root path
          // "https://claims.tiny.cloud/drive/root" => "/johndoe",
        
          "exp" => time() + 60 * 10 // 10 minute expiration
        );
        
        try {
          $token = JWT::encode($payload, $privateKey, 'RS256');
          http_response_code(200);
          header('Content-Type: application/json');
          echo json_encode(array("token" => $token));
        } catch (Exception $e) {
          http_response_code(500);
          header('Content-Type: application/json');
          echo $e->getMessage();
        }
    }

}
