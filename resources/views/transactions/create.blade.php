<!doctype html>
<html lang="ja">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>収支登録</title>
  <style>
    body { font-family: system-ui, -apple-system, "Segoe UI", Roboto, "Noto Sans JP", sans-serif; margin: 24px; }
    .container { max-width: 720px; margin: 0 auto; }
    h1 { font-size: 20px; margin-bottom: 16px; }
    .field { margin-bottom: 14px; }
    label { display:block; font-size: 14px; margin-bottom: 6px; }
    input, select { width: 100%; padding: 10px; font-size: 14px; box-sizing: border-box; }
    .row { display: flex; gap: 12px; }
    .row .field { flex: 1; }
    .actions { margin-top: 18px; display: flex; gap: 10px; }
    button, a.btn { padding: 10px 14px; font-size: 14px; cursor: pointer; text-decoration: none; display:inline-block; }
    button { border: 1px solid #333; background: #333; color: #fff; }
    a.btn { border: 1px solid #999; color: #333; }
    .hint { font-size: 12px; color: #666; margin-top: 6px; }
  </style>
</head>
<body>
  <div class="container">
    <h1>収支登録</h1>

    <form action="/transactions" method="post">
       @csrf
<div class="field">
  <label for="type-selector">種別（表示用）</label>
  <select id="type-selector">
    <option value="expense" selected>支出</option>
    <option value="income">収入</option>
  </select>
</div>


      <div class="row">
        <div class="field">
          <label for="date">日付</label>
          <input id="date" type="date" name="date" />
          <div class="hint"></div>
        </div>

        <div class="field">
          <label for="amount">金額</label>
          <input id="amount" type="number" name="amount" min="0" step="1" placeholder="例: 1200" />
        </div>
      </div>

      <div class="field">
        <label for="category_id">カテゴリ</label>
        <select id="category_id" name="category_id">
        <option value="" selected>選択してください</option>
            @foreach($categories as $category)
              <option value="{{ $category->id }}"
                      data-type="{{ $category->type }}">
                {{ $category->category_name }}
              </option>
            @endforeach
        </select>
      </div>

      <div class="actions">
        <button type="submit">登録</button>
        <!-- 一覧に戻る導線（仮） -->
        <a class="btn" href="/transactions">戻る</a>
      </div>
    </form>
  </div>
  <script>
        const typeSelector = document.getElementById('type-selector');
        const categorySelect = document.getElementById('category_id');
        const options = categorySelect.querySelectorAll('option[data-type]');

        function filterCategories(type) {
          options.forEach(option => {
            option.style.display =
              option.dataset.type === type ? '' : 'none';
          });

          // 非表示カテゴリが選択されていたら解除
          const selected = categorySelect.selectedOptions[0];
          if (selected && selected.dataset.type !== type) {
            categorySelect.selectedIndex = 0;
          }
        }

        // 初期表示（支出）
        filterCategories(typeSelector.value);

        // 切り替え
        typeSelector.addEventListener('change', e => {
          filterCategories(e.target.value);
        });
        </script>

</body>
</html>
