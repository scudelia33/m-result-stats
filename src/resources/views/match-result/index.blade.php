<x-main>
    <x-slot:title>
        {{ __('MatchResult') }}
    </x-slot>

    {{-- 検索条件 --}}
    <x-search-condition>
        {{-- シーズン --}}
        <x-season-list :season-id="$request->season_id" :seasons="$request->seasons" />

        {{-- 試合カテゴリ --}}
        <x-match_category-list :match_category-id="$request->match_category_id" :matchCategories="$request->matchCategories" :is-add-empty=true />

        {{-- チーム --}}
        <x-team-list :team-id="$request->team_id" :teams="$request->teams" :is-add-empty=true />

        {{-- 選手 --}}
        <x-player-list :player-id="$request->player_id" :players="$request->players" :is-add-empty=true />
    </x-search-condition>

    {{-- 検索結果に対する見出し --}}
    <x-search-result-headline
    text-center="{{ __('MatchResult') }}"
    />

    <x-table>
        <x-slot:title>
        </x-slot>

        <x-slot:header>
            <th @class([
                'text-center',
            ])>{{ __('Season') }}</th>
            <th @class([
                'text-center',
            ])>{{ __('MatchCategory') }}</th>
            <th @class([
                'text-center',
            ])>{{ __('MatchDate') }}</th>
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
            ])>{{ __('Penalty') }}</th>
        </x-slot>

        <x-slot:body>
            @foreach ($request->matchResults as $matchResult)
            <tr>
                <td @class([
                    'text-center',
                ])>{{ $matchResult->matchInformation->matchSchedule->season->season_name }}</td>
                <td @class([
                    'text-center',
                ])>{{ $matchResult->matchInformation->matchSchedule->matchCategory->match_category_name }}</td>
                <td @class([
                    'text-center',
                ])>{{ $matchResult->matchInformation->match_date_order_display }}</td>
                <td @class([
                    'text-center',
                ])>{{ $matchResult->rank }}</td>
                <td @class([
                    'text-center',
                ])>{{ $matchResult->playerAffiliation->player->player_name}}</td>
                @php
                    $background_color = "background-color: #{$matchResult->playerAffiliation->team->team_color_to_text}";
                @endphp
                <td @class([
                    'text-center',
                ])
                @style([
                    $background_color,
                ])>{{ $matchResult->playerAffiliation->team->team_name }}</td>
                {{-- ポイント --}}
                <x-point :point="$matchResult->point" />
                {{-- ペナルティ --}}
                <td @class([
                    'text-end',
                    'text-danger-emphasis',
                ])>{{ $matchResult->penalty ?? null }}</td>
            </tr>
            @endforeach
        </x-slot>
    </x-table>
</x-main>
