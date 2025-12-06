<?php

namespace App\Http\Controllers;

use App\Models\Wallet;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class WalletController extends Controller
{
    // Top Up Wallet
    public function topUp(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:10|max:10000'
        ]);

        DB::beginTransaction();
        try {
            $wallet = Wallet::where('user_id', Auth::id())->first();
            
            if (!$wallet) {
                $wallet = Wallet::create([
                    'user_id' => Auth::id(),
                    'balance' => 0,
                    'points' => 0,
                    'member_level' => 'Bronze',
                    'cashback_earned' => 0
                ]);
            }

            // Add balance
            $wallet->balance += $request->amount;
            
            // Add points (1 point per $1)
            $wallet->points += floor($request->amount);
            
            // Update member level based on points
            $wallet->member_level = $this->getMemberLevel($wallet->points);
            
            $wallet->save();

            // Create transaction record
            Transaction::create([
                'user_id' => Auth::id(),
                'type' => 'income',
                'category' => 'topup',
                'description' => 'Wallet Top Up',
                'amount' => $request->amount,
                'icon' => '💰'
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Top up successful!',
                'new_balance' => number_format($wallet->balance, 2),
                'points' => $wallet->points,
                'level' => $wallet->member_level
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Top up failed: ' . $e->getMessage()
            ], 500);
        }
    }

    // Transfer Money
    public function transfer(Request $request)
    {
        $request->validate([
            'recipient_email' => 'required|email|exists:users,email',
            'amount' => 'required|numeric|min:1'
        ]);

        // Prevent self transfer
        if ($request->recipient_email === Auth::user()->email) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot transfer to yourself'
            ], 400);
        }

        DB::beginTransaction();
        try {
            $sender = Auth::user();
            $senderWallet = Wallet::where('user_id', $sender->id)->first();

            // Check balance
            if (!$senderWallet || $senderWallet->balance < $request->amount) {
                return response()->json([
                    'success' => false,
                    'message' => 'Insufficient balance'
                ], 400);
            }

            // Get recipient
            $recipient = User::where('email', $request->recipient_email)->first();
            $recipientWallet = Wallet::firstOrCreate(
                ['user_id' => $recipient->id],
                [
                    'balance' => 0,
                    'points' => 0,
                    'member_level' => 'Bronze',
                    'cashback_earned' => 0
                ]
            );

            // Deduct from sender
            $senderWallet->balance -= $request->amount;
            $senderWallet->save();

            // Add to recipient
            $recipientWallet->balance += $request->amount;
            $recipientWallet->save();

            // Create sender transaction
            Transaction::create([
                'user_id' => $sender->id,
                'type' => 'expense',
                'category' => 'transfer',
                'description' => 'Transfer to ' . $recipient->name,
                'amount' => $request->amount,
                'icon' => '📤'
            ]);

            // Create recipient transaction
            Transaction::create([
                'user_id' => $recipient->id,
                'type' => 'income',
                'category' => 'transfer',
                'description' => 'Transfer from ' . $sender->name,
                'amount' => $request->amount,
                'icon' => '📥'
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Transfer successful!',
                'new_balance' => number_format($senderWallet->balance, 2)
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Transfer failed: ' . $e->getMessage()
            ], 500);
        }
    }

    private function getMemberLevel($points)
    {
        if ($points >= 7000) return 'Platinum';
        if ($points >= 3000) return 'Gold';
        if ($points >= 1000) return 'Silver';
        return 'Bronze';
    }
}