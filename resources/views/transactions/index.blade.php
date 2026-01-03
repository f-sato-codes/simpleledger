@extends('layouts.app')

@section('content')
<div style="
    max-width: 700px;
    margin: 24px auto;
    padding: 24px;
    background: #ffffff;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
">

    {{-- 月切替ヘッダ --}}
    <h2 style="
        text-align: center;
        margin-bottom: 16px;
        font-weight: bold;
    ">
        <a href="{{ route('transactions', ['month' => $month->copy()->subMonth()->format('Y-m')]) }}">
            ◀
        </a>

        <span style="margin: 0 12px;">
            {{ $month->format('Y年n月') }}
        </span>

        <a href="{{ route('transactions', ['month' => $month->copy()->addMonth()->format('Y-m')]) }}">
            ▶
        </a>
    </h2>

    {{-- 月次サマリー --}}
    <ul style="
        list-style: none;
        padding: 0;
        margin: 0 0 16px 0;
        line-height: 1.8;
    ">
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

    <hr style="margin: 16px 0;">

    {{-- 取引一覧 --}}
    @if ($transactions->isEmpty())
        <p>データがありません</p>
    @else
        <table style="
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
        ">
            <thead>
                <tr style="border-bottom: 2px solid #ccc; background: #f9fafb;">
                    <th style="padding: 8px; text-align: left;">日付</th>
                    <th style="padding: 8px; text-align: left;">カテゴリ</th>
                    <th style="padding: 8px; text-align: right;">金額</th>
                    <th style="padding: 8px; text-align: center;">編集</th>
                    <th style="padding: 8px; text-align: center;">削除</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($transactions as $transaction)
                    <tr style="border-bottom: 1px solid #eee;">
                        <td style="padding: 8px;">
                            {{ \Carbon\Carbon::parse($transaction->date)->format('Y年n月j日') }}
                        </td>

                        <td style="padding: 8px;">
                            {{ $transaction->category->category_name }}
                        </td>

                        <td style="
                            padding: 8px;
                            text-align: right;
                            {{ $transaction->type === 'income' ? 'color: blue;' : 'color: red;' }}
                        ">
                            {{ number_format($transaction->amount) }} 円
                        </td>

                        <td style="padding: 8px; text-align: center;">
                            <a href="{{ url('/transactions/' . $transaction->id . '/edit') }}">
                                編集
                            </a>
                        </td>

                        <td style="padding: 8px; text-align: center;">
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

    {{-- アクション --}}
    <div style="margin-top: 16px;">
        <a href="{{ url('/transactions/create') }}">
            収支を登録する
        </a>
    </div>

    <div style="margin-top: 8px;">
        <a href="{{ url('/categories') }}">
            カテゴリーを登録・削除する
        </a>
    </div>

</div>
@endsection
