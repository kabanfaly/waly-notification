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

    public function showSubscriptionPayment(Request $request, string $entryId)
    {
        $isProfessional = $request->input('isProfessional') === 'true';
        $fromMemberPage= $request->input('fromMember');
        if ($isProfessional)
        {
            $this->studentToProfessional($entryId); // upgrade to professional
        }

        $member = $this->getMemberByEntryId($entryId);
        return view('payment.subscription', ['member' => $member, 'isProfessional' => $isProfessional, 'fromMember' => $fromMemberPage]);
    }

    public function showSubscriptionForm(Request $request)
    {
        $isProfessional = $request->input('isProfessional') === 'true';
        $email = session()->get('email');

        if ($isProfessional && $email) // downgrade to student
        {
            $member = $this->getMemberByColumnName('email', $email);
            $this->professionalToStudent($member->entry_id);
        }
        return view('payment.subscription_form', ['error' => false, 'email' => $email]);
    }

    public function searchMember(Request $request)
    {
        $email = $request->input('email');
        session()->put('email', $email);
        $isNotStudent = $request->input('isStudent');
        $member = $this->getMemberByColumnName('email', $email);
        if ($member)
        {
            return redirect('/payment/subscription/' . $member->entry_id. '?isProfessional=' . ($isNotStudent ? 'true' : 'false') . '&fromMember=true');
        }
        return view('payment.subscription_form', ['error' => 'notification.account_not_found', 'email' => $email]);
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

    private function getMemberByEntryId(string $entryId)
    {
        return $this->getMemberByColumnName('entry_id', $entryId);
    }

    private function getMemberByColumnName(string $columnName, string $value)
    {
        return DB::table('VbE_view_wpforms_members')
        ->where($columnName, $value)
        ->first();
    }


    public function paySubscription(Request $request, string $entryId)
    {
        Log::info("Starting a subscription payment");
        try {
            $member = $this->getMemberByEntryId($entryId);
            $response = $this->gateway->purchase(array(
                'amount' => formatAmount($member->amount),
                'currency' => env('PAYPAL_CURRENCY'),
                'returnUrl' => url('/payment/subscription/success/' . $entryId),
                'cancelUrl' => url(self::CANCEL_URL),
            ))->send();
            session()->put('isStudent', false);
            if ($response->isRedirect()) {
                $response->redirect();
            }
            else {
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

            if ($response->isSuccessful())
            {
                $arr = $response->getData();
                $member = $this->getMemberByEntryId($entryId);
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
                            'form_id' => $amount > 30 ? 1648 : 1754,
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

    private function studentToProfessional(string $entryId)
    {
        // update form_id
        DB::table('VbE_wpforms_entry_fields')
        ->where('entry_id', $entryId)
        ->update(
            [
                'form_id' => 1648
            ],
        );
        // update status
        DB::table('VbE_wpforms_entry_fields')
        ->where('entry_id', $entryId)
        ->where('field_id', 143)
        ->update(
            [
                'field_id' => 88,
                'value' =>  'Professionnel(le) - &#36; ' . config('app.professional_fees')
            ],
        );

        // amount
        DB::table('VbE_wpforms_entry_fields')
        ->where('entry_id', $entryId)
        ->where('field_id', 130)
        ->update(
            [
                'field_id' => 129,
                'value' =>  '&#36; ' . config('app.professional_fees')
            ],
        );
    }

    private function professionalToStudent(string $entryId)
    {
        // update form_id
        DB::table('VbE_wpforms_entry_fields')
        ->where('entry_id', $entryId)
        ->update(
            [
                'form_id' => 1754
            ],
        );
        // update status
        DB::table('VbE_wpforms_entry_fields')
        ->where('entry_id', $entryId)
        ->where('field_id', 88)
        ->update(
            [
                'field_id' => 143,
                'value' =>  'Professionnel(le) - &#36; ' . config('app.student_fees')
            ],
        );

        // amount
        DB::table('VbE_wpforms_entry_fields')
        ->where('entry_id', $entryId)
        ->where('field_id', 129)
        ->update(
            [
                'field_id' => 130,
                'value' =>  '&#36; ' . config('app.student_fees')
            ],
        );
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
