<?php

namespace App\Models;

use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Session;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Project extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    /* relationships */
        public function userProjects() {
            return $this->hasMany(UserProject::class, 'project_id');
        }

        public function roles() {
            return $this->hasMany(Role::class, 'project_id');
        }
        public function supportConfig() {
            return $this->hasOne(SupportConfig::class, 'project_id');
        }
        public function users() {
            return $this->belongsToMany(User::class, 'user_projects', 'project_id', 'user_id');
        }
        public function subscription() {
            return $this->hasOne(ProjectSubscription::class, 'project_id');
        }
        public function trial() {
            return $this->hasOne(ProjectTrial::class, 'project_id');
        }
        public function dbsyncCredentials() {
            return $this->hasOne(DbsyncCredential::class, 'project_id', 'id');
        }
        public function maintenance() {
            return $this->hasOne(Maintenance::class, 'project_id', 'id');
        }
    /* relationships */

    public function findProject($id) {
        return $this->find($id);
    }

    public function findProjectByHash($projectHash) {
        return $this->where('project_hash', $projectHash)->first();
    }

}
