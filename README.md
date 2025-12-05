# freemarket-app

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

上記コマンドを実行することで、 実行後すぐに `http://localhost:8025/` から MailHog を起動できます。

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

   **必ずテストキー（pk_test / sk_test）を使用してください。**

- 公開可能キー（STRIPE_PUBLIC）
- 秘密キー（STRIPE_SECRET）
  <img width="1920" height="198" alt="Image" src="https://github.com/user-attachments/assets/308bc7cb-16e5-40c6-96fe-7f0a37f3d183" />

1. .env の設定
   取得したキーを `.env` に設定します。

```
   STRIPE_PUBLIC=pk_test_xxxxxxxxxxxxxxxxx
   STRIPE_SECRET=sk_test_xxxxxxxxxxxxxxxx
```

## 購入フロー

Stripe 決済を利用した商品購入時のステータス更新について、以下のように制御しています。

1. **カード決済**

   - 購入者がカード情報を入力し Stripe の決済が完了した直後に、商品状態を `sold` に変更します。

   - Stripe のテストでは「4242 4242 4242 4242」のダミーカード番号を使用すると即決済成功となります。

※詳細は公式ドキュメント（[Stripe テストカード一覧](https://stripe.com/docs/testing#international-cards)）をご参照ください。

2. **コンビニ払い**

   - コンビニ払いを選択し、購入画面にて "購入する" ボタンを押下した時点で、対象商品を即座に `sold` ステータスへ切り替えます。

## テスト用ユーザーアカウント（Seeder）

以下のテスト用アカウントが Seeder により自動生成されます。

**アカウント A**

- メールアドレス：test@example.com
- パスワード：password
- 商品 10 件を登録済みの状態です

**アカウント B**

- メールアドレス：test02@example.com
- パスワード：password

### テスト実行（確認手順）

1. テスト用ユーザーでログイン

2. 確認項目

- Seeder により商品 10 件が表示される
- ユーザーは新規商品登録が可能
- 他ユーザーの商品を購入できる
- 購入後、商品状態が Sold と表示される
- いいね機能が動作する
- 売却済み商品は購入不可になる

## PHPUnit テストの実行方法

本アプリでは、一部機能について自動テスト（Feature テスト）を実装しています。

```
php artisan test
```

## ER 図

<img width="942" height="1521" alt="Image" src="https://github.com/user-attachments/assets/05e439ab-58c4-46ad-a256-034d56b15117" />

## 使用技術(実行環境)

- PHP 8.1.33
- Laravel 8.83.8
- MySQL 8.0.26

## URL

- 開発環境：http://localhost/
- phpMyAdmin:：http://localhost:8080/
- MailHog : http://localhost:8025/
