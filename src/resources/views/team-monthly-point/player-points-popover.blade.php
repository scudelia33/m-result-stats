@if (isset($players) && count($players) > 0)
    <div class="player-points-list" style="max-height: 300px; overflow-y: auto;">
        <table class="table table-sm table-hover mb-0">
            <thead>
                <tr>
                    <th>選手</th>
                    <th class="text-end">ポイント</th>
                    <th class="text-center">順位内訳</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($players as $player)
                    <tr>
                        <td>{{ $player['player_name'] }}</td>
                        <td @class([
                            'text-end',
                            'fw-bold',
                            'text-danger' => $player['net_point'] < 0,
                        ])>
                            {{ number_format($player['net_point'], 1) }}
                        </td>
                        <td class="text-center">{{ $player['rank_detail'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@else
    <div class="text-center text-muted">データがありません</div>
@endif
