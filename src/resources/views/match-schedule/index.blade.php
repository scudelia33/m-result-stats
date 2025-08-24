<x-main>
    <x-slot:title>
        {{ __('MatchSchedule') }}
    </x-slot>

    {{-- 検索条件 --}}
    <x-search-condition>
        {{-- シーズン --}}
        <x-season-list :season-id="$request->season_id" :seasons="$request->seasons" />

        {{-- 試合区分 --}}
        <x-match-category-list :match-category-id="$request->match_category_id" :match-categories="$request->matchCategories" />
    </x-search-condition>

    {{-- 検索結果に対する見出し --}}
    <x-search-result-headline
    text-center="{{ __('MatchSchedule') }}"
    />

    <x-table>
        <x-slot:title>
        </x-slot>

        <x-slot:header>
            <th scope="col">{{ __('MatchDate') }}</th>
            <th>{{ __('Season') }}</th>
            <th>{{ __('MatchCategory') }}</th>
        </x-slot>

        <x-slot:body>
            @foreach ($request->matchSchedules as $matchSchedule)
            <tr>
                <th scope="row">{{ $matchSchedule->match_date_display }}</th>
                <td>{{ $matchSchedule->season->season_name }}</td>
                <td>{{ $matchSchedule->matchCategory->match_category_name }}</td>
            </tr>
            @endforeach
        </x-slot>
    </x-table>
</x-main>
