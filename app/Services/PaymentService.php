<?php
namespace App\Services;

use App\Models;
use App\Helpers\AppHelper;
use Mollie\Laravel\Facades\Mollie;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;
use App\Http\Resources\SubscriptionResource;

class PaymentService extends Service {
    
    public function setTrial() {
        extract(Request::post());

        $setTrialDate = Models\Project::where('id', Session::get('activeProject'))->first();
        if($setTrialDate->trial_end != null) return null;
        $dateToday = date("Y-m-d h:i");
        $trialEndDate = date('Y-m-d h:i', strtotime("+3 days", strtotime($dateToday)));
        $setTrialDate->trial_end = $trialEndDate;
        $setTrialDate->save();

        return $setTrialDate;
    }

    public function createPayment() {
        extract(Request::post());

        $products = AppHelper::$prices;
        
        if (!array_key_exists($productType,$products)) {
            return redirect()->to('/dashboard')->with('error', 'Es ist ein Fehler aufgetreten.');
        }

        // Check active subscription 
        $projectSubscriptions = Models\ProjectSubscription::where('project_id', session('activeProject'))->first();

        if($projectSubscriptions->{$subscriptionName}) {
            $date = $projectSubscriptions->{$subscriptionName};
        } else {
            $date = date("Y-m-d h:i:s");
        }
        

        switch ($selectRuntime) {
            case 1:
                $productPrice = strval($products[$productType] * $selectRuntime);
                $subscriptionEndDate = date('Y-m-d h:i', strtotime("+1 months", strtotime($date)));
                break;
            case 3:
                $productPrice = strval($products[$productType] * $selectRuntime);
                $subscriptionEndDate = date('Y-m-d h:i', strtotime("+3 months", strtotime($date)));
                break;
            default:
                return redirect()->to('/dashboard')->with('error', 'Es ist ein Fehler aufgetreten.');
                break;
        }

        $payment = Mollie::api()->payments->create([
            "amount" => [
                "currency" => "EUR",
                "value" => $productPrice, // Must be a double casted to string
            ],
            "description" => $productName,
            "redirectUrl" => route('payment.success'),
            "webhookUrl" => route('payment.webhook').'?user_id='.\App\Helpers\Auth::user('id'),
            "method" => ['creditcard', 'paypal', 'applepay', 'paysafecard', 'sofort'],
            "metadata" => [
                "product" => $subscriptionName,
                "subscriptionEndDate" => $subscriptionEndDate,
                "project_id" => session('activeProject'),
            ],
        ]);

        // redirect customer to Mollie checkout page
        return $payment->getCheckoutUrl();
    }

    public function handleWebhookNotification($request) {
        $paymentId = $request->input('id');
        $addPayment = $this->createModelFromArray(new Models\Payment, [
            'mollie_order_id' => $paymentId, 
            'status' => null, 
            'user_id' => $_GET['user_id'], 
        ]);
    }

    public function getMolliePaymentStatus($payment) {
        if ($payment->isPaid())
        {
            return 'paid';
        } 
        if ($payment->isFailed())
        {
            return 'failed';
        } 
        if ($payment->isExpired())
        {
            return 'expired';
        } 
        if ($payment->isCanceled())
        {
            return 'canceled';
        } 
        if ($payment->idPending())
        {
            return 'pending';
        } 
        if ($payment->isOpen())
        {
            return 'open';
        } 
    }

    // Timons very STRONG algorithm
    public function checkForInactiveSubscriptions() {

        $subscriptions = SubscriptionResource::collection(Models\ProjectSubscription::get());

        foreach ($subscriptions as $subscription) {
            $dateNow = date('Y-m-d H:i');
            $subscriptionArray = json_decode(json_encode($subscription), true);

            foreach ($subscriptionArray as $key=>$subscriptionValue) {
                if($key === 'id') {
                    $subscriptionId = $subscriptionValue;
                }
                $finalSubscriptionData = strtotime($subscriptionValue);
                $finalSubscriptionData = date( 'Y-m-d H:i', $finalSubscriptionData );

                if($key != 'id' && $finalSubscriptionData <= $dateNow) {
                    $setSubscriptionToNull = Models\ProjectSubscription::find($subscriptionId);
                    $setSubscriptionToNull->{$key} = NULL;
                    $setSubscriptionToNull->save();
                }
            }
        }
    }
}