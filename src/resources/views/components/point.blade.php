<td @class([
    'text-end',
    'text-danger-emphasis' => $point < 0, // マイナスポイントの場合は赤字にする
])>{{ number_format($point, 1) }}</td>
