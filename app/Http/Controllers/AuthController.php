<?php

namespace App\Http\Controllers;

use App\Models\PasswordReset;
use App\Models\User;
use App\Services\MailService;
use Illuminate\Http\Request;
use App\Services\UserService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    public function checkUserLoginSession(request $request) {

        if(!$request->session()->has('user')) {
            return view('pages/auth/loginView');
        } else {
            return redirect('/dashboard');
        }
    }

    public function checkUserRegisterSession(request $request) {

        if(!$request->session()->has('user')) {
            return view('pages/auth/registerView');
        } else {
            return redirect('/dashboard');
        }
    }
    
    public function login(UserService $userService) {
        $operation = $userService->login();

        if($operation === 'too_many_attempts') {
            return redirect('/')->with('error', 'Zu viele Versuche. Bitte versuchen Sie es in einigen Minuten erneut.');
        }

        if($operation === 'no_projects') {     
            return redirect('/start');
        }

        if($operation[1] != null) {
            
            return redirect('/dashboard');
        }

        
        return redirect()->back()->with('error', 'Falscher Benutzername oder Passwort.');
    }

    public function apiLogin(UserService $userService) {
        [$error, $operation] = $userService->login();

        if($error === true) {
            return $this->setApiResponse(true, null, 401);
        }

        if($error === 'too_many_attempts') {
            return $this->setApiResponse(true, 'too_many_attempts', 401);
        }

        $bearerToken = $userService->generateAccessToken($operation);

        if($error === 'no_projects') {
            return $this->setApiResponse(false, [
                'request_detail' => 'no_projects',
                'token' => $bearerToken
            ], 200);
        }

        return $this->setApiResponse(false, [
            'request_detail' => 'has_projects',
            'token' => $bearerToken
        ], 200);

    }

    public function register(UserService $userService) {

        [$error, $data] = $userService->register();

        if($error == true) {
            return redirect()->back()->with('error', $data);
        } else {
            return redirect('/')->with('success', 'Dein Konto wurde erfolgreich erstellt');
        }
    }

    public function apiRegister(UserService $userService) {

        [$error, $data] = $userService->register();

        return $this->setApiResponse($error, $data);

    }
    
    public function resetPassword(UserService $userService, $resetToken) {

        $operation = $userService->savePasswordReset($resetToken);

        if($operation == true) {
            return redirect()->to('/')->with('success', 'Dein Passwort wurde erfolgreich geändert');
        } else {
            return redirect()->back()->with('error', 'Passwörter stimmen nicht überein.');
        }
    }

    public function doPasswordReset(Request $request) {

        if(!$request->has('username')) {
            return redirect()->to('/')->with('error', 'Bitte gib deinen Benutzernamen ein');
        }

        $user = User::where('username', $request->get('username'))->first();

        if($user)
            $operation = (new MailService())->sendPasswordResetMail($user);

        return redirect('/passwords/checkMail')->with('success', 'Sollte diese Email registriert sein, wurde eine Email mit weiteren Anweisungen gesendet.');

    }

    public function logout() {
        Session::flush();
        return redirect('/');
    }

    public function passwordReset($resetToken) {
        return view('pages/auth/passwordReset', [
            'token' => $resetToken
        ]);
    }

    public function passwordResetcheckMail() {
        return view('pages/auth/passwordResetCheckMail', []);
    }

    public function passwordResetForm()
    {
        return view('pages.auth.password-reset-form');
    }

    public function discordRegister()
    {
        $discordURL = env('DISCORD_AUTH');
        return redirect($discordURL);
    }

    public function discordLogin()
    {
        $discordURL = env('DISCORD_AUTH');
        return redirect($discordURL);
    }

    public function discordProcess(UserService $userService)
    {
        if(!isset($_GET['code'])){
            echo 'no code';
            exit();
        }
        
        [$error, $data] = $userService->prozessDiscordAuth();

        if($error === 'too_many_attempts') {
            return redirect('/')->with('error', 'Zu viele Versuche. Bitte versuchen Sie es in einigen Minuten erneut.');
        }

        if($error === 'no_projects') {     
            return redirect('/start');
        }

        if($error != null) {
            
            return redirect('/dashboard');
        }

        return redirect('/');
    }

    public function googleRegister()
    {
        return Socialite::driver('google')->redirect();
    }

    public function googleLogin()
    {
        return Socialite::driver('google')->redirect();
    }

    public function googleProcess(UserService $userService)
    {
        try {
            $user = Socialite::driver('google')->user();
            // dd($user);
        } catch (\Exception $e) {
            return redirect('/login');
        }

        [$error, $data] = $userService->registerGoogleAuth($user);

        if($error === 'too_many_attempts') {
            return redirect('/')->with('error', 'Zu viele Versuche. Bitte versuchen Sie es in einigen Minuten erneut.');
        }

        if($error === 'no_projects') {     
            return redirect('/start');
        }

        if($error != null) {
            
            return redirect('/dashboard');
        }

        // return redirect('/');
    }

}
