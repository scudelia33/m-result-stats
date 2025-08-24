<form action="{{ url()->current() }}" method="get">
    <div @class([
        'card',
        'bg-body-tertiary',
    ])>
        <div @class([
            'card-body',
        ])>
            {{ $slot }}

            <button type="submit" @class([
                'btn',
                'btn-primary',
                'w-100',
            ])>{{ __('Search') }}</button>
        </div>
    </div>
</form>
