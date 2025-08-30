<div @class([
    'row',
    'mb-3',
])>
    <label for="" @class([
        'col-sm-2',
        'col-form-label'
    ])></label>
    <div @class([
        'col-sm-10'
    ])>
        <div @class([
            'form-check',
            'form-switch'
        ])>
            {{-- HTMLの仕様上チェックが外れているとサーバにデータが送信されないため、hiddenを利用してOFFの場合でも明示的に値を送信する --}}
            <input
                type="hidden"
                name="is_combine_carried_over_point"
                value="0"
            >
            <input
                type="checkbox"
                class="form-check-input"
                name="is_combine_carried_over_point"
                id="isCombineCarriedOverPoint"
                role="switch"
                value="1"
                @checked($isCombineCarriedOverPoint)
            >
            <label
                for="isCombineCarriedOverPoint"
                class="form-check-label"
                data-bs-toggle="tooltip"
                data-bs-placement="bottom"
                title="{{ __('IsCombineCarriedOverPointDescription') }}"
            >{{ __('IsCombineCarriedOverPoint') }}</label>
        </div>
    </div>
</div>
