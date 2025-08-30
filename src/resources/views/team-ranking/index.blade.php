<x-main>
    @php
        // 予選通過ライン情報が存在する場合
        // - 通過までのポイントを表示
        // - 通過順位の枠線を変更してボーダーラインを表示
        $isExistQualifyingLine = $request->qualifyingLine == null ? false : true;
    @endphp
    <x-slot:title>
        {{ __('TeamRanking') }}
    </x-slot>

    {{-- 検索条件 --}}
    <x-search-condition>
        {{-- シーズン --}}
        <x-season-list :season-id="$request->season_id" :seasons="$request->seasons" />

        {{-- 試合カテゴリー --}}
        <x-match-category-list :match-category-id="$request->match_category_id" :match-categories="$request->matchCategories" />

        {{-- 持ち越しポイントを合算する --}}
        <x-is-combine-carried-over-point :is-combine_carried_over_point="$request->is_combine_carried_over_point" />
    </x-search-condition>

    {{-- 検索結果に対する見出し --}}
    <x-search-result-headline
    text-center="{{ __('TeamRanking') }}"
    :text-end="$request->matchLastDateDisplay"
    />

    <x-table>
        <x-slot:title>
        </x-slot>
        {{-- ヘッダー --}}
        <x-slot:header>
            <th @class([
                'text-center',
                'align-middle',
            ])>{{ __('Ranking') }}</th>
            <th @class([
                'text-center',
                'align-middle',
            ])>{{ __('TeamName') }}</th>
            {{-- ポイントを合算する場合に表示する --}}
            @if ($request->is_combine_carried_over_point)
                <th @class([
                    'text-end',
                    'align-middle',
                ])>{{ __('CarriedOverPoint') }}</th>
                <th @class([
                    'text-end',
                    'align-middle',
                ])>{{ __('PointInCategory') }}</th>
            @endif
            <th @class([
                'text-end',
                'align-middle',
            ])>{{ __('Point') }}</th>
            <th @class([
                'text-end',
                'align-middle',
            ])>{{ __('PointGap') }}</th>
            @if ($isExistQualifyingLine)
                <th @class([
                    'text-end',
                    'align-middle',
                ])>{!! nl2br(e(__('QualifyingDifferencePoint'))) !!}</th>
            @endif
            <th @class([
                'text-end',
                'align-middle',
            ])>{{ __('MatchCount') }}</th>
            <th @class([
                'text-center',
                'align-middle',
            ])>{{ __('RankingBreakdown') }}</th>
        </x-slot>

        {{-- 詳細 --}}
        <x:slot:body>
            @foreach ($request->teamRankings as $teamRanking)
            <tr @class([
                'border-bottom border-light' => $isExistQualifyingLine && $teamRanking->team_rank == $request->qualifyingLine->qualifying_line_team_rank,
            ]) class=" ">
                {{-- 順位 --}}
                <td @class([
                    'text-center',
                ])>{{ $teamRanking->team_rank }}</td>
                {{-- チーム名 --}}
                <x-team-name :team-name="$teamRanking->team->team_name" :team-color="$teamRanking->team->team_color_to_text" />
                {{-- ポイントを合算する場合に表示する --}}
                @if ($request->is_combine_carried_over_point)
                    {{-- 持ち越しポイント --}}
                    <x-point :point="$teamRanking->carried_over_point" />
                    {{-- カテゴリ内ポイント --}}
                    <x-point :point="$teamRanking->point_in_category" />
                @endif
                {{-- ポイント --}}
                <x-point :point="$teamRanking->sum_point" />
                {{-- 差 --}}
                <td @class([
                    'text-end',
                ])>
                    {{-- 1位はハイフンで表示 --}}
                    @if ($loop->first)
                        {{ '-' }}
                    @else
                        {{ bcsub($sum_point_old, $teamRanking->sum_point, 1) }}
                    @endif
                </td>
                @php
                    $sum_point_old = $teamRanking->sum_point;
                @endphp
                {{-- 通過までのポイント --}}
                @if ($isExistQualifyingLine)
                    {{-- 予選通過チーム順位が同じ場合に、予選通過チームポイントを格納する --}}
                    @php
                        if ($teamRanking->team_rank == $request->qualifyingLine->qualifying_line_team_rank) {
                            $qualifyingLineTeamPoint = $teamRanking->sum_point;
                        }
                    @endphp
                    <x-qualifying-difference-point
                    :team-rank="$teamRanking->team_rank"
                    :sum-point="$teamRanking->sum_point"
                    :qualifying-line-team-rank="$request->qualifyingLine->qualifying_line_team_rank"
                    :qualifying-line-team-point="$qualifyingLineTeamPoint ?? null"
                    />
                @endif
                {{-- 試合数 --}}
                <td @class([
                    'text-end',
                ])>{{ $teamRanking->match_count }}</td>
                {{-- 順位詳細 --}}
                <td @class([
                    'text-center',
                ])>{{ $teamRanking->rank_detail }}</td>
            </tr>
            @endforeach
        </x:slot>
    </x-table>
</x-main>
