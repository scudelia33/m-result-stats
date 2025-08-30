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
</style>
<div class="table-responsive table-scroll">
    <caption>{{ $title }}</caption>
    <table @class([
        'table',
        'table-hover',
        'caption-top',
        'sticky-header',
    ])>
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
