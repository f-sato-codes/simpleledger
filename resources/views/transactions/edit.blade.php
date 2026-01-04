<h1>収支編集</h1>

<form action="{{ route('transactions.update', $transaction) }}" method="post">
    @csrf
    @method('PUT')

    {{-- 種別（表示用：保存しない） --}}
    <div>
        <label>種別</label>
        <select id="type-selector">
            <option value="income"
                {{ $transaction->category->type === 'income' ? 'selected' : '' }}>
                収入
            </option>
            <option value="expense"
                {{ $transaction->category->type === 'expense' ? 'selected' : '' }}>
                支出
            </option>
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
        <select name="category_id" id="category_id">
            @foreach ($categories as $category)
                <option value="{{ $category->id }}"
                    data-type="{{ $category->type }}"
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

<script>
const typeSelector = document.getElementById('type-selector');
const categorySelect = document.getElementById('category_id');
const options = categorySelect.querySelectorAll('option[data-type]');

function filterCategories(type) {
    options.forEach(option => {
        option.style.display =
            option.dataset.type === type ? '' : 'none';
    });

    const selected = categorySelect.selectedOptions[0];
    if (selected && selected.dataset.type !== type) {
        categorySelect.selectedIndex = -1;
    }
}

// 初期表示（編集中のカテゴリに合わせる）
filterCategories(typeSelector.value);

// 切り替え
typeSelector.addEventListener('change', e => {
    filterCategories(e.target.value);
});
</script>
