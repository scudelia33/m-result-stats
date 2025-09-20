<x-main>
    <x-slot:title>
        {{ __('AllPlayerRanking') }}
    </x-slot>

    {{-- 検索条件 --}}
    <x-search-condition>
        {{-- 試合カテゴリー --}}
        <x-match-category-list :match-category-id="$request->match_category_id" :match-categories="$request->matchCategories" />
    </x-search-condition>

    {{-- 検索結果に対する見出し --}}
    <x-search-result-headline
    text-center="{{ __('AllPlayerRanking') }}"
    />

    <x-table class="sortable-table">
        <x-slot:title>
        </x-slot>

        <x-slot:header>
            <th @class([
                'text-center',
                'sortable-header'
            ]) data-sort="player_rank" style="cursor: pointer;">
                {{ __('Ranking') }} <span class="sort-arrow"></span>
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
            @foreach ($request->allPlayerRankings as $allPlayerRankings)
            <tr
                data-player-rank="{{ $allPlayerRankings->player_rank }}"
                data-player-name="{{ $allPlayerRankings->player->player_name }}"
                data-sum-point="{{ $allPlayerRankings->sum_point }}"
                data-top-ratio="{{ $allPlayerRankings->top_ratio }}"
                data-avoid-bottom-ratio="{{ $allPlayerRankings->avoid_bottom_ratio }}"
                data-match-count="{{ $allPlayerRankings->match_count }}"
                data-average-rank="{{ $allPlayerRankings->average_rank }}"
            >
                {{-- 順位 --}}
                <td @class([
                    'text-center',
                ])>{{ $allPlayerRankings->player_rank }}</td>
                {{-- 選手名 --}}
                <td @class([
                    'text-center',
                ])>{{ $allPlayerRankings->player->player_name }}</td>
                {{-- ポイント --}}
                <x-point :point="$allPlayerRankings->sum_point" />
                {{-- トップ率 --}}
                <td @class([
                    'text-end',
                ])>{{ $allPlayerRankings->top_ratio }}</td>
                {{-- ラス回避率 --}}
                <td @class([
                    'text-end',
                ])>{{ $allPlayerRankings->avoid_bottom_ratio }}</td>
                {{-- 試合数 --}}
                <td @class([
                    'text-end',
                ])>{{$allPlayerRankings->match_count}}</td>
                {{-- 平均着順 --}}
                <td @class([
                    'text-end',
                ])>{{ number_format($allPlayerRankings->average_rank, 2) }}</td>
                {{-- 順位詳細 --}}
                <td @class([
                    'text-center',
                ])>{{ $allPlayerRankings->rank_detail }}</td>
            </tr>
            @endforeach
        </x-slot>
    </x-table>
</x-main>
