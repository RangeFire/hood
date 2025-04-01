<?php

namespace App\Http\Controllers;

use Exception;
use App\Helpers\Auth;
use App\Models\Project;
use App\Models\EventAction;
use App\Models\TextSnippets;
use Illuminate\Http\Request;
use App\Models\LivechatConfig;
use App\Models\SupportCategory;
use App\Services\DBSyncService;
use App\Services\LiveChatService;
use App\Services\SettingsService;
use Illuminate\Support\Facades\Http;
use App\Models\CommunityCenterConfig;
use Illuminate\Support\Facades\Session;

class SettingsController extends Controller
{
    public function settings(SettingsService $settingsService) {

        if(!Auth::hasPermission('settings')) return redirect('/');

        $project = Project::find(Session::get('activeProject'));

        $credentials = $project->dbsyncCredentials;

        /* creates project livechat_token if not exists */
        (new LiveChatService)->generateTokenIfNotExist($project);
        

        /* restricts frontend access to connection_data */
        if($credentials) {
            unset($credentials['connection_data']);
        }

        $discord_channels = false;

        if($project->guild_id) {
            try {
                $discord_channels = (string) Http::get(env('DISCORD_BOT_HOST').'/getChannels/'.$project->guild_id);
                $discord_channels = json_decode($discord_channels, false);
            } catch (Exception $e) {
                $discord_channels = null;
            }
        }

        $support_categories = SupportCategory::where('project_id', Session::get('activeProject'))->get();
        $text_snippets = TextSnippets::where('project_id', Session::get('activeProject'))->get();
        $livechatSettings = LivechatConfig::where('project_id', Session::get('activeProject'))->first();
        $communityCenterSettings = CommunityCenterConfig::where('project_id', Session::get('activeProject'))->first();
        $alerts = EventAction::where('project_id', Session::get('activeProject'))->get();

        return view('pages/settingsView', [
            'project' => $project,
            'credentials' => $credentials,
            'discord_channels' => $discord_channels,
            'support_categories' => $support_categories,
            'text_snippets' => $text_snippets,
            'livechatSettings' => $livechatSettings,
            'communityCenterSettings' => $communityCenterSettings,
            'alerts' => $alerts
        ]);    
    }

    public function setCustomCategories(SettingsService $settingsService) {

        if(!Auth::hasPermission('settings')) return redirect('/');

        $isSaved = $settingsService->setCustomCategories();

        if(!$isSaved) return redirect()->back()->with('error', 'Es ist ein Fehler aufgetreten'); 
        
        return redirect()->back()->with('success', 'Die Kategorien wurden erfolgreich gespeichert'); 

    }

    public function saveCredentials(DBSyncService $dbSyncService) {

        if(!Auth::hasPermission('settings')) return redirect('/');

        $isSaved = $dbSyncService->saveCredentials();
        
        if($isSaved == 'empty') {
            return redirect()->back()->with('error', 'Bitte alle Felder ausfüllen');
        }else if($isSaved == 'connection_error') {
            return redirect()->back()->with('error', 'Die Daten sind ungültig. Es konnte keine Verbindung hergestellt werden.');
        }else {
            return redirect()->back()->with('success', 'Die Datenbank wurde erfolgreich verknüpft.');
        }
    }

    public function setupDBSync() {

        if(!Auth::hasPermission('settings')) return redirect('/');

        $project = Project::find(Session::get('activeProject'));

        $credentials = $project->dbsyncCredentials;

        return view('pages/dbSyncSetupView', [
            'project' => $project,
            'credentials' => $credentials,
        ]); 

    }

    public function dbSync_getTables(DBSyncService $dbSyncService) {

        if(!Auth::hasPermission('settings')) return redirect('/');
        
        $tables = $dbSyncService->getTables();
        return response()->json($tables);
    }

    public function dbSync_getColumns(DBSyncService $dbSyncService, $table) {

        if(!Auth::hasPermission('settings')) return redirect('/');

        $tables = $dbSyncService->getColumns($table);
        return response()->json($tables);
    }

    public function dbSync_setData(DBSyncService $dbSyncService) {

        if(!Auth::hasPermission('settings')) return redirect('/');

        $isSaved = $dbSyncService->setData();

        if(!$isSaved)
            return redirect()->back()->with('error', 'Die Datenbankstruktur konnte nicht gespeichert werden');

        return redirect()->to('/settings')->with('success', 'Die Datenbankstruktur wurde erfolgreich konfiguriert'); 
    }

    public function ticketChannelMessage(SettingsService $settingsService) {

        if(!Auth::hasPermission('settings')) return redirect('/');

        $isSaved = $settingsService->ticketChannelMessage();

        if(!$isSaved) return redirect()->back()->with('error', 'Es ist ein Fehler aufgetreten');

        return redirect()->back()->with('success', 'Die Einstellung wurde erfolgreich gespeichert');

    }

    public function ticketWelcomeMessage(SettingsService $settingsService) {

        if(!Auth::hasPermission('settings')) return redirect('/');

        $isSaved = $settingsService->ticketWelcomeMessage();

        if(!$isSaved) return redirect()->back()->with('error', 'Es ist ein Fehler aufgetreten');
        
        return redirect()->back()->with('success', 'Die Einstellung wurde erfolgreich gespeichert');
    
    }

    public function addTextSnippet(SettingsService $settingsService) {
        if(!Auth::hasPermission('settings')) return redirect('/');

        $isSaved = $settingsService->addTextSnippet();

        if(!$isSaved) return redirect()->back()->with('error', 'Es ist ein Fehler aufgetreten'); 
        
        return redirect()->back()->with('success', 'Dein Text Snippet wurde erfolgreich erstellt');
    }

    public function deleteTextSnippet(SettingsService $settingsService) {
        if(!Auth::hasPermission('settings')) return redirect('/');
        
        $deleteSnippet = $settingsService->deleteTextSnippet();

        if($deleteSnippet === 'not_inside_team') {
            return redirect()->back()->with('error', 'Du hast keine Berechtigung dieses Snippet zu löschen.'); 
        } 
        if(!$deleteSnippet) {
            return redirect()->back()->with('error', 'Es ist ein Fehler aufgetreten'); 
        }
        return redirect()->back()->with('success', 'Das Text Snippet wurde gelöscht');
    }

    public function editSupportTime(SettingsService $settingsService) {

        if(!Auth::hasPermission('settings')) return redirect('/');

        $isSaved = $settingsService->editSupportTime();

        if(!$isSaved) return redirect()->back()->with('error', 'Es ist ein Fehler aufgetreten'); 
        
        return redirect()->back()->with('success', 'Die Supportzeiten wurden erfolgreich gespeichert'); 

    }

    public function editLogo(SettingsService $settingsService) {

        if(!Auth::hasPermission('settings')) return redirect('/');

        $isSaved = $settingsService->editLogo();

        if(!$isSaved) return redirect()->back()->with('error', 'Es ist ein Fehler aufgetreten'); 
        
        return redirect()->back()->with('success', 'Dein Logo wurde erfolgreich gespeichert'); 

    }
    
    public function editDomain(SettingsService $settingsService) {

        if(!Auth::hasPermission('settings')) return redirect('/');

        $isSaved = $settingsService->editDomain();

        if(!$isSaved) return redirect()->back()->with('error', 'Es ist ein Fehler aufgetreten'); 
        
        return redirect()->back()->with('success', 'Deine Domain wurde eingerichtet'); 

    }

    public function editWhitelabel(SettingsService $settingsService) {

        if(!Auth::hasPermission('settings')) return redirect('/');

        $isSaved = $settingsService->editWhitelabel();

        if(!$isSaved) return redirect()->back()->with('error', 'Es ist ein Fehler aufgetreten'); 
        
        return redirect()->back()->with('success', 'Der Whitelabel Status wurde geändert'); 

    }
    
    public function editLivechatTexts(SettingsService $settingsService) {

        if(!Auth::hasPermission('settings')) return redirect('/');

        $isSaved = $settingsService->editLivechatTexts();

        if(!$isSaved) return redirect()->back()->with('error', 'Es ist ein Fehler aufgetreten'); 
        
        return redirect()->back()->with('success', 'Deine Änderungen wurden gespeichert'); 

    }

    public function editLiveChatBubbleImage(SettingsService $settingsService) {

        if(!Auth::hasPermission('settings')) return redirect('/');

        $isSaved = $settingsService->editLiveChatBubbleImage();

        if(!$isSaved) return redirect()->back()->with('error', 'Es ist ein Fehler aufgetreten'); 
        
        return redirect()->back()->with('success', 'Das Livechat Toggle Bild wurde geändert'); 

    }

    public function editCommunityCenterHeadline(SettingsService $settingsService) {

        if(!Auth::hasPermission('settings')) return redirect('/');

        $isSaved = $settingsService->editCommunityCenterHeadline();

        if(!$isSaved) return redirect()->back()->with('error', 'Es ist ein Fehler aufgetreten'); 
        
        return redirect()->back()->with('success', 'Die Communitycenter Headline wurde geändert'); 

    }

    public function editProjectName(SettingsService $settingsService) {

        if(!Auth::hasPermission('settings')) return redirect('/');

        $isSaved = $settingsService->editProjectName();

        if(!$isSaved) return redirect()->back()->with('error', 'Es ist ein Fehler aufgetreten'); 
        
        return redirect()->back()->with('success', 'Dein Projektname wurde geändert'); 

    }

    public function deleteAlertElement(SettingsService $settingsService) {

        if(!Auth::hasPermission('settings')) return redirect('/');

        $isSaved = $settingsService->deleteAlertElement();

        if(!$isSaved) return redirect()->back()->with('error', 'Es ist ein Fehler aufgetreten'); 
        
        return redirect()->back()->with('success', 'Der Alert wurde gelöscht.'); 

    }
}
