<?php
/**
 * EcoTokenService - Manages carbon tokens and eco ratings
 * OOP-based eco-credit system for EcoWorld
 */
class EcoTokenService {
    private $db;
    
    // Token earning rules
    private $tokensPerKgCarbon = 10; // Tokens per kg of CO2e saved
    private $tokenBonus = 1.5; // Bonus multiplier for certain actions

    public function __construct() {
        $this->db = DB::getInstance();
    }

    /**
     * Calculate carbon tokens earned from a purchase
     * @param float $carbonFootprint - Total carbon footprint of order in kg CO2e
     * @return int
     */
    public function calculateTokensEarned($carbonFootprint) {
        // Tokens = carbon saved × multiplier (assuming eco-friendly products reduce carbon)
        $tokens = (int)($carbonFootprint * $this->tokensPerKgCarbon);
        return $tokens;
    }

    /**
     * Award tokens to user after purchase
     * @param int $userId
     * @param int $tokens
     * @param float $carbonSaved
     * @return bool
     */
    public function awardTokens($userId, $tokens, $carbonSaved = 0) {
        try {
            // Update user's carbon tokens
            $this->db->query('
                UPDATE users 
                SET carbon_tokens = carbon_tokens + :tokens,
                    total_carbon_saved = total_carbon_saved + :carbon_saved,
                    eco_rating = :rating
                WHERE user_id = :user_id
            ');
            $this->db->bind(':tokens', $tokens);
            $this->db->bind(':carbon_saved', $carbonSaved);
            $this->db->bind(':rating', $this->calculateEcoRating($userId, $tokens));
            $this->db->bind(':user_id', $userId);
            
            return $this->db->execute();
        } catch (Exception $e) {
            error_log('Award Tokens Error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Calculate eco rating based on tokens
     * @param int $userId
     * @param int $newTokens - Tokens being added
     * @return string - Rating: Bronze, Silver, Gold, Green Elite
     */
    private function calculateEcoRating($userId, $newTokens = 0) {
        try {
            // Get current user tokens
            $this->db->query('SELECT carbon_tokens FROM users WHERE user_id = :user_id');
            $this->db->bind(':user_id', $userId);
            $user = $this->db->single();
            
            $totalTokens = ($user->carbon_tokens ?? 0) + $newTokens;
            
            // Rating brackets
            if ($totalTokens >= 5000) return 'Green Elite';
            if ($totalTokens >= 3000) return 'Gold';
            if ($totalTokens >= 1000) return 'Silver';
            return 'Bronze';
        } catch (Exception $e) {
            return 'Bronze';
        }
    }

    /**
     * Get user's eco stats
     * @param int $userId
     * @return array
     */
    public function getUserEcoStats($userId) {
        try {
            $this->db->query('
                SELECT carbon_tokens, total_carbon_saved, eco_rating
                FROM users
                WHERE user_id = :user_id
            ');
            $this->db->bind(':user_id', $userId);
            $stats = $this->db->single();
            
            if (!$stats) {
                return [
                    'tokens' => 0,
                    'carbon_saved' => 0,
                    'rating' => 'Bronze',
                    'next_level_tokens' => 1000
                ];
            }

            return [
                'tokens' => $stats->carbon_tokens,
                'carbon_saved' => $stats->total_carbon_saved,
                'rating' => $stats->eco_rating,
                'next_level_tokens' => $this->getNextLevelTokens($stats->eco_rating),
                'progress_percentage' => $this->getProgressPercentage($stats->eco_rating, $stats->carbon_tokens)
            ];
        } catch (Exception $e) {
            error_log('Eco Stats Error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Get tokens needed for next level
     * @param string $currentRating
     * @return int
     */
    private function getNextLevelTokens($currentRating) {
        $levels = [
            'Bronze' => 1000,
            'Silver' => 3000,
            'Gold' => 5000,
            'Green Elite' => 10000
        ];
        return $levels[$currentRating] ?? 0;
    }

    /**
     * Calculate progress to next level
     * @param string $currentRating
     * @param int $currentTokens
     * @return int - Percentage 0-100
     */
    private function getProgressPercentage($currentRating, $currentTokens) {
        $levels = [
            'Bronze' => ['min' => 0, 'max' => 1000],
            'Silver' => ['min' => 1000, 'max' => 3000],
            'Gold' => ['min' => 3000, 'max' => 5000],
            'Green Elite' => ['min' => 5000, 'max' => 10000]
        ];

        if (!isset($levels[$currentRating])) {
            return 0;
        }

        $level = $levels[$currentRating];
        $range = $level['max'] - $level['min'];
        $progress = $currentTokens - $level['min'];
        
        return min(100, (int)(($progress / $range) * 100));
    }

    /**
     * Get eco leaderboard (top eco-conscious users)
     * @param int $limit
     * @return array
     */
    public function getEcoLeaderboard($limit = 10) {
        try {
            $this->db->query('
                SELECT user_id, first_name, last_name, carbon_tokens, eco_rating, total_carbon_saved
                FROM users
                ORDER BY carbon_tokens DESC
                LIMIT :limit
            ');
            $this->db->bind(':limit', $limit, PDO::PARAM_INT);
            return $this->db->resultSet();
        } catch (Exception $e) {
            error_log('Leaderboard Error: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Get user's rank on leaderboard
     * @param int $userId
     * @return int
     */
    public function getUserRank($userId) {
        try {
            $this->db->query('
                SELECT COUNT(*) as rank
                FROM users
                WHERE carbon_tokens > (SELECT carbon_tokens FROM users WHERE user_id = :user_id)
            ');
            $this->db->bind(':user_id', $userId);
            $result = $this->db->single();
            return ($result->rank ?? 0) + 1;
        } catch (Exception $e) {
            return 0;
        }
    }

    /**
     * Redeem tokens (convert to discount)
     * @param int $userId
     * @param int $tokensToRedeem
     * @return array
     */
    public function redeemTokens($userId, $tokensToRedeem) {
        try {
            $userStats = $this->getUserEcoStats($userId);
            if ($userStats['tokens'] < $tokensToRedeem) {
                return [
                    'success' => false,
                    'message' => 'Insufficient tokens'
                ];
            }

            // Calculate discount (1 token = 1 rupee)
            $discount = $tokensToRedeem;

            // Deduct tokens
            $this->db->query('
                UPDATE users
                SET carbon_tokens = carbon_tokens - :tokens
                WHERE user_id = :user_id
            ');
            $this->db->bind(':tokens', $tokensToRedeem);
            $this->db->bind(':user_id', $userId);
            $this->db->execute();

            return [
                'success' => true,
                'message' => 'Tokens redeemed successfully',
                'discount' => $discount
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Error redeeming tokens: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Get token earning history
     * @param int $userId
     * @param int $limit
     * @return array
     */
    public function getTokenHistory($userId, $limit = 20) {
        try {
            $this->db->query('
                SELECT o.order_id, o.carbon_tokens_earned, o.created_at,
                       GROUP_CONCAT(p.product_name) as products
                FROM orders o
                LEFT JOIN order_items oi ON o.order_id = oi.order_id
                LEFT JOIN products p ON oi.product_id = p.product_id
                WHERE o.user_id = :user_id AND o.payment_status = "completed"
                GROUP BY o.order_id
                ORDER BY o.created_at DESC
                LIMIT :limit
            ');
            $this->db->bind(':user_id', $userId);
            $this->db->bind(':limit', $limit, PDO::PARAM_INT);
            return $this->db->resultSet();
        } catch (Exception $e) {
            error_log('Token History Error: ' . $e->getMessage());
            return [];
        }
    }
}