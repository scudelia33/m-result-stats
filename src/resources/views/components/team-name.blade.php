@php
    // スタイルを使用して背景色を設定する
    $background_color = "background-color: #{$teamColor}";
@endphp
<td @class([
    'text-center',
])
@style([
    $background_color,
])>{{ $teamName }}</td>
