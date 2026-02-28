<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class MoMoService
{
    private $partnerCode;
    private $accessKey;
    private $secretKey;
    private $endpoint;

    public function __construct()
    {
        // Cấu hình MoMo - Lấy từ .env hoặc config
        $this->partnerCode = config('services.momo.partner_code', 'MOMOBKUN20180529');
        $this->accessKey = config('services.momo.access_key', 'klm05TvNBzhg7h7j');
        $this->secretKey = config('services.momo.secret_key', 'at67qH6mk8w5Y1nAyMoYKMWACiEi2bsa');
        
        // Sử dụng test endpoint hoặc production endpoint
        $isProduction = config('services.momo.production', false);
        $this->endpoint = $isProduction 
            ? 'https://payment.momo.vn/v2/gateway/api/create'
            : 'https://test-payment.momo.vn/v2/gateway/api/create';
    }

    /**
     * Lấy redirect URL
     */
    private function getRedirectUrl()
    {
        $redirectUrl = config('services.momo.redirect_url');
        if ($redirectUrl) {
            return $redirectUrl;
        }
        
        // Tạo URL từ route nếu có request context
        try {
            if (function_exists('route')) {
                return route('payment.momo.redirect');
            }
        } catch (\Exception $e) {
            // Ignore
        }
        
        // Fallback: sử dụng APP_URL từ config hoặc tạo URL từ request
        $appUrl = config('app.url', 'http://localhost:8080/');
        return rtrim($appUrl, '/') . '/payment/momo/redirect';
    }

    /**
     * Lấy IPN URL
     */
    private function getIpnUrl()
    {
        $ipnUrl = config('services.momo.ipn_url');
        if ($ipnUrl) {
            return $ipnUrl;
        }
        
        // Tạo URL từ route nếu có request context
        try {
            if (function_exists('route')) {
                return route('payment.momo.ipn');
            }
        } catch (\Exception $e) {
            // Ignore
        }
        
        // Fallback: sử dụng APP_URL từ config hoặc tạo URL từ request
        $appUrl = config('app.url', 'http://localhost:8080/');
        return rtrim($appUrl, '/') . '/payment/momo/ipn';
    }

    /**
     * Tạo thanh toán MoMo
     */
    public function createPayment($orderId, $amount, $orderInfo = null, $extraData = '')
    {
        try {
            // Đảm bảo amount là số nguyên (MoMo yêu cầu amount là VND, không có phần thập phân)
            $amount = (int) round($amount);
            
            // Tạo requestId unique bằng cách kết hợp timestamp với random string
            // Format: {timestamp}_{random_string} để đảm bảo không trùng lặp
            $requestId = time() . '_' . uniqid() . '_' . mt_rand(1000, 9999);
            $requestType = 'captureWallet';
            
            $orderInfo = $orderInfo ?? "Thanh toán đơn hàng #{$orderId}";
            
            // Lấy URLs
            $redirectUrl = $this->getRedirectUrl();
            $ipnUrl = $this->getIpnUrl();
            
            // Tạo signature - đảm bảo các giá trị được encode đúng
            // Thứ tự các tham số theo alphabet: accessKey, amount, extraData, ipnUrl, orderId, orderInfo, partnerCode, redirectUrl, requestId, requestType
            $rawHash = "accessKey={$this->accessKey}&amount={$amount}&extraData={$extraData}&ipnUrl={$ipnUrl}&orderId={$orderId}&orderInfo={$orderInfo}&partnerCode={$this->partnerCode}&redirectUrl={$redirectUrl}&requestId={$requestId}&requestType={$requestType}";
            
            $signature = hash_hmac('sha256', $rawHash, $this->secretKey);
            
            // Log rawHash và signature để debug (không log secretKey)
            Log::debug('MoMo Signature Generation', [
                'raw_hash' => $rawHash,
                'signature' => $signature,
                'has_secret_key' => !empty($this->secretKey),
            ]);
            
            $data = [
                'partnerCode' => $this->partnerCode,
                'partnerName' => config('app.name', 'TOH Fish'),
                'storeId' => config('services.momo.store_id', 'MomoTestStore'),
                'requestId' => $requestId,
                'amount' => $amount,
                'orderId' => $orderId,
                'orderInfo' => $orderInfo,
                'redirectUrl' => $redirectUrl,
                'ipnUrl' => $ipnUrl,
                'lang' => 'vi',
                'extraData' => $extraData,
                'requestType' => $requestType,
                'signature' => $signature,
            ];
            
            Log::info('MoMo Payment Request', [
                'order_id' => $orderId,
                'amount' => $amount,
                'data' => $data,
            ]);
            
            // Gửi request đến MoMo
            $response = Http::timeout(10)
                ->withHeaders([
                    'Content-Type' => 'application/json',
                ])
                ->post($this->endpoint, $data);
            
            // Log response chi tiết để debug
            $statusCode = $response->status();
            $responseBody = $response->body();
            
            Log::info('MoMo Payment Response', [
                'order_id' => $orderId,
                'status_code' => $statusCode,
                'response_body' => $responseBody,
                'endpoint' => $this->endpoint,
            ]);
            
            if ($response->successful()) {
                $result = $response->json();
                
                Log::info('MoMo Payment Response Parsed', [
                    'order_id' => $orderId,
                    'result' => $result,
                ]);
                
                if (isset($result['resultCode']) && $result['resultCode'] == 0) {
                    return [
                        'success' => true,
                        'payUrl' => $result['payUrl'] ?? null,
                        'requestId' => $requestId,
                        'orderId' => $orderId,
                    ];
                } else {
                    Log::warning('MoMo Payment Failed', [
                        'order_id' => $orderId,
                        'result_code' => $result['resultCode'] ?? null,
                        'message' => $result['message'] ?? 'Lỗi không xác định từ MoMo',
                        'full_response' => $result,
                    ]);
                    
                    return [
                        'success' => false,
                        'message' => $result['message'] ?? 'Lỗi không xác định từ MoMo',
                        'resultCode' => $result['resultCode'] ?? null,
                    ];
                }
            }
            
            // Response không successful - log chi tiết để debug
            Log::error('MoMo Payment HTTP Error', [
                'order_id' => $orderId,
                'status_code' => $statusCode,
                'response_body' => $responseBody,
                'endpoint' => $this->endpoint,
            ]);
            
            // Thử parse JSON response nếu có
            $errorResult = null;
            try {
                $errorResult = $response->json();
            } catch (\Exception $e) {
                // Ignore nếu không parse được JSON
            }
            
            $errorMessage = 'Không thể kết nối đến MoMo';
            if ($errorResult && isset($errorResult['message'])) {
                $errorMessage = $errorResult['message'];
            } elseif ($statusCode) {
                $errorMessage = "Lỗi HTTP {$statusCode}: Không thể kết nối đến MoMo";
            }
            
            return [
                'success' => false,
                'message' => $errorMessage,
                'status_code' => $statusCode,
                'response' => $errorResult,
            ];
            
        } catch (\Exception $e) {
            Log::error('MoMo Payment Error', [
                'order_id' => $orderId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            
            return [
                'success' => false,
                'message' => 'Lỗi khi tạo thanh toán MoMo: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Tạo thanh toán MoMo với redirect URL và IPN URL tùy chỉnh
     */
    public function createPaymentWithUrls($orderId, $amount, $orderInfo = null, $extraData = '', $redirectUrl = null, $ipnUrl = null)
    {
        try {
            // Đảm bảo amount là số nguyên (MoMo yêu cầu amount là VND, không có phần thập phân)
            $amount = (int) round($amount);
            
            // Tạo requestId unique bằng cách kết hợp timestamp với random string
            $requestId = time() . '_' . uniqid() . '_' . mt_rand(1000, 9999);
            $requestType = 'captureWallet';
            
            $orderInfo = $orderInfo ?? "Thanh toán đơn hàng #{$orderId}";
            
            // Sử dụng URL tùy chỉnh hoặc fallback về default
            $redirectUrl = $redirectUrl ?? $this->getRedirectUrl();
            $ipnUrl = $ipnUrl ?? $this->getIpnUrl();
            
            // Tạo signature
            $rawHash = "accessKey={$this->accessKey}&amount={$amount}&extraData={$extraData}&ipnUrl={$ipnUrl}&orderId={$orderId}&orderInfo={$orderInfo}&partnerCode={$this->partnerCode}&redirectUrl={$redirectUrl}&requestId={$requestId}&requestType={$requestType}";
            
            $signature = hash_hmac('sha256', $rawHash, $this->secretKey);
            
            Log::debug('MoMo Signature Generation (Custom URLs)', [
                'raw_hash' => $rawHash,
                'signature' => $signature,
                'redirect_url' => $redirectUrl,
                'ipn_url' => $ipnUrl,
            ]);
            
            $data = [
                'partnerCode' => $this->partnerCode,
                'partnerName' => config('app.name', 'TOH Fish'),
                'storeId' => config('services.momo.store_id', 'MomoTestStore'),
                'requestId' => $requestId,
                'amount' => $amount,
                'orderId' => $orderId,
                'orderInfo' => $orderInfo,
                'redirectUrl' => $redirectUrl,
                'ipnUrl' => $ipnUrl,
                'lang' => 'vi',
                'extraData' => $extraData,
                'requestType' => $requestType,
                'signature' => $signature,
            ];
            
            Log::info('MoMo Payment Request (Custom URLs)', [
                'order_id' => $orderId,
                'amount' => $amount,
                'redirect_url' => $redirectUrl,
                'ipn_url' => $ipnUrl,
            ]);
            
            // Gửi request đến MoMo
            $response = Http::timeout(10)
                ->withHeaders([
                    'Content-Type' => 'application/json',
                ])
                ->post($this->endpoint, $data);
            
            // Log response chi tiết để debug
            $statusCode = $response->status();
            $responseBody = $response->body();
            
            Log::info('MoMo Payment Response (Custom URLs)', [
                'order_id' => $orderId,
                'status_code' => $statusCode,
                'response_body' => $responseBody,
            ]);
            
            if ($response->successful()) {
                $result = $response->json();
                
                if (isset($result['resultCode']) && $result['resultCode'] == 0) {
                    return [
                        'success' => true,
                        'payUrl' => $result['payUrl'] ?? null,
                        'requestId' => $requestId,
                        'orderId' => $orderId,
                    ];
                } else {
                    return [
                        'success' => false,
                        'message' => $result['message'] ?? 'Lỗi không xác định từ MoMo',
                        'resultCode' => $result['resultCode'] ?? null,
                    ];
                }
            }
            
            // Response không successful
            $errorResult = null;
            try {
                $errorResult = $response->json();
            } catch (\Exception $e) {
                // Ignore
            }
            
            $errorMessage = 'Không thể kết nối đến MoMo';
            if ($errorResult && isset($errorResult['message'])) {
                $errorMessage = $errorResult['message'];
            } elseif ($statusCode) {
                $errorMessage = "Lỗi HTTP {$statusCode}: Không thể kết nối đến MoMo";
            }
            
            return [
                'success' => false,
                'message' => $errorMessage,
                'status_code' => $statusCode,
            ];
            
        } catch (\Exception $e) {
            Log::error('MoMo Payment Error (Custom URLs)', [
                'order_id' => $orderId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            
            return [
                'success' => false,
                'message' => 'Lỗi khi tạo thanh toán MoMo: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Xác thực signature từ callback MoMo
     */
    public function verifySignature($data)
    {
        $accessKey = $data['accessKey'] ?? '';
        $amount = $data['amount'] ?? '';
        $extraData = $data['extraData'] ?? '';
        $message = $data['message'] ?? '';
        $orderId = $data['orderId'] ?? '';
        $orderInfo = $data['orderInfo'] ?? '';
        $orderType = $data['orderType'] ?? '';
        $partnerCode = $data['partnerCode'] ?? '';
        $payType = $data['payType'] ?? '';
        $requestId = $data['requestId'] ?? '';
        $responseTime = $data['responseTime'] ?? '';
        $resultCode = $data['resultCode'] ?? '';
        $transId = $data['transId'] ?? '';
        $signature = $data['signature'] ?? '';
        
        $rawHash = "accessKey={$accessKey}&amount={$amount}&extraData={$extraData}&message={$message}&orderId={$orderId}&orderInfo={$orderInfo}&orderType={$orderType}&partnerCode={$partnerCode}&payType={$payType}&requestId={$requestId}&responseTime={$responseTime}&resultCode={$resultCode}&transId={$transId}";
        
        $calculatedSignature = hash_hmac('sha256', $rawHash, $this->secretKey);
        
        return hash_equals($calculatedSignature, $signature);
    }
}

