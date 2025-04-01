<?php

use App\Helpers\Auth;
use App\Http\Controllers;
use App\Http\Middleware\PostXSS;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\SessionAuthentication;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Community Center Subdomain
Route::domain('{projectHash}.'.env('APP_DOMAIN'))->group(function () {
    Route::get('/cc', [Controllers\ProjectController::class, 'communityCenterHome']);
    Route::get('/cc/wishes', [Controllers\WishController::class, 'wishesExtern']);
    Route::get('/cc/bugreport', [Controllers\BugreportController::class, 'getBugreportsExtern']);
    Route::get('/cc/status', [Controllers\MonitoringController::class, 'monitoringExtern']);
    Route::get('cc/changelog/{hash}/view', [Controllers\ChangelogController::class, 'viewChangelogExtern']);

    //Need to get a Rebuild
    // Route::get('/cc/survey/{id}', [Controllers\SurveyController::class, 'surveyShowExtern']);
    // Route::get('/cc/survey/result/{id}', [Controllers\SurveyController::class, 'surveyShowResultExtern']);
});

Route::get('/changelog/{hash}/like', [Controllers\ChangelogController::class, 'likeChangelog']);

//Live chat Dev
Route::get('/livechatdev', function () {
    return view('pages/livechatdev', ['name' => 'James']);
});
Route::get('/dev/createCommunityCenterDBEntry', [Controllers\ProjectController::class, 'createCommunityCenterDBEntry']);

// Session Routes
Route::get('/', [Controllers\AuthController::class, 'checkUserLoginSession']);
Route::get('/register', [Controllers\AuthController::class, 'checkUserRegisterSession']);

// Auth Routes
Route::get('/login', function() {
    return redirect('/');
});
Route::post('/login', [Controllers\AuthController::class, 'login']);
Route::post('/register', [Controllers\AuthController::class, 'register']);
Route::get('/logout', [Controllers\AuthController::class, 'logout']);
Route::get('/passwordReset/{resetToken}', [Controllers\AuthController::class, 'passwordReset'])->name('passwords.reset.token');
// Screen -> Check your E-Mail for Password Reset Link
Route::get('/passwords/checkMail', [Controllers\AuthController::class, 'passwordResetcheckMail'])->name('passwords.reset.checkMail');
// Send Mail for Password Reset
Route::post('/passwords/reset/link', [Controllers\AuthController::class, 'doPasswordReset'])->name('passwords.reset.link');
Route::get('/passwords/reset', [Controllers\AuthController::class, 'passwordResetForm'])->name('passwords.reset.form');
Route::post('/passwords/resetDone/{resetToken}', [Controllers\AuthController::class, 'resetPassword'])->name('passwords.reset');

Route::get('/discord/auth/register', [Controllers\AuthController::class, 'discordRegister']);
Route::get('/discord/auth/login', [Controllers\AuthController::class, 'discordLogin']);
Route::get('/discord/auth/process', [Controllers\AuthController::class, 'discordProcess']);

Route::get('/google/auth/register', [Controllers\AuthController::class, 'googleRegister']);
Route::get('/google/auth/login', [Controllers\AuthController::class, 'googleLogin']);
Route::get('/google/auth/process', [Controllers\AuthController::class, 'googleProcess']);

// Behind Login (active Session required)
Route::middleware([SessionAuthentication::class, PostXSS::class])->group(function () {

    Route::get('/start', function () {
        return view('pages/start/startView');
    })->name('start');

    Route::prefix('start')->group(function () {
        Route::get('/', function () { return view('pages/start/startView'); })->name('start');
        Route::get('/create', function () { return view('pages/start/createProjectView'); })->name('createProject');
        Route::get('/join', function () { return view('pages/start/joinProjectView'); })->name('joinProject');
        Route::get('/success', function () { return view('pages/start/success'); })->name('success');
    });

    Route::prefix('dashboard')->group(function () {
        Route::get('/', [Controllers\DashboardController::class, 'dashboard']);
    });

    Route::prefix('event-actions')->group(function () {
        Route::get('/', [Controllers\DashboardController::class, 'dashboard']);
    });

    Route::prefix('gameserver')->group(function () {
        Route::get('/', [Controllers\GameserverController::class, 'getGameservers']);
        Route::get('/{server}/details', [Controllers\GameserverController::class, 'viewGameserver']);
        Route::get('/getGameservers', [Controllers\GameserverController::class, 'getGameservers']);
        Route::get('/addGameserver', [Controllers\GameserverController::class, 'addGameserver']);
        Route::post('/createGameserver', [Controllers\GameserverController::class, 'createGameserver']);

        Route::post('/createGameserverHourLog/msk73K2L', [Controllers\GameserverController::class, 'createGameserverHourLog']);

        Route::get('/test', [Controllers\GameserverController::class, 'test']);
    });

    Route::prefix('settings')->group(function () {
        Route::get('/', [Controllers\SettingsController::class, 'settings']);
        Route::post('/setCustomCategories', [Controllers\SettingsController::class, 'setCustomCategories']);
        Route::post('/ticketChannelMessage', [Controllers\SettingsController::class, 'ticketChannelMessage']);
        Route::post('/ticketWelcomeMessage', [Controllers\SettingsController::class, 'ticketWelcomeMessage']);
        Route::post('/addTextSnippet', [Controllers\SettingsController::class, 'addTextSnippet']);
        Route::post('/deleteTextSnippet', [Controllers\SettingsController::class, 'deleteTextSnippet']);
        Route::post('/editSupportTime', [Controllers\SettingsController::class, 'editSupportTime']);

        Route::post('/editProjectName', [Controllers\SettingsController::class, 'editProjectName']);

        Route::post('/editLogo', [Controllers\SettingsController::class, 'editLogo']);
        Route::post('/editDomain', [Controllers\SettingsController::class, 'editDomain']);
        Route::post('/editWhitelabel', [Controllers\SettingsController::class, 'editWhitelabel']);
        Route::post('/editLivechatTexts', [Controllers\SettingsController::class, 'editLivechatTexts']);
        Route::post('/editLiveChatBubbleImage', [Controllers\SettingsController::class, 'editLiveChatBubbleImage']);
        Route::post('/deleteAlertElement', [Controllers\SettingsController::class, 'deleteAlertElement']);

        Route::post('/editCommunityCenterHeadline', [Controllers\SettingsController::class, 'editCommunityCenterHeadline']);
    });

    // TODO Need an Rework and Rename to getSurveys
    Route::prefix('surveys')->group(function () {
        Route::get('/', [Controllers\SurveyController::class, 'surveys']);
    });

    Route::prefix('changelogs')->group(function () {
        Route::get('/', [Controllers\ChangelogController::class, 'getChangelogs']);
        Route::get('/addChangelog', [Controllers\ChangelogController::class, 'addChangelog']);
        Route::get('/editChangelog/{hash}', [Controllers\ChangelogController::class, 'editChangelog']);
    });

    Route::prefix('changelog')->group(function () {
        Route::get('/{hash}/view', [Controllers\ChangelogController::class, 'viewChangelogExtern']);
        Route::post('/saveChangelog', [Controllers\ChangelogController::class, 'saveChangelog']);
        Route::post('/editChangelog/{hash}', [Controllers\ChangelogController::class, 'saveEditChangelog']);
        Route::get('/deleteChangelog/{hash}', [Controllers\ChangelogController::class, 'deleteChangelog']);
    });
    
    Route::prefix('bugreports')->group(function () {
        Route::get('/', [Controllers\BugreportController::class, 'getBugreports']);
    });

    Route::prefix('bugreport')->group(function () {
        Route::post('/deleteBugreport', [Controllers\BugreportController::class, 'deleteBugreport']);
        Route::post('/changeTag', [Controllers\BugreportController::class, 'changeBugreportTag']);
        Route::post('/changeAdminAsnwer', [Controllers\BugreportController::class, 'changeAdminAsnwer']);
    });
    
    Route::prefix('wishes')->group(function () {
        Route::get('/', [Controllers\WishController::class, 'getWishes']);
    });

    Route::prefix('wish')->group(function () {
        Route::post('/deleteWish', [Controllers\WishController::class, 'deleteWish']);
        Route::post('/changeTag', [Controllers\WishController::class, 'changeWishTag']);
        Route::post('/changeAdminAsnwer', [Controllers\WishController::class, 'changeAdminAsnwer']);
    });

    Route::view('/players', 'pages/playersView');

    Route::prefix('project')->group(function () {
        Route::post('/join', [Controllers\ProjectController::class, 'join']);
        Route::post('/create', [Controllers\ProjectController::class, 'create']);
        Route::post('/delete', [Controllers\ProjectController::class, 'deleteProject']);
        Route::get('/change/{id}', [Controllers\ProjectController::class, 'change']);
    });

    Route::prefix('survey')->group(function () {
        Route::post('/add', [Controllers\SurveyController::class, 'addSurvey']);
        Route::get('/stop/{id}', [Controllers\SurveyController::class, 'stopSurvey']);
        Route::get('/start/{id}', [Controllers\SurveyController::class, 'startSurvey']);
    });

    Route::prefix('invite')->group(function () {
        Route::get('/generate/{id}', [Controllers\ProjectController::class, 'generateInvite']);
    });

    Route::prefix('tickets')->group(function () {
        Route::get('/', [Controllers\TicketController::class, 'tickets']);
        Route::get('/countOpen', [Controllers\TicketController::class, 'countOpenTickets']);
        Route::post('/add', [Controllers\TicketController::class, 'addTicket']);
    });

    Route::prefix('ticket')->group(function () {
        Route::get('/{id}', [Controllers\TicketController::class, 'ticketDetail']);
        Route::post('/addNote/{id}', [Controllers\TicketController::class, 'addNote']);
        Route::get('/closeTicket/{id}', [Controllers\TicketController::class, 'closeTicket']);
        Route::get('/deleteTicket/{id}', [Controllers\TicketController::class, 'deleteTicket']);
        Route::get('/getTicketMessages/{id}', [Controllers\TicketController::class, 'getTicketMessages']);
        Route::get('/changeStatus/{status}/{id}', [Controllers\TicketController::class, 'ticketChangeStatus']);
        Route::post('/changeTitle', [Controllers\TicketController::class, 'ticketChangeTitle']);
        Route::post('/changeAgent/{id}', [Controllers\TicketController::class, 'ticketChangeAgent']);
        Route::post('/answer/{id}', [Controllers\TicketController::class, 'ticketAnswer']);
    });

    Route::prefix('ticketChat')->group(function () {
        Route::post('/add/{id}', [Controllers\TicketController::class, 'addTicketChatAnswere']);
    });
      
    Route::prefix('users')->group(function () {
        Route::get('/', [Controllers\UserController::class, 'users']);
    });

    Route::prefix('user')->group(function () {
        Route::post('/add', [Controllers\UserController::class, 'addUser']);
        Route::post('/delete/{user_id}/{project_id}', [Controllers\UserController::class, 'deleteUser']);
        Route::post('/editProfile', [Controllers\UserController::class, 'editProfile']);
        Route::post('/setFavoriteProject', [Controllers\UserController::class, 'setFavoriteProject']);
    });

    Route::prefix('user/role')->group(function () {
        Route::post('/add', [Controllers\UserController::class, 'addRole']);
        Route::post('/edit/{id}', [Controllers\UserController::class, 'editRole']);
        Route::post('/delete/{id}', [Controllers\UserController::class, 'deleteRole']);
        Route::post('/assign/{id}', [Controllers\UserController::class, 'assignRole']);
    });

    Route::prefix('monitoring')->group(function () {
        Route::get('/', [Controllers\MonitoringController::class, 'getServices']);
        Route::post('/add', [Controllers\MonitoringController::class, 'addService']);
        Route::post('/edit/{service_id}', [Controllers\MonitoringController::class, 'editService']);
        Route::post('/delete/{service_id}', [Controllers\MonitoringController::class, 'deleteService']);
        
        Route::post('/addAlert', [Controllers\MonitoringController::class, 'addAlert']);
        Route::post('/editAlert/{alert_id}', [Controllers\MonitoringController::class, 'editAlert']);
        Route::post('/deleteAlert/{alert_id}', [Controllers\MonitoringController::class, 'deleteAlert']);
    });

    Route::prefix('maintenance')->group(function () {
        Route::post('/changeMessage', [Controllers\MonitoringController::class, 'changeMaintenanceMessage']);
        Route::post('/stopMaintenanceMode', [Controllers\MonitoringController::class, 'stopMaintenanceMode']);
    });

    Route::prefix('products')->group(function () {
        Route::get('/', [Controllers\PaymentController::class, 'listSubscriptions']);
        Route::get('/startTrial/{product}', [Controllers\PaymentController::class, 'startTrial']);
        Route::get('/checkout/{product}', [Controllers\PaymentController::class, 'checkout']);
        Route::post('/setTrial', [Controllers\PaymentController::class, 'setTrial']);
    });

    // Mollie / Payment
    Route::post('payment/create', [Controllers\PaymentController::class, 'createPayment']);
    Route::post('payment/setTrial',[Controllers\PaymentController::class, 'setTrial']);
    Route::get('payment/success',[Controllers\PaymentController::class, 'paymentSuccess'])->name('payment.success');

    Route::get('payment/checkInactiveSubscriptions',[Controllers\PaymentController::class, 'checkForInactiveSubscriptions']);

    Route::post('editor/jwt',[Controllers\ProjectController::class, 'loadJwt']);
  
});

// Mollie
Route::match(['get', 'post'],'payment/webhook', [Controllers\PaymentController::class, 'handleWebhookNotification'])->name('payment.webhook');

// Cronjobs::
Route::get('/monitoring/hg20ff34', [Controllers\MonitoringController::class, 'checkServiceStatusAutomatic']);
Route::get('/checkForInactiveSubscriptions/0sdg9z42h0i', [Controllers\PaymentController::class, 'checkForInactiveSubscriptions']);

Route::get('/createGameserverHourLog/msk73K2L', [Controllers\GameserverController::class, 'createGameserverHourLog']);

Route::prefix('automaticActions/0284g0hj2srhpim5')->group(function () {
    Route::get('productRenewalToday', [Controllers\AutomaticController::class, 'productRenewalToday']); 
    Route::get('productRenewal24hours', [Controllers\AutomaticController::class, 'productRenewal24hours']); 
    Route::get('monitoringLogsRemoval10Days', [Controllers\AutomaticController::class, 'monitoringLogsRemoval10Days']); 
});

Route::get('/eventAction/{event}', [Controllers\EventActionController::class, 'doEventAction']);

// Discord Bot
Route::get('/setProjectGuildID', [Controllers\ProjectController::class, 'setProjectGuildID']);
Route::post('/setProjectInitChannel', [Controllers\ProjectController::class, 'setProjectInitChannel']);

Route::prefix('discordbot')->group(function () {
    Route::post('/ticketindex/{id}', [Controllers\DiscordBotController::class, 'ticketIndexAndNewTicket']);
    // Route::post('/createTicket', [Controllers\DiscordBotController::class, 'createTicket']);
    Route::post('/ticketMessage', [Controllers\DiscordBotController::class, 'ticketMessage']);
    Route::post('/closeTicket', [Controllers\DiscordBotController::class, 'closeTicket']);
});

// Livechat
Route::prefix('livechat/{livechat_token}')->group(function () {
    Route::get('/', [Controllers\LiveChatController::class, 'index']);

    Route::prefix('/v1')->group(function () {
        Route::get('/', [Controllers\ProjectController::class, 'livechat']);
        Route::get('/scriptSupplier', [Controllers\ProjectController::class, 'livechat_script']);
        Route::get('/doEventAction/{event}', [Controllers\EventActionController::class, 'APICallEventAction']);
        
        Route::post('loadProjectData', [Controllers\LiveChatController::class, 'loadProjectData']);
        Route::post('loadProjectBrandingStatus', [Controllers\LiveChatController::class, 'loadProjectBrandingStatus']);
        Route::post('loadLiveChatSettings', [Controllers\LiveChatController::class, 'loadLiveChatSettings']);

        Route::post('openTicket', [Controllers\LiveChatController::class, 'openTicket']);
        Route::post('loadTicketMessages', [Controllers\LiveChatController::class, 'loadTicketMessages']);
        Route::post('messageTicket', [Controllers\LiveChatController::class, 'messageTicket']);
    });
});

// External View (CommunityCenter Functional Routes)
Route::post('{projectHash}/survey/addAnswere/{id}', [Controllers\SurveyController::class, 'addAnswere']);
Route::post('/project/wish/add', [Controllers\WishController::class, 'addWish']);
Route::post('/project/wish/vote', [Controllers\WishController::class, 'vote']);
Route::post('/project/bugreport/add', [Controllers\BugreportController::class, 'addBugreport']);