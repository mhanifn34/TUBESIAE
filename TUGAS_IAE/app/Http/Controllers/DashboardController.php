<?php

namespace App\Http\Controllers;

use App\Models\Wallet;
use App\Models\Transaction;
use App\Models\Reward;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Get or create wallet for user
        $wallet = Wallet::firstOrCreate(
            ['user_id' => $user->id],
            [
                'balance' => 0,
                'points' => 0,
                'member_level' => 'Bronze',
                'cashback_earned' => 0
            ]
        );

        // Get recent transactions (last 5)
        $transactions = Transaction::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Get active reward/promo
        $activeReward = Reward::where('is_active', true)
            ->where('valid_until', '>=', now())
            ->first();

        // Calculate level progress
        $levelProgress = $this->calculateLevelProgress($wallet->points);

        // Calculate cashback this month
        $cashbackThisMonth = Transaction::where('user_id', $user->id)
            ->where('category', 'cashback')
            ->where('type', 'income')
            ->whereMonth('created_at', now()->month)
            ->sum('amount');

        return view('dashboard', compact(
            'user', 
            'wallet', 
            'transactions', 
            'activeReward', 
            'levelProgress',
            'cashbackThisMonth'
        ));
    }

    private function calculateLevelProgress($points)
    {
        $levels = [
            'Bronze' => ['min' => 0, 'max' => 999],
            'Silver' => ['min' => 1000, 'max' => 2999],
            'Gold' => ['min' => 3000, 'max' => 6999],
            'Platinum' => ['min' => 7000, 'max' => PHP_INT_MAX],
        ];

        $currentLevel = 'Bronze';
        $nextLevel = 'Silver';
        $pointsToNext = 1000;
        $progress = 0;

        foreach ($levels as $level => $range) {
            if ($points >= $range['min'] && $points <= $range['max']) {
                $currentLevel = $level;
                
                // Find next level
                $levelKeys = array_keys($levels);
                $currentIndex = array_search($level, $levelKeys);
                
                if ($currentIndex < count($levelKeys) - 1) {
                    $nextLevel = $levelKeys[$currentIndex + 1];
                    $nextLevelMin = $levels[$nextLevel]['min'];
                    $pointsToNext = $nextLevelMin - $points;
                    $rangeSize = $nextLevelMin - $range['min'];
                    $progress = (($points - $range['min']) / $rangeSize) * 100;
                } else {
                    // Already at max level
                    $nextLevel = $level;
                    $pointsToNext = 0;
                    $progress = 100;
                }
                break;
            }
        }

        return [
            'current_level' => $currentLevel,
            'next_level' => $nextLevel,
            'points_to_next' => $pointsToNext,
            'progress_percentage' => round($progress, 2)
        ];
    }
}