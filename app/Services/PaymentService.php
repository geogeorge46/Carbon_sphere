<?php
/**
 * PaymentService - Handles Razorpay payment integration
 * OOP-based payment processing for EcoWorld
 */
class PaymentService {
    private $razorpayKeyId = 'rzp_test_RVILbWsdKgmBKg';
    private $razorpayKeySecret = 'qKwxrz5bqbXhpYSxR4a1Eenz';
    private $db;

    public function __construct() {
        $this->db = DB::getInstance();
    }

    /**
     * Create a Razorpay order
     * @param float $amount - Amount in paise (multiply by 100)
     * @param int $userId - User ID
     * @param string $email - User email
     * @param array $metadata - Additional data
     * @return array - Razorpay order details
     */
    public function createRazorpayOrder($amount, $userId, $email, $metadata = []) {
        try {
            $amountInPaise = (int)($amount * 100); // Convert to paise
            
            $orderData = [
                'amount' => $amountInPaise,
                'currency' => 'INR',
                'receipt' => 'order_' . $userId . '_' . time(),
                'notes' => array_merge($metadata, ['user_id' => $userId])
            ];

            // Create cURL request to Razorpay API
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://api.razorpay.com/v1/orders');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
            curl_setopt($ch, CURLOPT_USERPWD, $this->razorpayKeyId . ':' . $this->razorpayKeySecret);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($orderData));
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($httpCode === 200) {
                return json_decode($response, true);
            } else {
                return ['error' => 'Failed to create Razorpay order'];
            }
        } catch (Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    /**
     * Verify Razorpay payment signature
     * @param string $razorpayOrderId
     * @param string $razorpayPaymentId
     * @param string $razorpaySignature
     * @return bool
     */
    public function verifyPaymentSignature($razorpayOrderId, $razorpayPaymentId, $razorpaySignature) {
        $signatureData = $razorpayOrderId . '|' . $razorpayPaymentId;
        $expectedSignature = hash_hmac('sha256', $signatureData, $this->razorpayKeySecret);
        return hash_equals($expectedSignature, $razorpaySignature);
    }

    /**
     * Record payment transaction in database
     * @param int $userId
     * @param int $orderId
     * @param string $razorpayOrderId
     * @param float $amount
     * @return bool
     */
    public function recordPaymentTransaction($userId, $orderId, $razorpayOrderId, $amount) {
        try {
            $this->db->query('
                INSERT INTO payment_transactions (user_id, order_id, razorpay_order_id, amount, status)
                VALUES (:user_id, :order_id, :razorpay_order_id, :amount, :status)
            ');
            $this->db->bind(':user_id', $userId);
            $this->db->bind(':order_id', $orderId);
            $this->db->bind(':razorpay_order_id', $razorpayOrderId);
            $this->db->bind(':amount', $amount);
            $this->db->bind(':status', 'initiated');
            
            return $this->db->execute();
        } catch (Exception $e) {
            error_log('Payment Transaction Error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Update payment transaction after successful payment
     * @param string $razorpayOrderId
     * @param string $razorpayPaymentId
     * @param string $razorpaySignature
     * @return bool
     */
    public function updatePaymentTransaction($razorpayOrderId, $razorpayPaymentId, $razorpaySignature) {
        try {
            $this->db->query('
                UPDATE payment_transactions 
                SET razorpay_payment_id = :payment_id, 
                    razorpay_signature = :signature, 
                    status = :status,
                    updated_at = NOW()
                WHERE razorpay_order_id = :order_id
            ');
            $this->db->bind(':payment_id', $razorpayPaymentId);
            $this->db->bind(':signature', $razorpaySignature);
            $this->db->bind(':status', 'successful');
            $this->db->bind(':order_id', $razorpayOrderId);
            
            return $this->db->execute();
        } catch (Exception $e) {
            error_log('Payment Update Error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get Razorpay API credentials
     * @return array
     */
    public function getCredentials() {
        return [
            'key_id' => $this->razorpayKeyId,
            'key_secret' => $this->razorpayKeySecret
        ];
    }

    /**
     * Fetch payment details from Razorpay
     * @param string $razorpayPaymentId
     * @return array
     */
    public function fetchPaymentDetails($razorpayPaymentId) {
        try {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://api.razorpay.com/v1/payments/' . $razorpayPaymentId);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
            curl_setopt($ch, CURLOPT_USERPWD, $this->razorpayKeyId . ':' . $this->razorpayKeySecret);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($httpCode === 200) {
                return json_decode($response, true);
            }
            return null;
        } catch (Exception $e) {
            error_log('Razorpay Fetch Error: ' . $e->getMessage());
            return null;
        }
    }
}