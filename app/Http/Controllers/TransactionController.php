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

    /*登録画面を表示する */
    public function create()
    {
        $categories = Category::all();
        return view('transactions.create',compact('categories'));
    }

    /*登録する */
    public function store(Request $request){
        
        //バリデーション
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'type'        => 'required|in:income,expense',
            'date'       => 'required',
            'amount'     => 'required|integer',
        ]);

        //登録
        Transaction::create([
            'user_id'       => Auth::id(),
            'category_id'  => $validated['category_id'],
            'type'          => $validated['type'],
            'date'          => $validated['date'],
            'amount'        => $validated['amount'],
        ]);

        return redirect()
            ->route('transactions')
            ->with('success','登録しました');

    }
    //更新画面へ
    public function edit(Transaction $transaction)
    {
        $categories = Category::all();
        return view('transactions.edit', compact('transaction', 'categories'));
    }

    //更新処理
    public function update(Request $request, Transaction $transaction)
    {
           /*
    |------------------------------------------
    | ① Request の中身を確認したいとき
    |------------------------------------------
    */
    // dd($request->all());

    /*
    |------------------------------------------
    | ② Transaction の中身を確認したいとき
    |------------------------------------------
    */
    // dd($transaction);
    // App\Models\Transaction.ph


            //バリデーション
        $validated = $request->validate([
            'type'        => 'required|in:income,expense',
             'date'       => 'required|date',
            'category_id' => 'required|exists:categories,id',
            'amount'      => 'required|integer|min:0',
        ]);

         //本人チェック
        if ($transaction->user_id !== Auth::id()) {
            abort(403);
        }
        //更新へ
        $transaction->update($validated);
        //一覧へ
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