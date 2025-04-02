<?php
class MomoHelper {
    private $endpoint = "https://test-payment.momo.vn/v2/gateway/api/create";
    private $partnerCode = "MOMOXXX"; // Thay bằng partner code thật của bạn
    private $accessKey = "xxx";   // Thay bằng access key thật
    private $secretKey = "xxx";   // Thay bằng secret key thật
    
    public function createPayment($orderId, $amount, $orderInfo) {
        $redirectUrl = "http://localhost/WebbandoTT/thanh-toan/ket-qua";
        $ipnUrl = "http://localhost/WebbandoTT/app/api/payments/momo_ipn.php";
        $requestId = time() . "";
        $requestType = "captureWallet";
        $extraData = "";

        $rawHash = "accessKey=" . $this->accessKey .
                   "&amount=" . $amount .
                   "&extraData=" . $extraData .
                   "&ipnUrl=" . $ipnUrl .
                   "&orderId=" . $orderId .
                   "&orderInfo=" . $orderInfo .
                   "&partnerCode=" . $this->partnerCode .
                   "&redirectUrl=" . $redirectUrl .
                   "&requestId=" . $requestId .
                   "&requestType=" . $requestType;
        
        $signature = hash_hmac('sha256', $rawHash, $this->secretKey);

        $data = [
            'partnerCode' => $this->partnerCode,
            'partnerName' => "Sport Elite",
            'storeId' => "SportEliteMoMo",
            'requestId' => $requestId,
            'amount' => $amount,
            'orderId' => $orderId,
            'orderInfo' => $orderInfo,
            'redirectUrl' => $redirectUrl,
            'ipnUrl' => $ipnUrl,
            'lang' => 'vi',
            'extraData' => $extraData,
            'requestType' => $requestType,
            'signature' => $signature
        ];

        $ch = curl_init($this->endpoint);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, 1);

        $result = curl_exec($ch);
        curl_close($ch);

        return json_decode($result, true);
    }
}
