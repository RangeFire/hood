<?php
namespace App\Services;

use App\Models;
use App\Models\EventAction;
use App\Helpers\Subscription;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;
use MailerSend\Helpers\Builder\Variable;
use MailerSend\Helpers\Builder\Recipient;
use MailerSend\Helpers\Builder\EmailParams;
use Propaganistas\LaravelPhone\PhoneNumber;


class EventActionService extends Service {

    public function getEventActions() {
        $eventActions = Models\EventAction::where('project_id', Session::get('activeProject'))->get();
        return $eventActions;
    }

    private function checkIfInstanceIsPermittedForMonitoringActions($project_id) {

        $project = Models\Project::find($project_id);

        if(!$project)
            return false;

        Session::put('activeProject', $project->id);

        if(Subscription::hasActiveSubscription('monitoring') || Subscription::hasFreeTrial('monitoring'))
            return true;

        return false;

    }

    public function doEventAction($event, $callee, $project_id) {
        info($event);
        info($project_id);
        if($project_id) {
            $eventActions = Models\EventAction::where([
                'event' => $event,
                'project_id' => $project_id
            ])->get();
            $callee = Models\Project::find($project_id);
        } else {
            $eventActions = Models\EventAction::where([
                'event' => $event,
                'project_id' => $callee->project_id
            ])->get();
        }


        if(!$eventActions) return false;

        foreach($eventActions as $eventAction) {

            switch ($eventAction->event) {
                case 'monitoring_down':
                    $title = 'Hood - Monitoring-Service ist ausgefallen';
                    $message = 'Der Service: "'.$callee->name.'" ist gerade eben ausgefallen';
                    break;
                case 'monitoring_online_again':
                    $title = 'Hood - Monitoring-Service ist wieder Online';
                    $message = 'Der Service: "'.$callee->name.'" ist ist nun wieder Online';
                    break;
                case 'new_support_chat':
                    $title = 'Hood - Neuer Chat gestartet';
                    $message = 'Es wurde ein neuer Chat in deinem Hood Projekt: "'.$callee->name.'" eröffnet.';
                    break;
                case 'new_bugreport':
                    $title = 'Hood - Neuer Bugreport';
                    $message = 'Es wurde ein neuer Bug in deinem Hood Projekt: "'.$callee->name.'" gemeldet.';
                    break;
                case 'new_user_wish':
                    $title = 'Hood - Neuer Wunsch geäußert';
                    $message = 'Es wurde ein neuer Wunsch in deinem Hood Projekt: "'.$callee->name.'" geäußert.';
                    break;                   
                default:
                    $title = 'Hood - Ein Unbekannter Alert wurde getriggert';
                    $message = 'Wir haben einen Alert erhalten, konnten diesen aber keinem deiner Alerts zuweisen. Bitte überprüfe zur Sicherheit dein System.';
                break;
            }

            try {
                if($eventAction->action == 'send_mail')
                    $this->actionEmail($eventAction, $callee, $title, $message);

                if($eventAction->action == 'mobile_push')
                    $this->actionMobilePush($eventAction, $title, $message);

                if($eventAction->action == 'discord_webhook')
                    $this->actionDiscordWebhook($eventAction, $callee, $title, $message);

                // if($eventAction->action == 'play_audio')
                //     $this->actionPlayAudio($eventAction, $callee, $title, $message);
            } catch (\Throwable $th) {}


        }
    }

    public function actionEmail($eventAction, $callee, $title, $message) {

        $recipient = $eventAction->action_reference;

        $mailersend = new \MailerSend\MailerSend(['api_key' => 'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIxIiwianRpIjoiMjJmNWI0MjJjYjU1ZmNmM2IyZjhlN2U4MzdkYWU0ZTNkZWZkYjc1YzIzN2JkZDBiMGVhNDNhYjI5MGQxOTFhNDNkMmM5M2IwNjUzZjJmMjQiLCJpYXQiOjE2NDU1NjM5NDEuNzkzNjY3LCJuYmYiOjE2NDU1NjM5NDEuNzkzNjcxLCJleHAiOjQ4MDEyMzc1NDEuNzkwMjgxLCJzdWIiOiIyMTY1MiIsInNjb3BlcyI6WyJlbWFpbF9mdWxsIiwiZG9tYWluc19mdWxsIiwiYWN0aXZpdHlfZnVsbCIsImFuYWx5dGljc19mdWxsIiwidG9rZW5zX2Z1bGwiLCJ3ZWJob29rc19mdWxsIiwidGVtcGxhdGVzX2Z1bGwiLCJzdXBwcmVzc2lvbnNfZnVsbCJdfQ.fRvq_r7HORJutIN26cmiRfw5LkHu_PZSXfjRkRPaJE3AiB7dc-BLR5_OH2VvFYgLwXXDc1hQW9oA2g9d6pedM7IZrP-3UclsNtUyceJGrQ95uV8xh8_Blq_lvDuBLO8s2UP3wrgg2ZVSnLQpdlhMu8F_F6_Zb9o0QnqMAdf_rn6R4C1nZevjZxntPLW_ogm-5yPLtDiAl-a7BzTJBnCcUHPXRwcPiLbKrrHmKkwQqvVM9ZwqJZPYkyPrT3t6b-zwuY4A2qVU6qndNWFwhS45u0_DeOSgy7_lH6n7Osn-aEbLJLsMnP4CfJ8cWfbyM2gXqo_YhH-ZgmYEnRUsxwj8r3hK09RfBSVF_6ONn_lQvwZTsnI3JeeA-oVRQTgx7WJRgxxFD5y3FbhVqPrjut7HYXIqvSCGa2ZAmMb7BiFRYiDZI7yHuHxnKrORKZCsUx5YP_OTKwNSz1HzAMalwdWqxni3tDRl28sqkRJywxTzHzeqBlsr1qwcjyl__dnS9ZOgTwss7BhwZF6QZGGxm9Fr3KcUreUti-LTY8RKXoix0Iw07nrsAYmBn5Em-IKXSOFumy3HrMWCTWqE3Ly5ll3Kl8aepFSiqBgISh47n-cg6xwWi96GtkFQRBjnLZ6mF9Xl2Aw8uyod_KAFb-CK6jh2UFHjyBrTUbxPce6ndaohuqY']);

        $customVariable = [
            'title' => $title,
            'message' => $message,
        ];

        $variables = [
            new Variable($recipient, $customVariable)
        ];

        $recipients = [
            new Recipient($recipient, 'Hood Community Management'),
        ];

        $emailParams = (new EmailParams())
            ->setFrom('noreply@wehood.io')
            ->setFromName('Hood Community Management')
            ->setRecipients($recipients)
            ->setSubject($title)
            ->setTemplateId('yzkq340eqy2gd796')
            ->setVariables($variables);

        return $mailersend->email->send($emailParams);

    }

    public function actionDiscordWebhook($eventAction, $callee, $title, $message) {

        $webhookurl = $eventAction->action_reference;

        $timestamp = date("c", strtotime("now"));

        $json_data = json_encode([
            "username" => $title,
            "tts" => false,
            "embeds" => [
                [
                    "title" => $title,
                    "type" => "rich",
                    "description" => $message,
                    "url" => "https://admin.mycraftit.com/",
                    "timestamp" => $timestamp,
                    "color" => hexdec( "af2d75" ),
                    "author" => [ "name" => 'Hood Community Management' ],
                ]
            ]
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

        $ch = curl_init($webhookurl);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: application/json'));
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $response = curl_exec($ch);
        curl_close($ch);

    }

    public function actionMobilePush($eventAction, $title, $message) {
        $simplePushId = $eventAction->action_reference;
    
        $apiURL = "https://api.simplepush.io/send/$simplePushId/$title/$message";
        $response = Http::get($apiURL);
        return $this->$apiURL();

    }

    // public function actionPlayAudio($eventAction, $title, $message) {

    //     return "callAudioNotification";

    // }

    public function addAlert(){

        if($this->isInvalid(['event', 'action', 'reference'], Request::post())) {
            return false;
        }

        return $this->manageAlert();

    }

    public function editAlert($alert_id){

        if($this->isInvalid(['event', 'action', 'reference'], Request::post())) {
            return false;
        }

        return $this->manageAlert($alert_id);

    }

    private function manageAlert($alert_id = null) {

        if($alert_id) {
            $eventAction = EventAction::find($alert_id);
        } else {
            $eventAction = new EventAction();
        }

        $eventAction = $this->setModelFromArray($eventAction, [
            'event' => Request::post('event'),
            'action' => Request::post('action'),
            'action_reference' => Request::post('reference'),
            'project_id' => Session::get('activeProject'),
        ]);

        $eventAction->save();

        if(!$eventAction) return false;

        return true;
 
    }

    public function deleteAlert($alert_id) {

        $eventAction = EventAction::find($alert_id);

        if(!$eventAction) return false;
        
        $deleted = $eventAction->delete();
        
        if(!$deleted) return false;

        return true;

    }

}