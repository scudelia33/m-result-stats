# Copilot / AI assistant instructions for this repository

このリポジトリは Laravel (PHP 8.2 / Laravel 11) をベースにした Web アプリケーションです。
以下は、AI 補助エージェント（Copilot等）がこのコードベースで効率的に作業するための簡潔で実用的な指示集です。

## 主要な構成
- app/
  - Http/Controllers: ルートハンドラ（コントローラ）
  - Http/Middleware: 既存のミドルウェア（多くはビュー用の前処理を含む）
  - Models: Eloquent モデル
  - Services: (追加済) ミドルウェアの処理を移植したサービスクラス
  - Traits: 共通ユーティリティ（CommonFunctionsTrait, MstatsFunctionsTrait など）
- routes/web.php: HTTP ルート定義
- resources/views: Blade ビュー
- database/: マイグレーション / シーディング / sqlite DB
- tests/: PHPUnit テスト

## 目的/コーディング・パターン
現在の主要目的は「ルート固有の前処理ロジックをミドルウェアからサービスクラスへ移動」することです。移行済みのルート例：
- /player-affiliations
- /match-schedules
- /match-results
- /team-ranking
- /team-stats
- /team-point-chart
- /season-player-ranking
- /all-player-ranking

推奨パターン（採用済）：
- ミドルウェアのロジックは App\Services\<Feature>Service.php に移す。
- Service は public function prepareIndexData(Request $request): Request を提供し、リクエストにデータを merge/put して返す。
- コントローラはサービスをコンストラクタで注入し、index() 内で $request = $this->service->prepareIndexData($request); を呼ぶ。
- ルート定義から該当ミドルウェアを削除し、ミドルウェアファイルは削除（または DEPRECATED スタブに置換）する。
- 既存のビューは現在 `compact('request')` でリクエストを受け取る設計になっているため、簡単な安全策としてリクエストをそのまま渡す。

## 小さな作業チェックリスト（変更手順）
1. 変更対象ミドルウェアを検索：`app/Http/Middleware/*`。
2. ミドルウェアの処理を読み、同等ロジックを `app/Services/<Name>Service.php` の `prepareIndexData(Request $request): Request` に移す。
   - 既に存在する trait やユーティリティを再利用する（例：CommonFunctionsTrait, MstatsFunctionsTrait）。
3. 対応コントローラを更新：コンストラクタインジェクションと index() のサービス呼び出しを追加。
4. routes/web.php からミドルウェア呼び出しを削除（コントローラのルートはそのまま）。
5. ミドルウェアファイルを DEPRECATED スタブに置換 -> 削除。変更前に repo 全体で参照検索（`grep`）して他箇所で使われていないか確認。
6. 変更後、必ず `php -l` で構文チェック。
7. 可能なら既存のユニット/フィーチャーテストを実行して動作を確認（`vendor/bin/phpunit`）。

## ルールと注意点
- PHP 構文エラーを避けるため、ファイル頭に二重の `<?php` タグや末尾の不正文字が入らないよう注意。
- 既存のビューが `compact('request')` を期待しているため、サービスは `$request` に必要なデータを `merge()` または `request->request->add()` する形で返す。
- 大きなクエリや複雑な SQL を扱う場合は、DB 負荷と SQL インジェクションを考慮する（Eloquent の where 句はパラメタライズする）。
- 共通関数は既存の Traits を使う。新しくユーティリティを作る場合は `app/Traits` に追加し再利用可能にする。
- 既に移行済のサービスがある場合、それらを参考にする。命名規則は `<Feature>Service`、メソッドは `prepareIndexData`。

## 便利なコード検索トークン
- `prepareIndexData(`
- `TeamPointChartService` `TeamStatsService` `TeamRankingService`
- `AllPlayerRankingService` `SeasonPlayerRankingService`
- `PlayerAffiliationService` `MatchScheduleService` `MatchResultService`
- `CommonFunctionsTrait` `MstatsFunctionsTrait`
- `compact('request')`

## 開発用コマンド
- 依存インストール: `composer install`
- ローカル DB（sqlite）準備: `php artisan migrate --seed`（初回のみ）
- 静的構文チェック: `php -l <file>` またはプロジェクト全体で `find . -name "*.php" -exec php -l {} ;` を使う
- テスト実行: `vendor/bin/phpunit`（`composer test` スクリプトは未定義）

## テストと品質ゲート
- 重要: 変更後は少なくとも該当機能のユニット/フィーチャーテストを一つ追加しておく（happy path と 1 つの境界ケース）。
- 変更が複数ファイルに跨る場合（サービス + コントローラ + ルート + ミドルウェア削除）、コミットメッセージは明確に：
  `Move <feature> request pre-processing from middleware to service; inject into controller; remove middleware`。

## 典型的なエッジケース
- リクエストのクエリパラメータが null/空のとき（デフォルト付与ロジックを services で正しく行う）。
- 参照されるマスタデータが存在しない（例：シーズンやチームが空）場合の安全な既定値。
- 大量のデータや長時間クエリ（ページングや eager loading を検討）

## 追加の注意
- `TeamPointChartMiddleware.php` に関して：過去に二重 PHP 開始タグ等で構文エラーが発生したことがあります。編集前に必ずファイルの先頭と末尾を確認してください。
- ユーザー（開発者）がファイルの編集を元に戻す場合があるため、自動的にファイルを削除する前に未コミットの変更や PR の意図を確認すること。

---

補足：このファイルは人間の開発者と AI 補助エージェントの橋渡し用の運用指示書です。必要に応じて更新してください（新しいパターンやルールを追加）。
