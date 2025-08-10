<button
    class="btn btn-dark bg-transparent border-0"
    data-bs-toggle="offcanvas"
    data-bs-target="#offcanvasTop"
    aria-controls="offcanvasTop"
    aria-label="サイドバーの表示"
    style="box-shadow:none;"
>
    <i class="bi bi-list" style="font-size: 1.5rem;"></i>
</button>

<div class="offcanvas offcanvas-start sidebar-narrow" tabindex="-1" id="offcanvasTop" aria-labelledby="offcanvasTopLabel">
    <div class="offcanvas-header">
        <h5 id="offcanvasTopLabel">サイドメニュー</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="閉じるe"></button>
    </div>
    <div class="offcanvas-body">
        <ul class="nav nav-pills flex-column mb-auto">
            <li class="nav-item">
                <a href="{{ route('player-affiliations') }}">{{ __('PlayerAffiliation') }}</a>
            </li>
        </ul>
        <ul class="nav nav-pills flex-column mb-auto">
            <li class="nav-item">
                <a href="{{ route('match-schedules') }}">{{ __('MatchSchedule') }}</a>
            </li>
        </ul>
        <ul class="nav nav-pills flex-column mb-auto">
            <li class="nav-item">
                <a href="{{ route('match-results') }}">{{ __('MatchResult') }}</a>
            </li>
        </ul>
        <ul class="nav nav-pills flex-column mb-auto">
            <li class="nav-item">
                <a href="{{ route('player-ranking') }}">{{ __('PlayerRanking') }}</a>
            </li>
        </ul>
        <ul class="nav nav-pills flex-column mb-auto">
            <li class="nav-item">
                <a href="{{ route('team-ranking') }}">{{ __('TeamRanking') }}</a>
            </li>
        </ul>
        <ul class="nav nav-pills flex-column mb-auto">
            <li class="nav-item">
                <a href="{{ route('team-point-chart') }}">{{ __('TeamPointChart') }}</a>
            </li>
        </ul>
        <ul class="nav nav-pills flex-column mb-auto">
            <li class="nav-item">
                <a href="{{ route('team-stats') }}">{{ __('TeamStats') }}</a>
            </li>
        </ul>
    </div>
</div>
