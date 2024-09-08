<?php
namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Log;

class PhonePePaymentController extends Controller
{
    protected $merchantId;
    protected $merchantKey;
    protected $merchantSalt;
    protected $baseUrl;

    public function __construct()
    {
        $this->merchantId = config('services.phonepe.merchant_id');
        $this->merchantKey = config('services.phonepe.merchant_key');
        $this->merchantSalt = config('services.phonepe.merchant_salt');
        $this->baseUrl = config('services.phonepe.base_url');
    }

    public function createOrder(Request $request)
    {
        $orderId = 'ORDER' . time(); // Unique order ID
        $amount = $request->amount * 100; // Amount in paise
        $payload = json_encode([
            'merchantId' => $this->merchantId,
            'transactionId' => $orderId,
            'amount' => $amount,
            'merchantOrderId' => $orderId,
            //'redirectUrl' => route('phonepe.verify-payment'),
            'redirectUrl' => route('verify-payment'),

            'merchantUserId' => 'user123',
            'paymentInstrument' => [
                'type' => 'PAY_PAGE'
            ]
        ]);

        // $checksum = hash('sha256', $payload . "/v3/payment" . $this->merchantSalt);

        // $response = Http::withHeaders([
        //     'Content-Type' => 'application/json',
        //     'X-VERIFY' => $checksum . '###' . $this->merchantKey,
        //     'X-MERCHANT-ID' => $this->merchantId,
        // ])->post($this->baseUrl . '/v3/payment', [
        //     'request' => base64_encode($payload),
        //     'checksum' => $checksum
        // ]);



        $checksum = hash('sha256', $payload . "/v3/payment" . $this->merchantSalt);

        $xVerify = $checksum . '###' . $this->merchantKey;
        
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'X-VERIFY' => $xVerify, // This is the correct X-VERIFY value
            'X-MERCHANT-ID' => $this->merchantId,
        ])->post($this->baseUrl . '/v3/payment', [
            'request' => base64_encode($payload),
            'checksum' => $checksum
        ]);
        







        return response()->json($response->json());
    }

    public function verifyPayment(Request $request)
    {
        $checksum = hash('sha256', $request->orderId . "/v3/payment/status" . $this->merchantSalt);
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'X-VERIFY' => $checksum . '###' . $this->merchantKey,
            'X-MERCHANT-ID' => $this->merchantId,
        ])->post($this->baseUrl . '/v3/payment/status', [
            'merchantId' => $this->merchantId,
            'transactionId' => $request->orderId,
        ]);

        return response()->json($response->json());
    }
}
