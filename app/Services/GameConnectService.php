<?php
namespace App\Services;

use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Http;
use App\Models;

class GameConnectService extends Service {

    public function getDataFromAltV($master_id, $host) {
        //b1ec3953cc1f6d5d7bd6f9d3a30fb326
        $response = Http::get('https://api.altv.mp/server/'.$master_id);
        $test = json_decode($response, true);
        $prepareData = [
            $host => [
                'gq_maxplayers' => $test['info']['maxPlayers'],
                'gq_numplayers' => $test['info']['players'],
                'gq_address' => $test['info']['host'],
                'gq_port_client' => $test['info']['port'],
                'gq_online' => $test['active'],
            ],
        ];

        return $prepareData;

    }
}