<x-main>
    <x-slot:title>
        {{ __('Team') }}
    </x-slot>

    {{-- 検索結果に対する見出し --}}
    <x-search-result-headline
    text-center="{{ __('Team') }}"
    />

    <x-table>
        <x-slot:title>
        </x-slot>

        <x-slot:header>
            <th scope="col">@sortablelink('team_id', 'ID')</th>
            <th scope="col">@sortablelink('team_name', 'チーム名')</th>
            <th scope="col">{{ __('team.team_name_shortened') }}</th>
            <th scope="col">{{ __('team.background_color') }}</th>
            <th scope="col">{{ __('team.graph_color') }}</th>
        </x-slot>

        <x-slot:body>
            @foreach ($teams as $team)
            <tr>
                <th scope="row">{{ $team->team_id }}</th>
                {{-- チーム名 --}}
                <x-team-name :team-name="$team->team_name" :team-color="$team->background_color" />
                {{-- 短縮名（チーム名の右側） --}}
                <td class="text-center">{{ $team->team_name_shortened }}</td>
                <td class="text-center">{{ $team->background_color }}</td>
                <td class="text-center">{{ $team->graph_color }}</td>
            </tr>
            @endforeach
        </x-slot>
    </x-table>
</x-main>
