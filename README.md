# 作業開始時
### エイリアス設定
```Bash
alias sail='[ -f sail ] && sh sail || sh vendor/bin/sail'
```
### コンテナ起動
```Bash
sail up -d &&
sail npm run dev
```
### パッケージ更新
```Bash
sail composer install && sail npm install
```
### データベース更新
```Bash
sail artisan migrate --seed
```
+ #### データをリセットする場合
```Bash
sail artisan migrate:refresh --seed
```

# 作業停止時
### コンテナ停止
```Bash
sail stop
```

# 初期設定
### Composer依存関係をインストール
```Bash
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php84-composer:latest \
    composer install --ignore-platform-reqs
```
### エイリアス設定
```Bash
alias sail='[ -f sail ] && sh sail || sh vendor/bin/sail'
```
### .env生成
```Bash
cp .env.example .env
```
### コンテナ起動
```Bash
sail up -d
```
### キー生成
```Bash
sail artisan key:generate
```
### Composer & NPMインストール
```Bash
sail composer install && sail npm install
```
### テーブル構築 & テストデータ作成
```Bash
sail artisan migrate --seed
```
### テストアカウント
+ test@example.com
+ password

# URL
+ 開発環境: http://localhost
+ DB: http://localhost:3306
+ phpMyAdmin: http://localhost:8888
+ Redis: http://localhost:6379

# 環境設定系
実行の必要はなし
### SCSS依存ライブラリのインストール
```Bash
sail npm install -D sass-embedded
```
### Breezeインストール
※今回はPHPUnit, Vue with Inertiaを選択
```Bash
sail composer require laravel/breeze --dev && sail artisan breeze:install
```
