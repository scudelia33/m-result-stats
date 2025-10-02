<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use App\Services\MatchResultService;

/**
 * 試合成績コントローラ
 */
class MatchResultController extends Controller
{
    private MatchResultService $service;

    public function __construct(MatchResultService $service)
    {
        $this->service = $service;
    }
    /**
     * 試合成績一覧画面を表示します。
     * サービスでデータ準備を行い、ビューに渡します。
     *
     * @param Request $request
     * @return View
     */
    public function index(Request $request): View
    {
        $request = $this->service->prepareIndexData($request);

        return View('match-result.index', compact('request'));
    }
}
