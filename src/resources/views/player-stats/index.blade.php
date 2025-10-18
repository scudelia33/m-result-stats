<x-main>
    <x-slot:title>
        {{ __('PlayerStats') }}
    </x-slot>

    {{-- 検索条件 --}}
    <x-search-condition>
        {{-- 試合カテゴリー --}}
        <x-match-category-list :match-category-id="$request->match_category_id" :match-categories="$request->matchCategories" />

        {{-- 選手名 --}}
        <x-player-list :player-id="$request->player_id == \App\Enums\BlankInList::EXIST->value ? null : $request->player_id" :players="$request->players" :is-add-empty="true" />
    </x-search-condition>

    {{-- 検索結果に対する見出し --}}
    <x-search-result-headline
    text-center="{{ __('PlayerStats') }}"
    />

    {{-- 選手成績表示 --}}
    @if ($request->playerStats->count() > 0)
        <x-table class="sortable-table">
            <x-slot:title>
            </x-slot>

            <x-slot:header>
                <th @class([
                    'text-center',
                    'sortable-header'
                ]) data-sort="season_name" style="cursor: pointer;">
                    {{ __('Season') }} <span class="sort-arrow"></span>
                </th>
                <th @class([
                    'text-center',
                    'sortable-header'
                ]) data-sort="player_name" style="cursor: pointer;">
                    {{ __('PlayerName') }} <span class="sort-arrow"></span>
                </th>
                <th @class([
                    'text-end',
                    'sortable-header'
                ]) data-sort="sum_point" style="cursor: pointer;">
                    {{ __('Point') }} <span class="sort-arrow"></span>
                </th>
                <th @class([
                    'text-end',
                    'sortable-header'
                ]) data-sort="top_ratio" style="cursor: pointer;">
                    {{ __('TopRatio') }} <span class="sort-arrow"></span>
                </th>
                <th @class([
                    'text-end',
                    'sortable-header'
                ]) data-sort="avoid_bottom_ratio" style="cursor: pointer;">
                    {{ __('AvoidBottomRatio') }} <span class="sort-arrow"></span>
                </th>
                <th @class([
                    'text-end',
                    'sortable-header'
                ]) data-sort="match_count" style="cursor: pointer;">
                    {{ __('MatchCount') }} <span class="sort-arrow"></span>
                </th>
                <th @class([
                    'text-end',
                    'sortable-header'
                ]) data-sort="average_rank" style="cursor: pointer;">
                    {{ __('AverageRank') }} <span class="sort-arrow"></span>
                </th>
                <th @class([
                    'text-center',
                ])>{{ __('RankingBreakdown') }}</th>
            </x-slot>

            <x-slot:body>
                @foreach ($request->playerStats as $playerStat)
                    @php
                        $season = $request->seasons->firstWhere('season_id', $playerStat->season_id);
                        $seasonName = $season ? $season->season_name : "シーズン {$playerStat->season_id}";
                    @endphp
                    <tr
                        data-season-name="{{ $seasonName }}"
                        data-player-name="{{ $playerStat->player->player_name }}"
                        data-sum-point="{{ $playerStat->sum_point }}"
                        data-top-ratio="{{ $playerStat->top_ratio }}"
                        data-avoid-bottom-ratio="{{ $playerStat->avoid_bottom_ratio }}"
                        data-match-count="{{ $playerStat->match_count }}"
                        data-average-rank="{{ $playerStat->average_rank }}"
                    >
                        {{-- シーズン名 --}}
                        <td @class([
                            'text-center',
                        ])>{{ $seasonName }}</td>
                        {{-- 選手名 --}}
                        <td @class([
                            'text-center',
                        ])>{{ $playerStat->player->player_name }}</td>
                        {{-- ポイント --}}
                        <x-point :point="$playerStat->sum_point" />
                        {{-- トップ率 --}}
                        <td @class([
                            'text-end',
                        ])>{{ $playerStat->top_ratio }}</td>
                        {{-- ラス回避率 --}}
                        <td @class([
                            'text-end',
                        ])>{{ $playerStat->avoid_bottom_ratio }}</td>
                        {{-- 試合数 --}}
                        <td @class([
                            'text-end',
                        ])>{{ $playerStat->match_count }}</td>
                        {{-- 平均着順 --}}
                        <td @class([
                            'text-end',
                        ])>{{ number_format($playerStat->average_rank, 2) }}</td>
                        {{-- 順位詳細 --}}
                        <td @class([
                            'text-center',
                        ])>{{ $playerStat->rank_detail }}</td>
                    </tr>
                @endforeach
            </x-slot>
        </x-table>
    @else
        <div class="alert alert-info">
            {{ __('NoData') }}
        </div>
    @endif
</x-main>
