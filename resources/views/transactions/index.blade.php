<h1>Transactions Index</h1>

<p>{{ $month }}</p>

@if ($transactions->isEmpty())
    <p>データがありません</p>
@else
    <table>
        <thead>
            <tr>
                <th>日付</th>
                <th>カテゴリ</th>
                <th>金額</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($transactions as $transaction)
                <tr>
                    <td>{{ $transaction->date }}</td>
                    <td>{{ $transaction->category->category_name }}</td>
                    <td>{{ $transaction->amount }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endif

<a href="{{ url('/transactions/create') }}" class="fab">登録する</a>
