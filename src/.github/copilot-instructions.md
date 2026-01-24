# Copilot / AI assistant instructions for this repository

このリポジトリは Laravel (PHP 8.2/8.4 / Laravel 12) をベースにした Web アプリケーションです。
スポーツ大会データ管理システムで、試合結果、チームランキング、選手スタッツなどの表示に特化しています。

## アーキテクチャの全体像

### 3層構造（データ→ビジネスロジック→表示）
1. **Models** (`app/Models/`) - Eloquent モデル（Team, Season, MatchResult など）
   - `HasMany`, `HasOne` リレーションを活用、`SoftDeletes` で論理削除対応
   - `team_id`, `season_id` など、自動採番でない主キーを使用（`protected $primaryKey` で明示）
2. **Services** (`app/Services/`) - ビジネスロジック層（主力）
   - `prepareIndexData(Request $request): Request` メソッドが標準インターフェース
   - Trait（CommonFunctionsTrait, MstatsFunctionsTrait）から共通処理を取得
   - 複雑な集計クエリ（JOIN, グループ化）を集約
3. **Controllers** (`app/Http/Controllers/`) - リクエスト→レスポンス仲介
   - コンストラクタインジェクションでサービスを受け取る
   - `index()` でサービスの `prepareIndexData()` を呼ぶだけ

### 現在の機能一覧
- `/player-affiliations` - 選手所属
- `/match-schedules` - 試合日程
- `/match-results` - 試合結果
- `/team-ranking` - チームランキング
- `/team-stats` - チームスタッツ
- `/team-point-chart` - チームポイント推移グラフ
- `/team-monthly-point` - 月別ポイント
- `/season-player-ranking` - シーズン選手ランキング
- `/all-player-ranking` - 通算選手ランキング

### 主要な構成
- `app/Http/Controllers/` - 12 個のコントローラ（各機能ごと）
- `app/Services/` - 11 個のサービス（ビジネスロジック）
- `app/Models/` - 9 個の Eloquent モデル
- `app/Http/Middleware/` - RequestLogger のみ（ミドルウェア汎用は削除済）
- `app/Traits/` - CommonFunctionsTrait, MstatsFunctionsTrait（共通ユーティリティ）
- `app/Enums/` - BlankInList, CheckBox（定数と UI 値の管理）
- `routes/web.php` - HTTP ルート定義（ミドルウェア登録なし）
- `resources/views/` - Blade ビュー（`compact('request')` でデータ受け取り）
- `database/` - マイグレーション / シーディング / SQLite DB
- `tests/` - PHPUnit テスト

## サービス層の標準パターン

すべてのサービスは以下のパターンを踏襲します：

```php
// app/Services/TeamRankingService.php
class TeamRankingService
{
    use CommonFunctionsTrait;
    use MstatsFunctionsTrait;

    public function prepareIndexData(Request $request): Request
    {
        // 1. クエリパラメータにデフォルト値を付与
        $this->addQueryParameter($request, [
            'season_id' => BlankInList::EXIST->value,
            'match_category_id' => BlankInList::EXIST->value,
        ]);

        // 2. マスタデータを取得（Season, MatchCategory など）
        $seasons = Season::query()->get();

        // 3. ビジネスロジック（集計、ランキング生成など）
        // ここで複雑な JOIN や集計を実行

        // 4. リクエストにデータを merge して返す
        return $request->merge([
            'seasons' => $seasons,
            'ranking' => $ranking,
        ]);
    }
}
```

**重要なポイント：**
- サービスは **リクエストを merge して返す** ことで、ビュー側で `compact('request')` から全データにアクセス可能
- `addQueryParameter()` で null 安全なデフォルト値を処理
- Trait から共通関数を呼び出す（自作ユーティリティは避ける）
- 全ルートがこのパターンで統一されているため、新機能追加時も同じ構造を採用

## 開発ワークフロー

### 新機能追加（推奨フロー）

1. **サービス作成**
   ```bash
   php artisan make:class Services/NewFeatureService
   ```
   - `prepareIndexData(Request $request): Request` を実装
   - `CommonFunctionsTrait`, `MstatsFunctionsTrait` を use
   - ビジネスロジック、DB クエリ、集計をここに集約

2. **コントローラ作成**
   ```bash
   php artisan make:controller NewFeatureController --no-interaction
   ```
   - サービスをコンストラクタインジェクション
   - `index()` でサービスの `prepareIndexData()` を呼ぶだけ

3. **ルート追加** (`routes/web.php`)
   ```php
   Route::get('/new-feature', [NewFeatureController::class, 'index'])
       ->name('new-feature');
   ```

4. **ビュー作成** (`resources/views/new-feature.blade.php`)
   - `$request` から全データにアクセス：`$request->get('seasons')` 等

5. **テスト追加**
   ```bash
   php artisan make:test Feature/NewFeatureTest
   ```

### 既存機能の修正
- ビジネスロジック変更 → サービス内の対応メソッドを修正
- UI変更のみ → ビュー（.blade.php）を修正
- クエリ最適化 → サービス内の Eloquent クエリを改善

## コーディング規約

### PHP ファイル構造
- 必ず `<?php` で開始（二重開始タグ厳禁）
- 末尾に余計な空行や制御文字なし
- 型ヒントとリターン型の明示必須
- PHPDoc で public メソッドを記述

### モデル定義
- 自動採番でない主キーは `protected $primaryKey` で明示（例：`team_id`）
- リレーションは `HasMany`, `HasOne`, `BelongsTo` で定義（戻り型含む）
- 論理削除が必要な場合は `SoftDeletes` trait を使用
- `$fillable` で mass assignment 対象を明示

### ビュー統合
- ビューは `compact('request')` でリクエストを受け取る
- リクエストオブジェクトから `$request->get('key')` でアクセス
- 日付表示は `MstatsFunctionsTrait::getWithWeekName()` を使用

### エラーハンドリング
- Eloquent クエリは eager loading で N+1 問題を回避
- クエリパラメータは `addQueryParameter()` で null 安全に処理
- 存在しないマスタデータは空コレクションなど安全な既定値を返す

## 便利なコード検索トークン
- `prepareIndexData(` - サービスの標準メソッド
- `CommonFunctionsTrait` - 共通処理トレイト
- `MstatsFunctionsTrait` - 統計関連ユーティリティ
- `compact('request')` - ビュー統合パターン
- `addQueryParameter(` - デフォルト値付与
- `BlankInList::EXIST->value` - UI 定数値

## コマンド・デバッグ

**開発環境準備**
```bash
composer install                    # 依存インストール
php artisan migrate --seed          # DB初期化（初回のみ）
```

**構文チェック・テスト**
```bash
php -l app/Services/NewFeatureService.php              # 単一ファイル構文チェック
find app -name "*.php" -exec php -l {} +               # 全 PHP ファイル構文チェック
php artisan test tests/Feature/NewFeatureTest.php      # 特定テスト実行
php artisan test --filter=testMethodName               # テスト名フィルタ
php artisan test                                        # 全テスト実行
vendor/bin/pint --dirty                                # PHP コード整形（変更箇所のみ）
```

**デバッグ・Tinker**
```bash
php artisan tinker                                     # PHP REPL（モデル確認等）
```

## 典型的なトラブルシューティング

| 症状 | 原因 | 対策 |
|------|------|------|
| Parse error: syntax error | ファイル頭の二重 `<?php` など | ファイルの先頭・末尾を確認 |
| Trying to get property of non-object | リクエスト内のキーが存在しない | `addQueryParameter()` でデフォルト値を確保 |
| N+1 query problem | eager loading なし | Eloquent `with()` で関連モデルを先読み |
| View error (undefined $request) | ビューへの受け渡し漏れ | `compact('request')` で全データを返却確認 |
=== foundation rules ===

# Laravel Boost Guidelines

The Laravel Boost guidelines are specifically curated by Laravel maintainers for this application. These guidelines should be followed closely to enhance the user's satisfaction building Laravel applications.

## Foundational Context
This application is a Laravel application and its main Laravel ecosystems package & versions are below. You are an expert with them all. Ensure you abide by these specific packages & versions.

- php - 8.4.12
- laravel/framework (LARAVEL) - v12
- laravel/prompts (PROMPTS) - v0
- laravel/mcp (MCP) - v0
- laravel/pint (PINT) - v1
- laravel/sail (SAIL) - v1
- phpunit/phpunit (PHPUNIT) - v11


## Conventions
- You must follow all existing code conventions used in this application. When creating or editing a file, check sibling files for the correct structure, approach, naming.
- Use descriptive names for variables and methods. For example, `isRegisteredForDiscounts`, not `discount()`.
- Check for existing components to reuse before writing a new one.

## Verification Scripts
- Do not create verification scripts or tinker when tests cover that functionality and prove it works. Unit and feature tests are more important.

## Application Structure & Architecture
- Stick to existing directory structure - don't create new base folders without approval.
- Do not change the application's dependencies without approval.

## Frontend Bundling
- If the user doesn't see a frontend change reflected in the UI, it could mean they need to run `npm run build`, `npm run dev`, or `composer run dev`. Ask them.

## Replies
- Be concise in your explanations - focus on what's important rather than explaining obvious details.

## Documentation Files
- You must only create documentation files if explicitly requested by the user.


=== boost rules ===

## Laravel Boost
- Laravel Boost is an MCP server that comes with powerful tools designed specifically for this application. Use them.

## Artisan
- Use the `list-artisan-commands` tool when you need to call an Artisan command to double check the available parameters.

## URLs
- Whenever you share a project URL with the user you should use the `get-absolute-url` tool to ensure you're using the correct scheme, domain / IP, and port.

## Tinker / Debugging
- You should use the `tinker` tool when you need to execute PHP to debug code or query Eloquent models directly.
- Use the `database-query` tool when you only need to read from the database.

## Reading Browser Logs With the `browser-logs` Tool
- You can read browser logs, errors, and exceptions using the `browser-logs` tool from Boost.
- Only recent browser logs will be useful - ignore old logs.

## Searching Documentation (Critically Important)
- Boost comes with a powerful `search-docs` tool you should use before any other approaches. This tool automatically passes a list of installed packages and their versions to the remote Boost API, so it returns only version-specific documentation specific for the user's circumstance. You should pass an array of packages to filter on if you know you need docs for particular packages.
- The 'search-docs' tool is perfect for all Laravel related packages, including Laravel, Inertia, Livewire, Filament, Tailwind, Pest, Nova, Nightwatch, etc.
- You must use this tool to search for Laravel-ecosystem documentation before falling back to other approaches.
- Search the documentation before making code changes to ensure we are taking the correct approach.
- Use multiple, broad, simple, topic based queries to start. For example: `['rate limiting', 'routing rate limiting', 'routing']`.
- Do not add package names to queries - package information is already shared. For example, use `test resource table`, not `filament 4 test resource table`.

### Available Search Syntax
- You can and should pass multiple queries at once. The most relevant results will be returned first.

1. Simple Word Searches with auto-stemming - query=authentication - finds 'authenticate' and 'auth'
2. Multiple Words (AND Logic) - query=rate limit - finds knowledge containing both "rate" AND "limit"
3. Quoted Phrases (Exact Position) - query="infinite scroll" - Words must be adjacent and in that order
4. Mixed Queries - query=middleware "rate limit" - "middleware" AND exact phrase "rate limit"
5. Multiple Queries - queries=["authentication", "middleware"] - ANY of these terms


=== php rules ===

## PHP

- Always use curly braces for control structures, even if it has one line.

### Constructors
- Use PHP 8 constructor property promotion in `__construct()`.
    - <code-snippet>public function __construct(public GitHub $github) { }</code-snippet>
- Do not allow empty `__construct()` methods with zero parameters.

### Type Declarations
- Always use explicit return type declarations for methods and functions.
- Use appropriate PHP type hints for method parameters.

<code-snippet name="Explicit Return Types and Method Params" lang="php">
protected function isAccessible(User $user, ?string $path = null): bool
{
    ...
}
</code-snippet>

## Comments
- Prefer PHPDoc blocks over comments. Never use comments within the code itself unless there is something _very_ complex going on.

## PHPDoc Blocks
- Add useful array shape type definitions for arrays when appropriate.

## Enums
- Typically, keys in an Enum should be TitleCase. For example: `FavoritePerson`, `BestLake`, `Monthly`.


=== laravel/core rules ===

## Do Things the Laravel Way

- Use `php artisan make:` commands to create new files (i.e. migrations, controllers, models, etc.). You can list available Artisan commands using the `list-artisan-commands` tool.
- If you're creating a generic PHP class, use `artisan make:class`.
- Pass `--no-interaction` to all Artisan commands to ensure they work without user input. You should also pass the correct `--options` to ensure correct behavior.

### Database
- Always use proper Eloquent relationship methods with return type hints. Prefer relationship methods over raw queries or manual joins.
- Use Eloquent models and relationships before suggesting raw database queries
- Avoid `DB::`; prefer `Model::query()`. Generate code that leverages Laravel's ORM capabilities rather than bypassing them.
- Generate code that prevents N+1 query problems by using eager loading.
- Use Laravel's query builder for very complex database operations.

### Model Creation
- When creating new models, create useful factories and seeders for them too. Ask the user if they need any other things, using `list-artisan-commands` to check the available options to `php artisan make:model`.

### APIs & Eloquent Resources
- For APIs, default to using Eloquent API Resources and API versioning unless existing API routes do not, then you should follow existing application convention.

### Controllers & Validation
- Always create Form Request classes for validation rather than inline validation in controllers. Include both validation rules and custom error messages.
- Check sibling Form Requests to see if the application uses array or string based validation rules.

### Queues
- Use queued jobs for time-consuming operations with the `ShouldQueue` interface.

### Authentication & Authorization
- Use Laravel's built-in authentication and authorization features (gates, policies, Sanctum, etc.).

### URL Generation
- When generating links to other pages, prefer named routes and the `route()` function.

### Configuration
- Use environment variables only in configuration files - never use the `env()` function directly outside of config files. Always use `config('app.name')`, not `env('APP_NAME')`.

### Testing
- When creating models for tests, use the factories for the models. Check if the factory has custom states that can be used before manually setting up the model.
- Faker: Use methods such as `$this->faker->word()` or `fake()->randomDigit()`. Follow existing conventions whether to use `$this->faker` or `fake()`.
- When creating tests, make use of `php artisan make:test [options] <name>` to create a feature test, and pass `--unit` to create a unit test. Most tests should be feature tests.

### Vite Error
- If you receive an "Illuminate\Foundation\ViteException: Unable to locate file in Vite manifest" error, you can run `npm run build` or ask the user to run `npm run dev` or `composer run dev`.


=== laravel/v12 rules ===

## Laravel 12

- Use the `search-docs` tool to get version specific documentation.
- Since Laravel 11, Laravel has a new streamlined file structure which this project uses.

### Laravel 12 Structure
- No middleware files in `app/Http/Middleware/`.
- `bootstrap/app.php` is the file to register middleware, exceptions, and routing files.
- `bootstrap/providers.php` contains application specific service providers.
- **No app\Console\Kernel.php** - use `bootstrap/app.php` or `routes/console.php` for console configuration.
- **Commands auto-register** - files in `app/Console/Commands/` are automatically available and do not require manual registration.

### Database
- When modifying a column, the migration must include all of the attributes that were previously defined on the column. Otherwise, they will be dropped and lost.
- Laravel 11 allows limiting eagerly loaded records natively, without external packages: `$query->latest()->limit(10);`.

### Models
- Casts can and likely should be set in a `casts()` method on a model rather than the `$casts` property. Follow existing conventions from other models.


=== pint/core rules ===

## Laravel Pint Code Formatter

- You must run `vendor/bin/pint --dirty` before finalizing changes to ensure your code matches the project's expected style.
- Do not run `vendor/bin/pint --test`, simply run `vendor/bin/pint` to fix any formatting issues.


=== phpunit/core rules ===

## PHPUnit Core

- This application uses PHPUnit for testing. All tests must be written as PHPUnit classes. Use `php artisan make:test --phpunit <name>` to create a new test.
- If you see a test using "Pest", convert it to PHPUnit.
- Every time a test has been updated, run that singular test.
- When the tests relating to your feature are passing, ask the user if they would like to also run the entire test suite to make sure everything is still passing.
- Tests should test all of the happy paths, failure paths, and weird paths.
- You must not remove any tests or test files from the tests directory without approval. These are not temporary or helper files, these are core to the application.

### Running Tests
- Run the minimal number of tests, using an appropriate filter, before finalizing.
- To run all tests: `php artisan test`.
- To run all tests in a file: `php artisan test tests/Feature/ExampleTest.php`.
- To filter on a particular test name: `php artisan test --filter=testName` (recommended after making a change to a related file).
</laravel-boost-guidelines>
