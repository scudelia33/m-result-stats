<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use App\Services\PlayerAffiliationService;

/**
 * 選手所属コントローラ
 */
class PlayerAffiliationController extends Controller
{
    private PlayerAffiliationService $service;

    public function __construct(PlayerAffiliationService $service)
    {
        $this->service = $service;
    }
    /**
     * 選手所属一覧画面を表示します。
     * サービスでデータ準備を行い、ビューに渡します。
     *
     * @param Request $request
     * @return View
     */
    public function index(Request $request): View
    {
        $request = $this->service->prepareIndexData($request);

        return View('player-affiliation.index', compact('request'));
    }
}
