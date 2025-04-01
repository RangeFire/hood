<?php
namespace App\Services;

use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Request;
use App\Models;

class GameOSAdminService extends Service {

    public function alertNewRegestration() {

        $webhookurl = "https://discord.com/api/webhooks/995763799221096459/oKk22wRoW6qYbvJDupzafZO1FYCRabDmGMBgei2aD7dqNVpQjXFXStDg0RrFm3-Z_tYE";
    
        $timestamp = date("c", strtotime("now"));

        $json_data = json_encode([
            "username" => '',
            "tts" => false,
            "embeds" => [
                [
                    "title" => 'Ein neuer Nutzer hat sich registriert',
                    "type" => "rich",
                    "description" => '',
                    "url" => "https://wehood.app",
                    "timestamp" => $timestamp,
                    "color" => hexdec( "af2d75" ),
                    "author" => [ "name" => 'GameOS Bot']
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

    public function alertNewGameOSTicket() {

        $webhookurl = "YOURWEBHOOKURL";
    
        $timestamp = date("c", strtotime("now"));

        $json_data = json_encode([
            "username" => '',
            "tts" => false,
            "embeds" => [
                [
                    "title" => 'Es wurde ein neues Ticket erstellt',
                    "type" => "rich",
                    "description" => '',
                    "url" => "https://wehood.app",
                    "timestamp" => $timestamp,
                    "color" => hexdec( "af2d75" ),
                    "author" => [ "name" => 'GameOS Bot']
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
    
    public function alertNewGlobalTicket() {

        $webhookurl = "https://discord.com/api/webhooks/995772000507273216/uHItNfYRYBQ_vvDS-6wq9FIRJBQ9jWjShjqeNuqhGs8J4qYABZcAJrxYL1smcRcolumA";
    
        $timestamp = date("c", strtotime("now"));

        $json_data = json_encode([
            "username" => '',
            "tts" => false,
            "embeds" => [
                [
                    "title" => 'Es wurde ein neues Ticket erstellt',
                    "type" => "rich",
                    "description" => '',
                    "url" => "https://wehood.app",
                    "timestamp" => $timestamp,
                    "color" => hexdec( "af2d75" ),
                    "author" => [ "name" => 'Hood Bot']
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
}