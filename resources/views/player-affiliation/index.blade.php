<!doctype html>
<html>

<head>
    <meta charset='utf-8' />
    <title>選手所属一覧</title>

    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>

<body>
    <form action="{{ route('player-affiliations') }}" method="get">
        {{-- チーム --}}
        <div class="mb-3">
            <label for="team_id" class="form-label">チーム</label>
            <select name="team_id" class="form-select" id="team_id">
                <option value=""></option>
                @foreach ($request->teams as $team)
                    <option value="{{ $team->team_id }}" @selected($request->team_id == $team->team_id)>
                        {{ $team->team_name }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- シーズン --}}
        <div class="mb-3">
            <label for="season_id" class="form-label">シーズン</label>{{ old('season_id') }}
            <select name="season_id" class="form-select" id="season_id">
                <option value=""></option>
                @foreach ($request->seasons as $season)
                    <option value="{{ $season->season_id }}" @selected($request->season_id == $season->season_id)>
                        {{ $season->season_name }}
                    </option>
                @endforeach
            </select>
        </div>

        <button type="submit" @class([
            'btn',
            'btn-primary',
        ])>検索</button>
    </form>
    <table class="table table-striped table-hover caption-top">
        <caption>選手所属一覧</caption>
        <thead>
            <tr>
                <th>ID</th>
                <th scope="col">選手名</th>
                <th scope="col">所属シーズン</th>
                <th scope="col">チーム名</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($request->player_affiliations as $player_affiliation)
            <tr>
                <th scope="row">{{ $player_affiliation->player_affiliation_id }}</th>
                <td>{{ $player_affiliation->player->player_name }}</td>
                <td>{{ $player_affiliation->season->season_name }}</td>
                <td>{{ $player_affiliation->team->team_name }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
