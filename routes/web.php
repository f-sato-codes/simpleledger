<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\CategoryController;


/*メインページ案内 */
Route::get('/', function(){
    return redirect()->route('transactions');

});

/*ログインのみのユーザーのみ */
Route::get('/transactions', [TransactionController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('transactions');

Route::middleware('auth')->group(function () {
    /*トランザクション*/
    /*登録画面へ*/
    Route::get('/transactions/create',[TransactionController::class,'create'])
        ->name('transactions.create');
    /*登録 */
    Route::post('/transactions', [TransactionController::class, 'store'])
        ->name('transacitons.store');
    /*編集画面へ*/
    Route::get('/transactions/{transaction}/edit', [TransactionController::class, 'edit'])
        ->name('transacitons.edit');
    /*更新*/
     Route::put('/transactions/{transaction}', [TransactionController::class, 'update'])
    ->name('transactions.update');
    /*削除 */ 
    Route::delete('/transactions/{transaction}', [TransactionController::class, 'destroy']);


    /*カテゴリーの登録*/
      Route::get('/categories', [CategoryController::class, 'index'])
        ->name('categories.index');

    Route::post('/categories', [CategoryController::class, 'store'])
        ->name('categories.store');

    Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])
        ->name('categories.destroy');
                                                                                
});

require __DIR__.'/auth.php';
