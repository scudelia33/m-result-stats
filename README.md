## はじめに

Mリーグの公式ページの成績を自分用に確認したかったので作成しました。

## 構築手順

### 1. コンテナの作成

以下コマンドを実行します。

```shell
docker compose up -d
```

### 2. .envファイルの設定

以下コマンドを実行します。

```shell
cp src/.env.local src/.env
```

DB接続情報が以下のようになっているかを確認してください。

```
DB_CONNECTION=mysql
DB_HOST=mstats-mysql
DB_PORT=3306
DB_DATABASE=mstats
DB_USERNAME=root
DB_PASSWORD=root
```

### 3. 依存パッケージのインストール

以下コマンドを実行します。

エラーや警告が表示されていなければ🆗です。

```shell
docker compose exec mstats-app composer install
docker compose exec mstats-app npm install
```

### 4. アプリケーションキーの作成

以下コマンドを実行します。

```shell
docker compose exec mstats-app php artisan key:generate
```

### 5. 書き込み権限の付与

以下コマンドを実行します。

```shell
docker compose exec mstats-app chmod -R 775 storage bootstrap/cache
docker compose exec mstats-app chown -R www-data:www-data storage bootstrap/cache
```

### 6. マイグレーションの実行

以下コマンドを実行します。

```shell
docker compose exec mstats-app php artisan migrate
docker compose exec mstats-app php artisan db:seed
```

### 7. Viteのビルド

以下コマンドを実行します。

```shell
docker compose exec mstats-app npm run dev
```

## 動作確認

http://localhost:8102にアクセスします。



