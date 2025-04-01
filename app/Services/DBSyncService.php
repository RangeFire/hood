<?php
namespace App\Services;

use App\Models;
use App\Helpers\Crypt;
use App\Helpers\DBSync;
use App\Models\Project;
use App\Models\DbsyncCredential;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;

class DBSyncService extends Service {

    public function saveCredentials() {

        if($this->isInvalid(['ip', 'db', 'user', 'password'], Request::post())) {
            return 'empty';
        }

        extract(Request::post());

        $credentials = [
            'ip' => $ip,
            'db' => $db,
            'user' => $user,
            'password' => $password
        ];

        $crypt = new Crypt();
        $encrypted = $crypt->encrypt(json_encode($credentials));

        $connection_valid = $this->checkConnection($encrypted);

        if(!$connection_valid) {
            return 'connection_error';
        }

        $project = Project::find(Session::get('activeProject'));

        $existingCredentials = $project->dbsyncCredentials;

        if($existingCredentials) {
            $credentials = $existingCredentials;
        } else {
            $credentials = new DbsyncCredential();
        }

        $isSaved = DbsyncCredential::updateOrCreate([
            'project_id' => $project->id,
        ], [
            'ip' => $ip,
            'user' => $user,
            'database' => $db,
            'connection_data' => $encrypted,
            'project_id' => $project->id,
        ]);

        return $isSaved;

    }   

    private function checkConnection($encrypted_data) {

        $dbSync = new DBSync($encrypted_data);

        if($dbSync->validConnection) {
            return true;
        } else {
            return false;
        }

    }

    public function getTables() {

        $project = Project::find(Session::get('activeProject'));
        $credentials = $project->dbsyncCredentials;

        $dbSync = new DBSync($credentials->connection_data);

        $data = $dbSync->query('SHOW TABLES')->fetch_all();

        $tables = [];

        foreach($data as $i => $e) $tables[] = $e[0];

        return $tables;

    }

    public function getColumns($table) {

        $project = Project::find(Session::get('activeProject'));
        $credentials = $project->dbsyncCredentials;

        $dbSync = new DBSync($credentials->connection_data);

        $data = $dbSync->query('SHOW COLUMNS FROM ' . $table)->fetch_all();

        $columns = [];

        foreach($data as $i => $e) $columns[] = $e[0];

        return $columns;
        
    }

    public function setData() {

        if($this->isInvalid(['table', 'column_id', 'column_name'], Request::post())) {
            return false;
        }

        $project = Project::find(Session::get('activeProject'));
        $credentials = $project->dbsyncCredentials;

        extract(Request::post());

        $data = [];

        $data['character_info'] = [
            'table' => $table,
            'id' => $column_id,
            'name' => $column_name,
        ];

        $credentials->database_setup = json_encode($data);
        $credentials->save();

        return true;
        
    }

    public function getSyncUsers() {
        $project = Project::find(Session::get('activeProject'));

        if(!$project->dbsyncCredentials) return false;

        $credentials = $project->dbsyncCredentials;

        if(!$credentials->connection_data) return false;

        $dbSync = new DBSync($credentials->connection_data);

        $playerSetup = json_decode($credentials->database_setup, true);

        if(!isset($playerSetup['character_info'])) return false;

        [   'table' => $table,
            'id' => $id,
            'name' => $name ] = $playerSetup['character_info'];

        $players = $dbSync->query("SELECT ".$id." as id, ".$name." as name FROM ".$table." ORDER BY ".$id." ASC")->fetch_all(MYSQLI_ASSOC);

        if(!$players) return false;

        return $players;
    }

}