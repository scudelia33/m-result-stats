<div @class([
    'row',
    'mb-3',
])>
    <label for="seasonId" @class([
        'col-sm-2',
        'col-form-label'
    ])>{{ __('Season') }}</label>
    <div @class([
        'col-sm-10'
    ])>
        <select @class([
            'form-select'
        ]) name="season_id" id="seasonId">
            @if ($isAddEmpty) <option value=""></option> @endif
            @foreach ($seasons as $season)
                <option value="{{ $season->season_id }}" @selected($seasonId == $season->season_id)>
                    {{ $season->season_name }}
                </option>
            @endforeach
        </select>
    </div>
</div>
