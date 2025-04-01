<?php

namespace App\Helpers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class RedisJson {

    public static function redisSetJson($key, $jsonData) {

        $connection = Redis::connection();
        $connection->executeRaw(["JSON.SET", $key, "$", $jsonData]);
        
        return $connection;
    }

    public static function redisArrayAppend($key, $jsonData) {

        $connection = Redis::connection();
        $connection->executeRaw(["JSON.ARRAPPEND", $key, "$", $jsonData]);
        
        return $connection;
    }

    public static function redisArrayInsert($key, $jsonData) {

        $connection = Redis::connection();
        $connection->executeRaw(["JSON.ARRAPPEND", $key, "$", 0, $jsonData]);
        
        return $connection;
    }

    public static function redisSetExpire($key, $seconds) {

        $connection = Redis::connection();
        $connection->executeRaw(["EXPIRE", $key, $seconds]);
        
        return $connection;
    }

    public static function redisGetSingleKey($key) {

        $connection = Redis::connection();
        $result = $connection->executeRaw(["JSON.MGET", $key, "$"]);
        
        return $result;
    }

    public static function redisGetMultipleKeys($key) {

        $connection = Redis::connection();
        $result = $connection->executeRaw(["KEYS", "*".$key."*"]);

        return $result;
    }

    public static function redisGetAllGameservers() {

        $connection = Redis::connection();
        $result = $connection->executeRaw(["KEYS", "*gameserver:*"]);
        return $result;
    }

}

?>