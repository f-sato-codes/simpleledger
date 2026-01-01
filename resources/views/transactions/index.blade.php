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
                    <td><a href="/transactions/{{ $transaction->id }}/edit">編集</a></td>
                <td>
                    <form action="/transactions/{{$transaction->id}}" method="post"
                        onsubmit="return confirm('本当に削除しますか？')";>
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

<a href="{{ url('/transactions/create') }}" class="fab">登録する</a>
