# 🎧 イヤホン・ヘッドホン専門 ECサイト

![PHP](https://img.shields.io/badge/PHP-8.2.12-777BB4?logo=php&logoColor=white)
![Laravel](https://img.shields.io/badge/Laravel-12.39.0-FF2D20?logo=laravel&logoColor=white)
![SQLite](https://img.shields.io/badge/SQLite-3-003B57?logo=sqlite&logoColor=white)

## 📌 アプリ概要

イヤホン・ヘッドホンを専門に扱うECサイトです。  
ユーザーは商品を閲覧し、カートに追加して購入することができます。

> ⚠️ デプロイについて  
> ngrokを使用して一時的な外部公開URLを生成し、ローカル環境から外部アクセスできることを確認しています。  
> 常時公開のデプロイは現在対応中です。

🔐 **テストアカウント**:  
- メール: `exam@example.com`  
- パスワード: `12345678`

---

## 🎯 作成背景・目的

PHP学習の一環として、最初にフレームワークを使わないPHP素書きでECサイトを作成しました。
その経験をベースに、Laravelを用いてリファクタリング・機能改善を行ったものが本アプリです。

🔗 **素書き版リポジトリ**: [yuzuniT/shop](https://github.com/yuzuniT/shop)

フレームワークなしで一度実装したことで、Laravelが内部で行っている処理への
理解を深めた状態で開発に臨むことができました。

開発開始時点ではHTML/CSSの基礎知識はあったものの、
PHP・Laravelともに未経験の状態からスタートしました。

このアプリを通じて、以下を一通り経験することを目標にしました。

- データベース設計（テーブル定義・リレーション）
- Laravelを使ったアプリ開発の基本的な流れ
- ユーザー認証機能の実装
- GitHubを使ったIssueベースの開発フロー

基本機能の実装に約2ヶ月かけ、現在も機能の改善や追加実装を継続しています。

---

## ⚙️ 使用技術

| カテゴリ | 技術・バージョン |
| --- | --- |
| フロントエンド | Blade, Tailwind CSS 4.0.7, Vite |
| バックエンド | PHP 8.2.12, Laravel 12.39.0 |
| データベース | SQLite 3 |
| DB管理ツール | Adminer |
| 開発環境 | Laravel Herd (Windows) |
| バージョン管理 | Git / GitHub / Sourcetree |
| CI/CD | GitHub Actions（PHPUnit自動テスト） |
| テスト | PHPUnit |

---

## 🗂️ 機能一覧

### ユーザー機能
- 会員登録（メールアドレス認証付き）
- ログイン・ログアウト
- パスワード再発行メール送信
- 商品一覧・詳細表示
- カートへの追加・削除
- 注文・購入手続き（配達先入力）
- お問い合わせ送信

### 自動入力・UX改善
- ログイン中はお問い合わせ・配達先入力フォームに会員情報を自動入力

### セキュリティ・品質
- 二重送信防止（お問い合わせ・注文確定時）
- メールアドレス認証による本人確認

### 管理者機能
- 現在開発中

---

## 📐 設計ドキュメント

### 画面遷移図
![画面遷移図](https://github.com/user-attachments/assets/eaf99552-bd6a-41ed-aafd-10c6b18cd4e4)

### ER図 / テーブル定義書
📄 [テーブル定義書（Google Sheets）](https://docs.google.com/spreadsheets/d/101n_6tjtNjLqDq1EUwKNTWGXp0tCrQ43mKaziv_Dh7k/)

### テスト仕様書
📄 [テスト仕様書（Google Sheets）](https://docs.google.com/spreadsheets/d/11X4_Hsr9IE6ipQLv1BW6WoUgGWmN8Gpaqcm-onyXw-o/)

---

## 🚀 ローカル環境でのセットアップ

### 必要な環境
- [Laravel Herd](https://herd.laravel.com/) がインストールされていること
- PHP 8.2以上

### 手順

**1. リポジトリをクローン**
```bash
git clone https://github.com/yuzuniT/shop_app3.git
```
HerdのサイトディレクトリにクローンするとURL (`http://shop-app3.test`) が自動で割り当てられます。

**2. 依存パッケージをインストール**
```bash
composer install
npm install
```

**3. 環境ファイルを作成**
```bash
cp .env.example .env
php artisan key:generate
```

**4. データベースのセットアップ**
```bash
touch database/database.sqlite
php artisan migrate --seed
```
> `--seed` オプションでカテゴリ・商品の初期データが自動で挿入されます。

**5. アセットをビルド**
```bash
npm run dev
```

**6. ブラウザでアクセス**
```
http://shop-app3.test
```

---

### メール機能について
デフォルトでは送信メールはログファイルに出力されます。
実際のメール送信を確認したい場合は `.env` の `MAIL_MAILER` を変更してください。
```env
# ログに出力（デフォルト）
MAIL_MAILER=log

# Mailtrapなどのテスト用SMTPを使う場合
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
```

---

## 💡 工夫した点

- **Issue駆動開発を実践**: 一人開発でもGitHubのIssueとPull Requestを活用し、実務に近い開発フローを意識しました
- **メール送信機能**: XAMPPのローカル環境でもMailHogを使ってメール送受信のテストができるよう環境構築しました
- **テスト設計書の作成**: PHPUnitによる自動テストに加え、手動テストの仕様書も作成し、品質担保を意識しました

---

## 💡 工夫した点・苦労した点

### 注文フローの実装
カートへの追加→注文内容確認→注文確定という一連のフローの実装が最も難しい部分でした。
最初はセッションやデータベース間でのデータの受け渡し方法がつかめず、
処理の流れを図に書き起こしながら整理することで理解を深めました。
また、プログラミングには「唯一の正解」がないため、
右も左もわからない状態で実装方針を自分で判断しなければならない難しさを痛感しました。

### 追加機能の選定
既存の大型ECサイトを利用した経験をもとに、自作アプリに不足している要素を洗い出し、
メールアドレス認証・パスワード再発行・会員情報の自動入力などを追加実装しました。
二重送信防止については、自身が送信ボタンを押した後に「本当に送れたのか」と
不安になった経験から、ユーザー目線で必要だと判断して実装しました。

### データベース設計
外部キー制約が正しく機能しているかどうかの確認に苦労しました。
また、注文テーブルと注文商品テーブルを分けるといった設計は
自力では思いつかなかったため、定番のDB設計パターンを調べることの重要性を学びました。
テーブル定義書を作成しながら設計を進めることで、リレーションの全体像を
把握しやすくなりました。

### GitHubのIssueベース開発
実装したい機能や改善点が頭の中で散漫になりがちだったため、
GitHubのIssue機能をTodoリストとして活用する開発フローを取り入れました。
個人開発ではありますが、就職後のチーム開発を見越してIssue・Pull Requestを
使った開発を意識的に経験するようにしました。

---

## 📈 今後追加したい機能

- [ ] 管理者機能（商品・注文の登録・編集・削除）
- [ ] 注文履歴確認機能
- [ ] 常時デプロイ環境の構築

---

## 📚 参考にした記事

- [PHP+MySQLで簡易ログインシステムを作る](https://qiita.com/Naughty1029/items/08b0ddeb805442916239)
- [一人開発でもIssueベース開発を行う](https://qiita.com/braveryk7/items/5208263cd06a8878f0c2)
- [DB設計 テーブル定義書 テンプレート](https://qiita.com/maco165/items/83ec720277a52cc61a27)
- [[テンプレート付]テスト設計書の書き方例](https://zenn.dev/ik_takagishi/books/5c6c9fe3a7ad2c/viewer/908fa3)