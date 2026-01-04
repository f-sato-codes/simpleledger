<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\CategoryController;
use App\Models\Transaction;
use App\Models\Category;
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


        // 月をリクエストから取得（なければ今月）
        $monthParam = $request->input('month');

        if ($monthParam) {
            $month = Carbon::createFromFormat('Y-m', $monthParam);
        } else {
            $month = Carbon::now();
        }

        // 月初・月末を算出
        $start = $month->copy()->startOfMonth();
        $end   = $month->copy()->endOfMonth();

        //  収支を取得
        $transactions = Transaction::with('category')
            ->where('user_id', auth()->id())
            ->whereBetween('date', [$start, $end])
            ->orderBy('date', 'asc')
            ->get();


         // 支出をとってくる
            $expenseTotal = Transaction::where('user_id', auth()->id()) 
            ->whereBetween('date', [$start, $end])
            ->where('type','expense')
            ->orderBy('date','asc')
            ->sum('amount');

            //収入をとってくる
            $incomeTotal = Transaction::where('user_id', auth()->id()) 
            ->whereBetween('date', [$start, $end])
            ->where('type','income')
            ->sum('amount');
            //収支の合計
            $balance = $incomeTotal - $expenseTotal;
            //dd($incomeTotal, $expenseTotal, $balance);

            // View に渡す
            return view('transactions.index', [
                'transactions' => $transactions,
                'incomeTotal'   => $incomeTotal,
                'expenseTotal' => $expenseTotal,
                'balance'      => $balance,
                'month' => $month,
            ]);

    }

    /*登録画面を表示する */
    public function create()
    {
          $categories = Category::where('user_id', Auth::id())->get();
            return view('transactions.create', compact('categories'));
    }

    /*登録する */
   public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'date'        => 'required|date',
            'amount'      => 'required|integer',
        ]);

        // ★ カテゴリを取得（本人のもの限定）
        $category = Category::where('id', $validated['category_id'])
            ->where('user_id', Auth::id())
            ->firstOrFail();

        Transaction::create([
            'user_id'     => Auth::id(),
            'category_id' => $category->id,
            'type'        => $category->type, // ★ 完全自動
            'date'        => $validated['date'],
            'amount'      => $validated['amount'],
        ]);

        return redirect()
            ->route('transactions')
            ->with('success', '登録しました');
    }

    //更新画面へ
    public function edit(Transaction $transaction)
    {
        // 本人チェック
        if ($transaction->user_id !== Auth::id()) {
            abort(403);
        }

    $categories = Category::where('user_id', Auth::id())->get();
    return view('transactions.edit', compact('transaction', 'categories'));
    }

    //更新処理
    public function update(Request $request, Transaction $transaction)
    {
        // 本人チェック
        if ($transaction->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'date'        => 'required|date',
            'amount'      => 'required|integer|min:0',
        ]);

        // ★ カテゴリから type を決定
        $category = Category::where('id', $validated['category_id'])
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $transaction->update([
            'category_id' => $category->id,
            'type'        => $category->type, // ★ 自動セット
            'date'        => $validated['date'],
            'amount'      => $validated['amount'],
        ]);

        return redirect()->route('transactions');
    }



     //削除処理
     public function destroy(Transaction $transaction)
     {
        $transaction->delete();
        //一覧へ
        return redirect()->route('transactions');

     }
}