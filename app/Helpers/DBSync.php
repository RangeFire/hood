<?php

namespace App\Helpers;

use mysqli;


class DBSync {

    public $connection;
    public $validConnection;

    function __construct($connection_data) {

        $this->prepareConnectionData($connection_data);

    }

    private function prepareConnectionData($connection_data) {

        $crypt = new Crypt();
        $decrypt = $crypt->decrypt($connection_data);

        $data = json_decode($decrypt, false);

        $connection = $this->connect($data);

        return $connection;

    }

    private function connect($data) {

        try {
            $conn = new mysqli($data->ip, $data->user, $data->password, $data->db);
        } catch (\Throwable $th) {
            return false;
        }
        
        // Check connection
        if ($conn->connect_errno) {
            $this->validConnection = false;
            return false;
        }

        $this->validConnection = true;
        $this->connection = $conn;

        return true;

    }

    public function query($query) {

        $query = $this->connection->query($query);

        return $query;

    }

}


?>