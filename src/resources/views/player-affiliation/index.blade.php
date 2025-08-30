<x-main>
    <x-slot:title>
        {{ __('PlayerAffiliation') }}
    </x-slot>

    {{-- 検索条件 --}}
    <x-search-condition>
        {{-- シーズン --}}
        <x-season-list :season-id="$request->season_id" :seasons="$request->seasons" />
        {{-- チーム --}}
        <x-team-list :team-id="$request->team_id" :teams="$request->teams" :is-add-empty=true />
    </x-search-condition>

    {{-- 検索結果に対する見出し --}}
    <x-search-result-headline
    text-center="{{ __('PlayerAffiliation') }}"
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
            ])>{{ __('TeamName') }}</th>
            <th @class([
                'text-center',
            ])>{{ __('PlayerName') }}</th>
        </x-slot>

        <x-slot:body>
            @foreach ($request->playerAffiliations as $playerAffiliation)
            <tr>
                {{-- シーズン --}}
                <td @class([
                    'text-center',
                ])>{{ $playerAffiliation->season->season_name }}</td>
                {{-- チーム名 --}}
                <x-team-name :team-name="$playerAffiliation->team->team_name" :team-color="$playerAffiliation->team->team_color_to_text" />
                {{-- 選手名 --}}
                <td @class([
                    'text-center',
                ])>{{ $playerAffiliation->player->player_name }}</td>
            </tr>
            @endforeach
        </x-slot>
    </x-table>
</x-main>
