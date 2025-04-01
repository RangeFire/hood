<?php
namespace App\Services;

use App\Models;
use App\Helpers\RedisJson;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;
use WebSockets\Client;
use GameQ\GameQ;

class GameserverService extends Service {

    public function createGameserver() {
        extract(Request::post());
        $gameserver_id = rand(1, 1000000);

        $prepareData = [
            'serverData' => [
                'id' => $gameserver_id,
                'name' => $serverName,
                'project_id' => Session::get('activeProject'),
                'monitoring_service_id' => "",
            ], 
            'connectionData' => [
                'gameType' => $gameCode,
                'ip' => $serverIP,
                'port' => $serverPort,
                'master' => $master_id,
                'queryPort' => $serverQueryPort,
            ], 
            'stats' => [
                'playerTop' => "",
                'playerTopDate' => ""
            ]
        ];

        $jsonExample =  json_encode($prepareData);
        RedisJson::redisSetJson("project_id:".Session::get('activeProject')."gameserver:".$gameserver_id, $jsonExample);

        return $prepareData;
    }

    public function getGameservers() {
        $result = RedisJson::redisGetMultipleKeys("project_id:".Session::get('activeProject')."gameserver:");

        return $result;
    }

    public function getGameserver($serverId) {
        $returnData = RedisJson::redisGetSingleKey($serverId);

        return $returnData;
    }

    public function getGameserverDayStatistic($serverDataArray) {
        $dateToday = date("Y-m-d");
        $returnData = RedisJson::redisGetSingleKey("gameserverDayLog:".$serverDataArray[0]->serverData->id."-Day:".$dateToday);
        return $returnData;
    }

    public function prepareGameserverDayPlayerHigh($serverLogKey) {
        $dateToday = date("Y-m-d");
        $returnData = RedisJson::redisGetSingleKey($serverLogKey);
        return $returnData;
    }

    public function getGameserverWeekStatistic($serverDatas) {
        // dd($serverData);

        $prepareFinalData = [];

        for($i = 0; $i <= 6; $i++) {

        $getGameserverDayLog = "gameserverDayLog:".$serverDatas[0]->serverData->id."-Day:".date("Y-m-d", strtotime("-$i days"));
        $prepareGameserverDayPlayerHigh = json_decode($this->prepareGameserverDayPlayerHigh($getGameserverDayLog)[0]);
        if($prepareGameserverDayPlayerHigh == null) {
            break;
        }
        // Calculate average Player day
        $dayPlayerAverage = 0;
        $dayPlayerHigh = 0;
        // dd($prepareGameserverDayPlayerHigh[0]);
        // ToDO; foreacht geht nicht mit  $prepareGameserverDayPlayerHigh[0]
        foreach($prepareGameserverDayPlayerHigh[0] as $dayData) {
            if($dayData->player != "Error") {
                $dayPlayerAverage += $dayData->player;
                if($dayData->player > $dayPlayerHigh) {
                    $dayPlayerHigh = $dayData->player;
                }
            }
        }

        // Save Data
        $prepareData = [
                'playerHigh' => $dayPlayerHigh,
                'avaragePlayer' => round($dayPlayerAverage/24),
                'date' => date("d.m.Y", strtotime("-$i days")),
        ];
        array_push($prepareFinalData, $prepareData);

        }

        return $prepareFinalData;

    }

    public static function getGameserverLiveData($serverData) {

        try {
            
        $GameQ = new GameQ();
        $gameserverData = json_encode($serverData, true);
        $data = json_decode($gameserverData, true);

        $gameCode = null;
        $gameTypes = \App\Helpers\AppHelper::$gameTypes;
        foreach ($gameTypes as $key => $gameType) {
            if($data[0]["connectionData"]["gameType"] === $key) {
                $gameCode = $gameType;
            }
        }

        // Check if AltV
        if($gameCode === "altv") {
            $host = $data[0]["connectionData"]["ip"] . ":" . $data[0]["connectionData"]["port"];
            $altV = (new GameConnectService)->getDataFromAltV($data[0]["connectionData"]["master"], $host);
            return $altV;
        }

        // Check if Server needs Query Port or just normal Port to check
        $host = $data[0]["connectionData"]["ip"] . ":" . $data[0]["connectionData"]["port"];
        if($data[0]["connectionData"]["queryPort"] == null) {
            $GameQ->addServer([
                'type' => $gameCode,
                'host' => $host,
            ]);
        } else {
            $GameQ->addServer([
                'type' => $gameCode,
                'host' => $host,
                'options' => [
                    'query_port' => $data[0]["connectionData"]["queryPort"],
                ],
            ]);
        }

        $results = $GameQ->process();
        return $results;

        } catch (\Exception $th) {
            info($th);
            $prepareData = [
                $host => [
                    'gq_maxplayers' => "",
                    'gq_numplayers' => "Error",
                    'gq_address' => "",
                    'gq_port_client' => "",
                    'gq_online' => "",
                ],
            ];
    
            return $prepareData;
        }
    }

    public function createGameserverHourLog() {
        $gameservers = RedisJson::redisGetAllGameservers();

        foreach ($gameservers as $key => $gameserver) {
            echo $gameserver;
            $gameserverData = $this->getGameserver($gameserver);
            $data = json_decode($gameserverData[0], true);
            $dateToday = date("Y-m-d");

            if($data[0] == null) { exit(); }
            // $serverIP = $data[0]["connectionData"]["ip"].":".$data[0]["connectionData"]["port"];
            $GameserverLiveData = GameserverService::getGameserverLiveData($data);
            $serverIP = $data[0]["connectionData"]["ip"].":".$data[0]["connectionData"]["port"];
            
            $server_id = $data[0]["serverData"]["id"];
            $gameserverDayLog = RedisJson::redisGetSingleKey("gameserverDayLog:".$server_id."-Day:".$dateToday);
            $gameserverDayLog = json_decode($gameserverDayLog[0], true);
        
            $prepareLog = [
                    'date' => date("G"),
                    'status' => $GameserverLiveData[$serverIP]["gq_online"],
                    'player' => $GameserverLiveData[$serverIP]["gq_numplayers"],
            ];

            // dd("gameserverDayLog:".$server_id."-Day:".$dateToday);
            // $saveLog = RedisJson::redisSetJson("gameserverDayLog:".$server_id."-Day:".$dateToday, $prepareLog);

            // echo $prepareLog;

            if(!$gameserverDayLog) {
                $prepareLog = json_encode([$prepareLog], true);
                $saveLog = RedisJson::redisSetJson("gameserverDayLog:".$server_id."-Day:".$dateToday, $prepareLog);
                $expireLog = RedisJson::redisSetExpire("gameserverDayLog:".$server_id."-Day:".$dateToday, 15768000); // Expire 6 Months
                
            } else {
                $prepareLog = json_encode($prepareLog, true);
                $saveLog = RedisJson::redisArrayAppend("gameserverDayLog:".$server_id."-Day:".$dateToday, $prepareLog);
                $expireLog = RedisJson::redisSetExpire("gameserverDayLog:".$server_id."-Day:".$dateToday, 15768000); // Expire 6 Months
            }

        }

        return true;
    }

    function sendRconCommand($host, $port, $password, $command) {
        $socket = fsockopen("tcp://$host", $port, $errno, $errstr, 2);
        if (!$socket) {
            return false;
        }

        $request_id = mt_rand(0, 65535); // Generated to match the response
        $data = pack('VV', $request_id, 3) . $password . "\x00";
        $data = pack('V', strlen($data)) . $data;
        fwrite($socket, $data, strlen($data));

        $response = fread($socket, 1400);
        $response = substr($response, 4);
        $response = unpack('Vid/Vtype/a*message', $response);

        if ($response['id'] != $request_id || $response['type'] != 2) {
            return false;
        }

        $request_id++;
        $data = pack('VV', $request_id, 2) . $command . "\x00";
        $data = pack('V', strlen($data)) . $data;
        fwrite($socket, $data, strlen($data));

        $response = fread($socket, 1400);
        $response = substr($response, 4);
        $response = unpack('Vid/Vtype/a*message', $response);

        if ($response['id'] != $request_id || $response['type'] != 0) {
            return false;
        }

        return $response;
    }
}