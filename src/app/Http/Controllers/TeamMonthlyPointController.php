<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use App\Services\TeamMonthlyPointService;

/**
 * チーム月毎ポイントコントローラ
 */
class TeamMonthlyPointController extends Controller
{
    private TeamMonthlyPointService $service;

    public function __construct(TeamMonthlyPointService $service)
    {
        $this->service = $service;
    }

    /**
     * チーム月毎ポイント一覧画面を表示します。
     * サービスでデータ準備を行い、ビューに渡します。
     *
     * @param Request $request
     * @return View
     */
    public function index(Request $request): View
    {
        $request = $this->service->prepareIndexData($request);

        return View('team-monthly-point.index', compact('request'));
    }
}
