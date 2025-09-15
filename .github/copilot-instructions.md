# Copilot Instructions for m-stats

## プロジェクト概要
- Mリーグ公式成績を個人用に集計・表示するWebアプリ。
- PHP (Laravel) + MySQL + Nginx + Docker構成。
- `src/`がアプリ本体、`docker/`配下に各種サービスの設定。

## 主要ディレクトリ・ファイル
- `src/app/Models/` ... Eloquentモデル
- `src/app/Http/Controllers/` ... コントローラ
- `src/resources/views/` ... Bladeテンプレート
- `src/routes/web.php` ... Webルート定義
- `src/database/migrations/` ... DBマイグレーション
- `docker/` ... 各種サービスのDocker設定
- `README.md` ... セットアップ・運用手順

## 開発・運用ワークフロー
- **初回セットアップ**: `README.md`の手順に従い、Dockerコンテナ起動・依存インストール・マイグレーション実行。
- **ビルド/起動**: `docker compose up -d` で全サービス起動。
- **依存追加**: `docker compose exec mstats-app composer require <pkg>` または `npm install <pkg>`。
- **DBマイグレーション**: `docker compose exec mstats-app php artisan migrate`。
- **フロントビルド**: `docker compose exec mstats-app npm run dev`。
- **テスト**: `docker compose exec mstats-app php artisan test`。

## コーディング規約・パターン
- **モデル/コントローラ/ビュー**はLaravel標準構成。
- **Eloquentリレーション**は明示的にモデルで定義。
- **Bladeテンプレート**は`resources/views/`配下。
- **環境変数**は`src/.env`で管理。
- **DBスキーマ**はマイグレーションで管理・変更。
- **Docker**で全サービス一括管理。ローカルDBは永続化。

## 注意点・プロジェクト固有の知識
- **DB初期化/リセット**は`php artisan migrate:fresh --seed`推奨。
- **MySQLデータ永続化**: `docker/mysql/`配下に物理ファイル。
- **Nginx/PHP設定**は`docker/nginx/`, `docker/php/`でカスタム可能。
- **ポート**: アプリは`http://localhost:8102`で動作。
- **日本語ローカライズ**: `lang/ja.json`参照。

## 参考
- 詳細手順・コマンド例は`README.md`参照。
- 主要な開発・運用コマンドは`docker compose exec mstats-app ...`で実行。

---
このファイルはAIエージェント向けのガイドです。プロジェクト固有の構成・運用・コーディングパターンを反映し、最新のREADMEやディレクトリ構成に合わせて随時更新してください。
