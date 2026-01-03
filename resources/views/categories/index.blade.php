<h1>カテゴリ管理</h1>

{{-- エラーメッセージ --}}
@if (session('error'))
    <p style="color:red;">{{ session('error') }}</p>
@endif

<h2>カテゴリ追加</h2>
<form action="{{ route('categories.store') }}" method="post">
    @csrf
    <input type="text" name="name" placeholder="カテゴリ名">
    <button type="submit">追加</button>
</form>

<h2>登録済みカテゴリ</h2>
<ul>
@foreach ($categories as $category)
    <li>
        {{ $category->category_name }}

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
