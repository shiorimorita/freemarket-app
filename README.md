# freemarket-app

## テストユーザー（Seeder）

- test@example.com / password
  ※出品者として商品を 10 件登録済みです。

- test02@example.com / password

## 環境構築

### Doker ビルド

1. GitHub からリポジトリをクローンします。

```
git clone git@github.com:shiorimorita/freemarket-app.git
```

2. クローンしたリポジトリのディレクトリに移動します。
3. DockerDesktop アプリを立ち上げます。

```
docker-compose up -d --build
```

※ Mac の M1・M2 チップの PC の場合、no matching manifest for linux/arm64/v8 in the manifest list entries のメッセージが表示されビルドができないことがあります。 エラーが発生する場合は、docker-compose.yml ファイルの「mysql」内に「platform」の項目を追加で記載してください

```yaml
mysql:
  platform: linux/x86_64(この行を追加)
  image: mysql:8.0.26
  environment:
```

### Laravel 環境構築

1. PHP コンテナに入ります。

```
docker-compose exec php bash
```

2. Laravel の必要パッケージをインストールします。

```
composer install
```

3. 「.env.example」ファイルを 「.env」ファイルにコピーまたはリネームします。
4. アプリケーションキーを生成します。

```
php artisan key:generate
```

5. データベースをマイグレーションします。

```
php artisan migrate
```

6. シーディングを実行します。

```
php artisan db:seed
```

### Stripe 決済の設定

本プロジェクトで Stripe 決済を利用するために、各開発者は以下の設定を行ってください。

1. Stripe アカウント作成（未作成の場合）
   https://dashboard.stripe.com/register  
   上記よりアカウントを作成してください。

2. API キー取得
   Stripe ダッシュボードへログインし、検索窓で **「API キー」** と検索し "開発者＞ API キー" へアクセスします。
   「テストデータを表示する」を ON にし、以下の 2 つのキーを控えてください。

   ※ **必ずテストキー（pk_test / sk_test）を使用してください。**

- 公開可能キー（Publishable key）
- 秘密キー（Secret key）
  <img width="1920" height="198" alt="Image" src="https://github.com/user-attachments/assets/308bc7cb-16e5-40c6-96fe-7f0a37f3d183" />

3. .env の設定
   取得したキーを `.env` に設定します。

```
   STRIPE_KEY=pk_test_xxxxxxxxxxxxxxxxx
   STRIPE_SECRET=sk_test_xxxxxxxxxxxxxxxx
```

## 使用技術(実行環境)

- PHP 8.1.33
- Laravel 8.83.8
- MySQL 8.0.26

## ER 図

<img width="941" height="1521" alt="Image" src="https://github.com/user-attachments/assets/2ecc3507-2ad5-4913-9e09-41a217c59dd4" />

## URL

- 開発環境：http://localhost/
- phpMyAdmin:：http://localhost:8080/
