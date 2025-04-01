<?php
namespace App\Services;

use App\Models;
use Carbon\Carbon;
use App\Models\User;
use App\Models\System;
use Illuminate\Support\Str;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Request;
use MailerSend\Helpers\Builder\Variable;
use MailerSend\Helpers\Builder\Recipient;
use MailerSend\Helpers\Builder\EmailParams;


class MailService extends Service {

    public function sendPasswordResetMail(User $user) {

        $mailersend = new \MailerSend\MailerSend(['api_key' => 'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIxIiwianRpIjoiMjJmNWI0MjJjYjU1ZmNmM2IyZjhlN2U4MzdkYWU0ZTNkZWZkYjc1YzIzN2JkZDBiMGVhNDNhYjI5MGQxOTFhNDNkMmM5M2IwNjUzZjJmMjQiLCJpYXQiOjE2NDU1NjM5NDEuNzkzNjY3LCJuYmYiOjE2NDU1NjM5NDEuNzkzNjcxLCJleHAiOjQ4MDEyMzc1NDEuNzkwMjgxLCJzdWIiOiIyMTY1MiIsInNjb3BlcyI6WyJlbWFpbF9mdWxsIiwiZG9tYWluc19mdWxsIiwiYWN0aXZpdHlfZnVsbCIsImFuYWx5dGljc19mdWxsIiwidG9rZW5zX2Z1bGwiLCJ3ZWJob29rc19mdWxsIiwidGVtcGxhdGVzX2Z1bGwiLCJzdXBwcmVzc2lvbnNfZnVsbCJdfQ.fRvq_r7HORJutIN26cmiRfw5LkHu_PZSXfjRkRPaJE3AiB7dc-BLR5_OH2VvFYgLwXXDc1hQW9oA2g9d6pedM7IZrP-3UclsNtUyceJGrQ95uV8xh8_Blq_lvDuBLO8s2UP3wrgg2ZVSnLQpdlhMu8F_F6_Zb9o0QnqMAdf_rn6R4C1nZevjZxntPLW_ogm-5yPLtDiAl-a7BzTJBnCcUHPXRwcPiLbKrrHmKkwQqvVM9ZwqJZPYkyPrT3t6b-zwuY4A2qVU6qndNWFwhS45u0_DeOSgy7_lH6n7Osn-aEbLJLsMnP4CfJ8cWfbyM2gXqo_YhH-ZgmYEnRUsxwj8r3hK09RfBSVF_6ONn_lQvwZTsnI3JeeA-oVRQTgx7WJRgxxFD5y3FbhVqPrjut7HYXIqvSCGa2ZAmMb7BiFRYiDZI7yHuHxnKrORKZCsUx5YP_OTKwNSz1HzAMalwdWqxni3tDRl28sqkRJywxTzHzeqBlsr1qwcjyl__dnS9ZOgTwss7BhwZF6QZGGxm9Fr3KcUreUti-LTY8RKXoix0Iw07nrsAYmBn5Em-IKXSOFumy3HrMWCTWqE3Ly5ll3Kl8aepFSiqBgISh47n-cg6xwWi96GtkFQRBjnLZ6mF9Xl2Aw8uyod_KAFb-CK6jh2UFHjyBrTUbxPce6ndaohuqY']);

        $recipient = $user->email;

        if(empty($recipient)) return;

        $token = Str::uuid();
        $password_reset = Models\PasswordReset::create([
            'username' => $user->username,
            'token' => $token,
            'is_used' => false
        ]);

        $customVariable = [
            'customerAccessLink' => route('passwords.reset.token', ['resetToken' => $token]),
        ];

        $variables = [
            new Variable($recipient, $customVariable)
        ];

        $recipients = [
            //new Recipient($recipient, 'Hood Community Management'),
            //new Recipient($user->email, 'Hood Community Management'),
            new Recipient($recipient, 'Hood Community Management'),
        ];

        $emailParams = (new EmailParams())
            ->setFrom('noreply@wehood.io')
            ->setFromName('Hood Community Management')
            ->setRecipients($recipients)
            ->setSubject('Passwort vergessen')
            ->setTemplateId('o65qngkdq03lwr12')
            ->setVariables($variables);

        return $mailersend->email->send($emailParams);

    }

    private function sendMail(string $recipient, string $subject, string $templateId, array $variables) {

        $mailersend = new \MailerSend\MailerSend(['api_key' => 'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIxIiwianRpIjoiMjJmNWI0MjJjYjU1ZmNmM2IyZjhlN2U4MzdkYWU0ZTNkZWZkYjc1YzIzN2JkZDBiMGVhNDNhYjI5MGQxOTFhNDNkMmM5M2IwNjUzZjJmMjQiLCJpYXQiOjE2NDU1NjM5NDEuNzkzNjY3LCJuYmYiOjE2NDU1NjM5NDEuNzkzNjcxLCJleHAiOjQ4MDEyMzc1NDEuNzkwMjgxLCJzdWIiOiIyMTY1MiIsInNjb3BlcyI6WyJlbWFpbF9mdWxsIiwiZG9tYWluc19mdWxsIiwiYWN0aXZpdHlfZnVsbCIsImFuYWx5dGljc19mdWxsIiwidG9rZW5zX2Z1bGwiLCJ3ZWJob29rc19mdWxsIiwidGVtcGxhdGVzX2Z1bGwiLCJzdXBwcmVzc2lvbnNfZnVsbCJdfQ.fRvq_r7HORJutIN26cmiRfw5LkHu_PZSXfjRkRPaJE3AiB7dc-BLR5_OH2VvFYgLwXXDc1hQW9oA2g9d6pedM7IZrP-3UclsNtUyceJGrQ95uV8xh8_Blq_lvDuBLO8s2UP3wrgg2ZVSnLQpdlhMu8F_F6_Zb9o0QnqMAdf_rn6R4C1nZevjZxntPLW_ogm-5yPLtDiAl-a7BzTJBnCcUHPXRwcPiLbKrrHmKkwQqvVM9ZwqJZPYkyPrT3t6b-zwuY4A2qVU6qndNWFwhS45u0_DeOSgy7_lH6n7Osn-aEbLJLsMnP4CfJ8cWfbyM2gXqo_YhH-ZgmYEnRUsxwj8r3hK09RfBSVF_6ONn_lQvwZTsnI3JeeA-oVRQTgx7WJRgxxFD5y3FbhVqPrjut7HYXIqvSCGa2ZAmMb7BiFRYiDZI7yHuHxnKrORKZCsUx5YP_OTKwNSz1HzAMalwdWqxni3tDRl28sqkRJywxTzHzeqBlsr1qwcjyl__dnS9ZOgTwss7BhwZF6QZGGxm9Fr3KcUreUti-LTY8RKXoix0Iw07nrsAYmBn5Em-IKXSOFumy3HrMWCTWqE3Ly5ll3Kl8aepFSiqBgISh47n-cg6xwWi96GtkFQRBjnLZ6mF9Xl2Aw8uyod_KAFb-CK6jh2UFHjyBrTUbxPce6ndaohuqY']);

        $variables = [
            new Variable($recipient, $variables)
        ];

        $recipients = [
            //new Recipient($recipient, 'Hood Community Management'),
            //new Recipient($user->email, 'Hood Community Management'),
            new Recipient($recipient, 'Hood Community Management'),
        ];

        $emailParams = (new EmailParams())
            ->setFrom('noreply@wehood.io')
            ->setFromName('Hood Community Management')
            ->setRecipients($recipients)
            ->setSubject($subject)
            ->setTemplateId($templateId)
            ->setVariables($variables);

        return $mailersend->email->send($emailParams);
    
    }

    public function sendProductRenewalToday() {

        // get project_subscriptions where monitoring is expiring today
        $project_subscriptions = Models\ProjectSubscription::whereDate('monitoring', '=', Carbon::now()->toDateString())->get();

        foreach ($project_subscriptions as $project_subscription) {
            $project = $project_subscription->project;

            $first_user = $project->users()->first();

            $sent = $this->sendMail($first_user->email, 'Produkt läuft heute aus', 'yzkq340eqy2gd796', [
                'title' => 'Produkt läuft heute aus',
                'message' => 'Ihr Produkt "Monitoring-Pro" läuft heute aus. Bitte verlängern Sie Ihr <a href="'.env('APP_URL').'/products'.'">Produkt</a>.',
            ]);

        }

    }

    public function sendProductRenewal24hours() {

        // get project_subscriptions where monitoring is expiring tomorrow
        $project_subscriptions = Models\ProjectSubscription::whereDate('monitoring', '=', Carbon::now()->addDays(1)->toDateString())->get();

        foreach ($project_subscriptions as $project_subscription) {
            $project = $project_subscription->project;

            $first_user = $project->users()->first();

            $sent = $this->sendMail($first_user->email, 'Produkt läuft aus', 'yzkq340eqy2gd796', [
                'title' => 'Produkt läuft aus',
                'message' => 'Ihr Produkt "Monitoring-Pro" läuft in 24 Stunden aus. Bitte verlängern Sie Ihr <a href="'.env('APP_URL').'/products'.'">Produkt</a>.',
            ]);

        }

    }

}