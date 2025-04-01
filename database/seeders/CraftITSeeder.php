<?php

namespace Database\Seeders;

use DateTime;
use App\Models;
use DateInterval;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\Hash;

class CraftITSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        Models\Project::factory()->create([
            'name' => 'DeveloperRP',
            'guild_id' => '980856674313203732',
            'project_hash' => 'TTLL2000',
            'livechat_token' => '084hg0c8h35gi0j03j',
            'owner_id' => 1,
        ]);

        Models\User::factory()->create([
            'fullname' => 'Timon Lutz',
            'username' => 'timon.lutz',
            'email' => 'timon.lutz@mycraftit.com',
            'password' => hash('sha256','password'),
        ]);

        Models\User::factory()->create([
            'fullname' => 'Andre Glaser',
            'username' => 'andre.glaser',
            'email' => 'andre.glaser@mycraftit.com',
            'password' => hash('sha256','password'),
        ]);

        $mollie = Models\User::factory()->create([
            'fullname' => 'Mollie Test',
            'username' => 'Mollie',
            'email' => 'mollie@mycraftit.com',
            'password' => hash('sha256','test2022'),
        ]);

        $jordi = Models\User::factory()->create([
            'fullname' => 'Jordi Isken',
            'username' => 'jordi',
            'email' => 'jordi@mycraftit.com',
            'password' => hash('sha256','12345678'),
        ]);

        $role = Models\Role::factory()->create([
            'title' => 'Test Rolle',
            'manage_users' => true,
            'settings' => true,
            'support' => true,
            'ingame_integration' => true,
            'monitoring' => true,
            'surveys' => true,
            'wishes' => true,
            'project_id' => 1,
        ]);

        Models\UserProject::factory()->create([
            'user_id' => 1,
            'project_id' => 1,
            'role_id' => $role->id,
        ]);

        Models\UserProject::factory()->create([
            'user_id' => 2,
            'project_id' => 1,
            'role_id' => $role->id,
        ]);

        Models\Role::factory()->create([
            'title' => 'Test Rolle',
            'manage_users' => true,
            'settings' => true,
            'support' => true,
            'ingame_integration' => true,
            'monitoring' => true,
            'surveys' => true,
            'wishes' => true,
            'project_id' => 1,
        ]);

        Models\UserProject::factory()->create([
            'user_id' => $mollie->id,
            'project_id' => 1,
            'role_id' => $role->id,
        ]);

        Models\Ticket::factory()->create([
            'project_id' => 1,
            'ticket_creator' => 'DerTester#3939'
        ]);

        Models\MonitoringService::factory()->create([
            'name' => 'Website CraftIT',
            'type' => 'Website',
            'url' => 'wehood.app',
            'project_id' => 1,
        ]);

        Models\MonitoringService::factory()->create([
            'name' => 'Offline Website',
            'type' => 'Website',
            'url' => 'wehoodx.app',
            'project_id' => 1,
        ]);

    }

}