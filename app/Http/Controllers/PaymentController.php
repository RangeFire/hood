<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\PaymentService;
use Mollie\Laravel\Facades\Mollie;
use App\Models;

class PaymentController extends Controller
{

    public function listSubscriptions(PaymentService $paymentService) {
        //$getProjectData = $projectService->getCommunityCenterHome($projectHash);

        return view('pages/payment/manageSubscriptionsView', [
            'subscriptions' => "",
        ]);   
    }

    public function startTrial(PaymentService $paymentService) {

            return view('pages/payment/checkoutTrial', [
            'product' => "",
            ]); 
        
        return redirect()->back()->with('error', 'Es ist ein Fehler aufgetreten'); 
    }
    
    public function setTrial(PaymentService $paymentService) {

        $isSaved = $paymentService->setTrial();

        if($isSaved) return redirect()->to('/products')->with('success', 'Deine Testphase wurde aktiviert');
        
        return redirect()->to('/products')->with('error', 'Es ist ein Fehler aufgetreten'); 
    }

    public function checkout(PaymentService $paymentService, $product) {

        switch ($product) {
            case 'one':
                return view('pages/payment/checkoutOne', [
                    'product' => "",
                ]); 
                break;

            case 'monitoring':
                return view('pages/payment/checkoutMonitoring', [
                    'product' => "",
                ]); 
                break;

            case 'support':
                return view('pages/payment/checkoutSupport', [
                    'product' => "",
                ]); 
                break;

            case 'branding':
                return view('pages/payment/checkoutBranding', [
                    'product' => "",
                ]); 
                break;
            
            default:
                return redirect()->back()->with('error', 'Das Produkt exitiert nicht'); 
                break;
        }  
        
        return redirect()->back()->with('error', 'Es ist ein Fehler aufgetreten'); 
    }

    public function createPayment(PaymentService $paymentService) {
        $payment = $paymentService->createPayment();

        return redirect($payment, 303);
    }

    public function handleWebhookNotification(Request $request, PaymentService $paymentService) {
        $payment = $paymentService->handleWebhookNotification($request);
    }

    public function paymentSuccess(PaymentService $paymentService) {

        $getLastPayment = Models\Payment::where('user_id', \App\Helpers\Auth::user('id'))->orderBy('id', 'desc')->first();
        $payment = Mollie::api()->payments->get($getLastPayment->mollie_order_id);
        
        $paymentData = json_encode($payment->metadata);
        $finalPaymentData = json_decode($paymentData, true);

        $status = $paymentService->getMolliePaymentStatus($payment);
        $getLastPayment->status = $status;
        $getLastPayment->save();

        $this->alertNewOrder($status);

        switch ($status) {
            case 'paid':
                $projectSubscriptions = Models\ProjectSubscription::where('project_id', $finalPaymentData['project_id'])->first();
                $projectSubscriptions->{$finalPaymentData['product']} = $finalPaymentData['subscriptionEndDate'];
                $projectSubscriptions->save();
                return redirect()->to('/dashboard')->with('success', 'Deine Zahlung war erfolgreich.');
            case 'failed':
                return redirect()->to('/dashboard')->with('error', 'Zahlung fehlgeschlagen');
            case 'expired':
                return redirect()->to('/dashboard')->with('error', 'Zahlung abgelaufen');
                break;
            case 'canceled':
                return redirect()->to('/dashboard')->with('error', 'Zahlung abgebrochen');
            case 'pending':
                return redirect()->to('/dashboard')->with('error', 'Zahlung ausstehend');
            case 'open':
                return redirect()->to('/dashboard')->with('error', 'Zahlung noch offen');
            default:
                return redirect()->to('/dashboard')->with('error', 'Irgendwas ist richtig in die Hose gegangen');
        }

        

    }

    private function alertNewOrder($orderType) {

        $webhookurl = "https://discord.com/api/webhooks/1008387192852320288/uy8RuWt3XKPleWYVkmFAavBBgPpkATVuWsj_Sj10-p3cO0Bu5ypFKD0pQpawd5QygoXb";
    
        $timestamp = date("c", strtotime("now"));

        $json_data = json_encode([
            "username" => '',
            "tts" => false,
            "embeds" => [
                [
                    "title" => 'Es wurde ein neuer Kauf durchgeführt',
                    "type" => "rich",
                    "description" => 'OrderType: '.$orderType,
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

    public function checkForInactiveSubscriptions(PaymentService $paymentService) {
        $checkSubscriptions = $paymentService->checkForInactiveSubscriptions();

        return $checkSubscriptions;
    }

    
}
