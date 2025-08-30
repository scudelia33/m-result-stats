<x-main>
    <x-slot:title>
        {{ __('PlayerRanking') }}
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
    text-center="{{ __('PlayerRanking') }}"
    :text-end="$request->matchLastDateDisplay"
    />

    <x-table>
        <x-slot:title>
        </x-slot>

        <x-slot:header>
            <th @class([
                'text-center',
            ])>{{ __('Ranking') }}</th>
            <th @class([
                'text-center',
            ])>{{ __('PlayerName') }}</th>
            <th @class([
                'text-center',
            ])>{{ __('TeamName') }}</th>
            <th @class([
                'text-end',
            ])>{{ __('Point') }}</th>
            <th @class([
                'text-end',
            ])>{{ __('TopRatio') }}</th>
            <th @class([
                'text-end',
            ])>{{ __('AvoidBottomRatio') }}</th>
            <th @class([
                'text-end',
            ])>{{ __('MatchCount') }}</th>
            <th @class([
                'text-center',
            ])>{{ __('RankingBreakdown') }}</th>
        </x-slot>

        <x-slot:body>
            @foreach ($request->teamRankings as $teamRanking)
            <tr>
                {{-- 順位 --}}
                <td @class([
                    'text-center',
                ])>{{ $teamRanking->player_rank }}</td>
                {{-- 選手名 --}}
                <td @class([
                    'text-center',
                ])>{{ $teamRanking->player->player_name }}</td>
                {{-- チーム名 --}}
                <x-team-name :team-name="$teamRanking->playerAffiliation->team->team_name" :team-color="$teamRanking->playerAffiliation->team->team_color_to_text" />
                {{-- ポイント --}}
                <x-point :point="$teamRanking->sum_point" />
                {{-- トップ率 --}}
                <td @class([
                    'text-end',
                ])>{{ $teamRanking->top_ratio }}</td>
                {{-- ラス回避率 --}}
                <td @class([
                    'text-end',
                ])>{{ $teamRanking->avoid_bottom_ratio }}</td>
                {{-- 試合数 --}}
                <td @class([
                    'text-end',
                ])>{{$teamRanking->match_count}}</td>
                {{-- 順位詳細 --}}
                <td @class([
                    'text-center',
                ])>{{ $teamRanking->rank_detail }}</td>
            </tr>
            @endforeach
        </x-slot>
    </x-table>
</x-main>
