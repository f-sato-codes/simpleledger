<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Carbon\Carbon;

class TransactionController extends Controller
{
    public function __construct()
    {
        // ログイン必須
        $this->middleware('auth');
    }

    /**
     * 収支一覧（R）
     */
    public function index(Request $request)
    {
        // ① 月をリクエストから取得（なければ今月）
        $month = $request->input('month');

        if (!$month) {
            $month = Carbon::now();
        } else {
            $month = Carbon::createFromFormat('Y-m', $month);
        }

        // ② 月初・月末を算出
        $start = $month->copy()->startOfMonth();
        $end   = $month->copy()->endOfMonth();

        // ③ ログインユーザーの取引を取得（カテゴリ含む）
        $transactions = Transaction::with('category')
            ->where('user_id', auth()->id())
            ->whereBetween('date', [$start, $end])
            ->get();

        // ④ View に渡す
        return view('transactions.index', [
            'transactions' => $transactions,
            'month' => $month->format('Y-m'),
        ]);
    }
}
