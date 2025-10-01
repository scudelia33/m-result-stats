<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use App\Services\MatchScheduleService;

/**
 * 試合日程コントローラ
 */
class MatchScheduleController extends Controller
{
    private MatchScheduleService $service;

    public function __construct(MatchScheduleService $service)
    {
        $this->service = $service;
    }
    /**
     * 試合日程一覧画面を表示します。
     * サービスでデータ準備を行い、ビューに渡します。
     *
     * @param Request $request
     * @return View
     */
    public function index(Request $request): View
    {
        $request = $this->service->prepareIndexData($request);

        return View('match-schedule.index', compact('request'));
    }
}
