<?php

namespace App\Http\Controllers;


use GameQ\GameQ;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use App\Helpers\RedisJson;
use App\Services\GameserverService;
use App\Services\Service;

class GameserverController extends Controller
{
    public function getGameservers() {
        $gameserver = new GameserverService;
        $gameserverData = $gameserver->getGameservers();

        if($gameserverData == null) {
            return view('pages/gameserver/gameserverAdd', [
                'gameserverData' => null
            ]);  
        } else {

            return view('pages/gameserver/gameserversView', [
                'gameserverData' => $gameserverData
            ]);  
        }
    }

    public function viewGameserver($serverId) {
        $gameserver = new GameserverService;
        $gameserverData = $this->getGameserver($serverId);
        $GameserverLiveData = $gameserver->getGameserverLiveData($gameserverData);
        $gameserverDayStatistic = $gameserver->getGameserverDayStatistic($gameserverData);
        $gameserverWeekStatistic = $gameserver->getGameserverWeekStatistic($gameserverData);

        return view('pages/gameserver/gameserverDetail', [
            'gameserverData' => $gameserverData,
            'GameserverLiveData' => $GameserverLiveData,
            'gameserverDayData' => json_encode($gameserverDayStatistic),
            'gameserverWeekData' => json_encode($gameserverWeekStatistic),
        ]);  
    }

    public static function getGameserver($serverId) {
        $gameserver = new GameserverService;
        
        $gameserverData = $gameserver->getGameserver($serverId);
        return json_decode($gameserverData[0]);
    }

    public function createGameserver(GameserverService $GameserverSerice) {
        $isSaved = $GameserverSerice->createGameserver();

        if($isSaved) return redirect('/gameserver')->with('success', 'Dein Server ist startklar.');
        return redirect()->back()->with('error', 'Es ist ein Fehler aufgetreten'); 
    }

    public function addGameserver() {

        return view('pages/gameserver/gameserverAdd', [

        ]);    
        
    }

    public function createGameserverHourLog(GameserverService $GameserverSerice) {
        $isSaved = $GameserverSerice->createGameserverHourLog();
        return $isSaved;
    }

    public function test(GameserverService $GameserverSerice) {
        $gameserver = new GameserverService;
        $ip = '85.190.160.6';
        $rconPort = '22003';
        $rconPassword = '4RvY5dSY';
        $isSaved = $GameserverSerice->sendRconCommand($ip, $rconPort, $rconPassword, 'Restart');

        // $ip = '5.83.168.127';
        // $rconPort = '53805';
        // $rconPassword = '2206';
        // $isSaved = $GameserverSerice->sendRconCommand($ip, $rconPort, $rconPassword, 'ban RangeFire22');
        
        // $gameserverData = $gameserver->getGameserver('project_id:1gameserver:368611');
        // $expireLog = RedisJson::redisSetExpire("ExpireTest", 15768000); // Expire 6 Months
        // dd($expireLog);
        // $GameQ = new GameQ();

        // $GameQ->addServer([
        //     'type' => 'arma3',
        //     'host' => '94.250.209.30:2302',
        //     'options' => [
        //         'query_port' => 2303,
        //     ],
        // ]);
        
        $GameQ->addServer([
            'type' => 'minecraft',
            'host' => '94.250.217.68:25565',
            'options' => [
                'query_port' => 25565,
            ],
        ]);


        // $GameQ->addServer([
        //     'type' => 'gta5m',
        //     'host' => '185.240.242.81:30120',
        // ]);

                // $GameQ->addServer([
        //     'type' => 'gta5m',
        //     'host' => '188.34.182.251:30120',
        // ]);





        // $GameQ->addServer([
        //     'type' => 'arkse',
        //     'host' => '185.239.211.69:37115',
        // ]);
 
        // Gibt Formatierungs Fehler
        // $GameQ->addServer([
        //     'type' => 'dayz',
        //     'host' => '205.178.177.113:2302',
        //     'options' => [
        //         'query_port' => 2303,
        //     ],
        // ]);

        $results = $GameQ->process();
        return $results;
    }
}
