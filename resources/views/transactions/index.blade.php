<h1>Transactions Index</h1>

<ul>
@foreach ($transactions as $t)
    <li>{{ $t['date'] }} : {{ $t['amount'] }}</li>
@endforeach
</ul>
