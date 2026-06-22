# Marketplace App（ECサイト）

## 概要

Laravelを用いて作成したECサイトです。
ユーザー登録から商品閲覧・購入までの基本的なEC機能を実装しています。

---

## 環境構築

```bash
git clone https://github.com/07tasuku06-cloud/MARKETPLACE-APP.git
cd MARKETPLACE-APP

docker-compose up -d --build

docker-compose exec php bash

composer install
cp .env.example .env
php artisan key:generate
php artisan migrate

php artisan storage:link

```

---

## メール認証確認

本プロジェクトではメール認証の確認に MailHog を使用しています。

新規会員登録後、以下のURLにアクセスすると認証メールを確認できます。

```text
http://localhost:8025
```

---

## Mac環境でphpコンテナが起動しない場合

Mac環境で `php` コンテナが起動しない場合は、`docker-compose.yml` の以下の記述が原因となる場合があります。

```yaml
user: "1000:1000"
```

その場合は、`php` サービス内の `user: "1000:1000"` をコメントアウトしてください。

```yaml
php:
    build: ./docker/php
    # user: "1000:1000"
    volumes:
        - ./src:/var/www
    working_dir: /var/www
```

変更後、以下を実行してください。

```bash
docker-compose down
docker-compose up -d --build
```

それでも起動しない場合は、以下のコマンドでログを確認してください。

```bash
docker-compose logs php
```

---

## 実行環境

- PHP 8.x（Laravel 10）
- MySQL 8.0
- Docker
    - Nginx 1.21.1
    - PHP 8.1-fpm
    - MySQL 8.0.26
    - phpMyAdmin
    - MailHog

---

## ER図

本プロジェクトのデータベース構造は以下の通りです。

![ER図](docs/erd.png)
