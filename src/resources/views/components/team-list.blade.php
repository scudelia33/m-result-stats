<div @class([
    'row',
    'mb-3',
])>
    <label for="teamId" @class([
        'col-sm-2',
        'col-form-label'
    ])>{{ __('Team') }}</label>
    <div @class([
        'col-sm-10'
    ])>
        <select @class([
            'form-select'
        ]) name="team_id" id="teamId">
            @if ($isAddEmpty) <option value=""></option> @endif
            @foreach ($teams as $team)
                <option value="{{ $team->team_id }}" @selected($teamId == $team->team_id)>
                    {{ $team->team_name }}
                </option>
            @endforeach
        </select>
    </div>
</div>
