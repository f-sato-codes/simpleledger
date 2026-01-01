<h1>収支編集</h1>

<form action="{{ route('transactions.update', $transaction) }}" method="post">
    @csrf
    @method('PUT')

    {{-- 種別 --}}
    <div>
        <label>種別</label>
        <select name="type">
            <option value="income"  {{ $transaction->type === 'income' ? 'selected' : '' }}>収入</option>
            <option value="expense" {{ $transaction->type === 'expense' ? 'selected' : '' }}>支出</option>
        </select>
    </div>  

    {{-- 日付 --}}
    <div>
        <label>日付</label>
        <input type="date" name="date" value="{{ $transaction->date->format('Y-m-d') }}">
    </div>

    {{-- カテゴリ --}}
    <div>
        <label>カテゴリ</label>
        <select name="category_id">
            @foreach ($categories as $category)
                <option value="{{ $category->id }}"
                    {{ $transaction->category_id === $category->id ? 'selected' : '' }}>
                    {{ $category->category_name }}
                </option>
            @endforeach
        </select>
    </div>

    {{-- 金額 --}}
    <div>
        <label>金額</label>
        <input type="number" name="amount" value="{{ $transaction->amount }}">
    </div>

    <button type="submit">更新</button>
</form>

        <a href="{{ route('transactions') }}">一覧へ戻る</a>
