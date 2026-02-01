<x-main>
    <x-slot:title>
        {{ __('TeamMonthlyPoint') }}
    </x-slot>

    @vite(['resources/js/team-monthly-point-popover.js'])

    {{-- 検索条件 --}}
    <x-search-condition>
        {{-- シーズン --}}
        <x-season-list :season-id="$request->season_id" :seasons="$request->seasons" />

        {{-- 試合カテゴリー --}}
        <x-match-category-list :match-category-id="$request->match_category_id" :match-categories="$request->matchCategories" />
    </x-search-condition>

    {{-- 検索結果に対する見出し --}}
    <x-search-result-headline
        text-center="{{ __('TeamMonthlyPoint') }}"
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
            ])>{{ __('TeamName') }}</th>
            @foreach ($request->months as $month)
                <th @class([
                    'text-end',
                    'align-middle',
                ])>{{ $month }}</th>
            @endforeach
            <th @class([
                'text-end',
                'align-middle',
            ])>{{ __('Total') }}</th>
        </x-slot>

        {{-- 詳細 --}}
        <x:slot:body>
            @foreach ($request->monthlyPointData as $teamData)
            <tr>
                {{-- チーム名 --}}
                <x-team-name :team-name="$teamData['team_name']" :team-color="$teamData['background_color']" />

                {{-- 月毎のポイント --}}
                @foreach ($request->months as $month)
                    @php
                        $monthParts = explode('/', $month);
                        $year = (int)$monthParts[0];
                        $monthNum = (int)$monthParts[1];
                    @endphp
                    <td @class([
                        'text-end',
                        'text-danger' => $teamData['monthly_points'][$month]['point'] < 0,
                        'team-monthly-point-cell'
                    ])
                    style="cursor: pointer;"
                    data-team-id="{{ $teamData['team_id'] }}"
                    data-year="{{ $year }}"
                    data-month="{{ $monthNum }}"
                    data-season-id="{{ $request->season_id }}"
                    data-match-category-id="{{ $request->match_category_id }}"
                    data-bs-toggle="popover"
                    data-bs-trigger="click"
                    data-bs-placement="top"
                    data-bs-html="true"
                    tabindex="0">
                        @if ($teamData['monthly_points'][$month]['point'] == 0)
                            -
                        @else
                            {{ number_format($teamData['monthly_points'][$month]['point'], 1) }}
                        @endif
                    </td>
                @endforeach

                {{-- 合計ポイント --}}
                <td @class([
                    'text-end',
                    'fw-bold',
                    'text-danger' => $teamData['total_point'] < 0,
                ])>
                    {{ number_format($teamData['total_point'], 1) }}
                </td>
            </tr>
            @endforeach
        </x:slot:body>
    </x-table>
</x-main>
