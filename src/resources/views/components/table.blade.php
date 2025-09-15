<style>
/* テーブルヘッダー固定用CSS */
.sticky-header thead th {
    position: sticky;
    top: 0;
    background-color: #fff;
    z-index: 10;
    /* ダークモードでも見やすいようにボーダーを追加 */
    border-bottom: 1px solid #444;
    color: inherit;
}
@media (prefers-color-scheme: dark) {
  .sticky-header thead th {
    background-color: #222;
    color: #fff;
  }
}
.sticky-header tbody td {
    background: inherit;
    position: relative;
    z-index: 1;
}
.table-scroll {
    max-height: calc(100vh - 200px); /* 200pxは必要に応じて調整 */
    overflow-y: auto;
}

/* カスタムストライプ効果（オプション） */
.table-striped tbody tr:nth-child(even) {
    background-color: rgba(0, 0, 0, 0.05);
}

@media (prefers-color-scheme: dark) {
    .table-striped tbody tr:nth-child(even) {
        background-color: rgba(255, 255, 255, 0.05);
    }
}
</style>
<div class="table-responsive table-scroll">
    <caption>{{ $title }}</caption>
    <table {{ $attributes->merge([
        'class' => 'table table-hover table-striped caption-top sticky-header'
    ]) }}>
        <thead>
            <tr>
                {{ $header }}
            </tr>
        </thead>
        <tbody>
            {{ $body }}
        </tbody>
    </table>
</div>
