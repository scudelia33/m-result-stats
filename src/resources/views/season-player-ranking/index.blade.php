<x-main>
    <x-slot:title>
        {{ __('SeasonPlayerRanking') }}
    </x-slot>

    {{-- 検索条件 --}}
    <x-search-condition>
        {{-- シーズン --}}
        <x-season-list :season-id="$request->season_id" :seasons="$request->seasons" />

        {{-- 試合カテゴリー --}}
        <x-match-category-list :match-category-id="$request->match_category_id" :match-categories="$request->matchCategories" />
    </x-search-condition>

    {{-- 検索結果に対する見出し --}}
    <x-search-result-headline
    text-center="{{ __('SeasonPlayerRanking') }}"
    :text-end="$request->matchLastDateDisplay"
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
                'text-center',
                'sortable-header'
            ]) data-sort="team_name" style="cursor: pointer;">
                {{ __('TeamName') }} <span class="sort-arrow"></span>
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
                'text-center',
            ])>{{ __('RankingBreakdown') }}</th>
        </x-slot>

        <x-slot:body>
            @foreach ($request->seasonPlayerRankings as $seasonPlayerRankings)
            <tr
                data-player-rank="{{ $seasonPlayerRankings->player_rank }}"
                data-player-name="{{ $seasonPlayerRankings->player->player_name }}"
                data-team-name="{{ $seasonPlayerRankings->playerAffiliation->team->team_name }}"
                data-sum-point="{{ $seasonPlayerRankings->sum_point }}"
                data-top-ratio="{{ $seasonPlayerRankings->top_ratio }}"
                data-avoid-bottom-ratio="{{ $seasonPlayerRankings->avoid_bottom_ratio }}"
                data-match-count="{{ $seasonPlayerRankings->match_count }}"
            >
                {{-- 順位 --}}
                <td @class([
                    'text-center',
                ])>{{ $seasonPlayerRankings->player_rank }}</td>
                {{-- 選手名 --}}
                <td @class([
                    'text-center',
                ])>{{ $seasonPlayerRankings->player->player_name }}</td>
                {{-- チーム名 --}}
                <x-team-name :team-name="$seasonPlayerRankings->playerAffiliation->team->team_name" :team-color="$seasonPlayerRankings->playerAffiliation->team->team_color_to_text" />
                {{-- ポイント --}}
                <x-point :point="$seasonPlayerRankings->sum_point" />
                {{-- トップ率 --}}
                <td @class([
                    'text-end',
                ])>{{ $seasonPlayerRankings->top_ratio }}</td>
                {{-- ラス回避率 --}}
                <td @class([
                    'text-end',
                ])>{{ $seasonPlayerRankings->avoid_bottom_ratio }}</td>
                {{-- 試合数 --}}
                <td @class([
                    'text-end',
                ])>{{$seasonPlayerRankings->match_count}}</td>
                {{-- 順位詳細 --}}
                <td @class([
                    'text-center',
                ])>{{ $seasonPlayerRankings->rank_detail }}</td>
            </tr>
            @endforeach
        </x-slot>
    </x-table>
</x-main>
