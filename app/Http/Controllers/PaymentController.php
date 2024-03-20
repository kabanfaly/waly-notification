<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Omnipay\Omnipay;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
class PaymentController extends Controller
{
    private $gateway;

    public function __construct() {
        $this->gateway = Omnipay::create('PayPal_Rest');
        $this->gateway->setClientId(env('PAYPAL_LIVE_CLIENT_ID'));
        $this->gateway->setSecret(env('PAYPAL_LIVE_CLIENT_SECRET'));
        $this->gateway->setTestMode(env('PAYPAL_MODE', 'sandbox') === 'sandbox');
    }

    public function showPendingPayment(string $paymentId) {
        $member = DB::table('VbE_view_wpforms_members_payments')
            ->join('VbE_view_wpforms_members', 'VbE_view_wpforms_members_payments.entry_id', '=', 'VbE_view_wpforms_members.entry_id')
            ->select('VbE_view_wpforms_members_payments.*', 'VbE_view_wpforms_members.name', 'VbE_view_wpforms_members.email')
            ->where('VbE_view_wpforms_members_payments.id', $paymentId)
            ->first();
        return view('payment.pending', ['member' => $member]);

    }

    public function payPending(string $paymentId)
    {
        try {
            $payment = DB::table('VbE_view_wpforms_members_payments')->where('id', $paymentId)->first();
            if ($payment && $payment->status == 'pending') {
                Log::info("Starting a pending payment of $payment->total_amount");
                $response = $this->gateway->purchase(array(
                    'amount' => $payment->total_amount,
                    'currency' => env('PAYPAL_CURRENCY'),
                    'returnUrl' => url('/payment/pending/success/' . $paymentId),
                    'cancelUrl' => url('/payment/declined'),
                ))->send();

                if ($response->isRedirect()) {
                    $response->redirect();
                }
                else{
                    return $response->getMessage();
                }
            } else {
                Log::info("Payment déjà effectué");
                return redirect('/payment/pending/' . $paymentId);
            }
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }

    public function paySubscription(Request $request)
    {
        Log::info("Starting a subscription payment");
        try {

            $response = $this->gateway->purchase(array(
                'amount' => $request->amount,
                'currency' => env('PAYPAL_CURRENCY'),
                'returnUrl' => url('/payment/success/' . request('entry_id')),
                'cancelUrl' => url('/payment/declined'),
            ))->send();

            if ($response->isRedirect()) {
                $response->redirect();
            }
            else{
                return $response->getMessage();
            }

        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }

    public function payPendingSuccess(Request $request, string $paymentId)
    {
        if ($request->input('paymentId') && $request->input('PayerID')) {
            $transaction = $this->gateway->completePurchase(array(
                'payer_id' => $request->input('PayerID'),
                'transactionReference' => $request->input('paymentId')
            ));

            $response = $transaction->send();

            if ($response->isSuccessful()) {

                $arr = $response->getData();

                DB::table('VbE_wpforms_payments')
                    ->where('id', $paymentId)
                        ->update(
                            [
                                'status' => 'completed',
                                'transaction_id' =>  $arr['id'],
                                'date_updated_gmt' => Carbon::now(),
                            ],
                        );
                Log::info("Payment is Successfull. Your Transaction Id is : " . $arr['id']);
                return redirect('/payment/success/' . $arr['id']);
            }
            else{
                return $response->getMessage();
            }
        }
        else{
            return 'Payment declined!!';
        }
    }

    public function error()
    {
        return view('payment.payment_cancelled');
    }

    public function success(string $transactionId)
    {
        return view('payment.payment_success', ['transactionId' => $transactionId]);
    }
}
