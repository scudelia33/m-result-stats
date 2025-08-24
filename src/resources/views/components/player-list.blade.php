<div @class([
    'row',
    'mb-3',
])>
    <label for="playerId" @class([
        'col-sm-2',
        'col-form-label',
    ])>{{ __('PlayerName') }}</label>
    <div @class([
        'col-sm-10'
    ])>
        <select @class([
            'form-select'
        ]) name="player_id" id="playerId">
            @if ($isAddEmpty) <option value=""></option> @endif
            @foreach ($players as $player)
                <option value="{{ $player->player_id }}" @selected($playerId == $player->player_id)>
                    {{ $player->player_name }}
                </option>
            @endforeach
        </select>
    </div>
</div>
