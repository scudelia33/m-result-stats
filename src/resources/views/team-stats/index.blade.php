<x-main data-page="teamStatsChart">
    @php
        // 予選通過ライン情報が存在する場合
        // - 通過までのポイントを表示
        // - 通過順位の枠線を変更してボーダーラインを表示
        $isExistQualifyingLine = $request->qualifyingLine == null ? false : true;
    @endphp
    <x-slot:title>
        {{ __('TeamStats') }}
    </x-slot>

    {{-- 検索条件 --}}
    <x-search-condition>
        {{-- シーズン --}}
        <x-season-list :season-id="$request->season_id" :seasons="$request->seasons" />

        {{-- 試合カテゴリー --}}
        <x-match-category-list :match-category-id="$request->match_category_id" :match-categories="$request->matchCategories" />

        {{-- チーム --}}
        <x-team-list :team-id="$request->team_id" :teams="$request->teams" />
    </x-search-condition>

    {{-- グラフ --}}
    <canvas id="teamStatsChart"></canvas>

    <button id="resetZoomButton" @class([
        'btn',
        'btn-info',
    ])>{{ __('ResetZoom') }}</button>

    <script type="module">
        const labels = @json($request->matchDates);
        const data = {
            labels: labels,
            datasets: [
                {
                    label: '{{ __('Point') }}',
                    data: @json($request->sumPoints),
                    borderColor: '',
                    backgroundColor: '',
                    yAxisID: 'y',
                    pointRadius: 3,
                },
                {
                    label: '{{ __('Ranking') }}',
                    data: @json($request->ranks),
                    borderColor: '',
                    backgroundColor: '',
                    yAxisID: 'y1',
                    pointRadius: 3,
                    borderDash: [3, 5],
                }
            ],
        };
        // グラフの初期化に必要な値のセット
        // 初期化はDOMContentLoadedイベントにて実施している
        idForGraph = 'teamStatsChart';
        startDateForGraph = '{{ $request->startDateForGraph }}';
        dataForGraph = data;
        titleTextForGraph = '{{ $request->teamName }}';
        teamCountForGraph = {{ $request->teamCount }};
    </script>
</x-main>
