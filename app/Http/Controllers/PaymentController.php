<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Omnipay\Omnipay;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
class PaymentController extends Controller
{
    const CANCEL_URL = '/payment/declined';
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

    public function showSubscriptionPayment(string $entryId) {
        $member = DB::table('VbE_view_wpforms_members')
            ->where('entry_id', $entryId)
            ->first();
        return view('payment.subscription', ['member' => $member]);
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
                    'cancelUrl' => url(self::CANCEL_URL),
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

    private function getMember(string $entryId)
    {
        return DB::table('VbE_view_wpforms_members')
        ->where('entry_id', $entryId)
        ->first();
    }

    public function paySubscription(string $entryId)
    {
        Log::info("Starting a subscription payment");
        try {
            $member = $this->getMember($entryId);
            $response = $this->gateway->purchase(array(
                'amount' => formatAmount($member->amount),
                'currency' => env('PAYPAL_CURRENCY'),
                'returnUrl' => url('/payment/subscription/success/' . $entryId),
                'cancelUrl' => url(self::CANCEL_URL),
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
            return view('payment.payment_cancelled');
        }
    }

    public function paySubscriptionSuccess(Request $request, string $entryId)
    {
        if ($request->input('paymentId') && $request->input('PayerID')) {
            $transaction = $this->gateway->completePurchase(array(
                'payer_id' => $request->input('PayerID'),
                'transactionReference' => $request->input('paymentId')
            ));

            $response = $transaction->send();

            if ($response->isSuccessful()) {
                $arr = $response->getData();
                $member = $this->getMember($entryId);
                if ($member)
                {
                    $amount = formatAmount($member->amount);
                    DB::table('VbE_wpforms_payments')
                        ->insert([
                            [
                                'entry_id' => $entryId,
                                'subtotal_amount' => $amount,
                                'discount_amount' => 0,
                                'currency' => 'CAD',
                                'total_amount' => $amount,
                                'gateway' => 'paypal_standard',
                                'type' => 'one-time',
                                'mode' => 'live',
                                'status' => 'completed',
                                'transaction_id' =>  $arr['id'],
                                'is_published' => 1,
                                'form_id' => $amount == 30 ? 1754: 1648,
                                'date_created_gmt' => Carbon::now(),
                                'date_updated_gmt' => Carbon::now(),
                            ],
                            ]);
                    Log::info("Payment is Successfull. Your Transaction Id is : " . $arr['id']);
                    return redirect('/payment/success/' . $arr['id']);
                }
            }
            else{
                Log::error($response->getMessage());
                return redirect(self::CANCEL_URL);
            }
        }
        else{
            return redirect(self::CANCEL_URL);
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
