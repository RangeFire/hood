<?php

namespace App\Http\Controllers;

use App\Models;
use App\Helpers\Auth;
use App\Models\System;
use Illuminate\Http\Request;
use App\Services\DashboardService;


class DashboardController extends Controller
{

    public function dashboard(DashboardService $dashboardService) 
    {
        $userProjects = (new Models\UserProject)->getUserProjects(Auth::user()->id);
        $projectData = (new Models\Project)->findProject(session('activeProject'));

        $discordUsers = 'N/A';
        if($projectData) {
            $discordGuildID = $projectData->guild_id;
            if($discordGuildID) {
                try {
                    $discordUsers = file_get_contents(env('DISCORD_BOT_HOST').'/countDiscordUsers/'.$discordGuildID);
                } catch (\Throwable $th) {}
            }

            
            // Create new Entry for new DB Table
            $livechatConfig = (new Models\LivechatConfig)::updateOrCreate(
                ['project_id' => session('activeProject')]
            );
            $livechatConfig->save();
        }



        if(!$userProjects) {
            return view('pages/startView', [
                'userProjects' => $userProjects,
            ]);
        } else {
            return view('pages/dashboardView', [
                'discordUsers' => $discordUsers,
                'statistics' => 'Nichts',
                'userProjects' => $userProjects,
            ]);
        }
    } 

    public function start(DashboardService $dashboardService) {

    }
}