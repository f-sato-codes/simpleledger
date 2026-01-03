@extends('layouts.app')

@section('content')

{{-- 月切替ヘッダ --}}
<h2 style="text-align:center; margin-bottom:16px;">
    <a href="{{ route('transactions', [
        'month' => $month->copy()->subMonth()->format('Y-m')
    ]) }}">
        ◀
    </a>

    <span style="margin:0 12px; font-weight:bold;">
        {{ $month->format('Y年n月') }}
    </span>

    <a href="{{ route('transactions', [
        'month' => $month->copy()->addMonth()->format('Y-m')
    ]) }}">
        ▶
    </a>
</h2>

{{-- 月次サマリー --}}
<ul>
    <li style="color: blue;">
        収入：{{ number_format($incomeTotal) }} 円
    </li>
    <li style="color: red;">
        支出：{{ number_format($expenseTotal) }} 円
    </li>
    <li style="{{ $balance >= 0 ? 'color: blue;' : 'color: red;' }}">
        収支：{{ number_format($balance) }} 円
    </li>
</ul>

{{-- 取引一覧 --}}
@if ($transactions->isEmpty())
    <p>データがありません</p>
@else
    <table border="1" cellpadding="6" cellspacing="0">
        <thead>
            <tr>
                <th>日付</th>
                <th>カテゴリ</th>
                <th>金額</th>
                <th>編集</th>
                <th>削除</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($transactions as $transaction)
                <tr>
                    <td>
                        {{ \Carbon\Carbon::parse($transaction->date)->format('Y年n月j日') }}
                    </td>

                    <td>{{ $transaction->category->category_name }}</td>

                    <td style="text-align:right;
                        {{ $transaction->type === 'income' ? 'color: blue;' : 'color: red;' }}">
                        {{ number_format($transaction->amount) }} 円
                    </td>

                    <td>
                        <a href="{{ url('/transactions/' . $transaction->id . '/edit') }}">
                            編集
                        </a>
                    </td>

                    <td>
                        <form action="{{ url('/transactions/' . $transaction->id) }}"
                              method="post"
                              onsubmit="return confirm('本当に削除しますか？');">
                            @csrf
                            @method('DELETE')
                            <button type="submit">削除</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endif

{{-- 登録ボタン --}}
<p style="margin-top:16px;">
    <a href="{{ url('/transactions/create') }}">
        収支を登録する
    </a>
</p>

{{-- カテゴリー管理 --}}
<p style="margin-top:16px;">
    <a href="{{ url('/categories') }}">
        カテゴリーを登録・削除する
    </a>
</p>

@endsection
