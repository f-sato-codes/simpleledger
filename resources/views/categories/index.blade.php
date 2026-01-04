<h1>カテゴリ管理</h1>

{{-- エラーメッセージ --}}
@if ($errors->any())
    <ul style="color:red;">
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
@endif

@if (session('error'))
    <p style="color:red;">{{ session('error') }}</p>
@endif

<h2>カテゴリ追加</h2>
<form action="{{ route('categories.store') }}" method="post">
    @csrf

    <input
        type="text"
        name="category_name"
        placeholder="カテゴリ名"
        required
    >

    <select name="type" required>
        <option value="">収支区分を選択</option>
        <option value="expense">支出</option>
        <option value="income">収入</option>
    </select>

    <button type="submit">追加</button>
</form>

<h2>登録済みカテゴリ</h2>
<ul>
@foreach ($categories as $category)
    <li>
        {{ $category->category_name }}
        （{{ $category->type === 'income' ? '収入' : '支出' }}）

        @if ($category->transactions_count === 0)
            <form action="{{ route('categories.destroy', $category) }}"
                  method="post"
                  style="display:inline">
                @csrf
                @method('delete')
                <button>削除</button>
            </form>
        @else
            <span style="color:gray;">（使用中）</span>
        @endif
    </li>
@endforeach
</ul>
